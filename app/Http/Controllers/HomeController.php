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

        return view('dashboard')->with([
            'myTickets'     =>  $myTickets,
            'gpUnassigned'  =>  $gpUnassigned,
            'tkSummary'     =>  $tkSummary
        ]);
    }
}
