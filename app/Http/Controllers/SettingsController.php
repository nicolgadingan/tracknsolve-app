<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Utils;
use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Group;

class SettingsController extends Controller
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
        $this->uid      =   ( auth()->check() == 1 ) ? auth()->user()->id : 99999;
    }

    /**
     * Show the application dashboard.
     *
     * @return view
     */
    public function index()
    {
        $config         =   new Config();
        $configs        =   $config->allConfig();

        $prsConfs       =   [];
        foreach ($configs as $conf) {
            $prsConfs   =   Arr::add($prsConfs, $conf['config_name'], $conf['value']);
        }

        // Get disk size
        $pubDirSize     =   0;

        // Sum all files' sizes
        foreach( File::allFiles(storage_path('/')) as $file)
        {
            $pubDirSize += $file->getSize();
        }

        // Convert it to Mb
        $pubDirSize =   number_format($pubDirSize / 1048576, 2);
        $stgInfo    =   [];

        // Covert it to Gb if size already in half Gb
        if (($pubDirSize / 512) >= 1) {
            $stgInfo['size']    =   number_format($pubDirSize / 1024, 2);
            $stgInfo['text']    =   'Gb';
        } else {
            $stgInfo['size']    =   $pubDirSize;
            $stgInfo['text']    =   'Mb';
        }

        // Get the group and user used
        $users      =   User::all();
        $groups     =   Group::all();

        // Get ticket sequence
        $tkSeq      =   $this->chkTicketSeq();

        $pubAccess  =   $this->utils->checkPublicId();

        return view('settings.index')->with([
            'configs'   =>  $prsConfs,
            'stats'     =>  [
                'diskInfo'  =>  $stgInfo,
                'userUsed'  =>  count($users),
                'groupUsed' =>  count($groups),
                'pubAccess' =>  $pubAccess,
                'ticketSeq' =>  $tkSeq
            ]
        ]);
    }

    /**
     * Check ticket sequence
     * 
     * @return  Int
     */
    public function chkTicketSeq()
    {
        info('CTRL.ST.GTKSQ', [
            'user'      =>  $this->uid,
            'status'    =>  'init'
        ]);

        $status         =   [];
        $status['code'] =   0;

        try {
            $chkData    =   DB::table('tickets as t')
                                ->select(
                                    DB::raw("(select length(value) from configs where config_name = 'ORG_KEY') as org_len"),
                                    DB::raw('max(t.id) as max_seq'),
                                    DB::raw("(select value from configs where config_name = 'LAST_TK_SEQ') as last_seq")
                                )
                                ->first();
            
            info('CTRL.ST.GTKSQ', [
                'user'      =>  $this->uid,
                'status'    =>  'fetched',
                'data'      =>  $chkData
            ]);

            $maxSeq     =   substr($chkData->max_seq, $chkData->org_len);

            $status['maxSeq']   =   $maxSeq;
            $status['nowSeq']   =   $chkData->last_seq;

            info('CTRL.ST.GTKSQ', [
                'user'      =>  $this->uid,
                'action'    =>  'verifying'
            ]);

            if ($maxSeq > $chkData->last_seq) {
                $status['action']   =   'recon';

            } else {
                $status['action']   =   'none';
                
            }

            info('CTRL.ST.GTKSQ', [
                'user'      =>  $this->uid,
                'status'    =>  'success'
            ]);

            $status['code'] =   1;

        } catch (\Throwable $th) {
            $status['code'] =   255;

            info('CTRL.ST.GTKSQ', [
                'user'      =>  $this->uid,
                'status'    =>  'error'
            ]);
            report($th);

        }

        info('CTRL.ST.GTKSQ', [
            'user'      =>  $this->uid,
            'status'    =>  'end'
        ]);

        return $status;
    }

}
