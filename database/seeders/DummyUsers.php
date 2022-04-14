<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // $faker = Faker\Factory::create();

        // for ($i=0; $i < 10; $i++) { 

        //     $now    =   Carbon::now();

        //     $firstName  =   $faker->firstName();
        //     $lastName   =   $faker->lastName();
        //     $userName   =   Str::lower($firstName[0] . $lastName);

        //     DB::table('users')->insert([
        //         'role'              =>  'user',
        //         'first_name'        =>  $firstName,
        //         'middle_name'       =>  null,
        //         'last_name'         =>  $lastName,
        //         'username'          =>  'mgadingan',
        //         'group_id'          =>  $userName,
        //         'slug'              =>  $userName,
        //         'email'             =>  $userName . '@yortik.com',
        //         'contact_no'        =>  null,
        //         'email_verified_at' =>  $now,
        //         'password'          =>  Hash::make('password'),
        //         'remember_token'    =>  Str::random(12),
        //         'created_by'        =>  999999,
        //         'updated_by'        =>  999999,
        //         'created_at'        =>  $now,
        //         'updated_at'        =>  $now,
        //     ]);
        // }
        \App\Models\User::factory(10)->create();
        
    }
}
