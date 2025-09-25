@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Curriculum Vitae — {{ $cv->name }}</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
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
    
    .sidebar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }
    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      transition: all 0.3s ease;
    }
    .sidebar .nav-link:hover {
      color: white;
      background-color: rgba(255, 255, 255, 0.1);
    }
    .sidebar .nav-link.active {
      color: white;
      background-color: rgba(255, 255, 255, 0.2);
    }
    .main-content {
      background: #f8f9fa;
    }
    .section-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
    }
    .section-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 10px 10px 0 0;
      padding: 1rem 1.5rem;
    }
    .contact-item {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    .contact-item i {
      width: 20px;
      margin-right: 10px;
    }
    .skill-item {
      background: #f8f9fa;
      border-radius: 20px;
      padding: 0.5rem 1rem;
      margin: 0.25rem;
      display: inline-block;
    }
    .language-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      border-bottom: 1px solid #eee;
    }
    .language-item:last-child {
      border-bottom: none;
    }
    .proficiency-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 15px;
      font-size: 0.8rem;
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
<body class="bg-light">

@if(isset($print_mode) && $print_mode)
<!-- Print mode is active - automatic print will trigger -->
@endif

  <!-- Mobile Navigation Toggle -->
  <nav class="navbar navbar-expand-lg d-lg-none bg-primary text-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('welcome') }}">
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
      <div class="col-lg-4 col-md-12 sidebar text-white p-4 collapse d-lg-block" id="mobileSidebar">
        <!-- Navigation -->
        <div class="mb-4">
          <a href="{{ route('welcome') }}" class="btn btn-outline-light btn-sm mb-3 d-flex align-items-center">
            <i class="fas fa-home me-2"></i>Back to Home
          </a>
        </div>

        <header class="text-center mb-4 position-relative">
          <div class="mb-3">
            <i class="fas fa-user-circle fa-5x"></i>
          </div>
          <h1 class="h2 mb-3">{{ $cv->name }}</h1>
          <p class="lead">Software Developer</p>
          <p>Address: {{ $cv->address }} | Phone: {{ $cv->phone_number }} | Email: {{ $cv->email }}</p>
          <p>Date of Birth: {{ $cv->date_of_birth ? \Carbon\Carbon::parse($cv->date_of_birth)->format('Y') : 'N/A' }} | 
            @if($cv->linkedin_profile)
              <a href="{{ $cv->linkedin_profile }}" target="_blank" class="text-white">LinkedIn Profile</a>
            @else
              <span class="text-white-50">LinkedIn Profile</span>
            @endif
          </p>
          @if($cv->portfolio)
          <p>
            <i class="fas fa-globe me-2"></i>
            <a href="{{ $cv->portfolio }}" target="_blank" class="text-white">Portfolio</a>
          </p>
          @endif
        </header>

        <!-- Skills -->
        @if($cv->skills->count() > 0)
        <section class="mb-4">
          <h3 class="h5 mb-3">
            <i class="fas fa-tools me-2"></i>Skills
          </h3>
          <div class="d-flex flex-wrap">
            @foreach($cv->skills as $skill)
            <div class="skill-item">
              {{ $skill->skill_name }}
            </div>
            @endforeach
          </div>
        </section>
        @endif

        <!-- Languages -->
        @if($cv->languages->count() > 0)
        <section class="mb-4">
          <h3 class="h5 mb-3">
            <i class="fas fa-language me-2"></i>Languages
          </h3>
          @foreach($cv->languages as $language)
          <div class="language-item">
            <span>{{ $language->language_name }}</span>
            <span class="proficiency-badge">{{ ucfirst($language->proficiency) }}</span>
          </div>
          @endforeach
        </section>
        @endif

        <!-- Hobbies -->
        @if($cv->hobbies->count() > 0)
        <section class="mb-4">
          <h3 class="h5 mb-3">
            <i class="fas fa-heart me-2"></i>Hobbies
          </h3>
          @foreach($cv->hobbies as $hobby)
          <div class="mb-2">
            <strong>{{ $hobby->hobby_name }}</strong>
            @if($hobby->description)
              <p class="small mb-0">{{ $hobby->description }}</p>
            @endif
          </div>
          @endforeach
        </section>
        @endif
      </div>

      <!-- Main Content -->
      <div class="col-lg-8 main-content p-4">
        <!-- Export Buttons - Show for CV owner, admin, or guest CVs -->
        @if((Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin')) || $cv->user_id === null)
        <div class="d-flex justify-content-end mb-4">
          <div class="d-flex flex-column gap-1">
            @if(Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin'))
              <!-- Authenticated user - show actual export links -->
              <a href="print-cv-working.php?id={{ $cv->id }}" class="btn btn-sm" style="background-color: #87CEEB; color: #000; border: none;" target="_blank">
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
        </div>
        @endif

        <!-- Profile Summary -->
        @if($cv->profile_summary)
        <div class="section-card">
          <div class="section-header">
            <h2 class="h4 mb-0">
              <i class="fas fa-user me-2"></i>Profile Summary
            </h2>
          </div>
          <div class="p-4">
            <p class="mb-0">{{ $cv->profile_summary }}</p>
          </div>
        </div>
        @endif

        <!-- Work Experience -->
        @if($cv->workExperiences->count() > 0)
        <div class="section-card">
          <div class="section-header">
            <h2 class="h4 mb-0">
              <i class="fas fa-briefcase me-2"></i>Work Experience
            </h2>
          </div>
          <div class="p-4">
            @foreach($cv->workExperiences as $work)
            <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
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
          <div class="p-4">
            @foreach($cv->education as $edu)
            <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
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
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
