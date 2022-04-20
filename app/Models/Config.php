<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    /**
     * Ticket key generator
     * 
     * @return  string
     */
    public function getKey()
    {
        // Locks the sequence
        $last_tk_seq    =   Config::where('config_name', 'LAST_TK_SEQ')
                                ->lockForUpdate()
                                ->first();

        // dd($last_tk_seq->value);

        // Increment the sequence and save
        $last_tk_seq->value += 1;
        $last_tk_seq->save();

        // Get organization key
        $org_key    =   $this->getOrg();

        return $org_key . $last_tk_seq->value;
    }

    public function getOrg()
    {
        $config =   Config::where('config_name', 'ORG_KEY')
                            ->first();

        return $config->value;
    }
}
