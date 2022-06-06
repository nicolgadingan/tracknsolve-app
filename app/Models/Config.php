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

        // Validate if sequence is reused
        $reUsed     =   DB::table('reserves')
                            ->where('key_id', $newKey)
                            ->where('category', 'TICKET_KEY')
                            ->selectRaw('count(id) as reserved')
                            ->first();
        
        if ($reUsed->reserved > 1) {
            // Ticket sequence is reused > Reconfigure
            $this->tkSeqReconf();

            $getSeq =   Config::where('config_name', 'LAST_TK_SEQ')
                            ->select('value')
                            ->first();

            $newKey     =   $org_key . $getSeq->value;
        }

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

    /**
     * Get subscription type
     * 
     * @return  text
     */
    public function subscription()
    {
        return  Config::where('config_name', 'SUBS_TYPE')
                    ->select('value')
                    ->first();
        
    }

    /**
     * Check if app is fully setup and ready to start
     * 
     * @return  Object
     */
    public function isFullySetup()
    {
        $isGood     =   true;
        $missing    =   [];
        $confList   =   [];

        $checks     =   [
                            'ORG_KEY',
                            'LAST_TK_SEQ',
                            'TK_AUTO_X_DAYS',
                            'SUBS_TYPE'
                        ];

        $allConfig  =   Config::select('config_name')
                            ->get();

        foreach ($allConfig as $confName) {
            $confList[]     =   $confName->config_name;
        }

        // Validate
        foreach ($checks as $conf) {
            if (!in_array($conf, $confList)) {
                $missing[]  =   $conf;
                $isGood     =   false;
            }
        }

        return [
                'isReady'   =>  $isGood,
                'missing'   =>  $missing
            ];

    }
    
    /**
     * Set basic subscription
     * 
     */
    public function setBasic()
    {
        info('CONFIG.SETBASIC', [
            'status'    =>  'init'
        ]);

        $user   =   [
            [
                'config_name'   =>  'CAN$SEE_USER',
                'value'         =>  'Y',
                'description'   =>  'See user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$ADD_USER',
                'value'         =>  'Y',
                'description'   =>  'Add user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$UPD_USER',
                'value'         =>  'N',
                'description'   =>  'Modify user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$DEL_USER',
                'value'         =>  'N',
                'description'   =>  'Delete user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$RAS_USER',
                'value'         =>  'N',
                'description'   =>  'Reassign user.',
                'created_by'    =>  99999
            ]
        ];

        $group  =   [
            [
                'config_name'   =>  'CAN$SEE_GROUP',
                'value'         =>  'Y',
                'description'   =>  'See group.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$ADD_GROUP',
                'value'         =>  'Y',
                'description'   =>  'Add group.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$UPD_GROUP',
                'value'         =>  'Y',
                'description'   =>  'Modify group.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$DEL_GROUP',
                'value'         =>  'N',
                'description'   =>  'Delete group.',
                'created_by'    =>  99999
            ]
        ];

        $ticket =   [
            [
                'config_name'   =>  'CAN$SEE_TICKET',
                'value'         =>  'Y',
                'description'   =>  'See ticket.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$ADD_TICKET',
                'value'         =>  'Y',
                'description'   =>  'Add ticket.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$UPD_TICKET',
                'value'         =>  'Y',
                'description'   =>  'Modify ticket.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$DEL_TICKET',
                'value'         =>  'N',
                'description'   =>  'Delete ticket.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$ADD_COMMENT',
                'value'         =>  'Y',
                'description'   =>  'Add comments.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$ADD_ATTACHMENT',
                'value'         =>  'Y',
                'description'   =>  'Add attachments.',
                'created_by'    =>  99999
            ]
        ];

        $admin  =   [
            [
                'config_name'   =>  'CAN$SEE_USAGE',
                'value'         =>  'Y',
                'description'   =>  'See usage.',
                'created_by'    =>  99999
            ]
        ];

        $extra  =   [
            [
                'config_name'   =>  'CAN$EXP_TICKET',
                'value'         =>  'Y',
                'description'   =>  'Export tickets.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$SEE_REPORT',
                'value'         =>  'Y',
                'description'   =>  'See reports.',
                'created_by'    =>  99999
            ]
        ];
        
        $mobile =   [
            [
                'config_name'   =>  'CAN$MOB_TICKET',
                'value'         =>  'Y',
                'description'   =>  'Tickets availability in mobile.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$MOB_GROUP',
                'value'         =>  'Y',
                'description'   =>  'Groups availability in mobile.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$MOB_REPORT',
                'value'         =>  'N',
                'description'   =>  'Reports availability in mobile.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$MOB_ADMIN',
                'value'         =>  'N',
                'description'   =>  'Admin availability in mobile.',
                'created_by'    =>  99999
            ]
        ];

        $email  =   [
            [
                'config_name'   =>  'CAN$EML_NEW_USER',
                'value'         =>  'Y',
                'description'   =>  'Send email to registered user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_VFY_USER',
                'value'         =>  'Y',
                'description'   =>  'Send email to verified user.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_TK_ASSIGN',
                'value'         =>  'Y',
                'description'   =>  'Send email when ticket assigned.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_TK_RESOLVED',
                'value'         =>  'Y',
                'description'   =>  'Send email when ticket resolved.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_TK_COMMENT',
                'value'         =>  'Y',
                'description'   =>  'Send email when ticket has new comment.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_TK_CLOSE',
                'value'         =>  'Y',
                'description'   =>  'Send email when ticket is closed.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$EML_SUBS_ALERT',
                'value'         =>  'Y',
                'description'   =>  'Send email subscription alerts.',
                'created_by'    =>  99999
            ]
            
        ];

        $notif  =   [
            [
                'config_name'   =>  'CAN$GUI_TK_ASSIGN',
                'value'         =>  'Y',
                'description'   =>  'Send notif when ticket assigned.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$GUI_TK_RESOLVED',
                'value'         =>  'N',
                'description'   =>  'Send notif when ticket resolved.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$GUI_TK_CLOSE',
                'value'         =>  'N',
                'description'   =>  'Send notif when ticket is closed.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$GUI_TK_COMMENT',
                'value'         =>  'Y',
                'description'   =>  'Send notif when ticket has new comment.',
                'created_by'    =>  99999
            ],
            [
                'config_name'   =>  'CAN$GUI_RAS_USER',
                'value'         =>  'N',
                'description'   =>  'Send notif when user is reassigned.',
                'created_by'    =>  99999
            ]
        ];

        info('CONFIG.SETBASIC', [
            'status'    =>  'in-progress',
            'config'    =>  'collected'
        ]);

        // Clear existing configuration
        Config::where('config_name', 'like', 'CAN$%')
                ->delete();

        info('CONFIG.SETBASIC', [
            'status'    =>  'in-progress',
            'config'    =>  'truncated'
        ]);

        // Add new configuration
        Config::insert($user);
        Config::insert($group);
        Config::insert($ticket);
        Config::insert($admin);
        Config::insert($extra);
        Config::insert($mobile);
        Config::insert($email);
        Config::insert($notif);

        info('CONFIG.SETBASIC', [
            'status'    =>  'complete',
            'config'    =>  'applied'
        ]);

    }
}
