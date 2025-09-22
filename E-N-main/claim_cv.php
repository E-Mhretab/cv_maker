<?php
/**
 * Claim CV Page
 * Allows users to claim their guest CVs by providing the CV ID and email
 */

require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'includes/middleware.php';

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$message = '';
$error = '';

// Handle CV claiming
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'claim_cv') {
    $cvId = (int)($_POST['cv_id'] ?? 0);
    $email = XSSProtection::sanitizeInput($_POST['email'] ?? '');
    
    if (!$cvId || !$email) {
        $error = 'Please provide both CV ID and email address.';
    } else {
        // Check if CV exists and belongs to guest user
        $cvQuery = "SELECT * FROM cv WHERE id = ? AND user_id IS NULL AND email = ?";
        $cvStmt = $conn->prepare($cvQuery);
        $cvStmt->bind_param("is", $cvId, $email);
        $cvStmt->execute();
        $cvResult = $cvStmt->get_result();
        
        if ($cvResult->num_rows === 0) {
            $error = 'CV not found or email does not match. Please check your CV ID and email address.';
        } else {
            // Claim the CV
            $updateQuery = "UPDATE cv SET user_id = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $user['id'], $cvId);
            
            if ($updateStmt->execute()) {
                $message = 'CV successfully claimed! You can now manage it from your profile.';
                header('Location: user_profile.php?message=' . urlencode($message));
                exit;
            } else {
                $error = 'Failed to claim CV. Please try again.';
            }
            $updateStmt->close();
        }
        $cvStmt->close();
    }
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
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="user_profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="index.php"><i class="fas fa-home me-2"></i>Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="claim-card p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                        <h2 class="h4">Claim Your Guest CV</h2>
                        <p class="text-muted">If you created a CV as a guest user, you can claim it here to manage it with your account.</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($message): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="action" value="claim_cv">
                        
                        <div class="mb-3">
                            <label for="cv_id" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>CV ID
                            </label>
                            <input type="number" class="form-control" id="cv_id" name="cv_id" 
                                   placeholder="Enter your CV ID" required>
                            <div class="form-text">You can find your CV ID in the URL when viewing your CV.</div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Enter the email address used in your CV" required>
                            <div class="form-text">This should be the same email address you used when creating your CV.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-hand-holding-heart me-2"></i>Claim CV
                            </button>
                            <a href="user_profile.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Profile
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <h6 class="text-muted">Need Help?</h6>
                        <p class="small text-muted">
                            If you can't find your CV ID, you can:
                        </p>
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check me-1"></i>Check the URL when viewing your CV</li>
                            <li><i class="fas fa-check me-1"></i>Look for the number after "id=" in the URL</li>
                            <li><i class="fas fa-check me-1"></i>Make sure you're using the exact email from your CV</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>