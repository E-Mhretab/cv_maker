<?php
require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'audit/audit_logger.php';

// Create auth instance
$auth = new Auth($conn);
$user = $auth->getCurrentUser();

// Logout user
$auth->logout();

// Log logout if user was logged in
if ($user) {
    $auditLogger = new AuditLogger($conn);
    $auditLogger->logAuth('LOGOUT', $user['id'], [
        'username' => $user['username'],
        'role' => $user['role']
    ]);
}

// Redirect to home page
header('Location: index.php?message=logged_out');
exit;
?>
