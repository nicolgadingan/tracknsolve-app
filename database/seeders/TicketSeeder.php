<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Config;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Arr;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count      =   5;
        $config     =   new Config();
        $ticket     =   new Ticket();
        $faker      =   \Faker\Factory::create();

        $groups     =   Group::where('status', 'A')
                            ->select('id as group_id')
                            ->get()
                            ->toArray();

        $statuses   =   [
            'in-progress',
            'resolved',
            'on-hold',
            'closed'
        ];

        $priorities =   [
            'task',
            'request',
            'urgent',
            'important'
        ];

        $startdate  =   strtotime('2022-04-01 00:00:00');
        $enddate    =   strtotime('2022-04-30 00:00:00');

        $collector  =   [];

        for ($i=0; $i < $count; $i++) {
            $tkSeq      =   $config->getKey();
            $group      =   Arr::random($groups, 1)[0]['group_id'];

            $users      =   User::where('group_id', $group)
                                ->select('id as user_id')
                                ->get()
                                ->toArray();
            $user       =   Arr::random($users, 1)[0]['user_id'];
            $priority   =   Arr::random($priorities, 1)[0];
            $status     =   Arr::random($statuses, 1)[0];
            $reporter   =   Arr::random($users, 1)[0]['user_id'];

            $randte     =   rand($startdate, $enddate);
            $tdate      =   date('Y-m-d H:i:s', $randte);

            $tdata      =   [
                'tkey'            =>  $tkSeq,
                'status'        =>  $status,
                'priority'      =>  $priority,
                'title'         =>  $faker->sentence(rand(5, 10), true),
                'description'   =>  $faker->sentences(rand(10, 15), true),
                'group'      =>  $group,
                'assignee'      =>  $user,
                'caller'      =>  $reporter,
                'reporter'      =>  $reporter,
                'created_at'    =>  $tdate
            ];

            $ticket->createTicket($tdata);

            $collector[$i]  =   $tdata;
        }
    }
}
