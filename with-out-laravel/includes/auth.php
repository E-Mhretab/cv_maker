<?php
/**
 * Authentication System
 * Step 1: Authentication Setup
 */

require_once 'security.php';
require_once __DIR__ . '/../session/session_manager.php';

class Auth {
    private $conn;
    private $sessionTimeout = 3600; // 1 hour
    private $sessionManager;
    
    public function __construct($connection) {
        $this->conn = $connection;
        try {
            // Check if user_sessions table exists before creating SessionManager
            $result = $connection->query("SHOW TABLES LIKE 'user_sessions'");
            if ($result && $result->num_rows > 0) {
                $this->sessionManager = new SessionManager($connection);
            } else {
                error_log("user_sessions table does not exist, using fallback session management");
                $this->sessionManager = null;
            }
        } catch (Exception $e) {
            // Fallback if SessionManager is not available
            error_log("SessionManager initialization failed: " . $e->getMessage());
            $this->sessionManager = null;
        }
    }
    
    /**
     * Start secure session
     */
    private function startSecureSession() {
        // Use the centralized session helper
        require_once __DIR__ . '/session_helper.php';
        startSecureSession();
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password, $role = 'user') {
        // Check if users table exists
        if (!$this->tableExists('users')) {
            return ['success' => false, 'errors' => ['Authentication system not initialized']];
        }
        
        // Validate input
        $errors = $this->validateRegistration($username, $email, $password);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check if user already exists
        if ($this->userExists($username, $email)) {
            return ['success' => false, 'errors' => ['User already exists']];
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $passwordHash, $role);
        
        if ($stmt->execute()) {
            $userId = $this->conn->insert_id;
            $stmt->close();
            
            // Automatically transfer guest CVs to the new user
            $this->transferGuestCVs($userId, $email);
            
            // Auto-login after registration
            $this->login($username, $password);
            
            return ['success' => true, 'user_id' => $userId];
        } else {
            $stmt->close();
            return ['success' => false, 'errors' => ['Registration failed']];
        }
    }
    
    /**
     * Login user
     */
    public function login($username, $password) {
        // Ensure session is started
        $this->startSecureSession();
        
        // Check if users table exists
        if (!$this->tableExists('users')) {
            return ['success' => false, 'error' => 'Authentication system not initialized'];
        }
        
        // Get user by username or email
        $stmt = $this->conn->prepare("SELECT id, username, email, password_hash, role, is_active FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $stmt->close();
            return ['success' => false, 'error' => 'Invalid credentials'];
        }
        
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid credentials'];
        }
        
        // Create enhanced session with device tracking
        if ($this->sessionManager) {
            try {
                // Use current session ID for consistency
                $currentSessionId = session_id();
                $sessionResult = $this->sessionManager->createSession($user['id'], $currentSessionId);
                
                if (!$sessionResult['success']) {
                    // Log the error but don't fail login
                    error_log("Session creation failed: " . ($sessionResult['error'] ?? 'Unknown error'));
                    error_log("Current session ID: " . $currentSessionId);
                    error_log("User ID: " . $user['id']);
                } else {
                    error_log("Session created successfully for user " . $user['id'] . " with session ID: " . $currentSessionId);
                    // Store session ID in PHP session for consistency
                    $_SESSION['session_id'] = $currentSessionId;
                }
            } catch (Exception $e) {
                // Log the error but don't fail login
                error_log("Session creation exception: " . $e->getMessage());
                error_log("Current session ID: " . session_id());
                error_log("User ID: " . $user['id']);
            }
        } else {
            error_log("SessionManager not available for user " . $user['id']);
        }
        
