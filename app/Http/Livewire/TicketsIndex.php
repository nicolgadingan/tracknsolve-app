<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;

class TicketsIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme  =   'bootstrap';

    public $search;
    public $filter;
    public $dueDays;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

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
                                ->when($this->filter, function($query, $filter) {
                                    $query->where('t.status', $filter);
                                })
                                ->select(
                                    't.id as tkey',
                                    't.status',
                                    'priority',
                                    'title',
                                    'g.name as group_name',
                                    'r.id as reporter_id',
                                    'r.first_name as reporter_fn',
                                    'r.last_name as reporter_ln',
                                    'a.id as assignee_id',
                                    'a.first_name as assignee_fn',
                                    'a.last_name as assignee_ln',
                                    't.created_at as created'
                                )
                                ->orderBy('t.created_at', 'desc')
                                ->paginate(10),
            'statuses'  =>  DB::table('tickets')
                                ->select(DB::raw('distinct status'))
                                ->orderBy(
                                    DB::raw(
                                        "case when status = 'new' then 1 " .
                                        "when status = 'in-progress' then 2 " .
                                        "when status = 'on-hold' then 3 " .
                                        "when status = 'resolved' then 4 " .
                                        "else 9 " .
                                        "end"
                                    )
                                )
                                ->get()
        ]);
    }
}
