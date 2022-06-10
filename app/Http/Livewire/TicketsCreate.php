<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\User;
use App\Models\Ticket;
use App\Jobs\Mailman;
use App\Mail\TicketCreated;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Utils;

class TicketsCreate extends Component
{
    // Protected
    protected $uid;

    // Declaration
    public $ticket_id;         // ticket key
    public $reporter;          // ticket creator
    public $priority;          // 
    public $status;            // 
    public $groups;            // group list
    public $group_id;          // ticket assignment group
    public $users;             // user list
    public $assignee;          // ticket is assigned to
    public $title;
    public $description;
    public $response;

    protected $rules    =   [
        'ticket_id'     =>  'required|exists:reserves,key_id',
        'reporter'      =>  'required|exists:users,id',
        'priority'      =>  'required',
        'group_id'      =>  'required|exists:groups,id',
        'status'        =>  'required',
        'assignee'      =>  'nullable|exists:users,id',
        'title'         =>  'required|min:5|max:100',
        'description'   =>  'required|min:20|max:4000',
    ];

    /**
     * Mount variables
     * 
     */
    public function mount()
    {
        $this->uid          =   auth()->user()->id;

        $this->status       =   'new';
        $this->users        =   [];
        $this->priority     =   '';
        $this->reporter     =   $this->uid;
        $this->assignee     =   '';
    }

    /**
     * When a required field is updated
     * 
     */
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    /**
     * When $group is updated
     * 
     */
    public function updatedGroupId()
    {
        $this->users    =   User::where('group_id', $this->group_id)
                                ->get();
    }

    /**
     * Fetch all active groups
     * 
     */
    protected function getGroups()
    {
        $this->groups   =   Group::where('status', 'A')
                                ->get();
    }

    /**
     * Submit form
     * 
     */
    public function submitTicket()
    {
        $tdata      =   $this->validate();

        $ticket     =   new Ticket();
        $retCode    =   $ticket->createTicket($tdata);

        if ($retCode == 1) {
            // Get recipients
            $recipients =   User::where('group_id', $tdata['group_id'])
                                ->select(
                                    'id as uid',
                                    'email',
                                )
                                ->get()
                                ->toArray();

            // Queue email to be sent
            foreach ($recipients as $rcpt) {
                if ($rcpt['uid'] == $tdata['assignee']) {
                    $subject    =   'Ticket ' . $tdata['ticket_id'] . ' has been assigned to you.';
                } else {
                    $subject    =   'Ticket ' . $tdata['ticket_id'] . ' has been assigned to your group.';
                }
                
                $email['to']        =   $rcpt['email'];
                $email['content']   =   new TicketCreated((object) [
                                            'subject'   =>  $subject,
                                            'user'      =>  $rcpt,
                                            'ticket'    =>  $tdata,
                                            'baseURL'   =>  URL::to('')
                                        ]);

                dispatch(new Mailman($email));
            }

            return redirect('/tickets')->with([
                'success'   =>  'You have successfully created and assigned your ticket.'
            ]);

        } else {
            $utils  =   new Utils;
            return $this->addError(
                'message',
                $utils->err->unexpected
            );

        }
    }

    public function render()
    {
        $this->getGroups();
        return view('livewire.tickets-create');
    }
}
