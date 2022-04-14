<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Utils extends Controller
{
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
}
