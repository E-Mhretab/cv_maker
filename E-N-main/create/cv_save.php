<?php
require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../includes/validation.php';
require_once '../audit/audit_logger.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cv_create.php');
    exit;
}

// Get user context
$userContext = $middleware->getUserContext();

// Validate required fields
$requiredFields = ['template_type', 'name', 'email', 'profile_summary'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        die("Error: Required field '$field' is missing.");
    }
}

// Validate email format
$emailValidation = validateEmail($_POST['email']);
if (!$emailValidation['valid']) {
    die("Error: " . $emailValidation['message']);
}

// Sanitize and validate input
$templateType = $_POST['template_type'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$profileSummary = trim($_POST['profile_summary']);

// Validate and convert template type
$templateTypeMap = [
    'nathan' => 1,
    'esey' => 2,
    'mirian' => 3
];

if (!array_key_exists($templateType, $templateTypeMap)) {
    die("Error: Invalid template type.");
}

$templateTypeId = $templateTypeMap[$templateType];

// Sanitize other fields
$phoneNumber = trim($_POST['phone_number'] ?? '');
$dateOfBirth = $_POST['date_of_birth'] ?? null;
$address = trim($_POST['address'] ?? '');
$linkedinProfile = trim($_POST['linkedin_profile'] ?? '');
$portfolio = trim($_POST['portfolio'] ?? '');

// Validate phone number format
$phoneValidation = validatePhoneNumber($phoneNumber);
if (!$phoneValidation['valid']) {
    die("Error: " . $phoneValidation['message']);
}

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

// Validate work experience dates
$workValidation = validateWorkExperienceDates($_POST);
if (!$workValidation['valid']) {
    die("Error: " . implode(', ', $workValidation['errors']));
}

// Validate education dates
$educationValidation = validateEducationDates($_POST);
if (!$educationValidation['valid']) {
    die("Error: " . implode(', ', $educationValidation['errors']));
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert CV record (personal info only)
    $userId = $userContext['is_logged_in'] ? $userContext['user']['id'] : null;
    
    $cvQuery = "INSERT INTO cv (name, address, phone_number, email, date_of_birth, linkedin_profile, portfolio, profile_summary, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $cvStmt = $conn->prepare($cvQuery);
    $cvStmt->bind_param("ssssssssi", $name, $address, $phoneNumber, $email, $dateOfBirth, $linkedinProfile, $portfolio, $profileSummary, $userId);
    
    if (!$cvStmt->execute()) {
        throw new Exception("Error inserting CV: " . $cvStmt->error);
    }
    
    $cvId = $conn->insert_id;
    $cvStmt->close();
    
    // Insert CV metadata
    $isPublic = isset($_POST['is_public']) ? 1 : 0;
    $publishedAt = $isPublic ? date('Y-m-d H:i:s') : null;
    
    $metadataQuery = "INSERT INTO cv_metadata (cv_id, template_type, is_public, published_at) VALUES (?, ?, ?, ?)";
    $metadataStmt = $conn->prepare($metadataQuery);
    $metadataStmt->bind_param("iiis", $cvId, $templateTypeId, $isPublic, $publishedAt);
    
    if (!$metadataStmt->execute()) {
        throw new Exception("Error inserting CV metadata: " . $metadataStmt->error);
    }
    
    $metadataStmt->close();

    // Insert work experience
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
                    throw new Exception("Error inserting work experience: " . $workStmt->error);
                }
            }
        }
        $workStmt->close();
    }

    // Insert education
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
                    throw new Exception("Error inserting education: " . $educationStmt->error);
                }
            }
        }
        $educationStmt->close();
    }

    // Insert skills
    if (isset($_POST['skill_name']) && is_array($_POST['skill_name'])) {
        $skillQuery = "INSERT INTO skills (cv_id, skill_name, description) VALUES (?, ?, ?)";
        $skillStmt = $conn->prepare($skillQuery);
        
        for ($i = 0; $i < count($_POST['skill_name']); $i++) {
            $skillName = trim($_POST['skill_name'][$i]);
            $skillDescription = trim($_POST['skill_description'][$i] ?? '');
            
            // Only insert if skill name is provided
            if ($skillName) {
                $skillStmt->bind_param("iss", $cvId, $skillName, $skillDescription);
                if (!$skillStmt->execute()) {
                    throw new Exception("Error inserting skill: " . $skillStmt->error);
                }
            }
        }
        $skillStmt->close();
    }

    // Insert languages
    if (isset($_POST['language_name']) && is_array($_POST['language_name'])) {
        $languageQuery = "INSERT INTO languages (cv_id, language_name, proficiency) VALUES (?, ?, ?)";
        $languageStmt = $conn->prepare($languageQuery);
        
        for ($i = 0; $i < count($_POST['language_name']); $i++) {
            $languageName = trim($_POST['language_name'][$i]);
            $proficiency = $_POST['language_proficiency'][$i] ?? 'basic';
            
            // Only insert if language name is provided
            if ($languageName) {
                $languageStmt->bind_param("iss", $cvId, $languageName, $proficiency);
                if (!$languageStmt->execute()) {
                    throw new Exception("Error inserting language: " . $languageStmt->error);
                }
            }
        }
        $languageStmt->close();
    }

    // Insert hobbies
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
                    throw new Exception("Error inserting hobby: " . $hobbyStmt->error);
                }
            }
        }
        $hobbyStmt->close();
    }

    // Commit transaction
    $conn->commit();

    // Log CV creation in audit log
    $auditLogger = new AuditLogger($conn);
    $cvData = [
        'name' => $name,
        'email' => $email,
        'template_type' => $templateTypeId,
        'is_public' => $isPublic
    ];
    $auditLogger->logCV('INSERT', $cvId, $userId ?? 0, null, $cvData);

    // Redirect to success page or CV view
    header("Location: cv_view.php?id=" . $cvId . "&created=1");
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Log error (in production, use proper logging)
    error_log("CV Creation Error: " . $e->getMessage());
    
    // Show user-friendly error
    die("Error creating CV: " . $e->getMessage() . "<br><a href='cv_create.php'>Try again</a>");
} finally {
    $conn->close();
}
?>
