<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Utils;
use App\Mail\HelloMail;
use App\Models\EmailVerify;
use Illuminate\Support\Facades\Mail;

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
        // $role   =   auth()->user()->role;
        $user   =   new User();

        // if ($role == 'admin') {
        //     $users  =   $user->getAllUsers();
        // } elseif ($role == 'manager') {
        //     $users  =   $user->getManangedUsers();
        // }

        // Grant all view for now
        $users  =   $user->getAllUsers();
        
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
        $xuser  =   auth()->user();

        // Validate input
        $this->validate($request, [
            'role'          =>  'required',
            'username'      =>  'required|max:20|unique:users',
            'first_name'    =>  'required|max:50',
            'last_name'     =>  'required|max:50',
            'email'         =>  'required|max:50|unique:users'
        ]);

        // Perform some cleanup on the data
        $name   =   ucwords($request->first_name) . ' ' . ucwords($request->last_name);
        $username   =   $this->utils->sanitize(Str::lower($request->username));

        // Save user data
        $user   =   new User();

        $user->role                 =   $request->role;
        $user->status               =   'I';
        $user->first_name           =   ucwords($request->first_name);
        $user->last_name            =   ucwords($request->last_name);
        $user->username             =   $username;
        $user->group_id             =   $request->group;
        $user->slug                 =   $username;
        $user->email                =   Str::lower($request->email);
        $user->contact_no           =   $request->contact_no;
        $user->password             =   Hash::make('password');
        $user->created_by           =   $xuser->id;
        $user->updated_by           =   $xuser->id;
        $user->save();

        // Create token entry for verification
        $token  =   EmailVerify::create([
            'user_id'   =>  $user->id,
            'token'     =>  Str::random(32)
        ]);

        // Send a hello to the user
        Mail::to($user->email)->send(new HelloMail($user));

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
        // Fetch user data
        $user   =   User::find($id);

        // Validate fetched data
        if ($user == null) {
            // If user not exists in users table
            // Check from deleted table
            $model      =   new User();
            $deleted    =   $model->isDeleted($id);

            if ($deleted != null) {
                // If found in deleted table
                return redirect('/users')->withErrors([
                    'warning'   =>  "User <b>" . $deleted->first_name . ' ' . $deleted->last_name . "</b>'s account has already been deleted."
                ]);
            } else {
                // If not found in deleted table
                return redirect('/users')->withErrors([
                    'error'   =>  "User <b>" . $deleted->first_name . ' ' . $deleted->last_name . "</b>'s account seems to be missing or invalid."
                ]);
            }
        } else {
            // Delete user data
            $delete     =   new User();
            $destroy    =   $delete->deleteUser($user);

            return redirect('/users')->with([
                'success'   =>  "User <b>" . $user->first_name . ' ' . $user->last_name . "</b>'s account was successfully deleted."
            ]);
        }
    }

    /**
     * Inactivate user account
     * 
     * @param   int $id
     * @return  Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        //
    }
}
