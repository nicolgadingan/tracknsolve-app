<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

class Utils extends Controller
{
    public $err;

    public function __construct()
    {
        $this->err  =   (Object) ([
            'unexpected'    =>  'Unexpected error encountered while processing your request. Kindly report this to your administrator for checking.',
            'nochange'      =>  'You do not seem to have an update on this record. Please check and try again.',
            'calltheguy'    =>  'Kindly report this to your administrator for further checking.',
            'nousgroup'     =>  'You cannot create a user without an active Group to assign to. Please create atleast one before proceeding.',
            'notkgroup'     =>  'You cannot create a ticket without an active Group to assign to. Please create atleast one before proceeding.'
        ]);
    }

    /**
     * isAdmin checks if current user is an admin
     * 
     * @return boolean $response
     */
    public function isAdmin()
    {
        $role   =   auth()->user()->role;
        $response   = ($role == 'admin') ? true : false ;
        
        return $response ;
    }

    /**
     * isManager checks if current user is a manager
     * 
     * @return boolean $response
     */
    public function isManager()
    {
        $role   =   auth()->user()->role;
        $response   = ($role == 'manager') ? true : false ;
        
        return $response;
    }

    /**
     * isManager checks if current user is a manager
     * 
     * @return boolean $response
     */
    public function isUser()
    {
        $role   =   auth()->user()->role;
        $response   = ($role == 'user') ? true : false ;
        
        return $response;
    }

    /**
     * Sanitize
     * Removes special characters from string
     * 
     */
    public function sanitize($str)
    {
        return preg_replace('/[^a-zA-Z0-9_ -]/s', '', $str);
    }

    /**
     * Event logger
     * 
     * @param   String  $data
     * @param   Int     $opt
     */
    public function loggr($data, $opt)
    {

        try {
            $user_id    =   auth()->user()->id;
        } catch (\Throwable $th) {
            $user_id    =   99999;
        }
        
        $dts        =   \Carbon\Carbon::now();
        $disk       =   'local';
        $file       =   'log/' . $user_id . '_' . $dts->format('Ymd') . '.log';

        if ($opt == 1) {
            Storage::disk($disk)->append($file, $dts . ' - ' . Str::padRight('', 60, '#'));
            Storage::disk($disk)->append($file, $dts . ' - ' . $data);
            Storage::disk($disk)->append($file, $dts . ' - ' . Str::padRight('', 60, '#'));
        } else {
            Storage::disk($disk)->append($file, $dts . ' - ' . $data);
        }
    }

    /**
     * Clean-up unused attachments after 24hrs
     * 
     */
    public function attCleanup()
    {
        // Init
        info('CTRL.UT.RUATT', [
            'user'      =>  99999,
            'status'    =>  'init'
        ]);

        // Paths
        $fileDir    =   storage_path('app/public/att');
        $archDir    =   $fileDir . '/archive';

        // Create archive if not exists
        File::ensureDirectoryExists($archDir);

        // Get all unused tickets
        $tickets    =   DB::table('ticket_atts')
                                ->where('created_at', '<', DB::raw('(current_timestamp - interval 1 day)'))
                                ->whereNotIn('ticket_id', DB::table('tickets')
                                                            ->select('id'))
                                ->select('ticket_id');
        
        // Get total
        $total      =   count($tickets->get());

        // Display total
        info('CTRL.UT.RUATT', [
            'status'    =>  'checked',
            'found'     =>  $total
        ]);

        // Check count
        if ($total > 0) {
            // Archive each ticket folder
            foreach ($tickets->get() as $ticket) {
                info('CTRL.UT.RUATT', [
                    'action'    =>  'archiving',
                    'data'      =>  $ticket->ticket_id
                ]);

                try {
                    File::moveDirectory(
                        $fileDir . "/" . $ticket->ticket_id,
                        $archDir . "/" . $ticket->ticket_id,
                        true
                    );

                    if (File::exists($archDir . "/" . $ticket->ticket_id)) {
                        info('CTRL.UT.RUATT', [
                            'action'    =>  'validate',
                            'status'    =>  'archived'
                        ]);

                        $isDeleted  =   DB::table('ticket_atts')
                                            ->where('ticket_id', $ticket->ticket_id)
                                            ->delete();

                        info('CTRL.UT.RUATT', [
                            'action'    =>  'delete',
                            'status'    =>  $isDeleted
                        ]);

                    } else {
                        info('CTRL.UT.RUATT', [
                            'action'    =>  'validate',
                            'status'    =>  'failed'
                        ]);
                    }

                } catch (\Throwable $th) {
                    info('CTRL.UT.RUATT',[
                        'action'    =>  'error'
                    ]);
                    report($th);
                }

            }
        }
        
        info('CTRL.UT.RUATT',[
            'status'    =>  'done'
        ]);

    }

    /**
     * Parse config
     * 
     * @param   Array   $configs
     * @return  String|Int
     */
    public function parseConfig($configs, $track)
    {
        $remap  =   [];
        foreach ($configs as $conf) {
            $remap    =   Arr::add($remap, $conf['config_name'],  $conf['value']);
        }

        $value  =   Arr::first($remap, function($value, $key) use ($track) {
            return $key == $track;
        });

        return $value;
    }

    /**
     * Create public access identifier file
     * 
     */
    public function createPublicId()
    {
        info('CTRL.UT.APUBV', [
            'user'      =>  99999,
            'status'    =>  'init'
        ]);

        // File identifier
        $fileName   =   'public-access.txt';
        $fileId     =   storage_path('app/public/' . $fileName);

        info('CTRL.UT.APUBV', [
            'action'    =>  'check',
            'file'      =>  $fileName
        ]);

        // Check and create
        if (File::exists($fileId)) {
            info('CTRL.UT.APUBV', [
                'status'    =>  'exists'
            ]);
        } else {
            File::put($fileId, 'hello');
            info('CTRL.UT.APUBV', [
                'status'    =>  'created'
            ]);
        }

    }

    /**
     * Check for public access identifier file
     * 
     * @return  Boolean
     */
    public function checkPublicId()
    {
        return File::exists(public_path('storage/public-access.txt'));
    }

}
