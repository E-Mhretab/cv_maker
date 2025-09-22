<?php
require_once '../connection.php';
require_once '../includes/template_utils.php';
require_once '../audit/audit_logger.php';

// Get CV ID from URL
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$cvId) {
    header('Location: cv_list.php');
    exit;
}

// Get CV data for confirmation
$cvQuery = "SELECT c.name, m.template_type 
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

// Convert template type to name if it's numeric
if (isset($cv['template_type']) && is_numeric($cv['template_type'])) {
    $cv['template_type'] = getTemplateName($cv['template_type']);
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
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

        // Delete work experience
        $deleteWorkQuery = "DELETE FROM work_experience WHERE cv_id = ?";
        $deleteWorkStmt = $conn->prepare($deleteWorkQuery);
        $deleteWorkStmt->bind_param("i", $cvId);
        $deleteWorkStmt->execute();
        $deleteWorkStmt->close();

        // Delete education
        $deleteEducationQuery = "DELETE FROM education WHERE cv_id = ?";
        $deleteEducationStmt = $conn->prepare($deleteEducationQuery);
        $deleteEducationStmt->bind_param("i", $cvId);
        $deleteEducationStmt->execute();
        $deleteEducationStmt->close();

        // Delete hobbies
        $deleteHobbiesQuery = "DELETE FROM hobbies WHERE cv_id = ?";
        $deleteHobbiesStmt = $conn->prepare($deleteHobbiesQuery);
        $deleteHobbiesStmt->bind_param("i", $cvId);
        $deleteHobbiesStmt->execute();
        $deleteHobbiesStmt->close();

        // Delete CV
        $deleteCvQuery = "DELETE FROM cv WHERE id = ?";
        $deleteCvStmt = $conn->prepare($deleteCvQuery);
        $deleteCvStmt->bind_param("i", $cvId);
        $deleteCvStmt->execute();
        $deleteCvStmt->close();

        // Commit transaction
        $conn->commit();

        // Log CV deletion in audit log
        $auditLogger = new AuditLogger($conn);
        $auditLogger->logCV('DELETE', $cvId, $oldCvData['user_id'] ?? 0, $oldCvData, null);

        // Redirect to success page
        header("Location: cv_list.php?deleted=1");
        exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Log error
        error_log("CV Deletion Error: " . $e->getMessage());
        
        // Show error
        die("Error deleting CV: " . $e->getMessage());
    } finally {
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete CV - <?php echo htmlspecialchars($cv['name']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .danger-section {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .cv-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
        }
        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="cv_list.php" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to CV List
            </a>
            <span class="navbar-text">
                <i class="fas fa-trash me-2"></i>Delete CV
            </span>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Danger Warning -->
                <div class="danger-section text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h1 class="h3 mb-3">Delete CV Confirmation</h1>
                    <p class="mb-0">This action cannot be undone. All data will be permanently removed.</p>
                </div>

                <!-- CV Information -->
                <div class="cv-info">
                    <h3 class="h5 mb-3">
                        <i class="fas fa-file-alt me-2"></i>CV Information
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($cv['name']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Template:</strong> 
                                <span class="badge bg-<?php echo ($cv['template_type'] ?? 'nathan') === 'nathan' ? 'primary' : 'success'; ?>">
                                    <?php echo ucfirst($cv['template_type'] ?? 'nathan'); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- What will be deleted -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="h6 mb-0">
                            <i class="fas fa-list me-2"></i>What will be deleted:
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-user text-danger me-2"></i>
                                Personal information and profile summary
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-briefcase text-danger me-2"></i>
                                All work experience entries
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-graduation-cap text-danger me-2"></i>
                                All education entries
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-heart text-danger me-2"></i>
                                All hobbies and interests
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Confirmation Form -->
                <form method="POST" class="text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Warning:</strong> This action is permanent and cannot be undone!
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="cv_list.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" name="confirm_delete" class="btn btn-danger btn-lg" 
                                onclick="return confirm('Are you absolutely sure you want to delete this CV? This action cannot be undone!')">
                            <i class="fas fa-trash me-2"></i>Yes, Delete CV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
