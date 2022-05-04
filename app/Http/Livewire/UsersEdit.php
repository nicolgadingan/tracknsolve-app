<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Utils;
use Livewire\Component;
use App\Models\Group;
use App\Models\User;

class UsersEdit extends Component
{
    public      $user;
    public      $user_id;
    public      $group_id;
    public      $role;
    public      $username;
    public      $email;
    public      $contact_no;
    public      $first_name;
    public      $last_name;

    public      $isFresh;

    protected   $rules    =   [
        'group_id'      =>  'required',
        'role'          =>  'required',
        'first_name'    =>  'required|min:2|string|max:100',
        'last_name'     =>  'required|min:2|string|max:100',
        'contact_no'    =>  'nullable|min:7',
    ];

    public function mount()
    {
        $this->user_id      =   $this->user->id;
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
        $utils  =   new Utils;
        $umodel       =   new User();

        $utils->loggr('USERS.UPDATE', 1);
        $utils->loggr('Action > Validating inputs.', 0);

        // Validate inputs
        $vldted =   $this->validate();

        $utils->loggr('Result > ' . json_encode([
                'isValidated'   =>  true,
                'data'          =>  $vldted
            ]), 0);
        
        $utils->loggr('Action > Comparing inputs with old data.', 0);

        // Compare to old values
        if ($this->group_id     ==  $this->user->group_id   &&
            $this->role         ==  $this->user->role       &&
            $this->first_name   ==  $this->user->first_name &&
            $this->last_name    ==  $this->user->last_name  &&
            $this->contact_no   ==  $this->user->contact_no) {

            $utils->loggr('Result > No updates. Throwing error.', 0);
            
            $this->addError('message', $utils->err->nochange);
        }

        $utils->loggr('Result > Found updates.', 0);
        $utils->loggr('Action > Adding user_id to array.', 0);

        // Add the user id to validated data
        $vldted['user_id']  =   $this->user_id;
        $utils->loggr('Result > Added.', 0);

        $utils->loggr('Action > Processing update.', 0);
        $isUpdated  =   $umodel->updateUser($vldted);

        // Valida update processing
        if ($isUpdated) {

            return redirect('/users')->with([
                'success'   =>  'Your update to user <b>' .
                                ucwords($this->first_name . ' ' . $this->last_name) .
                                '</b> has been saved.'
            ]);

        } else {
            
            $this->addError(
                'message',  $utils->err->unexpected
            );

        }
        
    }

    public function render()
    {
        return view('livewire.users-edit', [
            'groups'    =>  Group::all(),
        ]);
    }
}
