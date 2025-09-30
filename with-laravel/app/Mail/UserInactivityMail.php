<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInactivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lastActivityTime;
    public $inactiveDuration;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->lastActivityTime = $user->last_login;
        $this->inactiveDuration = now()->diffInMinutes($user->last_login);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We Miss You! - LuxDemo Estate CV Maker',
            from: new \Illuminate\Mail\Mailables\Address(env('MAIL_FROM_ADDRESS', 'web@luxdemoestate.com'), env('MAIL_FROM_NAME', 'LuxDemo Estate')),
            tags: ['user-inactivity', 'luxdemo-estate', 'reminder'],
            metadata: [
                'user_id' => $this->user->id,
                'type' => 'inactivity-reminder',
                'category' => 'user-engagement',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-inactivity',
            with: [
                'user' => $this->user,
                'lastActivityTime' => $this->lastActivityTime,
                'inactiveDuration' => $this->inactiveDuration,
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
        return [];
    }
}
