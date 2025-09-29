<?php

namespace App\Mail;

use App\Models\Cv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SimpleSendGridMail extends Mailable
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
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.simple-sendgrid-email',
            with: [
                'cv' => $this->cv,
                'userName' => $this->userName,
                'recipientEmail' => $this->recipientEmail,
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
        // No attachments for now - just a simple email
        return [];
    }
}
