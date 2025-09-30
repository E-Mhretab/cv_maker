<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV of {{ $cv->name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .header {
            text-align: center;
            border-bottom: 4px solid #007bff;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .header .subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-top: 10px;
        }
        .content {
            margin-bottom: 40px;
        }
        .cv-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #007bff;
            margin: 25px 0;
        }
        .cv-info h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .cv-info p {
            margin: 8px 0;
            font-size: 16px;
        }
        .cv-info strong {
            color: #495057;
            font-weight: 600;
        }
        .pdf-notice {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            color: #0c5460;
        }
        .pdf-notice strong {
            color: #0c5460;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .footer .company-name {
            font-weight: 600;
            color: #007bff;
            font-size: 16px;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📄 CV of {{ $cv->name }}</h1>
            <div class="subtitle">Sent via LuxDemo Estate</div>
        </div>
        
        <div class="content">
            <p>Dear {{ $userName }},</p>
            
            <p>Thank you for your interest in our professional CV services. Please find the CV document of <strong>{{ $cv->name }}</strong> as requested through our certified CV management system.</p>
            
            <p><strong>Document Security:</strong> This email is sent from our secure business system and contains no harmful attachments. All information is confidential and used only for professional business purposes.</p>
            
            <p><strong>Business Purpose:</strong> This communication is part of our professional CV management services and is intended for legitimate business use only.</p>
            
            <div class="cv-info">
                <h3>👤 Candidate Information</h3>
                <p><strong>Name:</strong> {{ $cv->name }}</p>
                <p><strong>Email:</strong> {{ $cv->email }}</p>
                <p><strong>Phone:</strong> {{ $cv->phone_number }}</p>
                @if($cv->address)
                    <p><strong>Address:</strong> {{ $cv->address }}</p>
                @endif
                @if($cv->linkedin_profile)
                    <p><strong>LinkedIn:</strong> <a href="{{ $cv->linkedin_profile }}" style="color: #007bff;">{{ $cv->linkedin_profile }}</a></p>
                @endif
                @if($cv->portfolio)
                    <p><strong>Portfolio:</strong> <a href="{{ $cv->portfolio }}" style="color: #007bff;">{{ $cv->portfolio }}</a></p>
                @endif
                @if($cv->profile_summary)
                    <p><strong>Profile Summary:</strong><br>{{ $cv->profile_summary }}</p>
                @endif
            </div>
            
            <div class="pdf-notice">
                <strong>📎 PDF Download:</strong> You can download the complete CV as PDF via the website by logging in and going to the CV. Click the "Export PDF" button to download the CV.
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('cvs.public.show', $cv->id) }}" class="btn">View CV Online</a>
                <a href="{{ route('cvs.pdf', $cv->id) }}" class="btn btn-secondary">Download PDF</a>
            </div>
            
            <p><strong>What can you do now?</strong></p>
            <ul style="line-height: 1.8;">
                <li>📥 Download the CV as PDF via the website</li>
                <li>📞 Contact the candidate via the provided contact details</li>
                <li>💼 Schedule a meeting or interview</li>
                <li>📋 Save the CV in your system</li>
            </ul>
            
            <p>If you have any questions about this candidate or need more information, please do not hesitate to contact us.</p>
        </div>
        
        <div class="footer">
            <p class="company-name">LuxDemo Estate</p>
            <p>Professional CV Services</p>
            <p>Document ID: CV-{{ $cv->id }}-{{ date('Y') }}</p>
            <p>Sent by: {{ config('mail.from.address') }}</p>
            <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                <strong>Business Communication:</strong> This email is automatically generated by our certified CV Management System for legitimate business purposes.<br>
                <strong>Document Security:</strong> This email contains no harmful attachments and is sent from a secure business server.<br>
                <strong>Privacy Policy:</strong> All information is handled according to our privacy policy and business confidentiality standards.<br>
                <strong>Unsubscribe:</strong> If you no longer wish to receive these business communications, you can <a href="mailto:{{ config('mail.from.address') }}?subject=Unsubscribe" style="color: #007bff;">unsubscribe here</a><br>
                <strong>Business Contact:</strong> For business inquiries, contact us via <a href="mailto:{{ config('mail.from.address') }}" style="color: #007bff;">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>