<?php

namespace App\Core\Notifications;

use App\Core\Models\Identity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class SetIdentityPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $uri)
    {
        //
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
    public function toMail(Identity $notifiable): MailMessage
    {
        return (new MailMessage())
            ->markdown('mail.notifications.identity.set_password', [
                'firstName' => $notifiable->name->firstName,
                'appName' => config('app.name'),
                'uri' => $this->uri,
            ])
            ->subject('Set your password');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(Identity $notifiable): array
    {
        return [
                //
            ];
    }
}
