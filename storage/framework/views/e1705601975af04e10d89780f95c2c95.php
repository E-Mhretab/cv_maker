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
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
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
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <?php echo e(strtoupper(substr(auth()->user()->username ?? auth()->user()->email, 0, 1))); ?>

                                </div>
                                <?php echo e(auth()->user()->username ?? auth()->user()->email); ?>

                            </a>
                            <ul class="dropdown-menu">
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-crown me-2"></i>Admin Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.cv-list')); ?>"><i class="fas fa-list me-2"></i>Manage All CVs</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.user-list')); ?>"><i class="fas fa-users me-2"></i>Manage Users</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('cv.index')); ?>"><i class="fas fa-list me-2"></i>My CVs</a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('cv.create')); ?>"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary-custom">
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
                            <a href="<?php echo e(route('cv.create')); ?>" class="btn-hero btn-hero-primary">
                                <i class="fas fa-plus"></i>Create Free CV
                            </a>
                            <a href="<?php echo e(route('login')); ?>" class="btn-hero btn-hero-secondary">
                                <i class="fas fa-sign-in-alt"></i>Login
                            </a>
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
                        <img src="<?php echo e(asset('img/get-a-job.png')); ?>" 
                             alt="Professional CV Builder Interface" 
                             class="img-fluid rounded-3 shadow-custom" 
                             style="max-height: 500px; object-fit: cover;">
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
            
            <div class="row g-4">
                <?php if($cvs->isEmpty()): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                            </div>
                            <h3 class="text-muted mb-3">No CVs Available Yet</h3>
                            <p class="text-muted mb-4">Be the first to create and publish a professional CV!</p>
                            <a href="<?php echo e(route('cv.create')); ?>" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>Create Your CV
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $cvs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="cv-card d-flex flex-column h-100">
                            <div class="cv-card-header d-flex flex-column justify-content-center">
                                <div class="cv-avatar mx-auto mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="cv-name mb-0"><?php echo e($cv->name); ?></h4>
                            </div>
                            <div class="cv-card-body d-flex flex-column">
                                <p class="cv-summary flex-grow-1">
                                    <?php echo e($cv->profile_summary ?: 'Professional CV available for viewing. Click to see the full profile and experience.'); ?>

                                </p>
                                <a href="<?php echo e(route('cv.show', $cv)); ?>" class="btn btn-primary btn-view-cv mt-auto">
                                    <i class="fas fa-eye me-2"></i>View Full CV
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Features</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">CV Builder</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Templates</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">PDF Export</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Get Started</h6>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('cv.create')); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Create CV
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light btn-sm">
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
    </body>
</html>
<?php /**PATH /Users/nathanjethoe/Documents/GitHub/cv_maker/resources/views/welcome.blade.php ENDPATH**/ ?>