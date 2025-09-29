# SendGrid Configuratie voor CV Maker

## Overzicht
Deze applicatie gebruikt Twilio SendGrid voor het versturen van CV's via e-mail met PDF-bijlagen.

## 1. .env Configuratie

Voeg de volgende instellingen toe aan je `.env` bestand:

```env
# SendGrid Configuration
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key-here
MAIL_FROM_ADDRESS=esey@businessdevelopment.es
MAIL_FROM_NAME="Business Development"
```

## 2. Mail Configuratie (config/mail.php)

De SendGrid mailer is al toegevoegd aan `config/mail.php`:

```php
'sendgrid' => [
    'transport' => 'sendgrid',
    'api_key' => env('SENDGRID_API_KEY'),
],
```

## 3. Mailable Class (SendGridCvMail)

```php
<?php

namespace App\Mail;

use App\Models\Cv;
use App\Services\PdfExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendGridCvMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cv;
    public $userName;
    public $recipientEmail;

    public function __construct(Cv $cv, string $userName, string $recipientEmail = null)
    {
        $this->cv = $cv;
        $this->userName = $userName;
        $this->recipientEmail = $recipientEmail ?? $cv->email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CV van ' . $this->cv->name . ' - Business Development',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sendgrid-cv-email',
            with: [
                'cv' => $this->cv,
                'userName' => $this->userName,
                'recipientEmail' => $this->recipientEmail,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        try {
            $pdfService = app(PdfExportService::class);
            $result = $pdfService->generatePdf($this->cv);
            
            if (isset($result['success']) && $result['success'] && isset($result['content']) && !empty($result['content'])) {
                $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $this->cv->name) . '_' . date('Y-m-d') . '.pdf';
                $path = 'temp/' . $filename;
                
                if (!Storage::disk('public')->exists('temp')) {
                    Storage::disk('public')->makeDirectory('temp');
                }
                
                Storage::disk('public')->put($path, $result['content']);
                
                $attachments[] = Attachment::fromStorage('public/' . $path)
                    ->as($filename)
                    ->withMime('application/pdf');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF for SendGrid email', [
                'cv_id' => $this->cv->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return $attachments;
    }
}
```

## 4. Controller Functionaliteit

```php
/**
 * Send CV via SendGrid to a specific recipient.
 */
public function sendViaSendGrid(Request $request, Cv $cv)
{
    // Check if user can access this CV
    if (Auth::user()->role !== 'admin' && $cv->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Validate the request
    $request->validate([
        'recipient_email' => 'required|email',
        'recipient_name' => 'nullable|string|max:255'
    ]);

    try {
        $user = Auth::user();
        $userName = $user->name ?? $cv->name;
        $recipientEmail = $request->recipient_email;
        $recipientName = $request->recipient_name ?? 'Geachte heer/mevrouw';
        
        // Load CV with all relationships for email
        $cv->load([
            'user', 'metadata', 'workExperiences', 
            'education', 'skills', 'languages', 'hobbies'
        ]);
        
        // Send via SendGrid
        Mail::mailer('sendgrid')
            ->to($recipientEmail)
            ->send(new SendGridCvMail($cv, $recipientName, $recipientEmail));
        
        return redirect()->back()->with('success', 'CV is succesvol verzonden naar ' . $recipientEmail . ' via SendGrid!');
        
    } catch (\Exception $e) {
        \Log::error('Failed to send CV via SendGrid', [
            'cv_id' => $cv->id,
            'recipient_email' => $request->recipient_email,
            'error' => $e->getMessage()
        ]);
        
        return redirect()->back()->withErrors(['error' => 'Er is een fout opgetreden bij het versturen van de CV via SendGrid: ' . $e->getMessage()]);
    }
}
```

## 5. Route Configuratie

```php
Route::post('/cv/{cv}/send-sendgrid', [CvController::class, 'sendViaSendGrid'])->name('cvs.send-sendgrid');
```

## 6. Frontend Modal Formulier

```html
<!-- SendGrid Modal -->
<div class="modal fade" id="sendGridModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verstuur CV via SendGrid</h5>
            </div>
            <form action="{{ route('cvs.send-sendgrid', $cv->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient_email" class="form-label">Ontvanger E-mailadres *</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Ontvanger Naam (optioneel)</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Verstuur via SendGrid</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

## 7. SendGrid Account Setup

### Stap 1: SendGrid Account Aanmaken
1. Ga naar [SendGrid.com](https://sendgrid.com)
2. Maak een account aan
3. Verifieer je e-mailadres

### Stap 2: API Key Genereren
1. Ga naar Settings → API Keys
2. Klik op "Create API Key"
3. Geef een naam (bijv. "CV Maker App")
4. Selecteer "Full Access" of "Restricted Access"
5. Kopieer de API key

### Stap 3: Sender Identity Verificeren
1. Ga naar Settings → Sender Authentication
2. Verificeer je domein of single sender
3. Voor `esey@businessdevelopment.es` moet je het domein `businessdevelopment.es` verifiëren

## 8. Testen

### Cache Clearen
```bash
php artisan config:clear
php artisan cache:clear
```

### Test E-mail Versturen
1. Log in op de website
2. Ga naar een CV
3. Klik op "Send via SendGrid"
4. Vul een test e-mailadres in
5. Controleer of de e-mail aankomt met PDF bijlage

## 9. Functionaliteiten

### Automatische PDF Bijlage
- CV wordt automatisch als PDF gegenereerd
- PDF wordt als bijlage toegevoegd aan de e-mail
- Fallback als PDF generatie faalt

### Professionele E-mail Template
- Mooie HTML e-mail template
- Kandidaat informatie
- Contact gegevens
- PDF bijlage instructies

### Veiligheid
- Alleen eigenaren en admins kunnen CV's versturen
- E-mail validatie
- Uitgebreide logging voor debugging

## 10. Troubleshooting

### E-mail komt niet aan
1. Controleer SendGrid API key
2. Controleer sender verification
3. Controleer spam folder
4. Bekijk SendGrid logs in dashboard

### PDF Bijlage Problemen
1. Controleer storage/app/public/temp map
2. Controleer PDF generatie service
3. Controleer Laravel logs

### API Fouten
1. Controleer API key permissions
2. Controleer rate limits
3. Controleer account status
