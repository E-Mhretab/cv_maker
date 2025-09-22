<?php
/**
 * Middleware for Authentication and Authorization
 * Step 2: Role Management & Step 3: Authorization Rules
 */

require_once 'auth.php';
require_once __DIR__ . '/../session/session_middleware.php';

class Middleware {
    private $auth;
    private $sessionMiddleware;
    
    public function __construct($auth) {
        $this->auth = $auth;
        
        // Only initialize session middleware if SessionManager is available and no output has been sent
        if ($auth->getSessionManager() && !headers_sent()) {
            $this->sessionMiddleware = new SessionMiddleware($auth->getSessionManager()->getConnection());
            $this->sessionMiddleware->handle();
        }
    }
    
    /**
     * Require authentication - redirect to login if not authenticated
     */
    public function requireAuth($redirectUrl = null) {
        if (!$this->auth->isLoggedIn()) {
            $redirect = $redirectUrl ?: $_SERVER['REQUEST_URI'];
            header('Location: login.php?redirect=' . urlencode($redirect));
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role, $redirectUrl = null) {
        $this->requireAuth($redirectUrl);
        
        if (!$this->auth->hasRole($role)) {
            $this->showUnauthorized();
        }
    }
    
    /**
     * Require admin role
     */
    public function requireAdmin($redirectUrl = null) {
        $this->requireRole('admin', $redirectUrl);
    }
    
    /**
     * Require user or admin role (not guest)
     */
    public function requireUserOrAdmin($redirectUrl = null) {
        $this->requireAuth($redirectUrl);
        
        if ($this->auth->isGuest()) {
            $this->showUnauthorized();
        }
    }
    
    /**
     * Check if user can access CV
     */
    public function requireCVAccess($cvId, $redirectUrl = null) {
        if (!$this->auth->canAccessCV($cvId)) {
            $this->showUnauthorized();
        }
    }
    
    /**
     * Check if user can view CV (for public CVs)
     */
    public function requireCVViewAccess($cvId, $redirectUrl = null) {
        if (!$this->auth->canAccessCV($cvId)) {
            $this->showUnauthorized();
        }
    }
    
    /**
     * Require export permission (not guest)
     */
    public function requireExportPermission($redirectUrl = null) {
        if ($this->auth->isGuest()) {
            $redirect = $redirectUrl ?: $_SERVER['REQUEST_URI'];
            header('Location: login.php?redirect=' . urlencode($redirect) . '&message=export_required');
            exit;
        }
    }
    
    /**
     * Show unauthorized page
     */
    private function showUnauthorized() {
        http_response_code(403);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Access Denied - CV Management System</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                body {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .error-container {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                    padding: 3rem;
                    text-align: center;
                    max-width: 500px;
                }
                .error-icon {
                    font-size: 4rem;
                    color: #dc3545;
                    margin-bottom: 1rem;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <i class="fas fa-ban error-icon"></i>
                <h1 class="text-danger mb-3">Access Denied</h1>
                <p class="text-muted mb-4">You don't have permission to access this resource.</p>
                <div class="d-grid gap-2">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </a>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Go to Homepage
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
    
    /**
     * Get user context for templates
     */
    public function getUserContext() {
        $user = $this->auth->getCurrentUser();
        return [
            'is_logged_in' => $this->auth->isLoggedIn(),
            'user' => $user,
            'is_admin' => $this->auth->isAdmin(),
            'is_user' => $this->auth->isUser(),
            'is_guest' => $this->auth->isGuest(),
            'role' => $user ? $user['role'] : 'guest'
        ];
    }
    
    /**
     * Check if user can perform action on CV
     */
    public function canPerformAction($action, $cvId = null) {
        switch ($action) {
            case 'create_cv':
                return true; // Anyone can create CVs
                
            case 'view_cv':
                if ($this->auth->isAdmin()) return true;
                if ($cvId) {
                    return $this->auth->canAccessCV($cvId);
                }
                return false;
                
            case 'edit_cv':
                if ($this->auth->isAdmin()) return true;
                if ($cvId) {
                    return $this->auth->canAccessCV($cvId);
                }
                return false;
                
            case 'delete_cv':
                if ($this->auth->isAdmin()) return true;
                if ($cvId) {
                    return $this->auth->canAccessCV($cvId);
                }
                return false;
                
            case 'export_cv':
                return !$this->auth->isGuest();
                
            case 'view_all_cvs':
                return $this->auth->isAdmin();
                
            default:
                return false;
        }
    }
    
    /**
     * Get CVs accessible to current user
     */
    public function getAccessibleCVs() {
        global $conn;
        
        if ($this->auth->isAdmin()) {
            // Admin can see all CVs
            $stmt = $conn->prepare("SELECT * FROM cv ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $cvs = [];
            while ($row = $result->fetch_assoc()) {
                $cvs[] = $row;
            }
            $stmt->close();
            return $cvs;
        } elseif ($this->auth->isLoggedIn()) {
            // User can only see their own CVs
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT * FROM cv WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cvs = [];
            while ($row = $result->fetch_assoc()) {
                $cvs[] = $row;
            }
            $stmt->close();
            return $cvs;
        } else {
            // Guest cannot see any CVs
            return [];
        }
    }
}

// Initialize middleware
$middleware = new Middleware($auth);

// Helper functions for templates
function isLoggedIn() {
    global $auth;
    return $auth->isLoggedIn();
}

function getCurrentUser() {
    global $auth;
    return $auth->getCurrentUser();
}

function isAdmin() {
    global $auth;
    return $auth->isAdmin();
}

function isUser() {
    global $auth;
    return $auth->isUser();
}

function isGuest() {
    global $auth;
    return $auth->isGuest();
}

function canAccessCV($cvId) {
    global $auth;
    return $auth->canAccessCV($cvId);
}

function canPerformAction($action, $cvId = null) {
    global $middleware;
    return $middleware->canPerformAction($action, $cvId);
}

function requireAuth() {
    global $middleware;
    $middleware->requireAuth();
}

function requireAdmin() {
    global $middleware;
    $middleware->requireAdmin();
}

function requireUserOrAdmin() {
    global $middleware;
    $middleware->requireUserOrAdmin();
}

function requireCVAccess($cvId) {
    global $middleware;
    $middleware->requireCVAccess($cvId);
}

function requireExportPermission() {
    global $middleware;
    $middleware->requireExportPermission();
}
?>
