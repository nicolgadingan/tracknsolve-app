<?php

namespace App\Models;

use App\Http\Controllers\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    protected $utils;
    protected $uid;

    use HasFactory;

    protected $fillable =   [
        'id'
    ];

    public function __construct()
    {
        $this->utils    =   new Utils;
        $this->uid      =   ( auth()->check() == 1 ) ? auth()->user()->id : 99999;
    }

    /**
     * Add relationship to Comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Add relationship to Groups
     * 
     */
    public function assignment()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Add relationship to Users
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'reporter');
    }

    /**
     * Add relationship to Users as assignee
     * 
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assignee');
    }

    /**
     * Create ticket record
     * 
     * @param   Array $tdata
     * @return  Object
     */
    public function createTicket($tdata)
    {
        $retcode    =   0;

        info('MODL.TK.CREAT', [
                'user'      =>  $this->uid,
                'status'    =>  'init',
                'data'      =>  $tdata
            ]);

        try {
            Ticket::insert([
                    'id'            =>  $tdata['ticket_id'],
                    'status'        =>  ($tdata['assignee'] != '') ? 'in-progress' : $tdata['status'],
                    'priority'      =>  $tdata['priority'],
                    'title'         =>  $tdata['title'],
                    'description'   =>  $tdata['description'],
                    'group_id'      =>  $tdata['group_id'],
                    'assignee'      =>  ($tdata['assignee'] != '') ? $tdata['assignee'] : null,
                    'reporter'      =>  $tdata['reporter'],
                    'created_at'    =>  \Carbon\Carbon::now()
                ]);

            info('MODL.TK.CREAT', [
                    'user'      =>  $this->uid,
                    'status'    =>  'created',
                ]);

            $retcode    =   1;

             // Add history
            info('MODL.TK.CREAT', [
                'user'      =>  $this->uid,
                'call'      =>  'modl.tk.ahist',
            ]);

            try {
                $this->addHistory($tdata);
            } catch (\Throwable $th) {
                report($th);
            }

            // Update reservation to P (processed)
            info('MODL.TK.CREAT', [
                'user'      =>  $this->uid,
                'call'      =>  'modl.tk.uresv',
            ]);

            try {
                $this->updReserves($tdata['ticket_id'], 'P');

            } catch (\Throwable $th) {
                report($th);
            }

        } catch (\Throwable $th) {
            info('MODL.TK.CREAT', [
                    'user'      =>  $this->uid,
                    'status'    =>  'error',
                ]);
            report($th);

            $retcode    =   255;

        }

        // Add event
        // info('MODL.TK.CREAT', [
        //         'user'      =>  $this->uid,
        //         'call'      =>  'modl.ev.creat',
        //     ]);

        // $evdata['category'] =   'TICKET';
        
        // $event  =   new Event();

        // $event->create([
        //     'category'      =>  'TICKET',
        //     'action'        =>  '',
        //     'key_id1'       =>  '',
        //     'key_id2'       =>  '',
        //     'key_id3'       =>  '',
        //     'description'   =>  ''
        // ]);

        return  $retcode;
    }

    /**
     * Update ticket record
     * 
     * @param   Array   $tdata
     * @return  Object
     */
    public function updateTicket($tdata)
    {
        $tdate      =   \Carbon\Carbon::now();

        $isUpdated  =   Ticket::where('id', $tdata['tkey'])
                            ->where('status', '!=', 'closed')
                            ->update([
                                'priority'      =>  $tdata['priority'],
                                'status'        =>  $tdata['status'],
                                'title'         =>  $tdata['title'],
                                'description'   =>  $tdata['description'],
                                'group_id'      =>  $tdata['group_id'],
                                'assignee'      =>  ($tdata['assignee'] != '') ? $tdata['assignee'] : null,
                                'updated_at'    =>  $tdate
                            ]);
        
        $this->addHistory($tdata);

        return $isUpdated;

    }

    /**
     * Assign to me
     * 
     * @param   Object  $tdata
     * @return  Int     $isAssigned
     */
    public function assignToMe($tdata)
    {
        info('MODL.TK.AS2ME', [
            'user'      =>  $this->uid,
            'status'    =>  'init'
        ]);

        $access     =   auth()->user();
        $isAssigned =   0;

        try {
            
            $succes =   Ticket::where('id', $tdata['ticket_id'])
                                ->update([
                                    'status'        =>  'in-progress',
                                    'group_id'      =>  $access->group_id,
                                    'assignee'      =>  $access->id,
                                    'updated_at'    =>  \Carbon\Carbon::now()
                                ]);

            if ($succes) {
                $isAssigned =   1;

                $tdata['assignee']      =   $this->uid;
                $tdata['created_by']    =   $this->uid;
                $tdata['status']        =   'in-progress';

                info('MODL.TK.AS2ME', [
                    'user'      =>  $this->uid,
                    'status'    =>  'assigned'
                ]);

            }

        } catch (\Throwable $th) {
            info('MODL.TK.AS2ME', [
                    'user'      =>  $this->uid,
                    'status'    =>  'error',
                ]);
            
            $isAssigned =   255;
            report($th);

        }

        info('MODL.TK.AS2ME', [
                'user'  =>  $this->uid,
                'call'  =>  'modl.tk.ahist'
            ]);

        try {
            $this->addHistory($tdata);
        } catch (\Throwable $th) {
            report($th);
        }

        return $isAssigned;
    }

    /**
     * Resolve ticket
     * 
     * @param   Object  $ticket_id
     * @return  Int     $retcode
     */
    public function resolveTicket($tdata)
    {
        info('MODL.TK.RESLV', [
            'user'      =>  $this->uid,
            'status'    =>  'init',
            'data'      =>  $tdata['ticket_id']
        ]);

        $now        =   \Carbon\Carbon::now();
        $retcode    =   0;

        try {
            Ticket::where('id', $tdata['ticket_id'])
                ->update([
                    'status'        =>  $tdata['status'],
                    'assignee'      =>  $tdata['assignee'],
                    'updated_at'    =>  $now
                ]);

            info('MODL.TK.RESLV', [
                'user'      =>  $this->uid,
                'status'    =>  'resolved'
            ]);

            $retcode    =   1;
            
        } catch (\Throwable $th) {
            info('MODL.TK.RESLV', [
                'user'      =>  $this->uid,
                'status'    =>  'error' 
            ]);
            $retcode    =   255;
            report($th);

        }

        info('MODL.TK.RESLV', [
                'user'      =>  $this->uid,
                'call'      =>  'modl.tk.ahist'
            ]);

        try {
            $this->addHistory($tdata);
        } catch (\Throwable $th) {
            report($th);
        }

        return $retcode;
    }


    /**
     * Auto close tickets resolved after
     * CONFIGS.TK_AUTO_X_DAYS (Value) days
     * 
     */
    public function autoClose()
    {
        info('MODL.TK.AUTOX > Started');
        info('MODL.TK.AUTOX > Getting auto-close date.');

        $xdate      =   Config::where('config_name', 'TK_AUTO_X_DAYS')
                            ->select(DB::raw('current_timestamp - interval configs.value day as cut_date'))
                            ->first()
                            ->cut_date;

        info('MODL.TK.AUTOX > Auto-close date is ' . $xdate . '.');
        info('MODL.TK.AUTOX > Checking tickets resolved prior ' . $xdate . '.');

        try {
            $tickets    =   DB::table('tickets as t')
                            ->leftJoin('ticket_hists as h', 'h.ticket_id', '=', 't.id')
                            ->select(
                                't.id as tkey',
                                't.status',
                                't.priority',
                                't.title',
                                't.description',
                                't.group_id as group',
                                't.assignee',
                                't.reporter',
                            )
                            ->where('t.status', 'resolved')
                            ->where('h.status', 'resolved')
                            ->where('h.created_at', '<', $xdate)
                            ->get();
            
            info('MODL.TK.AUTOX > Found ' . count($tickets) . ' tickets to auto-close.');
            
        } catch (\Throwable $th) {
            info('MODL.TK.AUTOX > ERROR: Unexpected.');
            report($th);

        }

        // Check if needs to proceed
        if (count($tickets) == 0) {
            info('MODL.TK.AUTOX > Terminating task.');
            return true;
        }

        // Process closing of tickets
        foreach ($tickets as $tk) {
            $tmp            =   $tk;
            $tmp->status    =   'closed';

            // Create history
            info('MODL.TK.AUTOX > Adding history for ' . $tmp->tkey . '.');
            try {
                DB::table('ticket_hists')
                    ->insert([
                        'ticket_id'     =>  $tmp->tkey,
                        'status'        =>  'closed',
                        'priority'      =>  $tmp->priority,
                        'title'         =>  $tmp->title,
                        'description'   =>  $tmp->description,
                        'group_id'      =>  $tmp->group,
                        'assignee'      =>  $tmp->assignee,
                        'reporter'      =>  $tmp->reporter,
                        'created_by'    =>  99999,
                        'created_at'    =>  \Carbon\Carbon::now()
                    ]);

                info('MODL.TK.AUTOX > Added history.');

            } catch (\Throwable $th) {
                info('MODL.TK.AUTOX > ERROR. Unexpected.');
                report($th);
            }

            // Update reserves
            $this->updReserves($tmp->tkey, 'X');

            // Close the ticket
            info('MODL.TK.AUTOX > Closing ticket ' . $tmp->tkey . '.');
            try {
                Ticket::where('id', $tmp->tkey)
                        ->update([
                            'status'        =>  'closed',
                            'updated_at'    =>  \Carbon\Carbon::now()
                        ]);
                info('MODL.TK.AUTOX > Ticket closed.');

            } catch (\Throwable $th) {
                info('MODL.TK.AUTOX > ERROR. Unexpected.');
                report($th);

            }

        }

    }

    /**
     * Clean-up unused reservations for 24hrs
     * 
     */
    public function cleanupReservations()
    {
        $hours  =   24;

        try {
            
            $reservations   =   DB::table('reserves')
                                    ->where('created_at', '<', DB::raw('current_timestamp - interval ' . $hours . ' hour'))
                                    ->where('category', '=', 'TICKET_KEY')
                                    ->whereNotIn('key_id', DB::table('tickets')
                                                                ->select('id'));

            $data           =   [];

            foreach ($reservations->select('key_id')->get() as $res) {
                $data[]     =   $res->key_id;
            }

            $total          =   count($data);

            $reservations->delete();

            info('RESERVES.UNUSED', [
                'deleted'   =>  $total,
                'data'      =>  $data
            ]);

        } catch (\Throwable $th) {
            info('RESERVES.UNUSED', [
                'status'    =>  'error'
            ]);

            report($th);
        }
    }

    /**
     * Update reserved key
     * 
     * @param   String  $tkey
     * @param   String  $status
     */
    protected function updReserves($tkey, $status)
    {
        info('MODL.TK.URESV', [
            'user'      =>  $this->uid,
            'status'    =>  'init',
            'data'      =>  [
                'tkey'      =>  $tkey,
                'status'    =>  $status
            ]
        ]);

        try {
            DB::table('reserves')
                ->where('category', 'TICKET_KEY')
                ->where('key_id', $tkey)
                ->update([
                    'status'    =>  $status
                ]);

            info('MODL.TK.URESV', [
                'user'      =>  $this->uid,
                'status'    =>  'updated',
                'data'      =>  []
            ]);
                
        } catch (\Throwable $th) {
            info('MODL.TK.URESV', [
                'user'      =>  $this->uid,
                'status'    =>  'error',
                'data'      =>  []
            ]);
            report($th);

        }
    }

    /**
     * Log ticket history
     * 
     * @param   Array $tdata
     */
    protected function addHistory($tdata)
    {
        info('MODL.TK.AHIST', [
            'user'      =>  $this->uid,
            'status'    =>  'init',
            'data'      =>  $tdata
        ]);

        try {

            DB::table('ticket_hists')
                ->insert([
                    'ticket_id'     =>  $tdata['ticket_id'],
                    'status'        =>  $tdata['status'],
                    'priority'      =>  $tdata['priority'],
                    'title'         =>  $tdata['title'],
                    'description'   =>  $tdata['description'],
                    'group_id'      =>  $tdata['group_id'],
                    'assignee'      =>  ($tdata['assignee'] != '') ? $tdata['assignee'] : null,
                    'reporter'      =>  $tdata['reporter'],
                    'created_by'    =>  $this->uid,
                    'created_at'    =>  \Carbon\Carbon::now()
                ]);

            info('MODL.TK.AHIST', [
                    'user'      =>  $this->uid,
                    'status'    =>  'success',
                    'data'      =>  []
                ]);

        } catch (\Throwable $th) {
            info('MODL.TK.AHIST', [
                'user'      =>  $this->uid,
                'status'    =>  'error',
                'data'      =>  []
            ]);

            report($th);

        }
    }

    /**
     * Get group's unassigned tickets
     * 
     * @param   Integer $group_id
     * @return  Object  $tickets
     */
    public function groupUnassignedTickets($group_id)
    {
        $tickets    =   Ticket::where('group_id', $group_id)
                            ->where('assignee', null)
                            ->select(
                                'tickets.id as tkey',
                                'priority',
                                'title',
                                'reporter',
                                'created_at'
                            )
                            ->orderBy('created_at')
                            ->paginate(10, ['*'], 'unassignedPage');

        return $tickets;
    }

    /**
     * Get assigned open tickets to me
     * 
     * @param   Integer $user_id
     * @return  Object  $tickets
     */
    public function openAssignedToUser($user_id)
    {
        $tickets    =   Ticket::where('assignee', $user_id)
                            ->where('status', '<>', 'resolved')
                            ->where('status', '<>', 'closed')
                            ->select(
                                'tickets.id as tkey',
                                'title',
                                'status',
                                'priority',
                                'reporter',
                                'created_at'
                            )
                            ->orderBy('created_at')
                            ->paginate(10, ['*'], 'assignedPage');

        return $tickets;
    }

    /**
     * Get all open group's tickets
     * 
     * @param   Integer $group_id
     * @return  Object  $tickets
     */
    public function groupsOpenTickets($group_id)
    {
        $tickets    =   Ticket::where('group_id', $group_id)
                            ->where('status', '<>', 'resolved')
                            ->orderBy('created_at')
                            ->paginate(10);

        return $tickets;
    }

    /**
     * Get all open tickets
     * 
     * @return  Object  $tickets
     */
    public function allOpenTickets()
    {
        $tickets    =   Ticket::where('status', '<>', 'resolved')
                            ->select(
                                DB::raw('tickets.id as tkey'),
                                'title',
                                'status',
                                'reporter',
                                'assignee',
                                'created_at',
                                'group_id'
                            )
                            ->orderBy('created_at')
                            ->paginate(10);

        return $tickets;
    }

    /**
     * Get all tickets created
     * 
     * @return  Object $tickets
     */
    public function allTickets()
    {
        $tickets    =   Ticket::select(DB::raw('tickets.id as tkey'),
                                    'title',
                                    'priority',
                                    'status',
                                    'reporter',
                                    'assignee',
                                    'created_at',
                                    'group_id'
                                )
                                ->orderBy('created_at')
                                ->paginate(10);

        return $tickets;
    }

    /**
     * Get ticket summary
     * 
     * @return  Object  $tickets
     */
    public function ticketSummary()
    {
        $tickets    =   Ticket::select(
                            'status',
                            DB::raw('count(1) as tkcount')
                        )
                        ->groupBy('status')
                        ->get();

        return $tickets;
    }

}
