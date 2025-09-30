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
    <a href="{{ route('home') }}" class="navbar-brand fw-bold">
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
  </div>
</header>

<div class="container my-5">
  <!-- Profile Summary -->
  @if($cv->profile_summary)
  <section class="mb-5">
    <h2 class="h3 mb-3 text-primary"><i class="fas fa-user me-2"></i>Profile Summary</h2>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <p class="mb-0">{{ $cv->profile_summary }}</p>
      </div>
    </div>
  </section>
  @endif

  <div class="row">
    <!-- Skills -->
    @if($cv->skills->count() > 0)
    <div class="col-md-6 mb-4">
      <h2 class="h3 mb-3 text-primary"><i class="fas fa-cogs me-2"></i>Skills</h2>
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          @foreach($cv->skills as $skill)
          <div class="mb-3">
            <h5 class="mb-1">{{ $skill->skill_name }}</h5>
            @if($skill->description)
              <p class="text-muted small mb-0">{{ $skill->description }}</p>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- Languages -->
    @if($cv->languages->count() > 0)
    <div class="col-md-6 mb-4">
      <h2 class="h3 mb-3 text-primary"><i class="fas fa-language me-2"></i>Languages</h2>
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          @foreach($cv->languages as $language)
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-medium">{{ $language->language_name }}</span>
              <span class="badge bg-primary">{{ ucfirst($language->proficiency) }}</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>

  <!-- Work Experience -->
  @if($cv->workExperience->count() > 0)
  <section class="mb-5">
    <h2 class="h3 mb-3 text-primary"><i class="fas fa-briefcase me-2"></i>Work Experience</h2>
    @foreach($cv->workExperience as $work)
    <div class="card mb-3 border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h4 class="h5 mb-0">{{ $work->position }}</h4>
          <small class="text-muted">
            {{ \Carbon\Carbon::parse($work->start_date)->format('M Y') }} - 
            {{ $work->end_date ? \Carbon\Carbon::parse($work->end_date)->format('M Y') : 'Present' }}
          </small>
        </div>
        <h5 class="text-muted mb-2">{{ $work->company_name }}</h5>
        @if($work->description)
          <p class="mb-0">{{ $work->description }}</p>
        @endif
      </div>
    </div>
    @endforeach
  </section>
  @endif

  <!-- Education -->
  @if($cv->education->count() > 0)
  <section class="mb-5">
    <h2 class="h3 mb-3 text-primary"><i class="fas fa-graduation-cap me-2"></i>Education</h2>
    @foreach($cv->education as $edu)
    <div class="card mb-3 border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h4 class="h5 mb-0">{{ $edu->degree }}</h4>
          <small class="text-muted">
            {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - 
            {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
          </small>
        </div>
        <h5 class="text-muted mb-2">{{ $edu->institution_name }}</h5>
        @if($edu->field_of_study)
          <p class="mb-0"><strong>Field of Study:</strong> {{ $edu->field_of_study }}</p>
        @endif
      </div>
    </div>
    @endforeach
  </section>
  @endif
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
