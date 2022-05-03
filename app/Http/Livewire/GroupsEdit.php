<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Utils;
use Livewire\Component;
use App\Models\Group;
use App\Models\User;

use Exception;

class GroupsEdit extends Component
{
    public $managers;
    public $manager_id;
    public $group_name;
    public $group_id;

    public $user;
    public $group;

    protected $rules    =   [
                                'group_name'    =>  'required|min:2|max:50',
                                'manager_id'    =>  'required|exists:users,id'
                            ];

    public function mount()
    {
        $this->user         =   new User;
        $this->group        =   [ 'name' => '' ];
        $this->group_name   =   $this->group['name'];
        $this->manager_id   =   '';
    }

    /**
     * Reload the data collection
     * This occurs when a group is selected for viewing
     * 
     * @return  void
     */
    public function reload()
    {
        // Intialize Utilities
        $utils              =   new Utils;
        $utils->loggr('GROUPS.VIEW', 1);

        // Fetch group data
        $this->group        =   Group::where('id', $this->group_id)
                                    ->first()
                                    ->toArray();

        // Fetch managers
        $this->managers     =   User::whereIn('role', array('admin', 'manager'))
                                    ->where('status', 'A')
                                    ->select('id'
                                            ,'first_name'
                                            ,'last_name')
                                    ->orderBy('first_name')
                                    ->get();

        // Assignment
        $this->group_name   =   $this->group['name'];
        $this->manager_id   =   $this->group['owner'];

        // Log fetched data
        $utils->loggr(json_encode([
                'data'  =>  [
                                'groupName' =>  $this->group_name,
                                'groupId'   =>  $this->group_id,
                                'managerId' =>  $this->manager_id
                            ]
            ]), 0);
    }

    /**
     * Validate every input
     * 
     */
    public function updated($inputs)
    {
        $this->validateOnly($inputs);
    }

    public function updateGroup()
    {
        $utils      =   new Utils;
        $group      =   new Group();
        $utils->loggr('GROUPS.UPDATE', 1);

        $validated  =   $this->validate();
        $validated['group_id']  =   $this->group_id;

        $isUpdated  =   $group->updGroup($validated);

        if ($isUpdated) {
            return redirect('/groups')->with([
                'success'   =>  'Group has been successfully updated.'
            ]);
        } else {
            $this->addError(
                'message',  $utils->err->unexpected
            );
        }

    }

    public function render()
    {
        return view('livewire.groups-edit');
    }
}
