<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Arr;
use App\Models\Ticket;
use App\Models\Config;
use App\Models\Group;
use App\Models\User;

class Utils extends Controller
{
    public $err;

    public function __construct()
    {
        $this->err  =   (Object) ([
            'unexpected'    =>  'Unexpected error encountered while processing your request. Kindly report this to your administrator for checking.'
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
     * @param   String $data
     * @param   Integer $opt
     */
    public function loggr($data, $opt)
    {
        $user_id    =   auth()->user()->id;
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
}
