<?php
/**
 * User Profile Page
 * Shows user information and their CVs after login
 */

// Start session first, before any output
session_start();

require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'includes/middleware.php';
require_once 'includes/template_utils.php';

// Use global auth instance
$auth = $GLOBALS['auth'] ?? new Auth($conn);
$middleware = new Middleware($auth);

// Simple session check first
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();

// Redirect admin users to admin dashboard
if ($user && $user['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

// Get user's CVs with metadata
$cvQuery = "SELECT c.id, c.name, c.profile_summary, m.created_at, c.email,
                   m.template_type, m.is_public, m.published_at
            FROM cv c 
            LEFT JOIN cv_metadata m ON c.id = m.cv_id 
            WHERE c.user_id = ? 
            ORDER BY m.created_at DESC";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("i", $user['id']);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

$cvs = [];
while ($row = $cvResult->fetch_assoc()) {
    $cvs[] = $row;
}
$cvStmt->close();

// Check if user has any guest CVs that can be claimed (same email)
$guestCvQuery = "SELECT c.id, c.name, c.profile_summary, m.created_at,
                        m.template_type
                 FROM cv c 
                 LEFT JOIN cv_metadata m ON c.id = m.cv_id 
                 WHERE c.user_id IS NULL AND c.email = ? 
                 ORDER BY m.created_at DESC";
$guestCvStmt = $conn->prepare($guestCvQuery);
$guestCvStmt->bind_param("s", $user['email']);
$guestCvStmt->execute();
$guestCvResult = $guestCvStmt->get_result();

$guestCvs = [];
while ($row = $guestCvResult->fetch_assoc()) {
    $guestCvs[] = $row;
}
$guestCvStmt->close();

// Handle CV actions
$message = '';
$error = '';

if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'cv_claimed':
            $message = 'CV successfully claimed! You can now manage it from your profile.';
            break;
        default:
            $message = $_GET['message'];
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'cv_not_found':
            $error = 'CV not found or cannot be claimed.';
            break;
        default:
            $error = $_GET['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CV Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .cv-card {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }
        .cv-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-user me-2"></i>My Profile
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="manage/cv_list.php"><i class="fas fa-list me-2"></i>Manage CVs</a></li>
                        <li><a class="dropdown-item" href="create/cv_create.php"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php"><i class="fas fa-home me-2"></i>Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h1 class="h3 mb-1"><?php echo htmlspecialchars($user['username']); ?></h1>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($user['email']); ?></p>
                            <span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="create/cv_create.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create CV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1"><?php echo count($cvs); ?></h3>
                    <p class="mb-0">Total CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1"><?php echo count(array_filter($cvs, function($cv) { return $cv['is_public']; })); ?></h3>
                    <p class="mb-0">Public CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1"><?php echo count(array_filter($cvs, function($cv) { return !$cv['is_public']; })); ?></h3>
                    <p class="mb-0">Private CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1"><?php echo count(array_unique(array_column($cvs, 'template_type'))); ?></h3>
                    <p class="mb-0">Templates Used</p>
                </div>
            </div>
        </div>

        <!-- Guest CVs Section (if any) -->
        <?php if (!empty($guestCvs)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-link me-2"></i>Linked CVs
                        </h2>
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        We found CVs created with your email address. These CVs have been automatically linked to your account.
                    </div>
                    <div class="row">
                        <?php foreach ($guestCvs as $guestCv): ?>
                            <div class="col-lg-6 col-xl-4 mb-4">
                                <div class="cv-card card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($guestCv['name']); ?></h5>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-hand-holding-heart me-1"></i>Guest
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-3">
                                            <?php echo htmlspecialchars(substr($guestCv['profile_summary'], 0, 100)); ?>
                                            <?php if (strlen($guestCv['profile_summary']) > 100): ?>...<?php endif; ?>
                                        </p>
                                        <div class="text-muted small mb-3">
                                            <i class="fas fa-calendar me-1"></i>
                                            Created: <?php echo date('M j, Y', strtotime($guestCv['created_at'])); ?>
                                        </div>
                                        <div class="d-grid">
                                            <a href="manage/cv_preview.php?id=<?php echo $guestCv['id']; ?>" class="btn btn-primary">
                                                <i class="fas fa-eye me-2"></i>View CV
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- CVs Section -->
        <div class="row">
            <div class="col-12">
                <div class="profile-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-file-alt me-2"></i>My CVs
                        </h2>
                        <a href="create/cv_create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New CV
                        </a>
                    </div>

                    <?php if (empty($cvs)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No CVs Found</h4>
                            <p class="text-muted">You haven't created any CVs yet. Create your first CV to get started!</p>
                            <a href="create/cv_create.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Your First CV
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($cvs as $cv): ?>
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="cv-card card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($cv['name']); ?></h5>
                                                <div class="d-flex flex-column align-items-end">
                                                    <span class="badge bg-<?php echo (is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type'])) === 1 ? 'primary' : 'success'; ?> mb-1">
                                                        <?php echo getTemplateDisplayName(is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type'])); ?>
                                                    </span>
                                                    <?php if ($cv['is_public']): ?>
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                                            <i class="fas fa-globe me-1"></i>Public
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                            <i class="fas fa-lock me-1"></i>Private
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <p class="card-text text-muted small mb-3">
                                                <?php echo htmlspecialchars(substr($cv['profile_summary'], 0, 100)); ?>
                                                <?php if (strlen($cv['profile_summary']) > 100): ?>...<?php endif; ?>
                                            </p>
                                            
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                Created: <?php echo date('M j, Y', strtotime($cv['created_at'])); ?>
                                                <?php if ($cv['published_at']): ?>
                                                    <br><i class="fas fa-globe me-1"></i>
                                                    Published: <?php echo date('M j, Y', strtotime($cv['published_at'])); ?>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="create/cv_view.php?id=<?php echo $cv['id']; ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="manage/cv_edit.php?id=<?php echo $cv['id']; ?>" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($cv['is_public']): ?>
                                                    <a href="manage/cv_publish.php?id=<?php echo $cv['id']; ?>&action=unpublish" class="btn btn-outline-secondary btn-sm" title="Make Private">
                                                        <i class="fas fa-lock"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="manage/cv_publish.php?id=<?php echo $cv['id']; ?>&action=publish" class="btn btn-outline-success btn-sm" title="Make Public">
                                                        <i class="fas fa-globe"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="manage/cv_delete.php?id=<?php echo $cv['id']; ?>" class="btn btn-outline-danger btn-sm" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this CV?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
