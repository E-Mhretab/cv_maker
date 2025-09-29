<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>CV - {{ $cv->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
        }
        .cv-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 3rem 0;
            position: relative;
            min-height: auto;
            overflow: visible;
        }
        .cv-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(29, 78, 216, 0.9));
        }
        .cv-header .container {
            position: relative;
            z-index: 1;
            max-width: 100%;
            width: 100%;
        }
        .cv-name {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            white-space: normal;
            line-height: 1.2;
            max-width: 100%;
            display: block;
        }
        .cv-contact {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .cv-contact a {
            color: white;
            text-decoration: none;
        }
        .cv-contact a:hover {
            color: #fbbf24;
        }
        .section-title {
            color: #2563eb;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .work-item, .education-item {
            border-left: 3px solid #2563eb;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }
        .work-title, .education-title {
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 0.25rem;
        }
        .work-company, .education-institution {
            color: #64748b;
            font-weight: 500;
        }
        .work-dates, .education-dates {
            color: #64748b;
            font-size: 0.9rem;
        }
        .skill-item, .language-item, .hobby-item {
            margin-bottom: 0.75rem;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }
        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        /* Responsive adjustments for long names */
        @media (max-width: 768px) {
            .cv-name {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .cv-name {
                font-size: 1.5rem;
            }
            .cv-contact {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a href="{{ route('cvs.index') }}" class="navbar-brand fw-bold">
            <i class="fas fa-home me-2"></i>Back to List
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('cvs.edit', $cv->id) }}" class="btn btn-light btn-sm">
                <i class="fas fa-edit me-1"></i>Edit CV
            </a>
            <button onclick="window.print()" class="btn btn-info btn-sm">
                <i class="fas fa-print me-1"></i>Print
            </button>
            <a href="{{ route('cvs.pdf', $cv->id) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
            <form action="{{ route('cvs.send-email', $cv->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Weet u zeker dat u uw CV naar uw e-mailadres wilt sturen?')">
                    <i class="fas fa-envelope me-1"></i>Send to Me
                </button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sendGridModal">
                <i class="fas fa-paper-plane me-1"></i>Send to Others
            </button>
        </div>
    </div>
</nav>

<header class="cv-header">
    <div class="container">
        <div class="text-center">
            <h1 class="cv-name">{{ $cv->name }}</h1>
            <div class="cv-contact">
                <i class="fas fa-map-marker-alt me-2"></i>
                {{ $cv->address }}
                &nbsp;|&nbsp;
                <i class="fas fa-phone me-2"></i>
                {{ $cv->phone_number }}
                &nbsp;|&nbsp;
                <i class="fas fa-envelope me-2"></i>
                <a href="mailto:{{ $cv->email }}">{{ $cv->email }}</a>
            </div>
            <div class="cv-contact">
                <i class="fas fa-birthday-cake me-2"></i>
                {{ $cv->date_of_birth ? \Carbon\Carbon::parse($cv->date_of_birth)->format('M d, Y') : '' }}
                @if($cv->portfolio)
                    &nbsp;|&nbsp;<a target="_blank" href="{{ $cv->portfolio }}">Portfolio</a>
                @endif
                @if($cv->linkedin_profile)
                    &nbsp;|&nbsp;<a target="_blank" href="{{ $cv->linkedin_profile }}"><i class="fab fa-linkedin"></i></a>
                @endif
            </div>
        </div>
    </div>
</header>

<main class="container my-5">
    <!-- Summary -->
    <section class="mb-5">
        <h2 class="section-title"><i class="fas fa-user me-2"></i>Summary</h2>
        <p class="fs-5">{{ $cv->profile_summary ? nl2br(e($cv->profile_summary)) : 'Profile summary not available.' }}</p>
    </section>

    <div class="row">
        <div class="col-md-8">
            <!-- Work Experience -->
            <section class="mb-5">
                <h2 class="section-title"><i class="fas fa-briefcase me-2"></i>Work Experience</h2>
                @if($cv->workExperiences->count() > 0)
                    @foreach($cv->workExperiences as $work)
                        <div class="work-item">
                            <div class="work-title">{{ $work->job_title }}</div>
                            <div class="work-company">{{ $work->company_name }}</div>
                            <div class="work-dates">
                                {{ $work->work_start ? \Carbon\Carbon::parse($work->work_start)->format('M Y') : '' }} 
                                @if($work->work_end)
                                    – {{ \Carbon\Carbon::parse($work->work_end)->format('M Y') }}
                                @else
                                    – Present
                                @endif
                            </div>
                            @if($work->description)
                                <p class="mt-2">{{ nl2br(e($work->description)) }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No work experience available.</p>
                @endif
            </section>

            <!-- Education -->
            <section class="mb-5">
                <h2 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Education</h2>
                @if($cv->education->count() > 0)
                    @foreach($cv->education as $edu)
                        <div class="education-item">
                            <div class="education-title">{{ $edu->degree }}</div>
                            <div class="education-institution">{{ $edu->institution }}</div>
                            <div class="education-dates">
                                {{ $edu->education_start ? \Carbon\Carbon::parse($edu->education_start)->format('M Y') : '' }} 
                                @if($edu->education_end)
                                    – {{ \Carbon\Carbon::parse($edu->education_end)->format('M Y') }}
                                @else
                                    – Present
                                @endif
                            </div>
                            @if($edu->description)
                                <p class="mt-2">{{ nl2br(e($edu->description)) }}</p>
                            @endif
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
                    <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $cv->email }}">{{ $cv->email }}</a></p>
                    @if($cv->phone_number)<p class="mb-1"><strong>Phone:</strong> {{ $cv->phone_number }}</p>@endif
                    @if($cv->date_of_birth)<p class="mb-1"><strong>DOB:</strong> {{ \Carbon\Carbon::parse($cv->date_of_birth)->format('M d, Y') }}</p>@endif
                    @if($cv->linkedin_profile)<p class="mb-1"><a href="{{ $cv->linkedin_profile }}" target="_blank" rel="noopener">LinkedIn</a></p>@endif
                    @if($cv->portfolio)<p class="mb-1"><a href="{{ $cv->portfolio }}" target="_blank" rel="noopener">Portfolio</a></p>@endif
                </div>
            </div>

            <!-- Skills Card -->
            @if($cv->skills->count() > 0)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cogs me-2"></i>Skills</h5>
                    @foreach($cv->skills as $skill)
                        <div class="skill-item">
                            <strong>{{ $skill->skill_name }}</strong>
                            @if($skill->description)
                                <br><small class="text-muted">{{ $skill->description }}</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Languages Card -->
            @if($cv->languages->count() > 0)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-language me-2"></i>Languages</h5>
                    @foreach($cv->languages as $language)
                        <div class="language-item">
                            <strong>{{ $language->language_name }}</strong>
                            <span class="badge bg-primary ms-2">{{ ucfirst($language->proficiency) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Hobbies Card -->
            @if($cv->hobbies->count() > 0)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-heart me-2"></i>Hobbies</h5>
                    @foreach($cv->hobbies as $hobby)
                        <div class="hobby-item">
                            <strong>{{ $hobby->hobby_name }}</strong>
                            @if($hobby->description)
                                — {{ $hobby->description }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <a href="{{ route('welcome') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Back to Home
        </a>
    </div>
</footer>

<!-- SendGrid Modal -->
<div class="modal fade" id="sendGridModal" tabindex="-1" aria-labelledby="sendGridModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendGridModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>Verstuur CV naar Anderen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cvs.send-sendgrid', $cv->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient_email" class="form-label">Ontvanger E-mailadres *</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email" required>
                        <div class="form-text">Het e-mailadres waar de CV naartoe gestuurd moet worden.</div>
                    </div>
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Ontvanger Naam (optioneel)</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" placeholder="Bijv. HR Manager">
                        <div class="form-text">De naam van de ontvanger voor persoonlijke begroeting.</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Let op:</strong> De CV wordt verzonden via SendGrid met PDF-bijlage van {{ $cv->name }} naar de opgegeven ontvanger.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Verstuur via SendGrid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>