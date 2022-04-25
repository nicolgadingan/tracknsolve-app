<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Ticket;

class TicketsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   =   auth()->user();
        $ticket =   new Ticket();

        if ($user->role == 'user' ||
            $user->role == 'manager') {
            
            $tickets    =   $ticket->groupsOpenTickets($user->group_id);

        } else {

            $tickets    =   $ticket->allOpenTickets();

        }

        return view('tickets.index')->with([
            'tickets'   =>  $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Generate key
        $config     =   new Config();
        $tkSeq      =   $config->getKey();

        return redirect('/tickets/' . $tkSeq);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'priority'          =>  'required',
            'status'            =>  'required',
            'group'             =>  'required|exists:groups,name',
            'title'             =>  'required|min:5',
            'description'       =>  'required|min:20',
            'assignee'          =>  'nullable|exists:users,username'
        ]);

        // Get specific data from value
        $group_id   =   Group::where('name', $request->group)->first()->id;

        if ($request->assignee != null) {
            $user       =   User::where('username', $request->assignee)->first();

            // Check if user belongs to the group
            if ($user->group_id != $group_id) {
                return back()->withErrors([
                    'assignee'  =>  'User does not belong to the selected group.',
                    'group'     =>  $request->group
                ]);
            }
        }
        
        $tdata  =   [
            'id'            =>  $request->tkey,
            'status'        =>  $request->status,
            'priority'      =>  $request->priority,
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'group_id'      =>  $group_id,
            'assignee'      =>  $request->assignee,
            'reporter'      =>  $request->reporter,
            'created_at'    =>  \Carbon\Carbon::now()
        ];

        $ticket =   new Ticket();
        $status =   $ticket->createTicket($tdata);

        if ($status->isCreated != true) {
            return back()->withErrors([
                'message'   =>  'We have encountered an error while saving your data.'
            ]);
        }

        if ($status->isLogged != true) {
            return back()->withErrors([
                'message'   =>  'Ticket data has been saved but failed in logging history.'
            ]);
        }
        
        return back()->with([
            'success'   =>  'You have successfully submitted your ticket.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket     =   Ticket::find($id);

        $reporter   =   User::find(auth()->user()->id);
        $groups     =   Group::where('status', 'A')
                            ->orderBy('name')
                            ->get();

        return view('tickets.create')->with([
            'reporter'  =>  $reporter,
            'groups'    =>  $groups,
            'tkey'      =>  $id,
            'ticket'    =>  $ticket
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
