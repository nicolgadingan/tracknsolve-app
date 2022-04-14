<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Utils;

class UsersController extends Controller
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
        $this->utils    =   new Utils();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $role   =   auth()->user()->role;
        $user   =   new User();

        if ($role == 'admin') {
            $users  =   $user->getAllUsers();
        } elseif ($role == 'manager') {
            $users  =   $user->getManangedUsers();
        }
        
        return view('users.index')->with([
            'users'     =>  $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->role != 'admin') {
            abort('401');
        }

        $groups =   Group::where('status', 'A')
                        ->orderBy('name')
                        ->get();

        return view('users.create')->with([
            'groups'    =>  $groups,
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
            'role'          =>  'required',
            'username'      =>  'required|max:20|unique:users',
            'first_name'    =>  'required|max:50',
            'last_name'     =>  'required|max:50',
            'email'         =>  'required|max:50|unique:users'
        ]);

        $name   =   ucwords($request->first_name) . ' ' . ucwords($request->last_name);
        $username   =   $this->utils->sanitize($request->username);

        $user   =   new User();
        $user->role                 =   $request->role;
        $user->status               =   'I';
        $user->first_name           =   ucwords($request->first_name);
        $user->last_name            =   ucwords($request->last_name);
        $user->username             =   Str::lower($username);
        $user->group_id             =   $request->group;
        $user->slug                 =   Str::slug($username);
        $user->email                =   Str::lower($request->email);
        $user->contact_no           =   $request->contact_no;
        $user->email_verified_at    =   null;
        $user->password             =   Hash::make('password');
        $user->remember_token       =   null;
        $user->created_by           =   auth()->user()->id;
        $user->updated_by           =   auth()->user()->id;
        $user->save();

        return redirect('/users')->with([
            'success'   =>  "You have successfully created <b>" . $name . "</b>'s account.",
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
