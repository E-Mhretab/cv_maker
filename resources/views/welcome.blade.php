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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
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
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('cv.index') }}"><i class="fas fa-list me-2"></i>My CVs</a></li>
                                <li><a class="dropdown-item" href="{{ route('cv.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                    @endauth
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
                            <a href="{{ route('cv.create') }}" class="btn-hero btn-hero-primary">
                                <i class="fas fa-plus"></i>Create Free CV
                            </a>
                            @auth
                                <a href="{{ route('cv.index') }}" class="btn-hero btn-hero-secondary">
                                    <i class="fas fa-cogs"></i>Manage CVs
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">
                                    <i class="fas fa-sign-in-alt"></i>Login
                                </a>
                            @endauth
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
                        <img src="{{ asset('img/get-a-job.png') }}" 
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
                @if ($cvs->isEmpty())
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                            </div>
                            <h3 class="text-muted mb-3">No CVs Available Yet</h3>
                            <p class="text-muted mb-4">Be the first to create and publish a professional CV!</p>
                            <a href="{{ route('cv.create') }}" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>Create Your CV
                            </a>
                        </div>
                    </div>
                @else
                    @foreach ($cvs as $cv)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="cv-card d-flex flex-column h-100">
                            <div class="cv-card-header d-flex flex-column justify-content-center">
                                <div class="cv-avatar mx-auto mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="cv-name mb-0">{{ $cv->name }}</h4>
                            </div>
                            <div class="cv-card-body d-flex flex-column">
                                <p class="cv-summary flex-grow-1">
                                    {{ $cv->profile_summary ?: 'Professional CV available for viewing. Click to see the full profile and experience.' }}
                                </p>
                                <a href="{{ route('cv.show', $cv) }}" class="btn btn-primary btn-view-cv mt-auto">
                                    <i class="fas fa-eye me-2"></i>View Full CV
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
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
                        <a href="{{ route('cv.create') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Create CV
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        @endguest
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
