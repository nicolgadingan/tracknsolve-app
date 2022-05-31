<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Config extends Model
{
    use HasFactory;

    /**
     * Ticket key generator
     * 
     * @param   Integer $user_id
     * @return  string
     */
    public function getKey()
    {
        // Locks the sequence
        $last_tk_seq    =   Config::where('config_name', 'LAST_TK_SEQ')
                                ->lockForUpdate()
                                ->first();

        // Increment the sequence and save
        $last_tk_seq->value += 1;
        $last_tk_seq->save();

        // Get organization key
        $org_key    =   $this->getOrg();
        $newKey     =   $org_key . $last_tk_seq->value;

        // Reserve Key
        DB::table('reserves')
                ->insert([
                    'status'        =>  'N',
                    'category'      =>  'TICKET_KEY',
                    'key_id'        =>  $newKey,
                    'created_by'    =>  auth()->user()->id,
                    'created_at'    =>  \Carbon\Carbon::now()
                ]);

        return $newKey;
    }

    public function getOrg()
    {
        $config =   Config::where('config_name', 'ORG_KEY')
                            ->first();

        return $config->value;
    }

    /**
     * Reconfigure ticket sequence
     * 
     * @return  Int $retCode
     */
    public function tkSeqReconf()
    {
        $retCode    =   0;
        $reconData  =   DB::table('tickets as t')
                            ->select(
                                DB::raw("(select length(value) from configs where config_name = 'ORG_KEY') as org_len"),
                                DB::raw('max(t.id) as max_seq'),
                                DB::raw("(select value from configs where config_name = 'LAST_TK_SEQ') as last_seq")
                            )
                            ->first();

        $maxSeq     =   substr($reconData->max_seq, $reconData->org_len);

        if ($maxSeq >= $reconData->last_seq) {
            try {
                $isReconfd  =   DB::table('configs')
                                    ->where('config_name', 'LAST_TK_SEQ')
                                    ->update([
                                        'value' =>  $maxSeq + 1
                                    ]);

                if ($isReconfd) {
                    $retCode    =   1;
                }

            } catch (\Throwable $th) {
                $retCode        =   255;
                report($th);

            }

        }

        return $retCode;
        
    }

}
