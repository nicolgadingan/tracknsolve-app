<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Utils;

class TicketsEdit extends Component
{
    public $group_id;
    public $assignee;
    public $status;

    public function mount()
    {
        $this->assignee     =   $this->assignee;
    }

    public function updatedGroupId()
    {
        $this->assignee =   null;
    }
    
    public function render()
    {
        return view('livewire.tickets-edit', [
            'groups'    =>  Group::where('status', 'A')->get(),
            'users'     =>  User::when($this->group_id, function($query, $group_id) {
                                    $query->where('group_id', $group_id);
                                })
                                ->select(
                                    'id as user_id',
                                    'first_name',
                                    'last_name',
                                )
                                ->get()
        ]);
    }
}
