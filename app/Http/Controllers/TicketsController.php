<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        // $user   =   auth()->user();
        $ticket     =   new Ticket();
        $tickets    =   $ticket->allTickets();

        // if ($user->role == 'user' ||
        //     $user->role == 'manager') {
            
        //     $tickets    =   $ticket->groupsOpenTickets($user->group_id);

        // } else {

        //     $tickets    =   $ticket->allOpenTickets();

        // }

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
        // Check reserved key
        $reserved   =   DB::table('reserves')
                            ->where('category', 'TICKET_KEY')
                            ->where('key_id', $id)
                            ->first();

        // Validate if ticket is reserved
        if ($reserved == null) {
            abort('404');
        }

        // Fetch prerequisite data

        // Validate if ticket is for New or not
        if ($reserved->status == 'N') {

            $action =   'create';

        } else {

            $action =   'edit';
        }

        return view( 'tickets.' . $action )->with([
            'tkey'      =>  $id,
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
        // Check reserved key
        $reserved   =   DB::table('reserves')
                            ->where('category', 'TICKET_KEY')
                            ->where('key_id', $id)
                            ->first();

        // Validate if ticket is reserved
        if ($reserved == null ||
                $reserved->status == 'N') {
            abort('404');
        }

        $ticket     =  Ticket::where('tickets.id', $id)
                            ->select(
                                'tickets.id as tkey',
                                'status',
                                'priority',
                                'title',
                                'description',
                                'group_id',
                                'assignee',
                                'reporter',
                                'created_at as ticket_created'
                            )->first();

        $reporter   =   User::find($ticket->reporter);

        return view( 'tickets.edit' )->with([
            'tkey'      =>  $id,
            'ticket'    =>  $ticket,
            'reporter'  =>  $reporter
        ]);
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
        $utils  =   new Utils;

        // Validate
        $this->validate($request, [
            'priority'      =>  'required',
            'group'         =>  'required|exists:groups,id',
            'status'        =>  'required',
            'assignee'      =>  'nullable|exists:users,id',
            'title'         =>  'required|min:5|max:100',
            'description'   =>  'required|min:20|max:4000',
        ]);

        // Validate changes
        $saved      =   Ticket::find($id);

        if ($saved->priority    ==  $request->priority  &&
            $saved->group_id    ==  $request->group     &&
            $saved->status      ==  $request->status    &&
            $saved->assignee    ==  $request->assignee  &&
            $saved->title       ==  $request->title     &&
            $saved->description ==  $request->description) {

                return back()->withErrors([
                    'message'    =>  'You do not seem to have changes on this ticket to be saved.'
                ]);

        }


        $revised    =   [];

        // Set to in-progress if it is assigned
        if ($request->status == 'new' &&
                $request->assignee != null) {

            $revised            =   $request->toArray();
            $revised['status']  =   'in-progress';

        } else if ($request->status != 'new' &&
            $request->assignee == null) {
            
            return back()->withErrors([
                'status'    =>  'Updating ticket status is not possible if it is not assigned.'
            ]);

        }

        $ticket     =   new Ticket;
        $isUpdated  =   false;
        $ticketData =   [];

        // Process changes
        if (count($revised) > 0) {
            $ticketData =   $revised;
            
        } else {
            $ticketData =   $request->toArray();
        }

        $isUpdated  =   $ticket->updateTicket($ticketData);        

        // Check if update is successful
        if ($isUpdated) {
            return redirect('/tickets/' . $id . '/edit')->with([
                'success'   =>  'Your changes have been saved successfully.'
            ]);
        } else {
            $utils->loggr('TICKETS-UPDATE', 1);
            $utils->loggr($ticketData, 0);

            return back()->withErrors([
                'message'    =>  $utils->err->unexpected
            ]);
        }

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
