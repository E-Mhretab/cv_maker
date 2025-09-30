<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV van {{ $cv->name }} - Business Development</title>
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
            <h1>📄 CV van {{ $cv->name }}</h1>
            <div class="subtitle">Verzonden via Business Development</div>
        </div>
        
        <div class="content">
            <p>Beste {{ $userName }},</p>
            
            <p>Hierbij ontvangt u de CV van <strong>{{ $cv->name }}</strong> zoals aangevraagd.</p>
            
            <div class="cv-info">
                <h3>👤 Kandidaat Informatie</h3>
                <p><strong>Naam:</strong> {{ $cv->name }}</p>
                <p><strong>E-mail:</strong> {{ $cv->email }}</p>
                <p><strong>Telefoon:</strong> {{ $cv->phone_number }}</p>
                @if($cv->address)
                    <p><strong>Adres:</strong> {{ $cv->address }}</p>
                @endif
                @if($cv->linkedin_profile)
                    <p><strong>LinkedIn:</strong> <a href="{{ $cv->linkedin_profile }}" style="color: #007bff;">{{ $cv->linkedin_profile }}</a></p>
                @endif
                @if($cv->portfolio)
                    <p><strong>Portfolio:</strong> <a href="{{ $cv->portfolio }}" style="color: #007bff;">{{ $cv->portfolio }}</a></p>
                @endif
                @if($cv->profile_summary)
                    <p><strong>Profiel Samenvatting:</strong><br>{{ $cv->profile_summary }}</p>
                @endif
            </div>
            
            <div class="pdf-notice">
                <strong>📎 PDF Bijlage:</strong> 
                @if(isset($hasPdfAttachment) && $hasPdfAttachment)
                    De volledige CV is als PDF-bijlage toegevoegd aan deze e-mail. U kunt deze downloaden en opslaan voor uw administratie.
                @else
                    De CV is beschikbaar via de onderstaande links. U kunt de CV online bekijken of als PDF downloaden.
                @endif
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('cvs.public.show', $cv->id) }}" class="btn">Bekijk CV Online</a>
                <a href="{{ route('cvs.pdf', $cv->id) }}" class="btn btn-secondary">Download PDF</a>
            </div>
            
            <p><strong>Wat kunt u nu doen?</strong></p>
            <ul style="line-height: 1.8;">
                <li>📥 Download de PDF-bijlage uit deze e-mail</li>
                <li>📞 Neem contact op met de kandidaat via de contactgegevens</li>
                <li>💼 Plan een gesprek of interview in</li>
                <li>📋 Bewaar de CV in uw systeem</li>
            </ul>
            
            <p>Als u vragen heeft over deze kandidaat of meer informatie nodig heeft, aarzel dan niet om contact met ons op te nemen.</p>
        </div>
        
        <div class="footer">
            <p class="company-name">Business Development</p>
            <p>Professional CV Services</p>
            <p>Verzonden door: {{ config('mail.from.address') }}</p>
            <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                Deze e-mail is automatisch gegenereerd door ons CV Management Systeem
            </p>
        </div>
    </div>
</body>
</html>