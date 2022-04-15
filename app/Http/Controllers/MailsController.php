<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloMail;

class MailsController extends Controller
{
    public function index()
    {
        $mailData   =   [
            'name'      =>  'Cortney',
            'username'  =>  'csmith',
            'manager'   =>  'John Troubleta',
            'verify'    =>  'https://yortik.app/email/verify/7815696ecbf1c96e6894b779456d330e'
        ];

        Mail::to('csmith@example.com')->send(new HelloMail($mailData));

        // dd("Email sent successfully!");
    }

    /**
     * Registration Email
     * 
     */
    public function hello()
    {
        $mailData   =   [
            'title' =>  'Hello from yortik.com',
            'body'  =>  'Sending you a warm welcome to Yortik!'
        ];

        Mail::to('sudocreateph@gmail.com')->send(new HelloMail($mailData));
    }
}
