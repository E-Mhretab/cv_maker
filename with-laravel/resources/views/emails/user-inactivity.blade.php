<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Miss You! - LuxDemo Estate CV Maker</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .subtitle {
            font-size: 16px;
            font-weight: 300;
            margin-top: 8px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.6;
        }
        .highlight-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }
        .highlight-box h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 700;
        }
        .highlight-box p {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .activity-info {
            background-color: #f8f9fa;
            border-left: 5px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .activity-info h3 {
            color: #667eea;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }
        .activity-info p {
            margin: 8px 0;
            font-size: 14px;
        }
        .cta-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 0 10px 10px 10px;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .footer {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 25px 30px;
            text-align: center;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .footer .company-name {
            font-weight: 700;
            font-size: 16px;
            color: #495057;
            margin-bottom: 10px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .unsubscribe {
            font-size: 12px;
            color: #adb5bd;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>👋 We Miss You!</h1>
            <div class="subtitle">LuxDemo Estate CV Maker</div>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>We noticed you haven't been active on our CV Maker platform for a while. Your last activity was <strong>{{ $lastActivityTime->format('F j, Y \a\t g:i A') }}</strong> - that's over <strong>{{ $inactiveDuration }} minutes</strong> ago!</p>
            
            <div class="highlight-box">
                <h2>🚀 Ready to Create Your Next CV?</h2>
                <p>Don't let your professional opportunities slip away!</p>
            </div>
            
            <div class="activity-info">
                <h3>📊 Your Activity Summary</h3>
                <p><strong>Last Login:</strong> {{ $lastActivityTime->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Inactive Duration:</strong> {{ $inactiveDuration }} minutes</p>
                <p><strong>Account Status:</strong> Active and Ready</p>
            </div>
            
            <p>We have some exciting features and templates waiting for you:</p>
            <ul style="line-height: 1.8; margin: 20px 0;">
                <li>🎨 <strong>Professional Templates</strong> - Nathan, Esey, and Mirian designs</li>
                <li>📄 <strong>PDF Export</strong> - Download your CV instantly</li>
                <li>📧 <strong>Email Sharing</strong> - Send CVs directly to employers</li>
                <li>🔒 <strong>Secure Storage</strong> - Your data is safe with us</li>
            </ul>
            
            <div class="cta-buttons">
                <a href="{{ url('/cvs') }}" class="btn">📋 View My CVs</a>
                <a href="{{ url('/cvs/create') }}" class="btn btn-secondary">✨ Create New CV</a>
            </div>
            
            <p>Don't miss out on your next career opportunity! Log back in and create that perfect CV that will land you your dream job.</p>
            
            <p>If you have any questions or need assistance, feel free to reach out to us.</p>
            
            <p>Best regards,<br><strong>The LuxDemo Estate Team</strong></p>
        </div>
        
        <div class="footer">
            <p class="company-name">LuxDemo Estate CV Maker</p>
            <p>Professional CV Creation Platform</p>
            <p>Email: <a href="mailto:web@luxdemoestate.com">web@luxdemoestate.com</a></p>
            <p>Website: <a href="{{ url('/') }}">{{ url('/') }}</a></p>
            <div class="unsubscribe">
                <p>This email was sent because you haven't been active for over 1 hour.</p>
                <p>If you no longer wish to receive these reminders, you can <a href="mailto:web@luxdemoestate.com?subject=Unsubscribe">unsubscribe here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
