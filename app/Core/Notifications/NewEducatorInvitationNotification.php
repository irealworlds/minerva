<?php

namespace App\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewEducatorInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $inviterName,
        public mixed $institutionId,
        public string $institutionName,
        public string $invitationUri,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object{email: string}|string $notifiable
     */
    public function toMail(object|string $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New educator invitation')
            ->greeting('Hello!')
            ->line(
                mb_strtoupper($this->inviterName) .
                    ' has invited you to join ' .
                    mb_strtoupper($this->institutionName) .
                    ' as an educator.',
            )
            ->action('View invitation', $this->invitationUri)
            ->line(
                'If this message was not intended for you, please disregard its contents and notify an administrator!',
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'institution_id' => $this->institutionId,
            'institution_name' => $this->institutionName,
            'inviter_name' => $this->inviterName,
            'invitation_url' => $this->invitationUri,
        ];
    }
}
