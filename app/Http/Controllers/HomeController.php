<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Database\Seeders\Configs;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $utils;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->utils    =   new Utils;
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
        $config         =   new Config();

        $myTickets      =   $ticket->openAssignedToUser($me->id);
        $gpUnassigned   =   $ticket->groupUnassignedTickets($me->group_id);
        $tkSummary      =   $ticket->ticketSummary();

        // Get Chart Data
        $cData          =   $this->chartBreakdown();

        $configs        =   $config->allConfig();
        $overDue        =   $this->utils->parseConfig($configs, 'OVERDUE_DAYS');

        return view('dashboard')->with([
            'myTickets'     =>  $myTickets,
            'gpUnassigned'  =>  $gpUnassigned,
            'tkSummary'     =>  $tkSummary,
            'chartData'     =>  $cData,
            'dueDate'       =>  \Carbon\Carbon::now()->subDays($overDue)
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
                                                else 9
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
                    $colors[]   =   '#706fd3';
                    break;
                case 'resolved':
                    $colors[]   =   '#33d9b2';
                    break;
                default:
                    $colors[]   =   '#84817a';
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
