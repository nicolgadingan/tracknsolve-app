<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\User;
use App\Models\Ticket;

class TicketsCreate extends Component
{
    // Declaration
    public $tkey;              // ticket key
    public $caller;            // ticket creator
    public $reporter;          // 
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
        'tkey'          =>  'required|exists:reserves,key_id',
        'caller'        =>  'required|exists:users,id',
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
        $this->reporter     =   auth()->user();
        $this->status       =   'new';
        $this->users        =   [];
        $this->priority     =   '';
        $this->caller       =   $this->reporter->id;
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
        $creResult  =   $ticket->createTicket($tdata);

        if ($creResult != true) {
            return redirect('/tickets')->withErrors([
                'Saving ticket data encountered an unexpected error. Please report this to your administrator for checking.'
            ]);

        } else {
            return redirect('/tickets')->with([
                'success'   =>  'You have successfully created and assigned your ticket.'
            ]);
        }
    }

    public function render()
    {
        $this->getGroups();

        return view('livewire.tickets-create');
    }
}
