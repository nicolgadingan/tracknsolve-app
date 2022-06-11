<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Config;

class Configs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        // Organization Key
        $tmpOrgKey      =   Config::firstOrCreate(
                                [
                                    'config_name'   =>  'ORG_KEY',
                                ],
                                [
                                    'value'         =>  'TNS',
                                    'description'   =>  "This is the organizations' 2 to 4 character key identifier.",
                                    'created_by'    =>  99999,
                                    'created_at'    =>  \Carbon\Carbon::now()
                                ]
                            );

        // Ticket Sequence
        $tmpTkSeq       =   Config::firstOrCreate(
                                [
                                    'config_name'   =>  'LAST_TK_SEQ',
                                ],
                                [
                                    'value'         =>  000,
                                    'description'   =>  "This is the last ticket sequence generated.",
                                    'created_by'    =>  99999,
                                    'created_at'    =>  \Carbon\Carbon::now()
                                ]
                            );

        // Ticket Auto-close configuration
        $tmpAutoClosse  =   Config::firstOrCreate(
                                [
                                    'config_name'   =>  'TK_AUTO_X_DAYS'
                                ],
                                [
                                    'value'         =>  5,
                                    'description'   =>  "Days identifier when a resolved ticket can be closed automatically.",
                                    'created_by'    =>  99999,
                                    'created_at'    =>  \Carbon\Carbon::now()
                                ]
                            );

        // Subscription type
        $tmpSubs        =   Config::firstOrCreate(
                                [
                                    'config_name'   =>  'SUBS_TYPE'
                                ],
                                [
                                    'value'         =>  'Basic',
                                    'description'   =>  "Indetifies the subscription type.",
                                    'created_by'    =>  99999,
                                    'created_at'    =>  \Carbon\Carbon::now()
                                ]
                            );

        // Overdue days
        $tmpOverdue     =   Config::firstOrCreate(
            [
                'config_name'   =>  'OVERDUE_DAYS'
            ],
            [
                'value'         =>  7,
                'description'   =>  "Days till a ticket will be flagged as overdue.",
                'created_by'    =>  99999,
                'created_at'    =>  \Carbon\Carbon::now()
            ]
        );

    }
}
