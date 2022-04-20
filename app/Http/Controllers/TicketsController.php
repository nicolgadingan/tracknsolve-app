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
        return view('tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reporter   =   User::find(auth()->user()->id);
        $groups     =   Group::all();

        $config     =   new Config();
        $tkSeq      =   $config->getKey();

        return view('tickets.create')->with([
            'reporter'  =>  $reporter,
            'groups'    =>  $groups,
            'tkey'      =>  $tkSeq
        ]);
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
            'attachment'        =>  'nullable|mimes:pdf,docx,xlsx,jpg,png|max:2048',
            'priority'          =>  'required',
            'status'            =>  'required',
            'group'             =>  'required|exists:groups,name',
            'title'             =>  'required|min:5',
            'description'       =>  'required|min:20',
            'assignee'          =>  'nullable|exists:users,username'
        ]);

        $group_id   =   Group::where('name', $request->group)->first()->id;
        $user       =   User::where('username', $request->assignee)->first();

        dd($request);

        if ($user->group_id != $group_id) {
            return back()->withErrors([
                'assignee'   =>  'User does not belong to the selected group.'
            ]);
        }

        return back()->with([
            'success'   =>  'Amazing'
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
        //
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
