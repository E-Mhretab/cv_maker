@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CV - {{ $cv->name }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Print styles */
    @media print {
      .navbar, .position-absolute, .d-flex.flex-column {
        display: none !important;
      }
      body {
        background: white !important;
        font-size: 12px;
        line-height: 1.4;
      }
      .container {
        max-width: none !important;
        padding: 0 !important;
      }
      .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        margin-bottom: 15px !important;
      }
      .card-header {
        background: #f8f9fa !important;
        border-bottom: 1px solid #ddd !important;
      }
      .bg-primary {
        background: #007bff !important;
      }
      .text-primary {
        color: #007bff !important;
      }
      .page-break {
        page-break-before: always;
      }
      .no-break {
        page-break-inside: avoid;
      }
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      min-height: 100vh;
    }
    .cv-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .cv-header {
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      color: #2c3e50;
      padding: 3rem 0;
      position: relative;
    }
    .cv-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(168, 237, 234, 0.9), rgba(254, 214, 227, 0.9));
    }
    .cv-header .container {
      position: relative;
      z-index: 1;
    }
    .cv-name {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: #2c3e50;
    }
    .cv-contact {
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }
    .section-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      margin-bottom: 2rem;
      overflow: hidden;
    }
    .section-header {
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      color: #2c3e50;
      padding: 1.5rem 2rem;
      border-bottom: none;
    }
    .section-header h2 {
      font-weight: 700;
      margin: 0;
    }
    .section-content {
      padding: 2rem;
    }
    .work-item, .education-item {
      border-left: 4px solid #a8edea;
      padding-left: 1.5rem;
      margin-bottom: 2rem;
    }
    .work-item:last-child, .education-item:last-child {
      margin-bottom: 0;
    }
    .skill-tag {
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      color: #2c3e50;
      padding: 0.5rem 1rem;
      border-radius: 25px;
      margin: 0.25rem;
      display: inline-block;
      font-weight: 500;
    }
    .language-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f8f9fa;
    }
    .language-item:last-child {
      border-bottom: none;
    }
    .proficiency-badge {
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      color: #2c3e50;
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-weight: 600;
    }
    .hobby-item {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    .hobby-item:last-child {
      margin-bottom: 0;
    }
  </style>
  
  @if(isset($print_mode) && $print_mode)
  <script>
    // Try immediate print
    setTimeout(function() {
      window.print();
    }, 100);
    
    // Also try on window load as fallback
    window.onload = function() {
      window.print();
    }
    
    // Redirect back to normal CV view after print dialog is closed
    window.addEventListener('afterprint', function() {
      // Redirect to the normal CV view
      window.location.href = '{{ route("cvs.show", $cv->id) }}';
    });
    
    // Also redirect if user navigates away or closes the tab
    window.addEventListener('beforeunload', function() {
      // This will trigger when user tries to navigate away
    });
  </script>
  @endif
</head>
<body>

@if(isset($print_mode) && $print_mode)
<!-- Print mode is active - automatic print will trigger -->
@endif

<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a href="{{ route('welcome') }}" class="navbar-brand fw-bold text-primary">
      <i class="fas fa-home me-2"></i>Back to Home
    </a>
  </div>
</nav>

<div class="container my-5">
  <div class="cv-container">
    <header class="cv-header">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="cv-name">{{ $cv->name }}</h1>
            <div class="cv-contact">
              <i class="fas fa-map-marker-alt me-2"></i>{{ $cv->address }}
            </div>
            <div class="cv-contact">
              <i class="fas fa-phone me-2"></i>{{ $cv->phone_number }}
            </div>
            <div class="cv-contact">
              <i class="fas fa-envelope me-2"></i>
              <a href="mailto:{{ $cv->email }}" class="text-dark">{{ $cv->email }}</a>
            </div>
            @if($cv->date_of_birth)
            <div class="cv-contact">
              <i class="fas fa-birthday-cake me-2"></i>{{ \Carbon\Carbon::parse($cv->date_of_birth)->format('F j, Y') }}
            </div>
            @endif
            @if($cv->linkedin_profile)
            <div class="cv-contact">
              <i class="fab fa-linkedin me-2"></i>
              <a href="{{ $cv->linkedin_profile }}" target="_blank" class="text-dark">LinkedIn Profile</a>
            </div>
            @endif
            @if($cv->portfolio)
            <div class="cv-contact">
              <i class="fas fa-globe me-2"></i>
              <a href="{{ $cv->portfolio }}" target="_blank" class="text-dark">Portfolio</a>
            </div>
            @endif
          </div>
          <div class="col-md-4 text-end">
            @if((Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin')) || $cv->user_id === null)
            <div class="d-flex flex-column gap-1">
              @if(Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin'))
                <!-- Authenticated user - show actual export links -->
                <a href="{{ route('cvs.print', $cv->id) }}" class="btn btn-sm" style="background-color: #87CEEB; color: #000; border: none;" target="_blank">
                  <i class="fas fa-print me-1"></i>Print
                </a>
                <a href="{{ route('cvs.pdf', $cv->id) }}" class="btn btn-sm" style="background-color: #DC3545; color: #fff; border: none;" target="_blank">
                  <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('cvs.export', $cv->id) }}" class="btn btn-sm" style="background-color: #28A745; color: #fff; border: none;">
                  <i class="fas fa-code me-1"></i>Export XML
                </a>
              @else
                <!-- Guest user - redirect to login -->
                <a href="{{ route('login') }}" class="btn btn-sm" style="background-color: #87CEEB; color: #000; border: none;">
                  <i class="fas fa-print me-1"></i>Print
                </a>
                <a href="{{ route('login') }}" class="btn btn-sm" style="background-color: #DC3545; color: #fff; border: none;">
                  <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('login') }}" class="btn btn-sm" style="background-color: #28A745; color: #fff; border: none;">
                  <i class="fas fa-code me-1"></i>Export XML
                </a>
              @endif
            </div>
            @endif
          </div>
        </div>
      </div>
    </header>

    <main class="p-4">
      <!-- Profile Summary -->
      @if($cv->profile_summary)
      <div class="section-card">
        <div class="section-header">
          <h2 class="h4 mb-0">
            <i class="fas fa-user me-2"></i>Profile Summary
          </h2>
        </div>
        <div class="section-content">
          <p class="mb-0 lead">{{ $cv->profile_summary }}</p>
        </div>
      </div>
      @endif

      <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
          <!-- Work Experience -->
          @if($cv->workExperiences->count() > 0)
          <div class="section-card">
            <div class="section-header">
              <h2 class="h4 mb-0">
                <i class="fas fa-briefcase me-2"></i>Work Experience
              </h2>
            </div>
            <div class="section-content">
              @foreach($cv->workExperiences as $work)
              <div class="work-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h3 class="h5 mb-0 text-dark">{{ $work->job_title }}</h3>
                  <span class="badge bg-primary">
                    @if($work->is_current)
                      Current
                    @else
                      {{ $work->work_start ? \Carbon\Carbon::parse($work->work_start)->format('M Y') : '' }} - {{ $work->work_end ? \Carbon\Carbon::parse($work->work_end)->format('M Y') : '' }}
                    @endif
                  </span>
                </div>
                <h4 class="h6 text-muted mb-2">{{ $work->company_name }}</h4>
                @if($work->description)
                  <p class="mb-0">{{ $work->description }}</p>
                @endif
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Education -->
          @if($cv->education->count() > 0)
          <div class="section-card">
            <div class="section-header">
              <h2 class="h4 mb-0">
                <i class="fas fa-graduation-cap me-2"></i>Education
              </h2>
            </div>
            <div class="section-content">
              @foreach($cv->education as $edu)
              <div class="education-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h3 class="h5 mb-0 text-dark">{{ $edu->degree }}</h3>
                  <span class="badge bg-success">
                    @if($edu->is_current)
                      Current
                    @else
                      {{ $edu->education_start ? \Carbon\Carbon::parse($edu->education_start)->format('M Y') : '' }} - {{ $edu->education_end ? \Carbon\Carbon::parse($edu->education_end)->format('M Y') : '' }}
                    @endif
                  </span>
                </div>
                <h4 class="h6 text-muted mb-2">{{ $edu->institution }}</h4>
                @if($edu->description)
                  <p class="mb-0">{{ $edu->description }}</p>
                @endif
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
          <!-- Skills -->
          @if($cv->skills->count() > 0)
          <div class="section-card">
            <div class="section-header">
              <h3 class="h5 mb-0">
                <i class="fas fa-tools me-2"></i>Skills
              </h3>
            </div>
            <div class="section-content">
              <div class="d-flex flex-wrap">
                @foreach($cv->skills as $skill)
                <span class="skill-tag">{{ $skill->skill_name }}</span>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          <!-- Languages -->
          @if($cv->languages->count() > 0)
          <div class="section-card">
            <div class="section-header">
              <h3 class="h5 mb-0">
                <i class="fas fa-language me-2"></i>Languages
              </h3>
            </div>
            <div class="section-content">
              @foreach($cv->languages as $language)
              <div class="language-item">
                <span class="fw-medium">{{ $language->language_name }}</span>
                <span class="proficiency-badge">{{ ucfirst($language->proficiency) }}</span>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Hobbies -->
          @if($cv->hobbies->count() > 0)
          <div class="section-card">
            <div class="section-header">
              <h3 class="h5 mb-0">
                <i class="fas fa-heart me-2"></i>Hobbies
              </h3>
            </div>
            <div class="section-content">
              @foreach($cv->hobbies as $hobby)
              <div class="hobby-item">
                <h4 class="h6 mb-1">{{ $hobby->hobby_name }}</h4>
                @if($hobby->description)
                  <p class="small text-muted mb-0">{{ $hobby->description }}</p>
                @endif
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
