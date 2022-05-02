<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Str;

class GroupsCreate extends Component
{
    public $managers;
    public $manager;
    public $name;

    protected $rules    =   [
        'manager'   =>  'required',
        'name'      =>  'required|min:2|unique:groups,name'
    ];

    public function updated($input)
    {
        $this->validateOnly($input);
    }

    public function createGroup()
    {
        $user               =   auth()->user();
        $validated          =   $this->validate();

        $group              =   new Group();
        $group->name        =   ucwords($validated['name']);
        $group->status      =   'A';
        $group->owner       =   $validated['manager'];
        $group->slug        =   Str::slug($validated['name'], '-');
        $group->created_by  =   $user->id;
        $group->updated_by  =   $user->id;
        $group->created_at  =   \Carbon\Carbon::now();

        $group->save();

        return redirect('/groups');
    }

    public function render()
    {
        return view('livewire.groups-create');
    }
}
