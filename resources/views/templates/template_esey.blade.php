<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>CV - {{ $cv['name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand fw-bold"> <!-- Ajusta a tu ruta de home si es necesario -->
            <i class="fas fa-home me-2"></i>Back to Home
        </a>
    </div>
</nav>

<header class="bg-primary text-white text-center py-5 position-relative">
    <div class="container">
        <h1 class="display-4 fw-bold">{{ $cv['name'] }}</h1>
        <p class="lead mb-1">
            <i class="fas fa-map-marker-alt"></i>
            {{ $cv['address'] }}
            &nbsp;|&nbsp;
            <i class="fas fa-phone"></i>
            {{ $cv['phone_number'] }}
            &nbsp;|&nbsp;
            <i class="fas fa-envelope"></i>
            <a class="text-white" href="mailto:{{ $cv['email'] }}">{{ $cv['email'] }}</a>
        </p>
        <p class="mb-0">
            <i class="fas fa-birthday-cake"></i>
            {{ $cv['date_of_birth'] ?? '' }}
            @if(isset($cv['portfolio']))
                &nbsp;|&nbsp;<a class="text-warning fw-bold" target="_blank" href="{{ $cv['portfolio'] }}">Portfolio</a>
            @endif
            @if(isset($cv['linkedin_profile']))
                &nbsp;|&nbsp;<a class="text-white" target="_blank" href="{{ $cv['linkedin_profile'] }}"><i class="fab fa-linkedin"></i></a>
            @endif
        </p>
        
        <!-- Export Buttons - Ajusta la lógica de usuario según tu auth (usa auth()->user()) -->
        @if (auth()->check() && (auth()->user()->is_guest_creator ?? false) || (auth()->check() && auth()->user()->is_guest ?? false))
            <div class="position-absolute top-0 end-0 p-3">
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-info btn-sm">
                        <i class="fas fa-print me-1"></i>Print
                    </button>
                    <a href="{{ route('login') }}?redirect={{ urlencode('manage/cv_list') }}&message=export_required" class="btn btn-danger btn-sm"> <!-- Ajusta rutas -->
                        <i class="fas fa-file-pdf me-1"></i>Export PDF
                    </a>
                    <a href="{{ route('login') }}?redirect={{ urlencode('manage/cv_list') }}&message=export_required" class="btn btn-success btn-sm">
                        <i class="fas fa-file-code me-1"></i>Export XML
                    </a>
                </div>
            </div>
        @endif
    </div>
</header>

<main class="container my-5">
    <!-- Summary -->
    <section class="mb-5">
        <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-user"></i> Summary</h2>
        <p class="fs-5">{{ nl2br($cv['profile_summary'] ?? 'Profile summary not available.') }}</p>
    </section>

    <div class="row">
        <div class="col-md-8">
            <!-- Work Experience -->
            <section class="mb-5">
                <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-briefcase"></i> Work Experience</h2>
                @if(isset($workExperiences) && count($workExperiences) > 0)
                    @foreach($workExperiences as $w)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title"><i class="fas fa-user-tie me-2"></i>{{ $w['job_title'] }}</h3>
                                <p class="card-subtitle mb-2 text-muted">
                                    <strong>{{ $w['company_name'] }}</strong>
                                    @if(isset($w['work_start']) || isset($w['work_end']))
                                        &nbsp;|&nbsp; {{ $w['work_start'] ?? '' }} @if(isset($w['work_end'])) – {{ $w['work_end'] }}@else – Present@endif
                                    @endif
                                </p>
                                @if(!empty($w['description']))
                                    <p class="card-text">{{ nl2br($w['description']) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No work experience available.</p>
                @endif
            </section>

            <!-- Education -->
            <section class="mb-5">
                <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-graduation-cap"></i> Education</h2>
                @if(isset($education) && count($education) > 0)
                    @foreach($education as $edu)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title"><i class="fas fa-school me-2"></i>{{ $edu['degree'] }}</h3>
                                <p class="card-subtitle mb-2 text-muted">
                                    <strong>{{ $edu['institution'] }}</strong>
                                    @if(isset($edu['education_start']) || isset($edu['education_end']))
                                        &nbsp;|&nbsp; {{ $edu['education_start'] ?? '' }} @if(isset($edu['education_end'])) – {{ $edu['education_end'] }}@else – Present@endif
                                    @endif
                                </p>
                                @if(!empty($edu['description']))
                                    <p class="card-text">{{ nl2br($edu['description']) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No education data available.</p>
                @endif
            </section>
        </div>

        <aside class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Contact</h5>
                    <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $cv['email'] }}">{{ $cv['email'] }}</a></p>
                    @if(isset($cv['phone_number']))<p class="mb-1"><strong>Phone:</strong> {{ $cv['phone_number'] }}</p>@endif
                    @if(isset($cv['date_of_birth']))<p class="mb-1"><strong>DOB:</strong> {{ $cv['date_of_birth'] }}</p>@endif
                    @if(isset($cv['linkedin_profile']))<p class="mb-1"><a href="{{ $cv['linkedin_profile'] }}" target="_blank" rel="noopener">LinkedIn</a></p>@endif
                    @if(isset($cv['portfolio']))<p class="mb-1"><a href="{{ $cv['portfolio'] }}" target="_blank" rel="noopener">Portfolio</a></p>@endif
                </div>
            </div>

            <!-- Skills Card -->
            @if(isset($skills) && count($skills) > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-cogs me-2"></i>Skills</h5>
                        <ul class="mb-0">
                            @foreach($skills as $skill)
                                <li class="mb-2">
                                    <strong>{{ $skill['skill_name'] }}</strong>
                                    @if(isset($skill['description']))
                                        <br><small class="text-muted">{{ $skill['description'] }}</small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Languages Card -->
            @if(isset($languages) && count($languages) > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-language me-2"></i>Languages</h5>
                        <ul class="mb-0">
                            @foreach($languages as $language)
                                <li class="mb-2">
                                    <strong>{{ $language['language_name'] }}</strong>
                                    <span class="badge bg-primary ms-2">{{ ucfirst($language['proficiency']) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Hobbies -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hobbies</h5>
                    @if(isset($hobbies) && count($hobbies) > 0)
                        <ul class="mb-0">
                            @foreach($hobbies as $hb)
                                <li><strong>{{ $hb['hobby_name'] }}</strong>@if(isset($hb['description'])) — {{ $hb['description'] }}@endif</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No hobbies listed.</p>
                    @endif
                </div>
            </div>
        </aside>
    </div>

    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Home
        </a>
    </div>
</main>

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
