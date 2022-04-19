<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Ticket key generator
     * 
     * @return  string
     */
    public function getKey()
    {
        $orgKey =   DB::table('configs')
                        ->insert([
                            'ticket'
                        ])
    }
}
