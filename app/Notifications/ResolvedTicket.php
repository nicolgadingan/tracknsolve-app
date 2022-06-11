<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResolvedTicket extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

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
        $tkUrl      =   url('/tickets/' . $this->data->ticket->ticket_id . '/edit');
        $subject    =   'Your ticket ' . $this->data->ticket->ticket_id . ' has been resolved.';

        return (new MailMessage)
                    ->subject($subject)

                    ->from(config('mail.from.address'),
                            config('mail.from.name'))

                    ->greeting('Hello ' . $this->data->user->first_name . '!')
                    ->line($subject)
                    ->line('<b>Title:</b> ' . $this->data->ticket->title)
                    ->line('<b>Description:</b> ' . $this->data->ticket->description)

                    ->action('View Ticket', $tkUrl)

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
