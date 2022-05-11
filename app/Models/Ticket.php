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
     * Close ticket
     */

    /**
     * Update reserved key
     * 
     * @param   String  $tkey
     * @param   String  $status
     */
    protected function updReserves($tkey, $status)
    {
        DB::table('reserves')
            ->where('category', 'TICKET_KEY')
            ->where('key_id', $tkey)
            ->update([
                'status'    =>  $status
            ]);
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
                            ->paginate(10);

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
                            ->paginate(10);

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
