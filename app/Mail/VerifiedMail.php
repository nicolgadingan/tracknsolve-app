<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->mail =   $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your account is now verified!')
                    ->from('hello@tracknsolve.com', 'Hello from Tracknsolve')
                    ->view('mails.verified');
    }
}
