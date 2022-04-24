<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Group;
use App\Models\User;

class TicketsProcessor extends Component
{
    public $tkey;           // Ticket ID
    public $ticket;         // Ticket Object
    public $groups;         // All Groups Object
    public $group;          // Selected Group
    public $user;           // User Object
    public $assignee;       // Selected Assignee
    public $users;          // All Users Object
    public $priority;
    public $status;
    public $title;
    public $description;

    protected $state;

    protected $rules    =   [
        'priority'          =>  'required',
        'status'            =>  'required',
        'group'             =>  'required|exists:groups,id',
        'title'             =>  'required|min:5',
        'description'       =>  'required|min:20',
        'assignee'          =>  'nullable|exists:users,id',
        'reporter'          =>  'required'
    ];

    public function mount()
    {
        $this->users        =   [];
        $this->group        =   '';
        $this->assignee     =   '';
        $this->priority     =   '';
        $this->status       =   '';
        $this->title        =   '';
        $this->description  =   '';
        $this->reporter     =   '';
    }

    public function submitData()
    {
        $request    =   $this->validate();

        $tdata  =   [
            'id'            =>  $this->tkey,
            'status'        =>  $request['status'],
            'priority'      =>  $request['priority'],
            'title'         =>  $request['title'],
            'description'   =>  $request['description'],
            'group_id'      =>  $request['group'],
            'assignee'      =>  ($request['assignee'] == '') ? null : $request['assignee'],
            'reporter'      =>  $request['reporter'],
            'created_at'    =>  \Carbon\Carbon::now()
        ];

        $ticket =   new Ticket();
        $status =   $ticket->createTicket($tdata);

        if ($status['isCreated'] != true) {
            return back()->withErrors([
                'message'   =>  'We have encountered an error while saving your data.'
            ]);
        }

        if ($status['isLogged'] != true) {
            return back()->withErrors([
                'message'   =>  'Ticket data has been saved but failed in logging history.'
            ]);
        }
        
        return back()->with([
            'success'   =>  'You have successfully submitted your ticket.'
        ]);
    }

    public function fetchTicket()
    {
        $this->ticket   =   Ticket::find($this->tkey);

        if ($this->ticket == null) {
            $this->state        =   'create';
            $this->user         =   auth()->user();
            $this->status       =   'new';
            $this->reporter     =   $this->user->id;
        } else {
            $this->state        =   'update';
            $this->user         =   $this->ticket->user;
            $this->group        =   $this->ticket->group_id;
            $this->reporter     =   $this->ticket->reporter;
            $this->assignee     =   $this->ticket->assignee;
            $this->priority     =   $this->ticket->priority;
            $this->status       =   $this->ticket->status;
            $this->title        =   $this->ticket->title;
            $this->description  =   $this->ticket->description;
            $this->reporter     =   $this->ticket->reporter;
        }
    }

    public function updatedGroup()
    {
        if ($this->group == '999') {
            $this->users    =   [];
            $this->assignee =   '';

            $this->addError('group', 'The group field is required.');
        } else {
            $this->users    =   User::where('group_id', $this->group)
                                ->orderBy('first_name')
                                ->get();

            $this->resetValidation('group');
        }
    }

    public function updatedPriority()
    {
        if ($this->priority == '') {
            $this->addError('priority', 'The priority field is required.');
        } else {
            $this->resetValidation('priority');
        }
        
    }

    public function fetchGroups()
    {
        $this->groups   =   Group::where('status', 'A')
                                ->orderBy('name')
                                ->get();
    }

    public function render()
    {
        $this->fetchGroups();
        $this->fetchTicket();
        
        return view('livewire.tickets-processor');
    }
}
