<?php

namespace App\Http\Livewire;

use App\Models\Config;
use Livewire\Component;
use App\Models\Group;

class GroupsIndex extends Component
{
    public $searchgroup;
    public $configs;
    public $canCreate;
    public $isExhausted;
    public $maxGroup;

    public function mount()
    {
        $this->searchgroup  =   '';
        $this->canCreate    =   true;

        $this->checkSecurity();
    }

    protected function checkSecurity()
    {
        $config                 =   new Config();
        
        $this->maxGroup         =   (int) $config->chkConfig('LIMIT#GROUP');
        $grpCount               =   Group::all();

        if ($this->maxGroup <= count($grpCount)) {
            $this->canCreate    =   false;
            $this->isExhausted  =   true;
        }
    }

    public function render()
    {
        return view('livewire.groups-index', [
            'data'      =>  Group::when($this->searchgroup, function($query, $key) {
                                        $query->where('name', 'like', '%' . $key . '%');
                                    })
                                ->orderBy('name')
                                ->paginate(10),
            'statuses'  =>  Group::selectRaw('distinct status')
                                ->get()
                                
        ]);
    }
}
