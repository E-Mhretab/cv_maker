<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>CV - {{ $cv['name'] }}</title>
    <style>
        /* PDF-optimized CSS (copiado idéntico del original) */
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
        
        /* Header Styles */
        .header {
            background: #007bff;
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header .contact-info {
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .header .contact-info a {
            color: white;
            text-decoration: none;
        }
        
        .header .contact-info a:hover {
            text-decoration: underline;
        }
        
        /* Section Styles */
        .section {
            margin-bottom: 25px;
        }
        
        .section h2 {
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .section h3 {
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
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .card-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .card-text {
            color: #555;
            font-size: 12px;
            line-height: 1.5;
        }
        
        /* Layout */
        .row {
            display: flex;
            gap: 20px;
        }
        
        .col-md-8 {
            flex: 2;
        }
        
        .col-md-4 {
            flex: 1;
        }
        
        /* Contact Box */
        .contact-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .contact-box h5 {
            color: #007bff;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .contact-box p {
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .contact-box a {
            color: #007bff;
            text-decoration: none;
        }
        
        /* Hobbies */
        .hobbies-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        
        .hobbies-box h5 {
            color: #007bff;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .hobbies-box ul {
            list-style: none;
            padding: 0;
        }
        
        .hobbies-box li {
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        /* Summary */
        .summary {
            background: #e3f2fd;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary p {
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
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
            body { font-size: 11px; }
            .container { padding: 10px; }
            .header { padding: 20px 10px; }
            .header h1 { font-size: 24px; }
            .section h2 { font-size: 16px; }
            .card-body { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <header class="header">
        <h1>{{ $cv['name'] }}</h1>
        <div class="contact-info">
            <div>📍 {{ $cv['address'] }}</div>
            <div>📞 {{ $cv['phone_number'] }} | ✉️ <a href="mailto:{{ $cv['email'] }}">{{ $cv['email'] }}</a></div>
            @if($cv['date_of_birth'])
                <div>🎂 {{ $cv['date_of_birth'] }}</div>
            @endif
            @if($cv['portfolio'])
                <div>🌐 <a href="{{ $cv['portfolio'] }}">Portfolio</a></div>
            @endif
            @if($cv['linkedin_profile'])
                <div>💼 <a href="{{ $cv['linkedin_profile'] }}">LinkedIn</a></div>
            @endif
        </div>
    </header>

    <!-- Summary -->
    <section class="section">
        <div class="summary">
            <h2>📋 Summary</h2>
            <p>{{ nl2br(e($cv['profile_summary'] ?? 'Profile summary not available.')) }}</p>
        </div>
    </section>

    <div class="row">
        <div class="col-md-8">
            <!-- Work Experience -->
            <section class="section">
                <h2>💼 Work Experience</h2>
                @if(count($workExperiences) > 0)
                    @foreach($workExperiences as $w)
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{ $w['job_title'] }}</h3>
                                <p class="card-subtitle">
                                    <strong>{{ $w['company_name'] }}</strong>
                                    @if($w['work_start'] || $w['work_end'])
                                        | {{ $w['work_start'] }} @if($w['work_end']) – {{ $w['work_end'] }}@else – Present@endif
                                    @endif
                                </p>
                                @if(!empty($w['description']))
                                    <p class="card-text">{{ nl2br(e($w['description'])) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No work experience available.</p>
                @endif
            </section>

            <!-- Education -->
            <section class="section">
                <h2>🎓 Education</h2>
                @if(count($education) > 0)
                    @foreach($education as $edu)
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{ $edu['degree'] }}</h3>
                                <p class="card-subtitle">
                                    <strong>{{ $edu['institution'] }}</strong>
                                    @if($edu['education_start'] || $edu['education_end'])
                                        | {{ $edu['education_start'] }} @if($edu['education_end']) – {{ $edu['education_end'] }}@else – Present@endif
                                    @endif
                                </p>
                                @if(!empty($edu['description']))
                                    <p class="card-text">{{ nl2br(e($edu['description'])) }}</p>
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
            <div class="contact-box">
                <h5>📞 Contact</h5>
                <p><strong>Email:</strong> <a href="mailto:{{ $cv['email'] }}">{{ $cv['email'] }}</a></p>
                @if($cv['phone_number'])<p><strong>Phone:</strong> {{ $cv['phone_number'] }}</p>@endif
                @if($cv['date_of_birth'])<p><strong>DOB:</strong> {{ $cv['date_of_birth'] }}</p>@endif
                @if($cv['linkedin_profile'])<p><a href="{{ $cv['linkedin_profile'] }}">LinkedIn</a></p>@endif
                @if($cv['portfolio'])<p><a href="{{ $cv['portfolio'] }}">Portfolio</a></p>@endif
            </div>

            <div class="hobbies-box">
                <h5>🎯 Hobbies</h5>
                @if(count($hobbies) > 0)
                    <ul>
                        @foreach($hobbies as $hb)
                            <li><strong>{{ $hb['hobby_name'] }}</strong>@if($hb['description']) — {{ $hb['description'] }}@endif</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hobbies listed.</p>
                @endif
            </div>
        </aside>
    </div>
</div>

</body>
</html>
