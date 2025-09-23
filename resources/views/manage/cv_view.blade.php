<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View CV - {{ $cv->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1>{{ $cv->name }}</h1>
        <p>Template: {{ $cv->metadata->template_type }}</p>
        <p>Email: {{ $cv->email }}</p>
        <p>Summary: {{ $cv->profile_summary }}</p>
        <!-- Agrega secciones para work, education, etc. con @foreach -->
        <h2>Work Experience</h2>
        @foreach ($cv->workExperiences as $work)
            <p>{{ $work->job_title }} at {{ $work->company_name }}</p>
        @endforeach
        <!-- Similar para otras secciones -->
    </div>
</body>
</html>
