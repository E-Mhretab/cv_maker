<?php
/**
 * CV Publish/Unpublish Handler
 */

// Start session first, before any output
session_start();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';

// Use global auth instance
$auth = $GLOBALS['auth'] ?? new Auth($conn);

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user = $auth->getCurrentUser();
$cvId = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$cvId || !in_array($action, ['publish', 'unpublish'])) {
    header('Location: cv_list.php');
    exit;
}

// Check if user can access this CV
if (!$auth->canAccessCV($cvId)) {
    header('Location: cv_list.php');
    exit;
}

// Get current CV status
$stmt = $conn->prepare("SELECT c.id, c.name, m.is_public FROM cv c LEFT JOIN cv_metadata m ON c.id = m.cv_id WHERE c.id = ?");
$stmt->bind_param("i", $cvId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: cv_list.php');
    exit;
}

$cv = $result->fetch_assoc();
$stmt->close();

// Perform the action
if ($action === 'publish') {
    $isPublic = 1;
    $publishedAt = date('Y-m-d H:i:s');
    $message = "CV '{$cv['name']}' has been published and is now visible to everyone.";
} else {
    $isPublic = 0;
    $publishedAt = null;
    $message = "CV '{$cv['name']}' has been unpublished and is now private.";
}

// Update the CV metadata
$updateStmt = $conn->prepare("UPDATE cv_metadata SET is_public = ?, published_at = ? WHERE cv_id = ?");
$updateStmt->bind_param("isi", $isPublic, $publishedAt, $cvId);

if ($updateStmt->execute()) {
    // Redirect back to CV list with success message
    header("Location: cv_list.php?message=" . urlencode($message) . "&type=success");
} else {
    // Redirect back with error message
    header("Location: cv_list.php?message=" . urlencode("Failed to update CV publication status.") . "&type=error");
}

$updateStmt->close();
exit;
?>
