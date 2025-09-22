<?php
/**
 * Enhanced Session Manager
 * Handles secure session management with device tracking and refresh tokens
 */

class SessionManager {
    private $conn;
    private $sessionLifetime = 3600; // 1 hour
    private $refreshTokenLifetime = 2592000; // 30 days
    private $maxSessionsPerUser = 5; // Maximum concurrent sessions per user
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Generate a secure device ID based on user agent and IP
     */
    private function generateDeviceId($userAgent, $ipAddress) {
        $deviceString = $userAgent . $ipAddress;
        return hash('sha256', $deviceString . time() . uniqid());
    }
    
    /**
     * Generate a secure refresh token
     */
    private function generateRefreshToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Get client IP address
     */
    private function getClientIpAddress() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * Get user agent
     */
    private function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }
    
    /**
     * Create a new session
     */
    public function createSession($userId, $sessionId = null) {
        try {
            $ipAddress = $this->getClientIpAddress();
            $userAgent = $this->getUserAgent();
            $deviceId = $this->generateDeviceId($userAgent, $ipAddress);
            $refreshToken = $this->generateRefreshToken();
            
            // Clean up old sessions for this user
            $this->cleanupUserSessions($userId);
            
            // Check if we're at the session limit
            $this->enforceSessionLimit($userId);
            
            // Use provided session ID or generate new one
            if (!$sessionId) {
                $sessionId = session_id() ?: $this->generateSessionId();
            }
            
            $stmt = $this->conn->prepare("
                INSERT INTO user_sessions (id, user_id, ip_address, user_agent, device_id, refresh_token, expires_at, last_activity) 
                VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NOW())
            ");
            
            $stmt->bind_param("sissssi", $sessionId, $userId, $ipAddress, $userAgent, $deviceId, $refreshToken, $this->sessionLifetime);
            
            if ($stmt->execute()) {
                $stmt->close();
                
                // Get the actual expires_at value from database
                $checkStmt = $this->conn->prepare("SELECT expires_at FROM user_sessions WHERE id = ?");
                $checkStmt->bind_param("s", $sessionId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $sessionData = $result->fetch_assoc();
                $checkStmt->close();
                
                return [
                    'success' => true,
                    'session_id' => $sessionId,
                    'device_id' => $deviceId,
                    'refresh_token' => $refreshToken,
                    'expires_at' => $sessionData['expires_at']
                ];
            } else {
                $stmt->close();
                return ['success' => false, 'error' => 'Failed to create session'];
            }
        } catch (Exception $e) {
            error_log("Session creation error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Session creation failed'];
        }
    }
    
    /**
     * Validate and refresh a session
     */
    public function validateSession($sessionId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, u.id as user_id, u.username, u.email, u.role 
                FROM user_sessions s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.id = ? AND s.expires_at > NOW()
            ");
            
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $result = $stmt->get_result();
            $session = $result->fetch_assoc();
            $stmt->close();
            
            if ($session) {
                // Update last activity
                $this->updateLastActivity($sessionId);
                
                // Check if session needs refresh
                $timeUntilExpiry = strtotime($session['expires_at']) - time();
                if ($timeUntilExpiry < ($this->sessionLifetime / 2)) {
                    $this->refreshSession($sessionId);
                }
                
                return [
                    'valid' => true,
                    'user' => [
                        'id' => $session['user_id'],
                        'username' => $session['username'],
                        'email' => $session['email'],
                        'role' => $session['role']
                    ],
                    'session' => $session
                ];
            }
            
            return ['valid' => false, 'error' => 'Invalid or expired session'];
        } catch (Exception $e) {
            error_log("Session validation error: " . $e->getMessage());
            return ['valid' => false, 'error' => 'Session validation failed'];
        }
    }
    
    /**
     * Refresh a session using refresh token
     */
    public function refreshSessionWithToken($refreshToken) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, u.id as user_id, u.username, u.email, u.role 
                FROM user_sessions s 
                JOIN users u ON s.user_id = u.id 
                WHERE s.refresh_token = ? AND s.expires_at > NOW()
            ");
            
            $stmt->bind_param("s", $refreshToken);
            $stmt->execute();
            $result = $stmt->get_result();
            $session = $result->fetch_assoc();
            $stmt->close();
            
            if ($session) {
                // Generate new refresh token
                $newRefreshToken = $this->generateRefreshToken();
                
                // Update session
                $updateStmt = $this->conn->prepare("
                    UPDATE user_sessions 
                    SET refresh_token = ?, expires_at = DATE_ADD(NOW(), INTERVAL ? SECOND), last_activity = NOW() 
                    WHERE id = ?
                ");
                
                $updateStmt->bind_param("sii", $newRefreshToken, $this->sessionLifetime, $session['id']);
                
                if ($updateStmt->execute()) {
                    $updateStmt->close();
                    
                    // Get the new expires_at value from database
                    $checkStmt = $this->conn->prepare("SELECT expires_at FROM user_sessions WHERE id = ?");
                    $checkStmt->bind_param("s", $session['id']);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();
                    $sessionData = $result->fetch_assoc();
                    $checkStmt->close();
                    
                    return [
                        'success' => true,
                        'session_id' => $session['id'],
                        'refresh_token' => $newRefreshToken,
                        'expires_at' => $sessionData['expires_at'],
                        'user' => [
                            'id' => $session['user_id'],
                            'username' => $session['username'],
                            'email' => $session['email'],
                            'role' => $session['role']
                        ]
                    ];
                }
                $updateStmt->close();
            }
            
            return ['success' => false, 'error' => 'Invalid refresh token'];
        } catch (Exception $e) {
            error_log("Session refresh error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Session refresh failed'];
        }
    }
    
    /**
     * Update last activity for a session
     */
    public function updateLastActivity($sessionId) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE user_sessions 
                SET last_activity = NOW() 
                WHERE id = ?
            ");
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Update last activity error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Refresh session expiry
     */
    private function refreshSession($sessionId) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE user_sessions 
                SET expires_at = DATE_ADD(NOW(), INTERVAL ? SECOND), last_activity = NOW() 
                WHERE id = ?
            ");
            $stmt->bind_param("is", $this->sessionLifetime, $sessionId);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Session refresh error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Destroy a session
     */
    public function destroySession($sessionId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user_sessions WHERE id = ?");
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Session destruction error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Destroy all sessions for a user
     */
    public function destroyUserSessions($userId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user_sessions WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("User session destruction error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions() {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM user_sessions 
                WHERE expires_at < NOW()
            ");
            $stmt->execute();
            $deletedCount = $stmt->affected_rows;
            $stmt->close();
            return $deletedCount;
        } catch (Exception $e) {
            error_log("Session cleanup error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Clean up old sessions for a specific user
     */
    private function cleanupUserSessions($userId) {
        try {
            // Keep only the most recent sessions
            $stmt = $this->conn->prepare("
                DELETE FROM user_sessions 
                WHERE user_id = ? AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM user_sessions 
                        WHERE user_id = ? 
                        ORDER BY last_activity DESC 
                        LIMIT ?
                    ) as recent_sessions
                )
            ");
            $stmt->bind_param("iii", $userId, $userId, $this->maxSessionsPerUser);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("User session cleanup error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Enforce session limit per user
     */
    private function enforceSessionLimit($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as session_count 
                FROM user_sessions 
                WHERE user_id = ? AND expires_at > NOW()
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_assoc()['session_count'];
            $stmt->close();
            
            if ($count >= $this->maxSessionsPerUser) {
                // Remove oldest sessions
                $this->cleanupUserSessions($userId);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Session limit enforcement error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user's active sessions
     */
    public function getUserSessions($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT id as session_id, device_id, ip_address, user_agent, last_activity, expires_at, created_at
                FROM user_sessions 
                WHERE user_id = ? AND expires_at > NOW()
                ORDER BY last_activity DESC
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $sessions = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $sessions;
        } catch (Exception $e) {
            error_log("Get user sessions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate a secure session ID
     */
    private function generateSessionId() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Get session statistics
     */
    public function getSessionStats() {
        try {
            $stats = [];
            
            // Total active sessions
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM user_sessions WHERE expires_at > NOW()");
            $stats['total_active'] = $stmt->fetch_assoc()['total'];
            
            // Sessions by user
            $stmt = $this->conn->query("
                SELECT u.username, COUNT(s.id) as session_count 
                FROM users u 
                LEFT JOIN user_sessions s ON u.id = s.user_id AND s.expires_at > NOW()
                GROUP BY u.id, u.username
                ORDER BY session_count DESC
            ");
            $stats['by_user'] = $stmt->fetch_all(MYSQLI_ASSOC);
            
            // Recent activity
            $stmt = $this->conn->query("
                SELECT COUNT(*) as recent 
                FROM user_sessions 
                WHERE last_activity > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stats['recent_activity'] = $stmt->fetch_assoc()['recent'];
            
            return $stats;
        } catch (Exception $e) {
            error_log("Session stats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->conn;
    }
}
?>
