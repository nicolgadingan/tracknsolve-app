<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\User;

class TicketsGetUsers extends Component
{
    public $query;

    /**
     * Mount variable
     * 
     */
    public function mount()
    {
        $this->query    =   '';
    }

    public function render()
    {
        return view('livewire.tickets-get-users', [
            'groups'    =>  Group::where('status', 'A')
                                ->orderBy('name')
                                ->get(),
            'users'     =>  User::where('group_id', '=', $this->query)
                                ->get()
        ]);
    }
}
