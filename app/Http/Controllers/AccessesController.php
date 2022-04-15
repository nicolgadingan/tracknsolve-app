<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailVerify;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccessesController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

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
                $message    =   "<b>Yay! Almost there.</b><br>Just confirm your password and you're ready to go.";
            }
        } else {
            $status     =   'not-exists';
            $message    =   "<b>Oh no!</b><br>Your verification link has expired. Kindly contact your administrator to assist you.";
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
        // Validate input
        $this->validate($request, [
            'user_id'               =>  'required',
            'password'              =>  'required|string|min:8|confirmed',
            'password_confirmation' =>  'required|string|min:8'
        ]);

        // Retrieve user account
        $user   =   User::find($request->user_id);
        
        if ($user != null) {
            // Apply password and verification
            $user->password             =   Hash::make($request->password);
            $user->email_verified_at    =   \Carbon\Carbon::now();
            $user->updated_by           =   $request->user_id;
            $user->updated_at           =   \Carbon\Carbon::now();
            $user->status               =   'A';
            $user->save();

            // Drop token
            DB::table('email_verifies')->where('user_id', $user->id)->delete();

        } else {
            return back()->withErrors([
                "We are having trouble finding your account. Kindly contact your administrator and provide this info: UID" .
                $request->user_id . 'NOTFOUND.'
            ]);
        }

        return redirect('/login')->with([
            'success'   =>  "You have successfully verified your account."
        ]);
    }
}
