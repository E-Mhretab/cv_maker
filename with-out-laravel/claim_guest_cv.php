<?php
/**
 * Claim Guest CV
 * Allows users to claim a specific guest CV
 */

// Start session first, before any output
session_start();

require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'includes/middleware.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Use global auth instance and initialize middleware
$auth = $GLOBALS['auth'] ?? new Auth($conn);
$middleware = new Middleware($auth);

$user = $auth->getCurrentUser();
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$cvId) {
    header('Location: user_profile.php');
    exit;
}

// Check if CV exists and can be claimed
$cvQuery = "SELECT * FROM cv WHERE id = ? AND user_id IS NULL AND LOWER(TRIM(email)) = LOWER(TRIM(?))";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("is", $cvId, $user['email']);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows === 0) {
    header('Location: user_profile.php?error=cv_not_found');
    exit;
}

$cv = $cvResult->fetch_assoc();
$cvStmt->close();

// Handle CV claiming
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'claim_cv') {
    // Claim the CV
    $updateQuery = "UPDATE cv SET user_id = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $user['id'], $cvId);
    
    if ($updateStmt->execute()) {
        header('Location: user_profile.php?message=cv_claimed');
        exit;
    } else {
        $error = 'Failed to claim CV. Please try again.';
    }
    $updateStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim CV - CV Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .claim-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-hand-holding-heart me-2"></i>Claim CV
            </span>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="user_profile.php">
                    <i class="fas fa-user me-1"></i>My Profile
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="claim-card p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                        <h2 class="h4">Claim Your CV</h2>
                        <p class="text-muted">This CV was created with your email address. Claim it to manage it with your account.</p>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- CV Preview -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>CV Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>CV Name:</h6>
                                    <p><?php echo htmlspecialchars($cv['name']); ?></p>
                                    
                                    <h6>Template:</h6>
                                    <p><span class="badge bg-primary"><?php echo ucfirst($cv['template_type']); ?></span></p>
                                    
                                    <h6>Email:</h6>
                                    <p><?php echo htmlspecialchars($cv['email']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Created:</h6>
                                    <p><?php echo date('M j, Y', strtotime($cv['created_at'])); ?></p>
                                    
                                    <h6>Summary:</h6>
                                    <p><?php echo htmlspecialchars(substr($cv['profile_summary'], 0, 200)); ?>
                                    <?php if (strlen($cv['profile_summary']) > 200): ?>...<?php endif; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Claim Form -->
                    <form method="POST">
                        <input type="hidden" name="action" value="claim_cv">
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>What happens when you claim this CV?</strong>
                            <ul class="mb-0 mt-2">
                                <li>This CV will be linked to your account</li>
                                <li>You can edit, delete, and export this CV</li>
                                <li>You can make it public or private</li>
                                <li>You will have full control over this CV</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-hand-holding-heart me-2"></i>Claim This CV
                            </button>
                            <a href="user_profile.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
