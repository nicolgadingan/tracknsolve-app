<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class Configs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'config_name'       =>  'ORG_KEY',
            'value'             =>  'YRTK',
            'description'       =>  "This is the organizations' 2 to 4 character key identifier.",
            'created_by'        =>  '999',
            'created_at'        =>  \Carbon\Carbon::now()
        ]);

        DB::table('configs')->insert([
            'config_name'       =>  'LAST_TK_SEQ',
            'value'             =>  842538001,
            'description'       =>  "This is the last ticket sequence generated.",
            'created_by'        =>  '999',
            'created_at'        =>  \Carbon\Carbon::now()
        ]);

    }
}
