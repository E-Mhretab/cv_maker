<?php
/**
 * Session Management Interface
 * Allows users to view and manage their active sessions
 */

// Start session using helper
require_once '../includes/session_helper.php';
startSecureSession();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once 'session_middleware.php';

// Create auth instance
$auth = new Auth($conn);

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user = $auth->getCurrentUser();
$sessionMiddleware = new SessionMiddleware($conn);

// Handle session actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'destroy_session':
            $sessionId = $_POST['session_id'] ?? '';
            if ($sessionId && $sessionId !== session_id()) {
                $sessionManager = $sessionMiddleware->getSessionManager();
                $sessionManager->destroySession($sessionId);
                $success = "Session destroyed successfully.";
            }
            break;
            
        case 'destroy_all_other':
            $currentSessionId = session_id();
            $sessions = $auth->getUserSessions();
            $sessionManager = $sessionMiddleware->getSessionManager();
            
            foreach ($sessions as $session) {
                if ($session['session_id'] !== $currentSessionId) {
                    $sessionManager->destroySession($session['session_id']);
                }
            }
            $success = "All other sessions destroyed successfully.";
            break;
            
        case 'destroy_all':
            $auth->destroyAllSessions();
            $auth->logout();
            header('Location: ../login.php?message=all_sessions_destroyed');
            exit;
            break;
    }
}

// Get user's active sessions
$sessions = $auth->getUserSessions();
$currentSessionId = session_id();

// Debug information
if (isset($_GET['debug'])) {
    error_log("Session Management Debug:");
    error_log("Current Session ID: " . $currentSessionId);
    error_log("User ID: " . ($user['id'] ?? 'Not set'));
    error_log("Session Manager Available: " . ($auth->isSessionManagerAvailable() ? 'Yes' : 'No'));
    error_log("Sessions Found: " . count($sessions));
    error_log("Session Data: " . print_r($_SESSION, true));
    
    // Check database directly
    if ($auth->isSessionManagerAvailable()) {
        $stmt = $conn->prepare("SELECT * FROM user_sessions WHERE user_id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $dbSessions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        error_log("Database sessions: " . count($dbSessions));
        error_log("Database session data: " . print_r($dbSessions, true));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Management - <?php echo htmlspecialchars($user['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .session-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .session-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .current-session {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }
        .device-info {
            font-size: 0.9em;
            color: #6c757d;
        }
        .session-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .session-card:hover .session-actions {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="../manage/cv_list.php" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to CV Management
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user['username']); ?>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h2><i class="fas fa-shield-alt me-2"></i>Session Management</h2>
                <p class="text-muted">Manage your active sessions and security settings</p>
            </div>
            <div class="col-auto">
                <a href="?debug=1" class="btn btn-outline-info">
                    <i class="fas fa-bug me-1"></i>Debug
                </a>
                <a href="debug_sessions.php" class="btn btn-outline-warning">
                    <i class="fas fa-search me-1"></i>Full Debug
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Debug Information -->
        <?php if (isset($_GET['debug'])): ?>
            <div class="alert alert-info">
                <h5>Debug Information:</h5>
                <p><strong>Current Session ID:</strong> <?php echo session_id(); ?></p>
                <p><strong>User ID:</strong> <?php echo $user['id'] ?? 'Not set'; ?></p>
                <p><strong>Session Manager Available:</strong> <?php echo $auth->isSessionManagerAvailable() ? 'Yes' : 'No'; ?></p>
                <p><strong>Sessions Found:</strong> <?php echo count($sessions); ?></p>
                <p><strong>Session Data:</strong></p>
                <pre><?php print_r($_SESSION); ?></pre>
            </div>
        <?php endif; ?>

        <!-- Session Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?php echo count($sessions); ?></h5>
                        <p class="card-text">Active Sessions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success"><?php echo count(array_filter($sessions, function($s) use ($currentSessionId) { return $s['session_id'] === $currentSessionId; })); ?></h5>
                        <p class="card-text">Current Session</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-warning"><?php echo count(array_filter($sessions, function($s) use ($currentSessionId) { return $s['session_id'] !== $currentSessionId; })); ?></h5>
                        <p class="card-text">Other Sessions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-info">5</h5>
                        <p class="card-text">Max Sessions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Bulk Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#destroyOtherModal">
                                <i class="fas fa-trash me-1"></i>Destroy All Other Sessions
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#destroyAllModal">
                                <i class="fas fa-exclamation-triangle me-1"></i>Destroy All Sessions
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Destroying all sessions will log you out from all devices.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Active Sessions</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($sessions)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No active sessions found</p>
                                <?php if (!$auth->isSessionManagerAvailable()): ?>
                                    <p class="text-warning">Session Manager is not available. This might be because the user_sessions table doesn't exist or there's a configuration issue.</p>
                                    <p class="text-info">Check the debug information above for more details.</p>
                                <?php else: ?>
                                    <p class="text-info">No active sessions found. Try logging out and logging back in to create a new session.</p>
                                    <p class="text-muted">Sessions are automatically created when you log in and expire after 1 hour of inactivity.</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($sessions as $session): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card session-card <?php echo $session['session_id'] === $currentSessionId ? 'current-session' : ''; ?>">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title">
                                                            <?php if ($session['session_id'] === $currentSessionId): ?>
                                                                <i class="fas fa-circle text-success me-1"></i>Current Session
                                                            <?php else: ?>
                                                                <i class="fas fa-circle text-primary me-1"></i>Other Session
                                                            <?php endif; ?>
                                                        </h6>
                                                        <div class="device-info">
                                                            <div><strong>Device ID:</strong> <?php echo substr($session['device_id'], 0, 16); ?>...</div>
                                                            <div><strong>IP Address:</strong> <?php echo htmlspecialchars($session['ip_address']); ?></div>
                                                            <div><strong>User Agent:</strong> <?php echo htmlspecialchars(substr($session['user_agent'], 0, 50)); ?>...</div>
                                                            <div><strong>Last Activity:</strong> <?php echo date('M j, Y H:i:s', strtotime($session['last_activity'])); ?></div>
                                                            <div><strong>Created:</strong> <?php echo date('M j, Y H:i:s', strtotime($session['created_at'])); ?></div>
                                                            <div><strong>Expires:</strong> <?php echo date('M j, Y H:i:s', strtotime($session['expires_at'])); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="session-actions">
                                                        <?php if ($session['session_id'] !== $currentSessionId): ?>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="destroy_session">
                                                                <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($session['session_id']); ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to destroy this session?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Destroy Other Sessions Modal -->
    <div class="modal fade" id="destroyOtherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Destroy All Other Sessions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will destroy all your other active sessions, keeping only the current one.</p>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="action" value="destroy_all_other">
                        <button type="submit" class="btn btn-warning">Destroy Other Sessions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Destroy All Sessions Modal -->
    <div class="modal fade" id="destroyAllModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Destroy All Sessions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will destroy ALL your active sessions, including the current one.</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>You will be logged out immediately.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="action" value="destroy_all">
                        <button type="submit" class="btn btn-danger">Destroy All Sessions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
