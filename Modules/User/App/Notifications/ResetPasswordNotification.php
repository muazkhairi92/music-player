<?php

namespace Modules\User\App\Notifications;

use App\Services\EmailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        $url = URL::temporarySignedRoute('redirect', now()->addHour(), ['email' => $this->data['user']->email, 'token' => $this->data['token'], 'front_url' => $this->data['user']->website->ticket_front_url]);
 

        return (new MailMessage())
                    ->subject('User requested for password reset')
                    ->line("Dear". $this->data['user']->name)
                    ->line("You have requested for a password reset. Please click buton below to change your password:")
                    ->action('reset password', url($url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
