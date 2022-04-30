<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketsIndex extends Component
{
    public $search;

    public function render()
    {
        return view('livewire.tickets-index', [
            'tickets'   =>  DB::table('tickets as t')
                                ->leftJoin('users as r', 'r.id', '=', 't.reporter')
                                ->leftJoin('users as a', 'a.id', '=', 't.assignee')
                                ->leftJoin('groups as g', 'g.id', '=', 't.group_id')
                                ->when($this->search, function($query, $search) {
                                    $query->where('t.id', 'like', '%' . $search . '%')
                                        ->orWhere('title', 'like', '%' . $search . '%')
                                        ->orWhere('r.first_name', 'like', '%' . $search . '%')
                                        ->orWhere('r.last_name', 'like', '%' . $search . '%')
                                        ->orWhere('a.first_name', 'like', '%' . $search . '%')
                                        ->orWhere('a.last_name', 'like', '%' . $search . '%')
                                        ->orWhere('g.name', 'like', '%' . $search . '%');
                                })
                                ->select(
                                    't.id as tkey',
                                    't.status',
                                    'priority',
                                    'title',
                                    'g.name as group_name',
                                    'r.first_name as reporter_fn',
                                    'r.last_name as reporter_ln',
                                    'a.first_name as assignee_fn',
                                    'a.last_name as assignee_ln',
                                    't.created_at as created'
                                )
                                ->orderBy('t.created_at', 'desc')
                                ->get()
        ]);
    }
}
