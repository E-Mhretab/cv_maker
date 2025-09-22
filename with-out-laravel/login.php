<?php
require_once 'includes/session_helper.php';
require_once 'connection.php';

// Start session using helper
startSecureSession();
require_once 'includes/auth.php';
require_once 'audit/audit_logger.php';

// Create auth instance
$auth = new Auth($conn);

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    if ($user && $user['role'] === 'admin') {
        $redirect = $_GET['redirect'] ?? 'admin_dashboard.php';
    } else {
        $redirect = $_GET['redirect'] ?? 'manage/cv_list.php';
    }
    header('Location: ' . $redirect);
    exit;
}

$error = '';
$success = '';
$info = '';

// Check for special messages
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'export_required':
            $info = 'Please login or create an account to export your CV as PDF or XML.';
            break;
        case 'logged_out':
            $info = 'You have been successfully logged out.';
            break;
    }
}

// Handle login form submission
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = XSSProtection::sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            $result = $auth->login($username, $password);
            if ($result['success']) {
                // Log successful login
                $auditLogger = new AuditLogger($conn);
                $auditLogger->logAuth('LOGIN', $result['user']['id'], [
                    'username' => $result['user']['username'],
                    'role' => $result['user']['role']
                ]);
                
                // Debug session after login
                error_log("Login successful - Session ID: " . session_id() . ", User ID: " . $result['user']['id']);
                error_log("Session data after login: " . print_r($_SESSION, true));
                
            // Check if user is admin and redirect accordingly
            if ($result['user']['role'] === 'admin') {
                // For admin users, always go to admin dashboard unless it's a specific export redirect
                if (isset($_GET['redirect']) && strpos($_GET['redirect'], 'export') !== false) {
                    $redirect = $_GET['redirect'];
                } else {
                    $redirect = 'admin_dashboard.php';
                }
            } else {
                // For regular users, check if there's a redirect parameter
                if (isset($_GET['redirect'])) {
                    $redirect = $_GET['redirect'];
                } else {
                    $redirect = 'manage/cv_list.php';
                }
            }
                
                // Redirect immediately after successful login
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = $result['error'];
            }
        } catch (Exception $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}

// Handle registration form submission
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = XSSProtection::sanitizeInput($_POST['reg_username'] ?? '');
    $email = XSSProtection::sanitizeInput($_POST['reg_email'] ?? '');
    $password = $_POST['reg_password'] ?? '';
    $confirmPassword = $_POST['reg_confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        $result = $auth->register($username, $email, $password);
        if ($result['success']) {
            $success = 'Registration successful! You are now logged in. Redirecting to CV management...';
            
            // Check if there's a redirect parameter (from export attempt)
            if (isset($_GET['redirect'])) {
                $redirect = $_GET['redirect'];
            } else {
                // Default redirect for new users - go to CV management
                $redirect = 'manage/cv_list.php';
            }
            
            // Redirect immediately after successful registration
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = implode(', ', $result['errors']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CV Management System</title>
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
        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .auth-content {
            padding: 2rem;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-container">
            <div class="auth-header">
                <h1><i class="fas fa-user-shield me-2"></i>CV Management System</h1>
                <p class="mb-0">Sign in or create an account</p>
            </div>
            
            <div class="auth-content">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($info): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($info); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <ul class="nav nav-tabs" id="authTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content mt-4" id="authTabsContent">
                    <!-- Login Tab -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel">
                        <form method="POST" action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">
                            <input type="hidden" name="action" value="login">
                            <?php echo CSRFProtection::getTokenField(); ?>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username or Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>
                        </form>
                        
                    </div>
                    
                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="register" role="tabpanel">
                        <form method="POST" action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">
                            <input type="hidden" name="action" value="register">
                            <?php echo CSRFProtection::getTokenField(); ?>
                            
                            <div class="mb-3">
                                <label for="reg_username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                                </div>
                                <div class="form-text">3-50 characters, letters, numbers, and underscores only</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg_email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="reg_email" name="reg_email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg_password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="reg_password" name="reg_password" required>
                                </div>
                                <div class="form-text">Minimum 6 characters</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg_confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="reg_confirm_password" name="reg_confirm_password" required>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = document.querySelector('.nav-link.active');
            if (activeTab) {
                const targetId = activeTab.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    const firstInput = targetPane.querySelector('input');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            }
        });
        
        // Switch focus when tab changes
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetPane = document.querySelector(e.target.getAttribute('data-bs-target'));
                if (targetPane) {
                    const firstInput = targetPane.querySelector('input');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            });
        });
    </script>
</body>
</html>
