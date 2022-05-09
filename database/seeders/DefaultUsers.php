<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now    =   \Carbon\Carbon::now();

        DB::table('users')->insert([
            'role'              =>  'admin',
            'first_name'        =>  'Nicol',
            'middle_name'       =>  null,
            'last_name'         =>  'Gadingan',
            'username'          =>  'mgadingan',
            'group_id'          =>  null,
            'slug'              =>  'mgadingan',
            'email'             =>  'mgadingan@yortik.com',
            'contact_no'        =>  null,
            'email_verified_at' =>  null,
            'password'          =>  Hash::make('admin123'),
            'remember_token'    =>  null,
            'created_by'        =>  999999,
            'updated_by'        =>  999999,
            'created_at'        =>  $now,
            'updated_at'        =>  $now,
        ]);
    }
}
