<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Mail\TicketCreated;
use Illuminate\Support\Facades\URL;
use App\Mail\VerifiedMail;
use App\Mail\HelloMail;
use App\Notifications\AssignedTicket;
use App\Models\User;
use App\Models\Ticket;
use App\Notifications\ReportPublicId;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Notification;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * This will serve as test runner.
     * Inside it, call a specific method inside this class.
     */
    public function execute()
    {
        $this->EmailPubIdMissing();
    }

    public function EmailPubIdMissing()
    {   
        // Notification::fake();

        dd("Sent");
    }

    public function TicketCreated()
    {
        $rcpt                   =   User::where('id', 700001)
                                        ->select('users.id as uid')
                                        ->first()
                                        ->toArray();

        $tdata                  =   Ticket::where('id', '=', 'YRTK842538125')
                                        ->first()
                                        ->toArray();

        $tdata['tkey']          =   'YRTK842538125';

        $email['to']        =   'mgadingan@tracknsolve.com';
        // $email['content']   =   new TicketCreated((object) [
        //                             'subject'   =>  'Ticket XXXXXXXXXXXX has been assigned to your group',
        //                             'user'      =>  $rcpt,
        //                             'ticket'    =>  $tdata,
        //                             'baseURL'   =>  URL::to('')
        //                         ]);

        dispatch(new \App\Jobs\Mailman($email));

        dd('done');
    }

    public function Verified()
    {
        // $user               =   User::where('id', 700001)
        //                                 ->first()
        //                                 ->toArray();

        // $email['to']        =   $user['email'];
        // $email['content']   =   new \App\Mail\VerifiedMail((object) [
        //                             'user'      =>  $user,
        //                             'baseURL'   =>  URL::to('')
        //                         ]);

        // dispatch(new \App\Jobs\Mailman($email));
        // dd('done');
    }
}
