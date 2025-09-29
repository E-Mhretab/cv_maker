<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uw CV is klaar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .cv-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .cv-info h3 {
            margin-top: 0;
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Uw CV is klaar!</h1>
        </div>
        
        <div class="content">
            <p>Beste {{ $userName }},</p>
            
            <p>Gefeliciteerd! Uw CV "<strong>{{ $cv->name }}</strong>" is succesvol aangemaakt en staat klaar voor gebruik.</p>
            
            <div class="cv-info">
                <h3>📄 CV Details</h3>
                <p><strong>Naam:</strong> {{ $cv->name }}</p>
                <p><strong>E-mail:</strong> {{ $cv->email }}</p>
                <p><strong>Telefoon:</strong> {{ $cv->phone_number }}</p>
                @if($cv->linkedin_profile)
                    <p><strong>LinkedIn:</strong> {{ $cv->linkedin_profile }}</p>
                @endif
                @if($cv->portfolio)
                    <p><strong>Portfolio:</strong> {{ $cv->portfolio }}</p>
                @endif
            </div>
            
            <p>U kunt uw CV als PDF downloaden via de website door in te loggen en naar uw CV te gaan. Klik op de "Export PDF" knop om uw CV te downloaden.</p>
            
            <p><strong>Wat kunt u nu doen?</strong></p>
            <ul>
                <li>📥 Download uw CV als PDF via de website</li>
                <li>✏️ Log in op onze website om uw CV te bewerken</li>
                <li>📤 Deel uw CV direct met werkgevers</li>
                <li>🔄 Maak meerdere versies voor verschillende functies</li>
            </ul>
            
            <p>Als u vragen heeft of hulp nodig heeft, aarzel dan niet om contact met ons op te nemen.</p>
        </div>
        
        <div class="footer">
            <p><strong>CV Maker</strong></p>
            <p>De beste manier om professionele CV's te maken</p>
            <p>Verzonden door: nathanjethoe007@gmail.com</p>
        </div>
    </div>
</body>
</html>
