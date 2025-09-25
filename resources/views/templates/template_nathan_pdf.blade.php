<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Curriculum Vitae — {{ $cv['name'] }}</title>
    <style>
        /* PDF-optimized CSS for Nathan template (copiado idéntico del original) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
            font-size: 12px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: #007bff;
            color: white;
            padding: 30px 20px;
            width: 35%;
            float: left;
            min-height: 100vh;
        }
        
        .main-content {
            width: 65%;
            float: right;
            padding: 30px 20px;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header .title {
            font-size: 16px;
            margin-bottom: 15px;
            color: #e3f2fd;
        }
        
        .header .contact-info {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header .contact-info p {
            margin-bottom: 5px;
        }
        
        .header .contact-info a {
            color: white;
            text-decoration: none;
        }
        
        /* Section Styles */
        .section {
            margin-bottom: 25px;
        }
        
        .section h2 {
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #e3f2fd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .section h3 {
            color: #333;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        /* Main Content Styles */
        .main-section h2 {
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .main-section h3 {
            color: #333;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        /* Card Styles */
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }
        
        .card-body {
            padding: 15px;
        }
        
        .card-title {
            color: #333;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .card-subtitle {
            color: #666;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .card-text {
            color: #555;
            font-size: 11px;
            line-height: 1.5;
        }
        
        /* Skills */
        .skills-list {
            list-style: none;
            padding: 0;
        }
        
        .skills-list li {
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        /* Work Experience & Education */
        .experience-item, .education-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .experience-item:last-child, .education-item:last-child {
            border-bottom: none;
        }
        
        /* Hobbies */
        .hobbies-list {
            list-style: none;
            padding: 0;
        }
        
        .hobbies-list li {
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        /* Utility Classes */
        .text-muted {
            color: #6c757d;
        }
        
        .text-primary {
            color: #007bff;
        }
        
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }
        .mb-5 { margin-bottom: 25px; }
        
        /* Print Styles */
        @media print {
            body { font-size: 10px; }
            .sidebar { padding: 20px 15px; }
            .main-content { padding: 20px 15px; }
            .header h1 { font-size: 20px; }
            .main-section h2 { font-size: 16px; }
            .section h2 { font-size: 14px; }
            .card-body { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <!-- Header -->
        <header class="header">
            <h1>{{ $cv['name'] }}</h1>
            <p class="title">Software Developer</p>
            <div class="contact-info">
                <p>&#x1F4CD; {{ $cv['address'] }}</p>
                <p>&#x1F4DE; {{ $cv['phone_number'] }}</p>
                <p>&#x2709;&#xFE0F; <a href="mailto:{{ $cv['email'] }}">{{ $cv['email'] }}</a></p>
                <p>&#x1F382; {{ $cv['date_of_birth'] ? date('Y', strtotime($cv['date_of_birth'])) : 'N/A' }}</p>
                @if($cv['linkedin_profile'])
                    <p>&#x1F4BC; <a href="{{ $cv['linkedin_profile'] }}">LinkedIn Profile</a></p>
                @endif
                @if($cv['portfolio'])
                    <p>&#x1F310; <a href="{{ $cv['portfolio'] }}">Portfolio</a></p>
                @endif
            </div>
        </header>

        <!-- Skills -->
        <section class="section">
            <h2>&#x1F527; Skills</h2>
            @if(count($skills) > 0)
                <ul class="skills-list">
                    @foreach($skills as $skill)
                        <li>{{ $skill['skill_name'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No skills available.</p>
            @endif
        </section>

        <!-- Languages -->
        <section class="section">
            <h2>&#x1F30D; Languages</h2>
            @if(count($languages) > 0)
                <ul class="skills-list">
                    @foreach($languages as $language)
                        <li>{{ $language['language_name'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No languages available.</p>
            @endif
        </section>

        <!-- Hobbies -->
        <section class="section">
            <h2>&#x1F3AF; Hobbies</h2>
            @if(count($hobbies) > 0)
                <ul class="hobbies-list">
                    @foreach($hobbies as $hobby)
                        <li><strong>{{ $hobby['hobby_name'] }}</strong>@if($hobby['description']) — {{ $hobby['description'] }}@endif</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No hobbies listed.</p>
            @endif
        </section>
    </div>

    <div class="main-content">
        <!-- Summary -->
        <section class="main-section">
            <h2>&#x1F4CB; Professional Summary</h2>
            <p>{{ $cv['profile_summary'] ? nl2br(e($cv['profile_summary'])) : 'Profile summary not available.' }}</p>
        </section>

        <!-- Work Experience -->
        <section class="main-section">
            <h2>&#x1F4BC; Work Experience</h2>
            @if(count($workExperiences) > 0)
                @foreach($workExperiences as $w)
                    <div class="experience-item">
                        <h3>{{ $w['job_title'] }}</h3>
                        <p class="text-muted">
                            <strong>{{ $w['company_name'] }}</strong>
                            @if($w['work_start'] || $w['work_end'])
                                | {{ $w['work_start'] }} @if($w['work_end']) – {{ $w['work_end'] }}@else – Present@endif
                            @endif
                        </p>
                        @if(!empty($w['description']))
                            <p>{{ nl2br(e($w['description'])) }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted">No work experience available.</p>
            @endif
        </section>

        <!-- Education -->
        <section class="main-section">
            <h2>&#x1F393; Education</h2>
            @if(count($education) > 0)
                @foreach($education as $edu)
                    <div class="education-item">
                        <h3>{{ $edu['degree'] }}</h3>
                        <p class="text-muted">
                            <strong>{{ $edu['institution'] }}</strong>
                            @if($edu['education_start'] || $edu['education_end'])
                                | {{ $edu['education_start'] }} @if($edu['education_end']) – {{ $edu['education_end'] }}@else – Present@endif
                            @endif
                        </p>
                        @if(!empty($edu['description']))
                            <p>{{ nl2br(e($edu['description'])) }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted">No education data available.</p>
            @endif
        </section>
    </div>
</div>

</body>
</html>
