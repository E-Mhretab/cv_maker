<?php
require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../templates/template_loader.php';

// Get CV ID from URL
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$created = isset($_GET['created']) ? true : false;

if (!$cvId) {
    header('Location: index.php');
    exit;
}

// Check if user can access this CV
requireCVAccess($cvId);

// Get user context
$userContext = $middleware->getUserContext();

// Add created flag to user context for templates
$userContext['is_guest_creator'] = $created;

// Get CV data from database with metadata
$cvQuery = "SELECT c.*, m.template_type, m.is_public, m.published_at 
            FROM cv c 
            LEFT JOIN cv_metadata m ON c.id = m.cv_id 
            WHERE c.id = ? LIMIT 1";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("i", $cvId);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows === 0) {
    die('<div class="container py-5"><div class="alert alert-danger">CV not found</div></div>');
}

$cv = $cvResult->fetch_assoc();
$cvStmt->close();

// Get work experience
$workQuery = "SELECT * FROM work_experience WHERE cv_id = ? ORDER BY work_start DESC";
$workStmt = $conn->prepare($workQuery);
$workStmt->bind_param("i", $cvId);
$workStmt->execute();
$workResult = $workStmt->get_result();

// Get education
$educationQuery = "SELECT * FROM education WHERE cv_id = ? ORDER BY education_start DESC";
$educationStmt = $conn->prepare($educationQuery);
$educationStmt->bind_param("i", $cvId);
$educationStmt->execute();
$educationResult = $educationStmt->get_result();

// Get skills
$skillsQuery = "SELECT * FROM skills WHERE cv_id = ? ORDER BY id";
$skillsStmt = $conn->prepare($skillsQuery);
$skillsStmt->bind_param("i", $cvId);
$skillsStmt->execute();
$skillsResult = $skillsStmt->get_result();

// Get languages
$languagesQuery = "SELECT * FROM languages WHERE cv_id = ? ORDER BY id";
$languagesStmt = $conn->prepare($languagesQuery);
$languagesStmt->bind_param("i", $cvId);
$languagesStmt->execute();
$languagesResult = $languagesStmt->get_result();

// Get hobbies
$hobbiesQuery = "SELECT * FROM hobbies WHERE cv_id = ? ORDER BY id";
$hobbiesStmt = $conn->prepare($hobbiesQuery);
$hobbiesStmt->bind_param("i", $cvId);
$hobbiesStmt->execute();
$hobbiesResult = $hobbiesStmt->get_result();

// Show success message if CV was just created
if ($created) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <i class="fas fa-check-circle me-2"></i>CV created successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}

// Show publication status
if (isset($cv['is_public']) && $cv['is_public']) {
    echo '<div class="alert alert-info alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
            <i class="fas fa-globe me-2"></i>This CV is public and visible to everyone.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
} else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
            <i class="fas fa-lock me-2"></i>This CV is private and only visible to you.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}

            // Render CV using template system with user context
            renderCVWithContext($cv, $workResult, $educationResult, $hobbiesResult, $userContext, $auth, $skillsResult, $languagesResult);

// Close database connections
$workStmt->close();
$educationStmt->close();
$skillsStmt->close();
$languagesStmt->close();
$hobbiesStmt->close();
$conn->close();
?>
