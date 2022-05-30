<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Utils extends Controller
{
    public $err;

    public function __construct()
    {
        $this->err  =   (Object) ([
            'unexpected'    =>  'Unexpected error encountered while processing your request. Kindly report this to your administrator for checking.',
            'nochange'      =>  'You do not seem to have an update on this record. Please check and try again.',
            'calltheguy'    =>  'Kindly report this to your administrator for further checking.',
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
     * Clean-up unused attachments
     * 
     */
    public function attCleanup()
    {
        $app_name   =   'UTILS.ATTCLNUP';
        $file_dir   =   public_path() . '\storage\att';
        $arch_dir   =   $file_dir . '\archive';

        // Init
        info($app_name, ['status' => 'started']);

        // Get all unused tickets
        $tickets    =   DB::table('ticket_atts')
                                ->whereNotIn('ticket_id', DB::table('tickets')
                                                            ->select('id'))
                                ->select('ticket_id');
        
        // Get total
        $total      =   count($tickets->get());

        // Display total
        info($app_name, ['found' => $total]);

        // Create archive if not exists
        File::ensureDirectoryExists($arch_dir);

        // Check count
        if ($total > 0) {
            // Archive each ticket folder
            foreach ($tickets->get() as $ticket) {
                info($app_name, [
                    'action'    =>  'archiving',
                    'ticket'    =>  $ticket->ticket_id
                ]);

                try {
                    File::moveDirectory(
                        $file_dir . "/" . $ticket->ticket_id,
                        $arch_dir . "/" . $ticket->ticket_id,
                        false
                    );

                    if (File::exists($arch_dir . "/" . $ticket->ticket_id)) {
                        info($app_name, ['status' => 'success']);

                        $isDeleted  =   DB::table('ticket_atts')
                                            ->where('ticket_id', $ticket->ticket_id)
                                            ->delete();

                        info($app_name, [
                            'action'    =>  'delete',
                            'status'    =>  $isDeleted
                        ]);

                    } else {
                        info($app_name, ['status' => 'failed']);
                    }

                } catch (\Throwable $th) {
                    info($app_name, ['status' => 'error']);
                    report($th);
                }

            }
        }
        
        info($app_name, ['action' => 'completing']);

    }

}
