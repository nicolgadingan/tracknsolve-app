<?php

namespace App\Jobs;

use App\Mail\VerifiedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Mailman implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData =   $mailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->mailData['email'])->send($this->mailData['content']);
    }
}
