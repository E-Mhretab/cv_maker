
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Preview - {{ $cv->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-top: 80px; }
        .preview-header { position: fixed; top: 0; left: 0; width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,0.15); }
        .preview-actions { position: fixed; top: 0; right: 0; z-index: 101; padding: 1rem; }
        .cv-container { background: white; border-radius: 10px; padding: 2rem; margin-top: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-title h1 { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .header-title p { font-size: 1.2rem; margin-bottom: 0; }
        .header-badge { font-size: 1rem; background: #ffc107; color: #333; border-radius: 5px; padding: 0.25rem 0.75rem; margin-left: 1rem; }
        .section-title { color: #764ba2; border-bottom: 2px solid #764ba2; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
        .cv-section { margin-bottom: 2rem; }
    </style>
</head>
<body class="bg-light">
    <!-- Preview Actions -->
    <div class="preview-actions">
        <a href="{{ route('manage.cvs.index') }}" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> Back to List</a>
        <a href="{{ route('manage.cvs.edit', $cv->id) }}" class="btn btn-warning me-2"><i class="fas fa-edit"></i> Edit CV</a>
        <a href="#" onclick="window.print();return false;" class="btn btn-info me-2"><i class="fas fa-print"></i> Print</a>
        <a href="{{ route('manage.cvs.export', ['id' => $cv->id, 'format' => 'pdf']) }}" class="btn btn-danger me-2"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('manage.cvs.export', ['id' => $cv->id, 'format' => 'xml']) }}" class="btn btn-success"><i class="fas fa-file-code"></i> Export XML</a>
    </div>

    <!-- Preview Header -->
    <div class="preview-header py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="header-title">
            <h1>{{ $cv->name }}</h1>
            <p>{{ $cv->profile_summary }}</p>
        </div>
        <div class="header-badge">
            Template: {{ $cv->metadata->template_type }}
            @if($cv->metadata->is_public)
                <span class="badge bg-success ms-2">Public</span>
            @else
                <span class="badge bg-secondary ms-2">Private</span>
            @endif
        </div>
    </div>

    <!-- CV Preview Container -->
    <div class="container cv-container">
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Email:</strong> {{ $cv->email }}<br>
                <strong>Phone:</strong> {{ $cv->phone_number }}<br>
                <strong>Date of Birth:</strong> {{ $cv->date_of_birth }}<br>
                <strong>Address:</strong> {{ $cv->address }}<br>
            </div>
            <div class="col-md-6">
                @if($cv->linkedin_profile)
                    <strong>LinkedIn:</strong> <a href="{{ $cv->linkedin_profile }}" target="_blank">{{ $cv->linkedin_profile }}</a><br>
                @endif
                @if($cv->portfolio)
                    <strong>Portfolio:</strong> <a href="{{ $cv->portfolio }}" target="_blank">{{ $cv->portfolio }}</a><br>
                @endif
            </div>
        </div>

        <!-- Work Experience -->
        <div class="cv-section">
            <h2 class="section-title"><i class="fas fa-briefcase me-2"></i>Work Experience</h2>
            @forelse($cv->workExperiences as $work)
                <div class="mb-3">
                    <strong>{{ $work->job_title }}</strong> at <strong>{{ $work->company_name }}</strong><br>
                    <span>{{ $work->work_start }} - {{ $work->is_current ? 'Present' : $work->work_end }}</span><br>
                    <span>{{ $work->description }}</span>
                </div>
            @empty
                <div class="alert alert-info">No work experience added.</div>
            @endforelse
        </div>

        <!-- Education -->
        <div class="cv-section">
            <h2 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Education</h2>
            @forelse($cv->education as $edu)
                <div class="mb-3">
                    <strong>{{ $edu->degree }}</strong> at <strong>{{ $edu->institution }}</strong><br>
                    <span>{{ $edu->education_start }} - {{ $edu->is_current ? 'Present' : $edu->education_end }}</span><br>
                    <span>{{ $edu->description }}</span>
                </div>
            @empty
                <div class="alert alert-info">No education added.</div>
            @endforelse
        </div>

        <!-- Skills -->
        <div class="cv-section">
            <h2 class="section-title"><i class="fas fa-lightbulb me-2"></i>Skills</h2>
            @forelse($cv->skills as $skill)
                <span class="badge bg-primary me-2 mb-2">{{ $skill->skill_name }}</span>
            @empty
                <div class="alert alert-info">No skills added.</div>
            @endforelse
        </div>

        <!-- Languages -->
        <div class="cv-section">
            <h2 class="section-title"><i class="fas fa-language me-2"></i>Languages</h2>
            @forelse($cv->languages as $lang)
                <span class="badge bg-info me-2 mb-2">{{ $lang->language_name }} ({{ ucfirst($lang->proficiency) }})</span>
            @empty
                <div class="alert alert-info">No languages added.</div>
            @endforelse
        </div>

        <!-- Hobbies -->
        <div class="cv-section">
            <h2 class="section-title"><i class="fas fa-heart me-2"></i>Hobbies</h2>
            @forelse($cv->hobbies as $hobby)
                <span class="badge bg-warning text-dark me-2 mb-2">{{ $hobby->hobby_name }}</span>
            @empty
                <div class="alert alert-info">No hobbies added.</div>
            @endforelse
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
