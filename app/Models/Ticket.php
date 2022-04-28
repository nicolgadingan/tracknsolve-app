<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable =   [
        'id'
    ];

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
                                'status'        =>  $tdata['status'],
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
        $tdate      =   \Carbon\Carbon::now();

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
                'created_by'    =>  $tdata['caller'],
                'created_at'    =>  $tdate
            ]);
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
}
