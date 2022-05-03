<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;

class UsersEdit extends Component
{
    public $user;
    public $group_id;
    public $role;
    public $username;
    public $email;
    public $contact_no;
    public $first_name;
    public $last_name;

    public $isFresh;

    protected $rules    =   [
        'group_id'      =>  'required',
        'role'          =>  'required',
        'contact_no'    =>  'nullable',
        'first_name'    =>  'required|min:2|string|max:100',
        'last_name'     =>  'required|min:2|string|max:100',
        'email'         =>  'required|min:5|max:100'
    ];

    public function mount()
    {
        $this->group_id     =   $this->user->group_id;
        $this->role         =   $this->user->role;
        $this->username     =   $this->user->username;
        $this->email        =   $this->user->email;
        $this->contact_no   =   $this->user->contact_no;
        $this->first_name   =   $this->user->first_name;
        $this->last_name    =   $this->user->last_name;
        
        $this->isFresh      =   true;
    }

    public function updated($userData)
    {
        $this->isFresh      =   false;
        $this->validateOnly($userData);
    }

    public function updateUser()
    {
        
    }

    public function render()
    {
        return view('livewire.users-edit', [
            'groups'    =>  Group::all(),
        ]);
    }
}
