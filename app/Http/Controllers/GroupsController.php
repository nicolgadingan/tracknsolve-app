<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Controllers\Utils;

class GroupsController extends Controller
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
        // Validate restriction
        if ($this->utils->isUser()) {
            abort('401');
        }

        // Get users that can manage a group
        $user   =   new User();
        $users  =   $user->canManage();

        // Get groups
        $group  =   new Group();
        $groups =   $group->getGroups();

        return view('groups.index')->with([
            'managers'  =>  $users,
            'groups'    =>  $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort('404');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creation of GROUPS can only be done by Admin
        if (!$this->utils->isAdmin()) {
            abort('401');
        }

        // Validate input
        $this->validate($request, [
            'name'      =>  'required|max:20|unique:groups',
            'manager'   =>  'required|numeric'
        ]);

        $groupName      =   $request->name;

        // Insert group
        $group  =   new Group;
        $group->name        =   $request->name;
        $group->status      =   'A';
        $group->owner       =   $request->manager;
        $group->slug        =   Str::slug($request->name);
        $group->created_by  =   auth()->user()->id;
        $group->updated_by  =   auth()->user()->id;
        $group->save();

        return redirect('/groups')->with([
            'success'       =>  "You have successfully created <b>" . $groupName . "</b> group."
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
