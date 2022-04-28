<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Group;
use App\Models\User;

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
    public $title;
    public $description;
    public $response;
    public $ticket;

    protected $rules    =   [
        'tkey'          =>  'required|exists:reserves,key_id',
        'caller'        =>  'required|exists:users,id',
        'priority'      =>  'required',
        'group'         =>  'required|exists:groups,id',
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
        $this->ticket       =   Ticket::find($this->tkey);
        $this->group        =   $this->ticket->group_id;
        $this->groups       =   Group::where('status', 'A')->get();
        $this->assignee     =   $this->ticket->assignee;
        $this->priority     =   $this->ticket->priority;
        $this->users        =   $this->getUsers();
        $this->title        =   $this->ticket->title;
        $this->description  =   $this->ticket->description;
        $this->reporter     =   $this->ticket->user;
        // $this->reporter     =   $this->ticket->reporter;
        // $this->status       =   'new';
        // $this->users        =   [];
        // $this->priority     =   '';
        // $this->caller       =   $this->reporter->id;
        // $this->assignee     =   '';
    }

    public function updatedGroup()
    {
        $this->users    =   $this->getUsers();
    }

    public function getUsers()
    {
        return User::where('group_id', $this->group)->get();
    }

    public function render()
    {
        return view('livewire.tickets-edit');
    }
}
