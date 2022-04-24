<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketsGetUsers extends Component
{
    public $assignment  =   '';
    public $group       =   '';
    public $assignee    =   '';
    public $state       =   '';

    protected $rules = [
        'group'     =>  'required|exists:groups,name',
        'assignee'  =>  'nullable|exists:users,username'
    ];

    /**
     * Mount variable
     * 
     */
    public function mount()
    {
        $this->group    =   '';
        $this->assignee =   '';
    }

    public function updated($names)
    {
        $this->validateOnly($names);
    }

    public function render()
    {
        return view('livewire.tickets-get-users', [
            'groups'    =>  Group::where('status', 'A')
                                ->orderBy('name')
                                ->get(),
            'users'     =>  DB::table('groups')
                                ->leftJoin('users', 'users.group_id', '=', 'groups.id')
                                ->where('groups.name', $this->group)
                                ->select('users.id'
                                        ,DB::raw("concat(first_name, ' ', last_name) as fullname")
                                        ,'users.username')
                                ->get()
        ]);
    }
}
