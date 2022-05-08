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
            // abort('401');
        }

        $user       =   new User();
        $managers   =   $user->canManage();

        return view('groups.index')->with([
            'managers'  =>  $managers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $group  =   new Group();

        // Check if group exists
        $this->utils->loggr("GROUPS.CHECK.EXISTS", 1);
        $this->utils->loggr("Action > Checking if group ID " . $id . " exists.", 0);

        $exists =   $group->exists($id);
        
        if ($exists == 1) {
            $this->utils->loggr("Result > Group exists.", 0);
            
        } elseif ($exists == 0) {
            $this->utils->loggr("Result > Group does not exists.", 0);
            return redirect('/groups')->withErrors(
                'message', 'Group does not exists or has already been deleted.'
            );

        } else {
            $this->utils->loggr("Result > ERROR. Check logs for more details.", 0);
            return redirect('/groups')->withErrors(
                'message', $this->utils->err->unexpected
            );

        }
        
        // Check if has members
        $this->utils->loggr("GROUPS.CHECK.MEMBERS", 1);
        $this->utils->loggr("Action > Checking if group ID " . $id . " has members.", 0);

        $hasMemeber =   $group->hasMember($id);

        if ($hasMemeber == 1) {
            $this->utils->loggr("Result > Group has members.", 0);
            return redirect('/groups')->withErrors(
                'message', 'Groups with members cannot be deleted.'
            );

        } elseif ($hasMemeber == 0) {
            $this->utils->loggr("Result > Group has NO members.", 0);

        } else {
            $this->utils->loggr("Result > ERROR. Check logs for more details.", 0);
            return redirect('/groups')->withErrors(
                'message', $this->utils->err->unexpected
            );

        }

        // Delete group
        $this->utils->loggr("GROUPS.DESTROY", 1);
        $this->utils->loggr("Action > Deleting group " . $id . ".", 0);

        $groupInfo  =   Group::find($id);
        $isDeleted  =   $group->delGroup($id);

        if ($isDeleted == 1) {
            $this->utils->loggr("Result > Group has been deleted.", 0);
            return redirect('/groups')->with([
                'success'   =>  'Group <b>' . $groupInfo->name . '</b> has been deleted.'
            ]);

        } elseif ($hasMemeber == 0) {
            $this->utils->loggr("Result > Failed to delete group.", 0);
            return redirect('/groups')->withErrors(
                'message', 'Failed to delete <b>' . $groupInfo->name . '</b> group. Kindly report this to your administrator for checking.'
            );

        } else {
            $this->utils->loggr("Result > ERROR. Check logs for more details.", 0);
            return redirect('/groups')->withErrors(
                'message', $this->utils->err->unexpected
            );

        }

    }

    /**
     * Activate group
     * 
     * @param   int     $id
     * @return  \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $isActive   =   false;
        $group      =   Group::find($id);

        if ($group == null) {
            return redirect('/groups')->withErrors(
                'message', 'The group you are trying to deactivate does not exists.'
            );

        }

        try {
            $isActive   =   Group::where('id', $id)
                                ->update([
                                    'status'        =>  'A',
                                    'updated_by'    =>  auth()->user()->id,
                                    'updated_at'    =>  \Carbon\Carbon::now()
                                ]);

        } catch (\Throwable $th) {
            report($th);

        }

        if ($isActive) {
            return redirect('/groups')->with([
                'success'   =>  'Group <b>' . $group->name . '</b> has been <b>activated</b>.'
            ]);

        } else {
            return back()->withErrors(
                'message', $this->utils->err->unexpected
            );

        }
        
        
    }

    /**
     * Deactivate group
     * 
     * @param   int     $id
     * @return  \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        $isInactive =   false;
        $group      =   Group::where('id', $id)
                            ->first();

        if ($group == null) {
            return redirect('/groups')->withErrors(
                'message', 'The group you are trying to deactivate does not exists.'
            );
        }

        try {
            $isInactive =   Group::where('id', $id)
                                ->update([
                                    'status'        =>  'I',
                                    'updated_by'    =>  auth()->user()->id,
                                    'updated_at'    =>  \Carbon\Carbon::now()
                                ]);

        } catch (\Throwable $th) {
            report($th);

        }

        if ($isInactive) {
            return redirect('/groups')->with([
                'success'   =>  'Group <b>' . $group->name . '</b> has been <b>deactivated</b>.'
            ]);

        } else {
            return back()->withErrors(
                'message', $this->utils->err->unexpected
            );

        }
        
    }

}
