<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Utils;
use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Str;

class GroupsCreate extends Component
{
    public $managers;
    public $manager;
    public $descn;
    public $name;

    protected $rules    =   [
        'name'          =>  'required|min:2|unique:groups,name',
        'descn'         =>  'required|max:255',
        'manager'       =>  'required'
    ];

    public function updated($input)
    {
        $this->validateOnly($input);
    }

    public function saveGroup()
    {
        $utils                  =   new Utils;
        $utils->loggr('GROUPS.CREATE', 1);
        
        $utils->loggr('Action > Validating inputs.', 0);
        $validated              =   $this->validate();

        $utils->loggr('Result > Success.', 0);

        $user                   =   auth()->user();
        $group                  =   new Group();
        
        $data['name']           =   $validated['name'];
        $data['description']    =   $validated['descn'];
        $data['owner']          =   $validated['manager'];
        $data['slug']           =   Str::slug($validated['name'], '-');

        $utils->loggr('Data > ' . json_encode($data), 0);
        $utils->loggr('Action > Creating group.', 0);
        $created                =   $group->createGroup($data);

        if ($created == 1) {
            $utils->loggr('Result > Success.', 0);

            return redirect('/groups')->with([
                'success'   =>  'Group <b>' .  $data['name'] . '</b> was successfully created.'
            ]);

        } else if ($created == 0) {
            $utils->loggr('Result > Failed. Check global logs for more details.', 0);
            $this->addError('message', 'Creating your group failed. ' . $utils->err->calltheguy);

        } else {
            $utils->loggr('Result > ' . $utils->err->unexpected, 0);
            $this->addError('message', $utils->err->unexpected);

        }

    }

    public function render()
    {
        return view('livewire.groups-create');
    }
}
