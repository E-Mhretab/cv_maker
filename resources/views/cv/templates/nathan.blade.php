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
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/nathan.css') }}">
</head>
<body class="bg-light">
  <!-- Mobile Navigation Toggle -->
  <nav class="navbar navbar-expand-lg d-lg-none bg-primary text-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('home') }}">
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
          <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm mb-3 d-flex align-items-center">
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
            @endif
          </p>
          @if($cv->portfolio)
            <p><a href="{{ $cv->portfolio }}" target="_blank" class="text-warning fw-bold">Portfolio</a></p>
          @endif
        </header>

        <!-- Profile Summary -->
        @if($cv->profile_summary)
        <section class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-user me-2"></i>Profile Summary</h3>
          <p class="small">{{ $cv->profile_summary }}</p>
        </section>
        @endif

        <!-- Skills -->
        @if($cv->skills->count() > 0)
        <section class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-cogs me-2"></i>Skills</h3>
          <div class="row g-2">
            @foreach($cv->skills as $skill)
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <span class="small">{{ $skill->skill_name }}</span>
                @if($skill->description)
                  <small class="text-muted">{{ $skill->description }}</small>
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </section>
        @endif

        <!-- Languages -->
        @if($cv->languages->count() > 0)
        <section class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-language me-2"></i>Languages</h3>
          <div class="row g-2">
            @foreach($cv->languages as $language)
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <span class="small">{{ $language->language_name }}</span>
                <span class="badge bg-light text-dark">{{ ucfirst($language->proficiency) }}</span>
              </div>
            </div>
            @endforeach
          </div>
        </section>
        @endif
      </div>

      <!-- Main Content -->
      <div class="col-lg-8 col-md-12 p-4">
        <!-- Work Experience -->
        @if($cv->workExperience->count() > 0)
        <section class="mb-5">
          <h2 class="h4 mb-4 text-primary"><i class="fas fa-briefcase me-2"></i>Work Experience</h2>
          @foreach($cv->workExperience as $work)
          <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0">{{ $work->position }}</h5>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($work->start_date)->format('M Y') }} - 
                  {{ $work->end_date ? \Carbon\Carbon::parse($work->end_date)->format('M Y') : 'Present' }}
                </small>
              </div>
              <h6 class="card-subtitle mb-2 text-muted">{{ $work->company_name }}</h6>
              @if($work->description)
                <p class="card-text">{{ $work->description }}</p>
              @endif
            </div>
          </div>
          @endforeach
        </section>
        @endif

        <!-- Education -->
        @if($cv->education->count() > 0)
        <section class="mb-5">
          <h2 class="h4 mb-4 text-primary"><i class="fas fa-graduation-cap me-2"></i>Education</h2>
          @foreach($cv->education as $edu)
          <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0">{{ $edu->degree }}</h5>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - 
                  {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                </small>
              </div>
              <h6 class="card-subtitle mb-2 text-muted">{{ $edu->institution_name }}</h6>
              @if($edu->field_of_study)
                <p class="card-text"><strong>Field of Study:</strong> {{ $edu->field_of_study }}</p>
              @endif
            </div>
          </div>
          @endforeach
        </section>
        @endif
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
