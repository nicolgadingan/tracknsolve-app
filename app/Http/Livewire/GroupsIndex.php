<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

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
            'data'    =>  DB::table('groups as g')
                                ->leftJoin('users as u', 'u.id', '=', 'g.owner')
                                ->when($this->searchgroup, function($query, $searchgroup) {
                                    $query->where('g.name', 'like', '%' . $searchgroup . '%');
                                })
                                ->select('g.id',
                                        'g.name',
                                        'g.status',
                                        'g.slug',
                                        'g.created_at',
                                        'u.first_name',
                                        'u.last_name')
                                ->orderBy('g.name')
                                ->paginate(10)
        ]);
    }
}