        // Update last login
        try {
            $this->updateLastLogin($user['id']);
        } catch (Exception $e) {
            // Ignore last login update errors
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['session_id'] = $sessionResult['session_id'] ?? session_id();
        $_SESSION['login_time'] = time();
        
        // Set enhanced session variables if available
        if ($this->sessionManager && isset($sessionResult['device_id'])) {
            $_SESSION['device_id'] = $sessionResult['device_id'];
            $_SESSION['refresh_token'] = $sessionResult['refresh_token'];
        }
        
        // Regenerate session ID for security (only if no output has been sent)
        if (!headers_sent()) {
            session_regenerate_id(true);
        }
        
        // Debug session after login
        error_log("Login successful - Session ID: " . session_id() . ", User ID: " . $user['id']);
        
        // Transfer any guest CVs with matching email to this user
        try {
            $this->transferGuestCVs($user['id'], $user['email']);
        } catch (Exception $e) {
            // Log error but don't fail login
            error_log("Failed to transfer guest CVs: " . $e->getMessage());
        }
        
        return ['success' => true, 'user' => $user, 'session' => $sessionResult];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Destroy session in database
        if ($this->sessionManager && isset($_SESSION['session_id'])) {
            $this->sessionManager->destroySession($_SESSION['session_id']);
        }
        
        // Clear session variables
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        
        return ['success' => true];
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        // Ensure session is started
        $this->startSecureSession();
        
        // Simple session check - if user_id exists in session, user is logged in
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Check session timeout (optional)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $this->sessionTimeout) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role']
        ];
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($role) {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === $role;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin() {
        return $this->hasRole('admin');
    }
    
    /**
     * Check if user is regular user
     */
    public function isUser() {
        return $this->hasRole('user');
    }
    
    /**
     * Check if user is guest
     */
    public function isGuest() {
        return $this->hasRole('guest') || !$this->isLoggedIn();
    }
    
    /**
     * Require authentication
     */
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role) {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            header('Location: unauthorized.php');
            exit;
        }
    }
    
    /**
     * Require admin role
     */
    public function requireAdmin() {
        $this->requireRole('admin');
    }
    
    /**
     * Check if user can access CV
     */
    public function canAccessCV($cvId) {
        if ($this->isAdmin()) {
            return true; // Admin can access all CVs
        }
        
        // Check if CV exists and get its details
        $stmt = $this->conn->prepare("SELECT c.user_id, c.email, m.is_public FROM cv c LEFT JOIN cv_metadata m ON c.id = m.cv_id WHERE c.id = ?");
        $stmt->bind_param("i", $cvId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $stmt->close();
            return false;
        }
        
        $cv = $result->fetch_assoc();
        $stmt->close();
        
        // If CV is public, anyone can access it
        if ($cv['is_public']) {
            return true;
        }
        
        // For private CVs, check ownership
        if ($this->isLoggedIn()) {
            // Logged in user can access their own private CVs
            return $cv['user_id'] == $_SESSION['user_id'];
        } else {
            // Guest can access private CVs they created (user_id IS NULL)
            return $cv['user_id'] === null;
        }
    }
    
    /**
     * Validate registration data
     */
    private function validateRegistration($username, $email, $password) {
        $errors = [];
        
        // Username validation
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters long';
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username can only contain letters, numbers, and underscores';
        }
        
        // Email validation
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        // Password validation
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }
        
        return $errors;
    }
    
    /**
     * Check if user exists
     */
    private function userExists($username, $email) {
        // Check if users table exists
        if (!$this->tableExists('users')) {
            return false;
        }
        
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }
    
    /**
     * Check if table exists
     */
    private function tableExists($tableName) {
        $result = $this->conn->query("SHOW TABLES LIKE '$tableName'");
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Create session
     */
    private function createSession($userId) {
        $sessionId = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + $this->sessionTimeout);
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $this->conn->prepare("INSERT INTO user_sessions (id, user_id, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $sessionId, $userId, $ipAddress, $userAgent, $expiresAt);
        $stmt->execute();
        $stmt->close();
        
        return $sessionId;
    }
    
    /**
     * Check if session is valid
     */
    private function isSessionValid($sessionId) {
        $stmt = $this->conn->prepare("SELECT id FROM user_sessions WHERE id = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $valid = $result->num_rows > 0;
        $stmt->close();
        
        return $valid;
    }
    
    /**
     * Destroy session
     */
    private function destroySession($sessionId) {
        $stmt = $this->conn->prepare("DELETE FROM user_sessions WHERE id = ?");
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Update last login
     */
    private function updateLastLogin($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Transfer guest CVs to a new user account
     */
    private function transferGuestCVs($userId, $email) {
        // Find all guest CVs with matching email
        $stmt = $this->conn->prepare("SELECT id FROM cv WHERE user_id IS NULL AND LOWER(TRIM(email)) = LOWER(TRIM(?))");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transferredCount = 0;
        $foundCVs = [];
        while ($row = $result->fetch_assoc()) {
            $foundCVs[] = $row['id'];
            // Transfer each CV to the new user
            $updateStmt = $this->conn->prepare("UPDATE cv SET user_id = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $userId, $row['id']);
            if ($updateStmt->execute()) {
                $transferredCount++;
            }
            $updateStmt->close();
        }
        $stmt->close();
        
        // Log the transfer for debugging
        if ($transferredCount > 0) {
            error_log("Transferred $transferredCount guest CV(s) to user ID $userId with email $email");
        }
        
        return $transferredCount;
    }
    
    /**
     * Clean expired sessions
     */
    public function cleanExpiredSessions() {
        if ($this->sessionManager) {
            return $this->sessionManager->cleanupExpiredSessions();
        }
        return 0;
    }
    
    /**
     * Check if session manager is available
     */
    public function isSessionManagerAvailable() {
        return $this->sessionManager !== null;
    }
    
    /**
     * Get user's active sessions
     */
    public function getUserSessions($userId = null) {
        if (!$this->sessionManager) {
            return [];
        }
        
        if (!$userId) {
            $userId = $_SESSION['user_id'] ?? null;
        }
        
        if (!$userId) {
            return [];
        }
        
        try {
            return $this->sessionManager->getUserSessions($userId);
        } catch (Exception $e) {
            error_log("Error getting user sessions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Destroy all sessions for current user
     */
    public function destroyAllSessions($userId = null) {
        if (!$this->sessionManager) {
            return false;
        }
        
        if (!$userId) {
            $userId = $_SESSION['user_id'] ?? null;
        }
        
        if (!$userId) {
            return false;
        }
        
        return $this->sessionManager->destroyUserSessions($userId);
    }
    
    /**
     * Refresh current session
     */
    public function refreshSession() {
        if (!$this->sessionManager || !isset($_SESSION['session_id'])) {
            return false;
        }
        
        return $this->sessionManager->refreshSession($_SESSION['session_id']);
    }
    
    /**
     * Get session statistics
     */
    public function getSessionStats() {
        if (!$this->sessionManager) {
            return [];
        }
        
        return $this->sessionManager->getSessionStats();
    }
    
    /**
     * Get session manager instance
     */
    public function getSessionManager() {
        return $this->sessionManager;
    }
}

// Initialize authentication
$auth = new Auth($conn);

// Make auth globally available
$GLOBALS['auth'] = $auth;

// Clean expired sessions periodically
if (rand(1, 100) === 1) { // 1% chance
    try {
        $auth->cleanExpiredSessions();
    } catch (Exception $e) {
        // Ignore cleanup errors to prevent breaking the app
        error_log("Session cleanup error: " . $e->getMessage());
    }
}
?>
