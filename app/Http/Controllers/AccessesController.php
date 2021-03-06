<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailVerify;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifiedMail;
use App\Jobs\Mailman;
use App\Models\Event;
use App\Notifications\UserVerified;
use Illuminate\Support\Facades\URL;

class AccessesController extends Controller
{
    protected $uid;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    public function __construct()
    {
        $this->uid  =   (auth()->check() == 1) ? auth()->user()->id : 99999;
    }

    /**
     * Registered user verification form
     * 
     * @return view
     */
    public function verification($token)
    {
        $token      =   EmailVerify::where('token', $token)->first();
        $status     =   'not-exists';
        $message    =   null;
        $userid     =   null;

        // Verify validity of token
        if ($token != null) {
            $userid     =   $token->user_id;
            $verified   =   User::find($userid)->email_verified_at;

            // Verify if account has been verified
            if ($verified) {
                $status     =   'verified';
                $message    =   '<b>Hmmm!</b><br>Your account has already been verified. Have you forgotten your password?';
            } else {
                $status     =   'not-verified';
                $message    =   "<b>Almost there!</b><br>Just confirm your password and you're ready to go.";
            }
        } else {
            $status     =   'not-exists';
            $message    =   "<b>Oh no!</b><br>Your verification link has expired. Kindly contact your administrator for assistance.";
        }
        
        return view('accesses.verify')->with([
            'status'    =>  $status,
            'message'   =>  $message,
            'userid'    =>  $userid,
        ]);
    }

    /**
     * Verify password and verify account
     * 
     * @return Response
     */
    public function verify(Request $request)
    {
        info('CTRL.US.VERFY', [
            'user'      =>  $this->uid,
            'status'    =>  'init',
        ]);

        // Validate input
        $this->validate($request, [
            'user_id'               =>  'required',
            'password'              =>  'required|string|min:8|confirmed',
            'password_confirmation' =>  'required|string|min:8'
        ]);

        // Retrieve user account
        $user   =   User::find($request->user_id);
        
        if ($user != null) {
            info('CTRL.US.VERFY', [
                'user'      =>  $this->uid,
                'action'    =>  'verifying',
            ]);

            try {
                $user->password             =   Hash::make($request->password);
                $user->email_verified_at    =   \Carbon\Carbon::now();
                $user->updated_by           =   $request->user_id;
                $user->updated_at           =   \Carbon\Carbon::now();
                $user->status               =   'A';
                $user->save();

            } catch (\Throwable $th) {
                info('CTRL.US.VERFY', [
                    'user'      =>  $this->uid,
                    'status'    =>  'error',
                ]);
                report($th);

            }

            info('CTRL.US.VERFY', [
                'user'      =>  $this->uid,
                'status'    =>  'verified',
            ]);

            try {
                $user->notify(new UserVerified($user));
            } catch (\Throwable $th) {
                report($th);
            }

            // Drop token
            DB::table('email_verifies')->where('user_id', $user->id)->delete();

        } else {
            return back()->withErrors([
                "We are having trouble finding your account. Kindly contact your administrator and provide this info: UID" .
                $request->user_id . 'NOTFOUND.'
            ]);
        }

        // Create event
        $event          =   new Event();
        $event->create([
            'category'      =>  'user',
            'action'        =>  'verify',
            'key_id1'       =>  $user->id,
        ]);

        // Queue email to user
        info('CTRL.US.VERFY', [
            'user'      =>  $this->uid,
            'status'    =>  'sent',
        ]);

        try {
            

        } catch (\Throwable $th) {
            info('USERS.REGISTER', ['result' => 'error']);
            report($th);

        }


        return redirect('/login')->with([
            'success'   =>  "You have successfully verified your account."
        ]);
    }
}
