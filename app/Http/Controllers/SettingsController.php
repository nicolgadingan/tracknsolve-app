<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Utils;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
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
     * Show the application dashboard.
     *
     * @return view
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Check ticket sequence
     * 
     * @return  Int
     */
    public function chkTicketSeq()
    {
        $this->utils->loggr('SETTINGS.CHKTKSEQ', 1);
        $this->utils->loggr('Action > Check ticket sequence.', 0);

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
            
            $this->utils->loggr(json_encode([
                'result'    =>  'success',
                'data'      =>  $chkData
            ]), 0);

            $maxSeq     =   substr($chkData->max_seq, $chkData->org_len);

            $status['maxSeq']   =   $maxSeq;
            $status['nowSeq']   =   $chkData->last_seq;

            $this->utils->loggr('Action > Compare sequence.', 0);

            if ($maxSeq >= $chkData->last_seq) {
                $status['action']   =   'recon';

            } else {
                $status['action']   =   'none';
                
            }

            $this->utils->loggr(json_encode([
                'result'    =>  'success',
                'require'   =>  $status['action']
            ]), 0);

            $status['code'] =   1;

        } catch (\Throwable $th) {
            $status['code'] =   255;
            $this->utils->loggr('Result > Error.', 0);
            report($th);

        }

        return $status;
    }

}
