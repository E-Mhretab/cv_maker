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
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a href="{{ route('welcome') }}" class="navbar-brand fw-bold">
      <i class="fas fa-home me-2"></i>Back to Home
    </a>
  </div>
</nav>

<header class="bg-primary text-white text-center py-5 position-relative">
  <div class="container">
    <h1 class="display-4 fw-bold">{{ $cv->name }}</h1>
    <p class="lead mb-1">
      <i class="fas fa-map-marker-alt"></i>
      {{ $cv->address }}
      &nbsp;|&nbsp;
      <i class="fas fa-phone"></i>
      {{ $cv->phone_number }}
      &nbsp;|&nbsp;
      <i class="fas fa-envelope"></i>
      <a class="text-white" href="mailto:{{ $cv->email }}">{{ $cv->email }}</a>
    </p>
    <p class="mb-0">
      <i class="fas fa-birthday-cake"></i>
      {{ $cv->date_of_birth ? \Carbon\Carbon::parse($cv->date_of_birth)->format('Y-m-d') : '' }}
      @if($cv->portfolio)
        &nbsp;|&nbsp;<a class="text-warning fw-bold" target="_blank" href="{{ $cv->portfolio }}">Portfolio</a>
      @endif
      @if($cv->linkedin_profile)
        &nbsp;|&nbsp;<a class="text-white" target="_blank" href="{{ $cv->linkedin_profile }}"><i class="fab fa-linkedin"></i></a>
      @endif
    </p>
    
    <!-- Export Buttons - Show for CV owner, admin, or guest CVs -->
    @if((Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin')) || $cv->user_id === null)
    <div class="position-absolute top-0 end-0 p-3">
      <div class="d-flex flex-column gap-1">
        @if(Auth::check() && (Auth::id() == $cv->user_id || Auth::user()->role === 'admin'))
          <!-- Authenticated user - show actual export links -->
          <a href="{{ route('cvs.pdf', $cv->id) }}" class="btn btn-sm" style="background-color: #87CEEB; color: #000; border: none;" target="_blank">
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
  </div>
</header>

<main class="container my-5">
  <!-- Profile Summary -->
  @if($cv->profile_summary)
  <section class="mb-5">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h2 class="h4 mb-0 text-primary">
          <i class="fas fa-user me-2"></i>Profile Summary
        </h2>
      </div>
      <div class="card-body">
        <p class="mb-0">{{ $cv->profile_summary }}</p>
      </div>
    </div>
  </section>
  @endif

  <div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
      <!-- Work Experience -->
      @if($cv->workExperiences->count() > 0)
      <section class="mb-5">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h2 class="h4 mb-0 text-primary">
              <i class="fas fa-briefcase me-2"></i>Work Experience
            </h2>
          </div>
          <div class="card-body">
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
      </section>
      @endif

      <!-- Education -->
      @if($cv->education->count() > 0)
      <section class="mb-5">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h2 class="h4 mb-0 text-primary">
              <i class="fas fa-graduation-cap me-2"></i>Education
            </h2>
          </div>
          <div class="card-body">
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
      </section>
      @endif
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
      <!-- Skills -->
      @if($cv->skills->count() > 0)
      <section class="mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h3 class="h5 mb-0 text-primary">
              <i class="fas fa-tools me-2"></i>Skills
            </h3>
          </div>
          <div class="card-body">
            @foreach($cv->skills as $skill)
            <div class="mb-3">
              <h4 class="h6 mb-1">{{ $skill->skill_name }}</h4>
              @if($skill->description)
                <p class="small text-muted mb-0">{{ $skill->description }}</p>
              @endif
            </div>
            @endforeach
          </div>
        </div>
      </section>
      @endif

      <!-- Languages -->
      @if($cv->languages->count() > 0)
      <section class="mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h3 class="h5 mb-0 text-primary">
              <i class="fas fa-language me-2"></i>Languages
            </h3>
          </div>
          <div class="card-body">
            @foreach($cv->languages as $language)
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-medium">{{ $language->language_name }}</span>
              <span class="badge bg-info">{{ ucfirst($language->proficiency) }}</span>
            </div>
            @endforeach
          </div>
        </div>
      </section>
      @endif

      <!-- Hobbies -->
      @if($cv->hobbies->count() > 0)
      <section class="mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h3 class="h5 mb-0 text-primary">
              <i class="fas fa-heart me-2"></i>Hobbies
            </h3>
          </div>
          <div class="card-body">
            @foreach($cv->hobbies as $hobby)
            <div class="mb-3">
              <h4 class="h6 mb-1">{{ $hobby->hobby_name }}</h4>
              @if($hobby->description)
                <p class="small text-muted mb-0">{{ $hobby->description }}</p>
              @endif
            </div>
            @endforeach
          </div>
        </div>
      </section>
      @endif
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
