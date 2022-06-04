<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Mail\TicketResolved;
use App\Jobs\Mailman;

class TicketsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user   =   auth()->user();
        // $ticket     =   new Ticket();

        return view('tickets.index')->with([
            // 'tickets'   =>  $tickets
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
        //
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
                                'created_at as ticket_created',
                                DB::raw("(select created_at
                                            from ticket_hists
                                           where ticket_id = tickets.id
                                             and status = 'resolved'
                                           order by id desc
                                           limit 1) as resolved_at"),
                                DB::raw("(select created_at
                                            from ticket_hists
                                           where ticket_id = tickets.id
                                             and status = 'closed'
                                           order by id desc
                                           limit 1) as closed_at")
                            )
                            ->first();

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
            'group_id'      =>  'required|exists:groups,id',
            'status'        =>  'required',
            'assignee'      =>  'nullable|exists:users,id',
            'title'         =>  'required|min:5|max:100',
            'description'   =>  'required|min:20|max:4000',
            'caller'        =>  'nullable'
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
     * Resolve ticket
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resolve(Request $request, $id)
    {
        $utils      =   new Utils;
        $ticket     =   new Ticket();
        $access     =   auth()->user();

        $utils->loggr('TICKETS.RESOLVE', 1);
        
        $utils->loggr('Action > Fetching data.', 0);
        $tdata      =   Ticket::find($id);

        $utils->loggr('Result > Completed.', 0);
        $utils->loggr(json_encode(['data' => $tdata]), 0);

        $tdata['tkey']      =   $id;
        $tdata['status']    =   'resolved';
        $tdata['assignee']  =   $access->id;

        $tdata              =   Arr::except($tdata, ['id']);
        
        $utils->loggr('Action > Resolving ticket ' . $id . '.', 0);
        $retcode    =   $ticket->resolveTicket($tdata);

        if ($retcode == 1) {
            $utils->loggr('Result > Success.', 0);

            // Send email
            $reporter           =   User::where('id', $tdata['reporter'])
                                        ->select(
                                            'email',
                                            'id as uid'
                                        )
                                        ->first()
                                        ->toArray();

            $email['to']        =   $reporter['email'];
            $email['content']   =   new TicketResolved((object) [
                                        'subject'   =>  'Your ticket ' . $tdata['tkey'] . ' has been resolved.',
                                        'ticket'    =>  $tdata
                                    ]);

            dispatch(new Mailman($email));
            
            return redirect('/tickets/' . $id . '/edit')->with([
                'success'       =>  'Ticket <b>' . $id . '</b> has been resolved.'
            ]);

        } else if ($retcode == 0) {
            $utils->loggr('Result > Failed. ' . $utils->err->calltheguy, 0);
            return back()->withErrors([
                'message'       =>  'Failed to resolve ticket. ' . $utils->err->calltheguy
            ]);

        } else {
            return back()->withErrors([
                'message'   =>  $utils->err->unexpected
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

    /**
     * Assign ticket to self
     * 
     * @param   String  $id
     * @return  \Illuminate\Http\Response
     */
    public function get($id)
    {
        $this->utils->loggr('TICKETS.ASSIGNTOME', 1);
        
        $access         =   auth()->user();
        $ticket         =   new Ticket();

        $this->utils->loggr('Action > Getting ticket ' . $id . ' data.', 0);

        $tdata          =   Ticket::where('id', $id)
                                ->first()
                                ->toArray();
        $tdata['id']    =   $id;

        $this->utils->loggr('Result > ' . json_encode($tdata), 0);

        $this->utils->loggr('Action > Check assignment group.', 0);

        if($tdata['group_id'] !=  $access->group_id) {
            $this->utils->loggr('Result > Assigned to different group. Terminating process.', 0);
            return back()->withErrors([
                'message'   =>  'Assigning ticket ticket to yourself failed as it belongs to different group. Please check and try again.'
            ]);

        }

        $this->utils->loggr("Result > Assigned to user's group.", 0);
        $this->utils->loggr("Action > Assigning to " . $access->id . ".", 0);

        $rcode  =   $ticket->assignToMe($tdata);

        if ($rcode  ==  1) {
            return redirect('/tickets/' . $id . '/edit')->with([
                'success'   =>  'Ticket <b>' . $id . '</b> was successfully assigned to you.'
            ]);

        } elseif ($rcode    ==  0) {
            return back()->withErrors([
                'message'   =>  'Failed to assign the ticket to you for uknown cause. Kindly contact your administrator for further checking.'
            ]);

        } else {
            return back()->withErrors([
                'message'   =>  $this->utils->err->unexpected
            ]);

        }
        
    }

}
