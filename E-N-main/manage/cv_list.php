<?php
// Start session first, before any output
session_start();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../includes/template_utils.php';

// Create auth instance and initialize middleware
$auth = new Auth($conn);
$middleware = new Middleware($auth);

// Get user context
$userContext = $middleware->getUserContext();

// Get search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$templateFilter = isset($_GET['template']) ? $_GET['template'] : '';

// Build query with filters and user restrictions
$whereConditions = [];
$params = [];
$paramTypes = '';

// Add user restriction (unless admin)
if (!$userContext['is_admin']) {
    if ($userContext['is_logged_in']) {
        // Show CVs owned by user OR CVs created with same email (guest CVs that can be claimed)
        $whereConditions[] = "(c.user_id = ? OR (c.user_id IS NULL AND c.email = ?))";
        $params[] = $userContext['user']['id'];
        $params[] = $userContext['user']['email'];
        $paramTypes .= 'is';
    } else {
        // Guest can see CVs they created (user_id IS NULL)
        $whereConditions[] = "c.user_id IS NULL";
    }
}

if (!empty($search)) {
    $whereConditions[] = "(c.name LIKE ? OR c.profile_summary LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $paramTypes .= 'ss';
}

if (!empty($templateFilter)) {
    $whereConditions[] = "m.template_type = ?";
    $params[] = $templateFilter;
    $paramTypes .= 's';
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

$query = "SELECT c.id, c.name, c.profile_summary, c.user_id, c.email,
                 m.created_at, m.template_type, m.is_public, m.published_at 
          FROM cv c 
          LEFT JOIN cv_metadata m ON c.id = m.cv_id 
          $whereClause 
          ORDER BY m.created_at DESC, c.id DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$cvs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Management - List All CVs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cv-card {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }
        .cv-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .template-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
        .search-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Fixed Navigation Header -->
    <nav class="navbar navbar-dark bg-primary fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; box-shadow: 0 2px 20px rgba(0,0,0,0.15);">
        <div class="container">
            <a href="../index.php" class="navbar-brand fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
            <span class="navbar-text fw-semibold">
                <i class="fas fa-cogs me-2"></i>CV Management
            </span>
            
            <!-- User Menu -->
            <div class="navbar-nav ms-auto">
                <?php if ($userContext['is_logged_in']): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($userContext['user']['username']); ?>
                            <span class="badge bg-light text-dark ms-1"><?php echo ucfirst($userContext['role']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../create/cv_create_form.php"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="../login.php" class="nav-link">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="padding-top: 120px;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-3">
                    <i class="fas fa-list me-2"></i>CV Management
                </h1>
                <p class="text-muted">Manage all CVs in the system</p>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_GET['message'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <?php if (isset($_GET['type']) && $_GET['type'] === 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_GET['message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($_GET['message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1"><?php echo count($cvs); ?></h3>
                    <p class="mb-0">Total CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1"><?php echo count(array_filter($cvs, function($cv) { 
                        $templateId = is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type']);
                        return $templateId === 1; 
                    })); ?></h3>
                    <p class="mb-0">Nathan Templates</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1"><?php echo count(array_filter($cvs, function($cv) { 
                        $templateId = is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type']);
                        return $templateId === 2; 
                    })); ?></h3>
                    <p class="mb-0">Esey Templates</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1"><?php echo count(array_filter($cvs, function($cv) { return $cv['created_at'] && date('Y-m-d', strtotime($cv['created_at'])) === date('Y-m-d'); })); ?></h3>
                    <p class="mb-0">Created Today</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="search-section">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search CVs</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Search by name or summary...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="template" class="form-label">Filter by Template</label>
                    <select class="form-select" id="template" name="template">
                        <option value="">All Templates</option>
                        <option value="1" <?php echo $templateFilter === '1' ? 'selected' : ''; ?>>Nathan Template</option>
                        <option value="2" <?php echo $templateFilter === '2' ? 'selected' : ''; ?>>Esey Template</option>
                        <option value="3" <?php echo $templateFilter === '3' ? 'selected' : ''; ?>>Mirian Template</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- CV List -->
        <div class="row">
            <?php if (empty($cvs)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No CVs Found</h4>
                        <p>No CVs match your search criteria.</p>
                        <a href="../create/cv_create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New CV
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($cvs as $cv): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="cv-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($cv['name']); ?></h5>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge template-badge bg-<?php echo (is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type'])) === 1 ? 'primary' : 'success'; ?> mb-1">
                                        <?php echo getTemplateDisplayName(is_numeric($cv['template_type']) ? $cv['template_type'] : getTemplateId($cv['template_type'])); ?>
                                    </span>
                                    <?php if (isset($cv['is_public']) && $cv['is_public']): ?>
                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                            <i class="fas fa-globe me-1"></i>Public
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                            <i class="fas fa-lock me-1"></i>Private
                                        </span>
                                    <?php endif; ?>
                                    <?php if (isset($cv['user_id']) && $cv['user_id']): ?>
                                        <span class="badge bg-info" style="font-size: 0.7rem;">
                                            <i class="fas fa-user me-1"></i>Owned
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning" style="font-size: 0.7rem;">
                                            <i class="fas fa-hand-holding-heart me-1"></i>Guest
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
                                Created: <?php echo $cv['created_at'] ? date('M j, Y', strtotime($cv['created_at'])) : 'Date not available'; ?>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="cv_preview.php?id=<?php echo $cv['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="cv_edit.php?id=<?php echo $cv['id']; ?>" 
                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (isset($cv['is_public']) && $cv['is_public']): ?>
                                    <a href="cv_publish.php?id=<?php echo $cv['id']; ?>&action=unpublish" 
                                       class="btn btn-outline-secondary btn-sm" title="Make Private"
                                       onclick="return confirm('Are you sure you want to make this CV private?')">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="cv_publish.php?id=<?php echo $cv['id']; ?>&action=publish" 
                                       class="btn btn-outline-success btn-sm" title="Make Public"
                                       onclick="return confirm('Are you sure you want to make this CV public? It will be visible to everyone.')">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="cv_delete.php?id=<?php echo $cv['id']; ?>" 
                                   class="btn btn-outline-danger btn-sm" title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this CV?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination (if needed) -->
        <?php if (count($cvs) > 12): ?>
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="CV pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link">1</span>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
