<?php
/**
 * Session Helper - Centralized session management
 */

if (!function_exists('startSecureSession')) {
    function startSecureSession() {
        // Only start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Only configure session settings if headers haven't been sent
            if (!headers_sent()) {
                ini_set('session.cookie_httponly', 1);
                ini_set('session.use_only_cookies', 1);
                ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
                ini_set('session.cookie_samesite', 'Lax');
                ini_set('session.cookie_lifetime', 0); // Session cookie expires when browser closes
                ini_set('session.gc_maxlifetime', 3600); // 1 hour
            }
            
            session_start();
            
            // Regenerate session ID periodically for security
            if (!isset($_SESSION['last_regeneration'])) {
                $_SESSION['last_regeneration'] = time();
            } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
        }
    }
}
?>
