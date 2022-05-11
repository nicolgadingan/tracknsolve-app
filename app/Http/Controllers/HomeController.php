<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $me             =   auth()->user();
        $ticket         =   new Ticket;

        $myTickets      =   $ticket->openAssignedToUser($me->id);
        $gpUnassigned   =   $ticket->groupUnassignedTickets($me->group_id);
        $tkSummary      =   $ticket->ticketSummary();

        // Get Chart Data
        $cData          =   $this->chartBreakdown();

        return view('dashboard')->with([
            'myTickets'     =>  $myTickets,
            'gpUnassigned'  =>  $gpUnassigned,
            'tkSummary'     =>  $tkSummary,
            'chartData'     =>  $cData
        ]);
    }

    /**
     * Tickets Chart Data Generator
     * 
     * @return  Array   $cdata
     */
    protected function chartBreakdown()
    {
        // Initialize variables
        $labels         =   [];
        $counts         =   [];
        $colors         =   [];

        // Fetch needed data
        $tickets    =   DB::table('tickets as t')
                            ->select('t.status', DB::raw('count(*) as tkcount'))
                            ->groupBy('t.status')
                            ->orderByRaw("case when t.status = 'new' then 1
                                                when t.status = 'in-progress' then 2
                                                when t.status = 'on-hold' then 3
                                                when t.status = 'resolved' then 4
                                            end")
                            ->get();

        // Process data
        foreach ($tickets as $ticket) {
            $labels[]   =   ucwords($ticket->status);
            $counts[]   =   $ticket->tkcount;

            switch ($ticket->status) {
                case 'new':
                    $colors[]   =   '#34ace0';
                    break;
                case 'in-progress':
                    $colors[]   =   '#ffda79';
                    break;
                case 'on-hold':
                    $colors[]   =   '#bcc1c7';
                    break;
                case 'resolved':
                    $colors[]   =   '#33d9b2';
                    break;
            }
        }

        // Consolidate data
        $cdata          =   [
            'labels'    =>  $labels,
            'counts'    =>  $counts,
            'colors'    =>  $colors
        ];

        return $cdata;
    }
}
