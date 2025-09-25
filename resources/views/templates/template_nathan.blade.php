<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Curriculum Vitae — {{ $cv['name'] }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/nathan.css') }}"> <!-- Asume que nathan.css está en public/css -->
</head>
<body class="bg-light">
    <!-- Mobile Navigation Toggle -->
    <nav class="navbar navbar-expand-lg d-lg-none bg-primary text-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12 bg-primary text-white p-4 collapse d-lg-block" id="mobileSidebar">
                <!-- Navigation -->
                <div class="mb-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm mb-3 d-flex align-items-center">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>

                <header class="text-center mb-4 position-relative">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h1 class="h2 mb-3">{{ $cv['name'] }}</h1>
                    <p class="lead">Software Developer</p>
                    <p>Address: {{ $cv['address'] }} | Phone: {{ $cv['phone_number'] }} | Email: {{ $cv['email'] }}</p>
                    <p>Date of Birth: {{ isset($cv['date_of_birth']) ? date('Y', strtotime($cv['date_of_birth'])) : 'N/A' }} | 
                        @if(isset($cv['linkedin_profile']))
                            <a href="{{ $cv['linkedin_profile'] }}" target="_blank" class="text-white">LinkedIn Profile</a>
                        @else
                            <span class="text-white-50">LinkedIn Profile</span>
                        @endif
                    </p>
                    
                    <!-- Export Buttons - Show for guest creators or logged-in guest users -->
                    @if (auth()->check() && (auth()->user()->is_guest_creator ?? false) || (auth()->check() && auth()->user()->is_guest ?? false))
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="d-flex flex-column gap-1">
                                <button onclick="window.print()" class="btn btn-info btn-sm">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                                <a href="{{ route('login') }}?redirect={{ urlencode('manage/cv_list') }}&message=export_required" class="btn btn-danger btn-sm">
                                    <i class="fas fa-file-pdf me-1"></i>PDF
                                </a>
                                <a href="{{ route('login') }}?redirect={{ urlencode('manage/cv_list') }}&message=export_required" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-code me-1"></i>XML
                                </a>
                            </div>
                        </div>
                    @endif
                </header>

                <!-- Skills Section -->
                @if(isset($skills) && count($skills) > 0)
                    <div class="mb-4">
                        <h3 class="h5 mb-3"><i class="fas fa-cogs me-2"></i>Skills</h3>
                        @foreach($skills as $skill)
                            <div class="mb-2">
                                <strong>{{ $skill['skill_name'] }}</strong>
                                @if(isset($skill['description']))
                                    <br><small class="text-white-75">{{ $skill['description'] }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Languages Section -->
                @if(isset($languages) && count($languages) > 0)
                    <div class="mb-4">
                        <h3 class="h5 mb-3"><i class="fas fa-language me-2"></i>Languages</h3>
                        @foreach($languages as $language)
                            <div class="mb-2">
                                <strong>{{ $language['language_name'] }}</strong>
                                <span class="badge bg-light text-dark ms-2">{{ ucfirst($language['proficiency']) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Hobbies and Interests -->
                <div class="mb-4">
                    <h3 class="h5 mb-3"><i class="fas fa-heart me-2"></i>Hobbies and Interests</h3>
                    @if(isset($hobbies) && count($hobbies) > 0)
                        @foreach($hobbies as $hobby)
                            <p class="mb-2">{{ $hobby['hobby_name'] }}</p>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8 col-md-12">
                <main class="p-4">
                    <!-- Summary Section -->
                    <section class="mb-5">
                        <h2 class="h4 text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-user me-2"></i>Profile Summary
                        </h2>
                        <p>{{ $cv['profile_summary'] }}</p>
                    </section>

                    <!-- Work Experience Section -->
                    <section class="mb-5">
                        <h2 class="h4 text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-briefcase me-2"></i>Work Experience
                        </h2>
                        @if(isset($workExperiences) && count($workExperiences) > 0)
                            @foreach($workExperiences as $work)
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <h3 class="h5 card-title mb-1">{{ $work['job_title'] }}</h3>
                                        <p class="card-subtitle text-muted mb-1">{{ $work['company_name'] }}</p>
                                        <p class="card-subtitle text-muted mb-2">
                                            {{ isset($work['work_start']) ? date('F Y', strtotime($work['work_start'])) : 'Start date not specified' }} - 
                                            {{ isset($work['work_end']) ? date('F Y', strtotime($work['work_end'])) : 'Present' }}
                                        </p>
                                        @if(isset($work['description']))
                                            <p class="card-text">{{ $work['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </section>

                    <!-- Education Section -->
                    <section class="mb-5">
                        <h2 class="h4 text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Education
                        </h2>
                        @if(isset($education) && count($education) > 0)
                            @foreach($education as $edu)
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <h3 class="h5 card-title mb-1">{{ $edu['degree'] }}</h3>
                                        <p class="card-subtitle text-muted mb-1">{{ $edu['institution'] }}</p>
                                        <p class="card-subtitle text-muted mb-2">
                                            {{ isset($edu['education_start']) ? date('F Y', strtotime($edu['education_start'])) : 'Start date not specified' }} - 
                                            {{ isset($edu['education_end']) ? date('F Y', strtotime($edu['education_end'])) : 'Present' }}
                                        </p>
                                        @if(isset($edu['description']))
                                            <p class="card-text">{{ $edu['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </section>
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Print Styles -->
    <style>
        @media print {
            /* Hide export buttons when printing */
            .position-absolute.top-0.end-0 {
                display: none !important;
            }
            
            /* Hide navigation elements */
            .navbar, .btn {
                display: none !important;
            }
            
            /* Ensure proper page breaks */
            .card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            
            /* Remove shadows and borders for cleaner print */
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
</body>
</html>
