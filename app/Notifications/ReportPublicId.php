<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportPublicId extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data =   $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Public Access Token is Inacessible!')
                    ->from(config('mail.from.address'),
                            config('mail.from.name'))
                    ->line('<div style="color: red;">')
                    ->greeting('Hello!')
                    ->line('<b>Public Access Token is inaccessible as of the moment.</b>')

                    ->line('<b>Impacts:</b>')
                    ->line('- Attachment downloads')
                    ->line('- Access to Images')

                    ->line('<b>Possible Cause:</b>')
                    ->line('Storage directory link was deleted/removed/renamed accidentally.')

                    ->action('Check Alert', env('APP_URL', ''))
                    ->line('</div>')
                    
                    ->line('Please do not reply to this email as it is not being monitored and used for sending only.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
