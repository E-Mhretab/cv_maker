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

class SendCvMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cv;
    public $userName;
    public $recipientEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(Cv $cv, string $userName, string $recipientEmail = null)
    {
        $this->cv = $cv;
        $this->userName = $userName;
        $this->recipientEmail = $recipientEmail ?? $cv->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CV van ' . $this->cv->name . ' - Business Development',
            from: new \Illuminate\Mail\Mailables\Address(env('MAIL_FROM_ADDRESS', 'esey@businessdevelopment.es'), env('MAIL_FROM_NAME', 'Business Development')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Check if PDF generation will be successful
        $hasPdfAttachment = false;
        try {
            $pdfService = app(PdfExportService::class);
            $result = $pdfService->generatePdf($this->cv);
            $hasPdfAttachment = isset($result['success']) && $result['success'] && isset($result['content']) && !empty($result['content']);
        } catch (\Exception $e) {
            $hasPdfAttachment = false;
        }

        return new Content(
            view: 'emails.cv-email',
            with: [
                'cv' => $this->cv,
                'userName' => $this->userName,
                'recipientEmail' => $this->recipientEmail,
                'hasPdfAttachment' => $hasPdfAttachment,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        try {
            // Generate PDF using the service
            $pdfService = app(PdfExportService::class);
            $result = $pdfService->generatePdf($this->cv);
            
            // Check if PDF generation was successful and content is not null
            if (isset($result['success']) && $result['success'] && isset($result['content']) && !empty($result['content'])) {
                // Save PDF to storage temporarily
                $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $this->cv->name) . '_' . date('Y-m-d') . '.pdf';
                $path = 'temp/' . $filename;
                
                // Ensure the temp directory exists
                if (!Storage::disk('public')->exists('temp')) {
                    Storage::disk('public')->makeDirectory('temp');
                }
                
                Storage::disk('public')->put($path, $result['content']);
                
                $attachments[] = Attachment::fromStorage('public/' . $path)
                    ->as($filename)
                    ->withMime('application/pdf');
                    
                \Log::info('PDF attachment added to email', ['cv_id' => $this->cv->id, 'filename' => $filename]);
            } else {
                \Log::warning('PDF generation failed or returned empty content - skipping PDF attachment', [
                    'cv_id' => $this->cv->id,
                    'success' => $result['success'] ?? false,
                    'has_content' => !empty($result['content'] ?? null),
                    'result_keys' => array_keys($result ?? [])
                ]);
                // Don't add any attachment if PDF generation failed
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF for email - skipping PDF attachment', [
                'cv_id' => $this->cv->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't add any attachment if PDF generation failed
        }
        
        return $attachments;
    }
}
