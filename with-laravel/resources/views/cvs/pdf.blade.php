<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cv->name }} - CV</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .cv-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .cv-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0d6efd;
        }

        .cv-name {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .cv-contact {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .cv-contact:last-child {
            margin-bottom: 0;
        }

        /* Section Styles */
        .cv-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .section-content {
            font-size: 14px;
            line-height: 1.5;
        }

        /* Profile Summary */
        .profile-summary {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
            font-style: italic;
        }

        /* Work Experience */
        .work-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .work-item:last-child {
            border-bottom: none;
        }

        .work-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .work-company {
            font-size: 14px;
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .work-dates {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .work-description {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
        }

        /* Education */
        .education-item {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .education-item:last-child {
            border-bottom: none;
        }

        .education-degree {
            font-size: 15px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .education-institution {
            font-size: 13px;
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .education-dates {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .education-description {
            font-size: 12px;
            color: #555;
            line-height: 1.4;
        }

        /* Skills Grid */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .skill-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #0d6efd;
        }

        .skill-name {
            font-weight: bold;
            color: #333;
            font-size: 13px;
        }

        .skill-description {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }

        /* Languages */
        .languages-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .language-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #28a745;
        }

        .language-name {
            font-weight: bold;
            color: #333;
            font-size: 13px;
        }

        .language-proficiency {
            font-size: 11px;
            color: #28a745;
            font-weight: 600;
            margin-top: 2px;
        }

        /* Hobbies */
        .hobbies-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .hobby-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #fd7e14;
        }

        .hobby-name {
            font-weight: bold;
            color: #333;
            font-size: 13px;
        }

        .hobby-description {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }

        /* Footer */
        .cv-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #999;
        }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .cv-container {
                padding: 0;
            }
            
            .cv-section {
                page-break-inside: avoid;
            }
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .current {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <!-- CV Header -->
        <div class="cv-header">
            <h1 class="cv-name">{{ $cv->name }}</h1>
            @if($cv->email)
                <div class="cv-contact">{{ $cv->email }}</div>
            @endif
            @if($cv->phone_number)
                <div class="cv-contact">{{ $cv->phone_number }}</div>
            @endif
            @if($cv->address)
                <div class="cv-contact">{{ $cv->address }}</div>
            @endif
            @if($cv->linkedin_profile)
                <div class="cv-contact">
                    <a href="{{ $cv->linkedin_profile }}" style="color: #0d6efd; text-decoration: none;">{{ $cv->linkedin_profile }}</a>
                </div>
            @endif
            @if($cv->portfolio)
                <div class="cv-contact">
                    <a href="{{ $cv->portfolio }}" style="color: #0d6efd; text-decoration: none;">{{ $cv->portfolio }}</a>
                </div>
            @endif
        </div>

        <!-- Profile Summary -->
        @if($cv->profile_summary)
            <div class="cv-section">
                <h2 class="section-title">Professional Summary</h2>
                <div class="profile-summary">
                    {{ $cv->profile_summary }}
                </div>
            </div>
        @endif

        <!-- Work Experience -->
        @if($cv->workExperiences->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">Work Experience</h2>
                @foreach($cv->workExperiences as $work)
                    <div class="work-item">
                        <div class="work-title">{{ $work->job_title }}</div>
                        <div class="work-company">{{ $work->company_name }}</div>
                        <div class="work-dates">
                            {{ \Carbon\Carbon::parse($work->work_start)->format('M Y') }} - 
                            @if($work->is_current)
                                <span class="current">Present</span>
                            @else
                                {{ \Carbon\Carbon::parse($work->work_end)->format('M Y') }}
                            @endif
                        </div>
                        @if($work->description)
                            <div class="work-description">{{ $work->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Education -->
        @if($cv->education->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">Education</h2>
                @foreach($cv->education as $edu)
                    <div class="education-item">
                        <div class="education-degree">{{ $edu->degree }}</div>
                        <div class="education-institution">{{ $edu->institution }}</div>
                        <div class="education-dates">
                            {{ \Carbon\Carbon::parse($edu->education_start)->format('M Y') }} - 
                            @if($edu->is_current)
                                <span class="current">Present</span>
                            @else
                                {{ \Carbon\Carbon::parse($edu->education_end)->format('M Y') }}
                            @endif
                        </div>
                        @if($edu->description)
                            <div class="education-description">{{ $edu->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Skills -->
        @if($cv->skills->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">Skills</h2>
                <div class="skills-grid">
                    @foreach($cv->skills as $skill)
                        <div class="skill-item">
                            <div class="skill-name">{{ $skill->skill_name }}</div>
                            @if($skill->description)
                                <div class="skill-description">{{ $skill->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Languages -->
        @if($cv->languages->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">Languages</h2>
                <div class="languages-grid">
                    @foreach($cv->languages as $language)
                        <div class="language-item">
                            <div class="language-name">{{ $language->language_name }}</div>
                            <div class="language-proficiency">{{ ucfirst($language->proficiency) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Hobbies & Interests -->
        @if($cv->hobbies->count() > 0)
            <div class="cv-section">
                <h2 class="section-title">Hobbies & Interests</h2>
                <div class="hobbies-grid">
                    @foreach($cv->hobbies as $hobby)
                        <div class="hobby-item">
                            <div class="hobby-name">{{ $hobby->hobby_name }}</div>
                            @if($hobby->description)
                                <div class="hobby-description">{{ $hobby->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="cv-footer">
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
