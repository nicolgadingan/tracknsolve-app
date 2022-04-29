<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Utils;

class TicketsEdit extends Component
{
    // Declaration
    public $tkey;              // ticket key
    public $caller;            // ticket creator
    public $reporter;          // 
    public $priority;          // 
    public $status;            // 
    public $groups;            // group list
    public $group;             // ticket assignment group
    public $users;             // user list
    public $assignee;          // ticket is assigned to
    public $created_at;
    public $title;
    public $description;
    public $response;
    public $ticket;

    protected $rules    =   [
        'tkey'          =>  'required|exists:reserves,key_id',
        // 'caller'        =>  'required|exists:users,id',
        'priority'      =>  'required',                         // required
        'group'         =>  'required|exists:groups,id',        // required
        'status'        =>  'required',                         // required
        'assignee'      =>  'nullable|exists:users,id',         // required
        'title'         =>  'required|min:5|max:100',           // required
        'description'   =>  'required|min:20|max:4000',         // required
    ];

    /**
     * Mount variables
     * 
     */
    public function mount()
    {
        $this->getData();
    }

    /**
     * Validate every update
     * 
     */
    public function updated($updates)
    {
        $this->validateOnly($updates);
    }

    /**
     * When $group is updated
     * 
     */
    public function updatedGroup()
    {
        $this->users    =   $this->getUsers();
    }

    /**
     * Get all users under $group
     */
    public function getUsers()
    {
        return User::where('group_id', $this->group)->get();
    }

    /**
     * Update ticket
     * 
     */
    public function updateTicket()
    {
        $tdata  =   $this->validate();
        $utils  =   new Utils;

        $ticket =   new Ticket();
        $result =   $ticket->updateTicket($tdata);

        if ($result ==  true) {
            return view('/tickets')->with([
                'success'   =>  "Ticket <a hred='/tickets/" . $this->tkey . "/edit'>" . $this->tkey . "</a> was successfully updated."
            ]);

        } else {
            $utils->loggr('TICKETS-UPDATE', 1);
            $utils->loggr($tdata, 0);
            $this->addError('message', 'We have encountered and unexpected error upon saving you changes. Kindly report this with your administrator for further checking.');   

        }
        
    }

    /**
     * Pull ticket data
     * 
     */
    public function getData()
    {
        $ticket =   Ticket::where('tickets.id', $this->tkey)
                            ->select(
                                'tickets.id as tkey',
                                'status',
                                'priority',
                                'title',
                                'description',
                                'group_id',
                                'assignee',
                                'reporter',
                                'created_at'
                            )->first();

        $this->ticket   =   $ticket;
        $this->reporter =   User::find($ticket->reporter);

        $this->tkey         =   $ticket->tkey;
        $this->status       =   $ticket->status;
        $this->priority     =   $ticket->priority;
        $this->title        =   $ticket->title;
        $this->description  =   $ticket->description;
        $this->group        =   $ticket->group_id;
        $this->assignee     =   $ticket->assignee;
        $this->created_at   =   $ticket->created_at;
        
        $this->groups       =   Group::where('status', 'A')->get();
        $this->users        =   $this->getUsers();
    }

    public function render()
    {
        return view('livewire.tickets-edit');
    }
}
