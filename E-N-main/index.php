<?php
// Start session first, before any output
session_start();

require_once 'connection.php';

// Check if authentication system is initialized
$result = $conn->query("SHOW TABLES LIKE 'users'");
if (!$result || $result->num_rows === 0) {
    // Redirect to setup page if tables don't exist
    header('Location: setup_required.php');
    exit;
}

require_once 'includes/auth.php';
require_once 'includes/middleware.php';

// Use global auth instance and initialize middleware
$auth = $GLOBALS['auth'] ?? new Auth($conn);
$middleware = new Middleware($auth);

// Get user context
$userContext = $middleware->getUserContext();

// Redirect admins to admin dashboard (but allow them to view public homepage if they want)
if ($userContext['is_admin'] && !isset($_GET['view_public'])) {
    header('Location: admin_dashboard.php');
    exit;
}

// Get CVs based on user role - only show published CVs on homepage
if ($userContext['is_admin']) {
    // Admin can see all published CVs on homepage
    $cvQuery = "SELECT c.id, c.name, c.profile_summary 
                FROM cv c 
                INNER JOIN cv_metadata m ON c.id = m.cv_id 
                WHERE m.is_public = 1 
                ORDER BY m.published_at DESC";
    $cvResult = $conn->query($cvQuery);
} else {
    // Regular users and guests see only published CVs
    $cvQuery = "SELECT c.id, c.name, c.profile_summary 
                FROM cv c 
                INNER JOIN cv_metadata m ON c.id = m.cv_id 
                WHERE m.is_public = 1 
                ORDER BY m.published_at DESC";
    $cvResult = $conn->query($cvQuery);
}

if (!$cvResult) {
    $cvs = [];
} else {
    $cvs = [];
    while ($row = $cvResult->fetch_assoc()) {
        $cvs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Builder Pro - Create Professional CVs in Minutes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/E-N/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-file-alt me-2"></i>CV Builder Pro
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Gallery</a>
                    </li>
                    <?php if ($userContext['is_logged_in']): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($userContext['user']['username'], 0, 1)); ?>
                                </div>
                                <?php echo htmlspecialchars($userContext['user']['username']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="user_profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="manage/cv_list.php"><i class="fas fa-list me-2"></i>My CVs</a></li>
                                <li><a class="dropdown-item" href="create/cv_create.php"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-primary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content fade-in-up">
                        <h1 class="hero-title">Create Professional CVs in Minutes</h1>
                        <p class="hero-subtitle">Build stunning, ATS-friendly CVs with our easy-to-use builder. Choose from professional templates and land your dream job faster.</p>
                        
                        <div class="hero-buttons">
                            <a href="create/cv_create.php" class="btn-hero btn-hero-primary">
                                <i class="fas fa-plus"></i>Create Free CV
                            </a>
                            <?php if ($userContext['is_logged_in']): ?>
                                <a href="manage/cv_list.php" class="btn-hero btn-hero-secondary">
                                    <i class="fas fa-cogs"></i>Manage CVs
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="hero-features">
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Free to use</span>
                            </div>
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>ATS-friendly</span>
                            </div>
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Professional templates</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="./img/get-a-job.png" 
                             alt="Professional CV Builder Interface" class="img-fluid rounded-3 shadow-custom" style="max-height: 500px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h3 class="feature-title">Easy CV Builder</h3>
                        <p class="feature-description">Create professional CVs in minutes with our intuitive drag-and-drop builder. No design skills required.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3 class="feature-title">Multiple Formats</h3>
                        <p class="feature-description">Export your CV in PDF, XML, and other formats. Perfect for online applications and printing.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Friendly</h3>
                        <p class="feature-description">Access and edit your CVs from any device. Our responsive design works perfectly on all screens.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CV Gallery Section -->
    <section id="gallery" class="cv-gallery">
        <div class="container">
            <h2 class="section-title">Featured Professional CVs</h2>
            <p class="section-subtitle">Discover talented professionals and get inspired by their CVs</p>
            
            <!-- Messages -->
            <?php if (isset($_GET['message']) && $_GET['message'] === 'logged_out'): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>You have been successfully logged out.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <?php if (empty($cvs)): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                            </div>
                            <h3 class="text-muted mb-3">No CVs Available Yet</h3>
                            <p class="text-muted mb-4">Be the first to create and publish a professional CV!</p>
                            <a href="create/cv_create.php" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>Create Your CV
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cvs as $cv): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="cv-card d-flex flex-column h-100">
                            <div class="cv-card-header d-flex flex-column justify-content-center">
                                <div class="cv-avatar mx-auto mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="cv-name mb-0"><?php echo e($cv['name']); ?></h4>
                            </div>
                            <div class="cv-card-body d-flex flex-column">
                                <p class="cv-summary flex-grow-1">
                                    <?php echo e($cv['profile_summary'] ?: 'Professional CV available for viewing. Click to see the full profile and experience.'); ?>
                                </p>
                                <?php 
                                $cvName = $cv['name'];
                                $phpFile = '';
                                if (strpos($cvName, 'Nathan') !== false) {
                                    $phpFile = 'nathan.php';
                                } elseif (strpos($cvName, 'Esey') !== false) {
                                    $phpFile = 'esey.php';
                                } else {
                                    $phpFile = 'create/cv_view.php?id=' . $cv['id'];
                                }
                                ?>
                                <a href="<?php echo $phpFile; ?>" class="btn btn-primary btn-view-cv mt-auto">
                                    <i class="fas fa-eye me-2"></i>View Full CV
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-file-alt me-2"></i>CV Builder Pro
                    </h5>
                    <p class="text-white-50 mb-4">Create professional CVs that stand out from the crowd. Our easy-to-use builder helps you land your dream job faster.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 fs-5"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Features</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">CV Builder</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Templates</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">PDF Export</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Mobile App</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Resources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">CV Tips</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Job Search</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Career Advice</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Terms</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Get Started</h6>
                    <div class="d-grid gap-2">
                        <a href="create/cv_create.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Create CV
                        </a>
                        <a href="login.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy; 2025 CV Builder Pro. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-white-50 mb-0">Made with <i class="fas fa-heart text-danger"></i> for job seekers</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth scrolling for anchor links -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe all feature cards and CV cards
        document.querySelectorAll('.feature-card, .cv-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
