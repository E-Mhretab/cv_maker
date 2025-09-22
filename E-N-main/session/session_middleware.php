<?php
/**
 * Session Middleware
 * Handles session activity tracking and automatic cleanup
 */

require_once 'session_manager.php';

class SessionMiddleware {
    private $sessionManager;
    private $excludedPaths = [
        '/css/',
        '/js/',
        '/img/',
        '/fonts/',
        '/favicon.ico',
        '/robots.txt'
    ];
    
    public function __construct($connection) {
        $this->sessionManager = new SessionManager($connection);
    }
    
    /**
     * Initialize session and track activity
     */
    public function handle() {
        // Start session if not already started and no output has been sent
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        
        // Skip tracking for excluded paths
        if ($this->shouldSkipTracking()) {
            return;
        }
        
        // Clean up expired sessions periodically (1% chance)
        if (rand(1, 100) === 1) {
            $this->sessionManager->cleanupExpiredSessions();
        }
        
        // Track activity for current session
        $sessionId = session_id();
        if ($sessionId) {
            $this->sessionManager->updateLastActivity($sessionId);
        }
    }
    
    /**
     * Check if current path should be excluded from tracking
     */
    private function shouldSkipTracking() {
        $currentPath = $_SERVER['REQUEST_URI'] ?? '';
        
        foreach ($this->excludedPaths as $excludedPath) {
            if (strpos($currentPath, $excludedPath) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get current session info
     */
    public function getCurrentSession() {
        $sessionId = session_id();
        if (!$sessionId) {
            return null;
        }
        
        return $this->sessionManager->validateSession($sessionId);
    }
    
    /**
     * Force session refresh
     */
    public function refreshSession() {
        $sessionId = session_id();
        if (!$sessionId) {
            return false;
        }
        
        return $this->sessionManager->refreshSession($sessionId);
    }
    
    /**
     * Get session manager instance
     */
    public function getSessionManager() {
        return $this->sessionManager;
    }
}
?>
