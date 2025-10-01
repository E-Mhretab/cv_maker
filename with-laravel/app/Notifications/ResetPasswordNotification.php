<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);
        
        return (new MailMessage)
            ->subject('Password Reset - LuxDemo Estate CV Maker')
            ->from(env('MAIL_FROM_ADDRESS', 'web@luxdemoestate.com'), env('MAIL_FROM_NAME', 'LuxDemo Estate'))
            ->greeting('Hello!')
            ->line('We received a request to reset your password for your LuxDemo Estate CV Maker account.')
            ->line('If you requested this password reset, please click the button below to set a new password:')
            ->action('Reset My Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation('Thank you for using LuxDemo Estate!');
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(Config::get('auth.passwords.users.expire', 60)),
            [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
