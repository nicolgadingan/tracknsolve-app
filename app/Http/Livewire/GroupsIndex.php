<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\User;

class GroupsIndex extends Component
{
    public $searchgroup;

    public function mount()
    {
        $this->searchgroup  =   '';
    }

    public function render()
    {
        return view('livewire.groups-index', [
            'data'      =>  Group::paginate(10)
        ]);
    }
}
