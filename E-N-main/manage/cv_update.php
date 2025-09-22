<?php
require_once '../connection.php';
require_once '../audit/audit_logger.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cv_list.php');
    exit;
}

// Validate required fields
$requiredFields = ['cv_id', 'name', 'email', 'profile_summary'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        die("Error: Required field '$field' is missing.");
    }
}

// Sanitize and validate input
$cvId = (int)$_POST['cv_id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$profileSummary = trim($_POST['profile_summary']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.");
}

// Sanitize other fields
$phoneNumber = trim($_POST['phone_number'] ?? '');
$dateOfBirth = $_POST['date_of_birth'] ?? null;
$address = trim($_POST['address'] ?? '');
$linkedinProfile = trim($_POST['linkedin_profile'] ?? '');
$portfolio = trim($_POST['portfolio'] ?? '');

// Get template and public settings
$templateTypeId = (int)($_POST['template_type'] ?? 1);
$isPublic = isset($_POST['is_public']) ? 1 : 0;

// Validate URLs if provided
if ($linkedinProfile && !filter_var($linkedinProfile, FILTER_VALIDATE_URL)) {
    die("Error: Invalid LinkedIn profile URL.");
}
if ($portfolio && !filter_var($portfolio, FILTER_VALIDATE_URL)) {
    die("Error: Invalid portfolio URL.");
}

// Validate date of birth if provided
if ($dateOfBirth) {
    $dob = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
    if (!$dob || $dob->format('Y-m-d') !== $dateOfBirth) {
        die("Error: Invalid date of birth format.");
    }
}

