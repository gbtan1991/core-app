<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly string $token,
        private readonly string $inviterName,
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
        $acceptUrl = route('invitation.accept', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('You have been invited to join the team')
            ->greeting('Hello!')
            ->line("{$this->inviterName} has invited you to join the team as a staff member.")
            ->action('Accept Invitation', $acceptUrl)
            ->line('This invitation link will expire in 48 hours.')
            ->line('If you did not expect this invitation, you may ignore this email.');
    }
}
