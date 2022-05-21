<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Utils;
use Livewire\Component;
use App\Models\Group;
use App\Models\User;

class GroupsEdit extends Component
{
    public $gid;
    public $isEditable;
    public $group;
    public $hasUpdate;

    // Inputs
    public $group_name;
    public $description;
    public $manager_id;

    // Validation
    protected $rules        =   [
        'group_name'        =>  'required',
        'description'       =>  'required|max:255',
        'manager_id'        =>  'required|exists:users,id'
    ];

    public function mount()
    {
        $this->hasUpdate    =   false;
        $this->isEditable   =   (auth()->user()->role == 'admin') ? true : false;

        $this->group        =   Group::find($this->gid);

        $this->group_name   =   $this->group->name;
        $this->description  =   $this->group->description;
        $this->manager_id   =   $this->group->owner;
    }

    public function updated($input)
    {
        $this->hasUpdate    =   true;
        $this->validateOnly($input);
    }

    /**
     * When $group_name is updated
     * 
     */
    public function updatedGroupName()
    {
        if ($this->isGroupExist()) {
            $this->addError('group_name',  'The group name ' . $this->group_name . ' already taken.');

        }
    }

    /**
     * Check group if exists
     * 
     */
    public function isGroupExist()
    {
        $exists         =   false;

        $groupExists    =   Group::where('name', '=', $this->group_name)
                                ->where('id', '!=', $this->group->id)
                                ->get();

        if (count($groupExists) > 0) {
            $exists     =   true;
        }

        return $exists;
    }

    /**
     * Save group detail changes
     * 
     */
    public function saveUpdate()
    {
        $utils              =   new Utils;
        $utils->loggr('GROUPS.UPDATE', 1);
        $utils->loggr('Action > Validating inputs.', 0);

        $validated          =   $this->validate();

        if ($this->isGroupExist()) {
            $this->addError('group_name',  'The group name ' . $this->group_name . ' already taken.');

        } else {
            $validated['group_id']      =   $this->gid;
            $validated['group_name']    =   $this->group_name;
            
        }

        $utils->loggr('Result > Validating completed.', 0);
        $utils->loggr(json_encode([
            'data'  =>  $validated
        ]), 0);

        $utils->loggr('Action > Updating group.', 0);
        $group              =   new Group();
        $isUpdated          =   $group->updGroup($validated);

        if ($isUpdated == 1) {
            $utils->loggr('Result > Success.', 0);
            return redirect('/groups/' . $this->group->id)->with([
                'success'   =>  'Group <b>' . $this->group_name . '</b> has been updated successfully.'
            ]);

        } else if ($isUpdated == 0) {
            $utils->loggr('Result > Failed.', 0);
            $this->addError('message', 'Failed to save your changes. ' . $utils->err->calltheguy);

        } else {
            $utils->loggr('Result > Unexpected Error.', 0);
            $this->addError('message', $utils->err->unexpected);

        }

    }

    public function render()
    {
        $user   =   new User();

        return view('livewire.groups-edit', [
            'members'       =>  $this->group->members,
            'managers'      =>  $user->canManage()
        ]);
    }
}
