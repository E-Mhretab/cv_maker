<?php
require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'includes/middleware.php';
require_once 'templates/template_loader.php';

// Get Nathan's CV data from database with metadata
$cvQuery = "SELECT c.*, m.template_type, m.is_public, m.published_at 
            FROM cv c 
            LEFT JOIN cv_metadata m ON c.id = m.cv_id 
            WHERE c.name = 'Nathan Jethoe' LIMIT 1";
$cvResult = $conn->query($cvQuery);

if (!$cvResult) {
    die('<div class="container py-5"><div class="alert alert-danger">Database query failed: ' . htmlspecialchars($conn->error) . '</div></div>');
}

if ($cvResult->num_rows === 0) {
    die('<div class="container py-5"><div class="alert alert-danger">CV not found in database</div></div>');
}

$cv = $cvResult->fetch_assoc();

// Get work experience
$workQuery = "SELECT * FROM work_experience WHERE cv_id = ? ORDER BY work_start DESC";
$workStmt = $conn->prepare($workQuery);
$workStmt->bind_param("i", $cv['id']);
$workStmt->execute();
$workResult = $workStmt->get_result();

// Get education
$educationQuery = "SELECT * FROM education WHERE cv_id = ? ORDER BY education_start DESC";
$educationStmt = $conn->prepare($educationQuery);
$educationStmt->bind_param("i", $cv['id']);
$educationStmt->execute();
$educationResult = $educationStmt->get_result();

// Get skills
$skillsQuery = "SELECT * FROM skills WHERE cv_id = ? ORDER BY id";
$skillsStmt = $conn->prepare($skillsQuery);
$skillsStmt->bind_param("i", $cv['id']);
$skillsStmt->execute();
$skillsResult = $skillsStmt->get_result();

// Get languages
$languagesQuery = "SELECT * FROM languages WHERE cv_id = ? ORDER BY id";
$languagesStmt = $conn->prepare($languagesQuery);
$languagesStmt->bind_param("i", $cv['id']);
$languagesStmt->execute();
$languagesResult = $languagesStmt->get_result();

// Get hobbies
$hobbiesQuery = "SELECT * FROM hobbies WHERE cv_id = ? ORDER BY id";
$hobbiesStmt = $conn->prepare($hobbiesQuery);
$hobbiesStmt->bind_param("i", $cv['id']);
$hobbiesStmt->execute();
$hobbiesResult = $hobbiesStmt->get_result();

// Get user context
$userContext = $middleware->getUserContext();

// Ensure template type is set correctly for Nathan
$cv['template_type'] = 1; // Force Nathan template

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