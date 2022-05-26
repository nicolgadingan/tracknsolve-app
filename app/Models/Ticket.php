<?php

namespace App\Models;

use App\Http\Controllers\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    protected $utils;

    use HasFactory;

    protected $fillable =   [
        'id'
    ];

    public function __construct()
    {
        $this->utils    =   new Utils;
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
        $tdate      =   \Carbon\Carbon::now();

        $isCreated  =   Ticket::insert([
                                'id'            =>  $tdata['tkey'],
                                'status'        =>  ($tdata['assignee'] != '') ? 'in-progress' : $tdata['status'],
                                'priority'      =>  $tdata['priority'],
                                'title'         =>  $tdata['title'],
                                'description'   =>  $tdata['description'],
                                'group_id'      =>  $tdata['group'],
                                'assignee'      =>  ($tdata['assignee'] != '') ? $tdata['assignee'] : null,
                                'reporter'      =>  $tdata['caller'],
                                'created_at'    =>  $tdate
                            ]);
        
        $this->addHistory($tdata);

        $this->updReserves($tdata['tkey'], 'P');

        return  $isCreated;
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
                                'group_id'      =>  $tdata['group'],
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
        $access     =   auth()->user();
        $now        =   \Carbon\Carbon::now();
        $isAssigned =   0;

        try {
            
            $succes =   Ticket::where('id', $tdata['id'])
                                ->update([
                                    'status'        =>  'in-progress',
                                    'group_id'      =>  $access->group_id,
                                    'assignee'      =>  $access->id,
                                    'updated_at'    =>  $now
                                ]);

            if ($succes) {
                $isAssigned =   1;

                $this->addHistory([
                    'ticket_id'     =>  $tdata['id'],
                    'status'        =>  $tdata['status'],
                    'priority'      =>  $tdata['priority'],
                    'title'         =>  $tdata['title'],
                    'description'   =>  $tdata['description'],
                    'group_id'      =>  $tdata['group_id'],
                    'assignee'      =>  $access->id,
                    'reporter'      =>  $tdata['reporter'],
                    'created_by'    =>  $access->id,
                    'created_at'    =>  $now
                ]);

            }

        } catch (\Throwable $th) {
            
            $isAssigned =   255;
            report($th);

        }

        return $isAssigned;
    }

    /**
     * Resolve ticket
     * 
     * @param   Object  $tdata
     * @return  Int     $retcode
     */
    public function resolveTicket($tdata)
    {
        $now        =   \Carbon\Carbon::now();
        $retcode    =   0;

        try {
            $isUpdated  =   Ticket::where('id', $tdata['tkey'])
                            ->update([
                                'status'        =>  $tdata['status'],
                                'assignee'      =>  $tdata['assignee'],
                                'updated_at'    =>  $now
                            ]);

            if ($isUpdated) {
                $retcode    =   1;
                $this->addHistory($tdata);

            }
            
        } catch (\Throwable $th) {
            $retcode    =   255;
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
        info('TICKETS.AUTOCLOSE > Started');
        info('TICKETS.AUTOCLOSE > Getting auto-close date.');

        $xdate      =   Config::where('config_name', 'TK_AUTO_X_DAYS')
                            ->select(DB::raw('current_timestamp - interval configs.value day as cut_date'))
                            ->first()
                            ->cut_date;

        info('TICKETS.AUTOCLOSE > Auto-close date is ' . $xdate . '.');
        info('TICKETS.AUTOCLOSE > Checking tickets resolved prior ' . $xdate . '.');

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
            
            info('TICKETS.AUTOCLOSE > Found ' . count($tickets) . ' tickets to auto-close.');
            
        } catch (\Throwable $th) {
            info('TICKETS.AUTOCLOSE > ERROR: Unexpected.');
            report($th);

        }

        // Check if needs to proceed
        if (count($tickets) == 0) {
            info('TICKETS.AUTOCLOSE > Terminating task.');
            return true;
        }

        // Process closing of tickets
        foreach ($tickets as $tk) {
            $tmp            =   $tk;
            $tmp->status    =   'closed';

            // Create history
            info('TICKETS.AUTOCLOSE > Adding history for ' . $tmp->tkey . '.');
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

                info('TICKETS.AUTOCLOSE > Added history.');

            } catch (\Throwable $th) {
                info('TICKETS.AUTOCLOSE > ERROR. Unexpected.');
                report($th);
            }

            // Update reserves
            $this->updReserves($tmp->tkey, $tmp->status);

            // Close the ticket
            info('TICKETS.AUTOCLOSE > Closing ticket ' . $tmp->tkey . '.');
            try {
                Ticket::where('id', $tmp->tkey)
                        ->update([
                            'status'        =>  'closed',
                            'updated_at'    =>  \Carbon\Carbon::now()
                        ]);
                info('TICKETS.AUTOCLOSE > Ticket closed.');

            } catch (\Throwable $th) {
                info('TICKETS.AUTOCLOSE > ERROR. Unexpected.');
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
        $this->utils->loggr('TICKETS.UPDATERESERVES', 1);

        $this->utils->loggr('Action > Updating ticket ' . $tkey . ' to ' . $status . '.' , 0);

        try {
            DB::table('reserves')
                ->where('category', 'TICKET_KEY')
                ->where('key_id', $tkey)
                ->update([
                    'status'    =>  $status
                ]);

            $this->utils->loggr('Result > Done.' , 0);
                
        } catch (\Throwable $th) {
            $this->utils->loggr('Result > ERROR. Unexpected.' , 0);
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
        try {
            
            DB::table('ticket_hists')
                ->insert([
                    'ticket_id'     =>  $tdata['tkey'],
                    'status'        =>  $tdata['status'],
                    'priority'      =>  $tdata['priority'],
                    'title'         =>  $tdata['title'],
                    'description'   =>  $tdata['description'],
                    'group_id'      =>  $tdata['group'],
                    'assignee'      =>  ($tdata['assignee'] != '') ? $tdata['assignee'] : null,
                    'reporter'      =>  $tdata['caller'],
                    'created_by'    =>  auth()->user()->id,
                    'created_at'    =>  \Carbon\Carbon::now()
                ]);

        } catch (\Throwable $th) {
            
            $this->utils->loggr('TICKETS.ADDHIST', 1);
            $this->utils->loggr(json_encode([
                                    'data'  =>  $tdata,
                                    'error' =>  $th
                                ]), 0);

            return false;

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
