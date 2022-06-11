<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Utils;
use App\Jobs\Mailman;
use App\Mail\HelloMail;
use App\Models\EmailVerify;
use App\Models\Event;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\URL;

class UsersController extends Controller
{
    protected $utils;
    protected $uid;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->utils    =   new Utils;
        $this->uid      =   (auth()->check() == 1) ? auth()->user()->email : 99999;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('users.index');
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

        // Create event
        $event          =   new Event();
        $event->create( [
            'category'      =>  'user',
            'action'        =>  'create',
            'key_id1'       =>  $user->id,
        ]);

        // Queue email to user
        info('CTRL.US.RGSTR', [
                'user'      =>  $this->uid,
                'action'    =>  'email',
            ]);

        try {
            
            $user->notify(new UserRegistered((Object) [
                'user'  =>  $user,
                'token' =>  $user->emailVerify->token
            ]));

            info('CTRL.US.RGSTR', [
                    'user'      =>  $this->uid,
                    'status'    =>  'sent',
                ]);

        } catch (\Throwable $th) {
            info('CTRL.US.RGSTR', [
                    'user'      =>  $this->uid,
                    'status'    =>  'error',
                ]);
            report($th);

        }

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
        $user   =   User::find($id);

        if ($user == null ||
            $user == '') {
            
            abort("404");
            
        } else {

            return view('users.show')->with([
                'user'      =>  $user
            ]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort("404");
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
        abort("404");
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
        $model  =   new User();

        $this->utils->loggr('USERS.DELETE', 1);
        $this->utils->loggr(json_encode([
                                    'data'  =>  $user
                                ]), 0);

        // Validate fetched data
        if ($user == null) {
            // If user not exists in users table
            // Check from deleted table
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
            $destroy    =   $model->deleteUser($user);

            if ($destroy == null ||
                $destroy == true) {
                return redirect('/users')->with([
                    'success'   =>  "User <b>" . $user->first_name . ' ' . $user->last_name . "</b>'s account was successfully deleted."
                ]);
            }

            return redirect('/users')->withErrors(
                'message',  $this->utils->err->unexpected
            );
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
