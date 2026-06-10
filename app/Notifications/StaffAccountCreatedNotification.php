<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffAccountCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $temporaryPassword The plain-text temporary password (sent once, never stored).
     * @param string $staffName         The staff member's full name.
     */
    public function __construct(
        private readonly string $temporaryPassword,
        private readonly string $staffName,
    ) {}

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
        $loginUrl = route('login');

        return (new MailMessage)
            ->subject('Your ' . config('app.name') . ' account is ready')
            ->greeting("Hello, {$this->staffName}!")
            ->line('An administrator has created an account for you on ' . config('app.name') . '.')
            ->line('**Your login credentials:**')
            ->line("**Email:** {$notifiable->email}")
            ->line("**Temporary Password:** `{$this->temporaryPassword}`")
            ->action('Log In Now', $loginUrl)
            ->line('**Please log in and change your password immediately.** Your temporary password will expire after first use.')
            ->line('If you were not expecting this email, please contact your administrator.');
    }
}