try {
    // Get old CV data for audit logging
    $oldCvQuery = "SELECT c.*, m.template_type, m.is_public FROM cv c LEFT JOIN cv_metadata m ON c.id = m.cv_id WHERE c.id = ?";
    $oldCvStmt = $conn->prepare($oldCvQuery);
    $oldCvStmt->bind_param("i", $cvId);
    $oldCvStmt->execute();
    $oldCvData = $oldCvStmt->get_result()->fetch_assoc();
    $oldCvStmt->close();

    // Start transaction
    $conn->begin_transaction();

    // Update CV record
    $cvQuery = "UPDATE cv SET name = ?, address = ?, phone_number = ?, email = ?, date_of_birth = ?, linkedin_profile = ?, portfolio = ?, profile_summary = ? WHERE id = ?";
    $cvStmt = $conn->prepare($cvQuery);
    $cvStmt->bind_param("ssssssssi", $name, $address, $phoneNumber, $email, $dateOfBirth, $linkedinProfile, $portfolio, $profileSummary, $cvId);
    
    if (!$cvStmt->execute()) {
        throw new Exception("Error updating CV: " . $cvStmt->error);
    }
    
    $cvStmt->close();

    // Delete existing work experience
    $deleteWorkQuery = "DELETE FROM work_experience WHERE cv_id = ?";
    $deleteWorkStmt = $conn->prepare($deleteWorkQuery);
    $deleteWorkStmt->bind_param("i", $cvId);
    $deleteWorkStmt->execute();
    $deleteWorkStmt->close();

    // Insert updated work experience
    if (isset($_POST['work_title']) && is_array($_POST['work_title'])) {
        $workQuery = "INSERT INTO work_experience (cv_id, job_title, company_name, work_start, work_end, is_current, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $workStmt = $conn->prepare($workQuery);
        
        for ($i = 0; $i < count($_POST['work_title']); $i++) {
            $title = trim($_POST['work_title'][$i]);
            $company = trim($_POST['work_company'][$i]);
            $startDate = $_POST['work_start'][$i] ?: null;
            $endDate = $_POST['work_end'][$i] ?: null;
            $isCurrent = isset($_POST['work_current'][$i]) ? 1 : 0;
            $description = trim($_POST['work_description'][$i] ?? '');
            
            // Only insert if title and company are provided
            if ($title && $company) {
                $workStmt->bind_param("issssis", $cvId, $title, $company, $startDate, $endDate, $isCurrent, $description);
                if (!$workStmt->execute()) {
                    throw new Exception("Error updating work experience: " . $workStmt->error);
                }
            }
        }
        $workStmt->close();
    }

    // Delete existing education
    $deleteEducationQuery = "DELETE FROM education WHERE cv_id = ?";
    $deleteEducationStmt = $conn->prepare($deleteEducationQuery);
    $deleteEducationStmt->bind_param("i", $cvId);
    $deleteEducationStmt->execute();
    $deleteEducationStmt->close();

    // Insert updated education
    if (isset($_POST['education_degree']) && is_array($_POST['education_degree'])) {
        $educationQuery = "INSERT INTO education (cv_id, degree, institution, education_start, education_end, is_current, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $educationStmt = $conn->prepare($educationQuery);
        
        for ($i = 0; $i < count($_POST['education_degree']); $i++) {
            $degree = trim($_POST['education_degree'][$i]);
            $institution = trim($_POST['education_institution'][$i]);
            $startDate = $_POST['education_start'][$i] ?: null;
            $endDate = $_POST['education_end'][$i] ?: null;
            $isCurrent = isset($_POST['education_current'][$i]) ? 1 : 0;
            $description = trim($_POST['education_description'][$i] ?? '');
            
            // Only insert if degree and institution are provided
            if ($degree && $institution) {
                $educationStmt->bind_param("issssis", $cvId, $degree, $institution, $startDate, $endDate, $isCurrent, $description);
                if (!$educationStmt->execute()) {
                    throw new Exception("Error updating education: " . $educationStmt->error);
                }
            }
        }
        $educationStmt->close();
    }

    // Delete existing hobbies
    $deleteHobbiesQuery = "DELETE FROM hobbies WHERE cv_id = ?";
    $deleteHobbiesStmt = $conn->prepare($deleteHobbiesQuery);
    $deleteHobbiesStmt->bind_param("i", $cvId);
    $deleteHobbiesStmt->execute();
    $deleteHobbiesStmt->close();

    // Insert updated hobbies
    if (isset($_POST['hobby_name']) && is_array($_POST['hobby_name'])) {
        $hobbyQuery = "INSERT INTO hobbies (cv_id, hobby_name, description) VALUES (?, ?, ?)";
        $hobbyStmt = $conn->prepare($hobbyQuery);
        
        for ($i = 0; $i < count($_POST['hobby_name']); $i++) {
            $hobbyName = trim($_POST['hobby_name'][$i]);
            $hobbyDescription = trim($_POST['hobby_description'][$i] ?? '');
            
            // Only insert if hobby name is provided
            if ($hobbyName) {
                $hobbyStmt->bind_param("iss", $cvId, $hobbyName, $hobbyDescription);
                if (!$hobbyStmt->execute()) {
                    throw new Exception("Error updating hobby: " . $hobbyStmt->error);
                }
            }
        }
        $hobbyStmt->close();
    }

    // Update CV metadata (template type and public status)
    $metadataQuery = "INSERT INTO cv_metadata (cv_id, template_type, is_public) VALUES (?, ?, ?) 
                      ON DUPLICATE KEY UPDATE template_type = VALUES(template_type), is_public = VALUES(is_public)";
    $metadataStmt = $conn->prepare($metadataQuery);
    $metadataStmt->bind_param("iii", $cvId, $templateTypeId, $isPublic);
    
    if (!$metadataStmt->execute()) {
        throw new Exception("Error updating CV metadata: " . $metadataStmt->error);
    }
    $metadataStmt->close();

    // Commit transaction
    $conn->commit();

    // Log CV update in audit log
    $auditLogger = new AuditLogger($conn);
    $newCvData = [
        'name' => $name,
        'email' => $email,
        'template_type' => $templateTypeId,
        'is_public' => $isPublic
    ];
    $auditLogger->logCV('UPDATE', $cvId, $oldCvData['user_id'] ?? 0, $oldCvData, $newCvData);

    // Redirect to success page
    header("Location: cv_preview.php?id=" . $cvId . "&updated=1");
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Log error (in production, use proper logging)
    error_log("CV Update Error: " . $e->getMessage());
    
    // Redirect to error page instead of using die()
    header("Location: cv_edit.php?id=" . $cvId . "&error=" . urlencode($e->getMessage()));
    exit;
} finally {
    $conn->close();
}
?>
