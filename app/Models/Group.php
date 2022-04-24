<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    /**
     * Get all Groups with their managers
     * 
     * @return object
     */
    public function getGroups()
    {
        $groups =   DB::table('groups')
                        ->leftJoin('users', 'users.id', '=', 'groups.owner')
                        ->select('groups.id',
                                'groups.name',
                                'groups.status',
                                'groups.slug',
                                'groups.created_at',
                                'users.first_name',
                                'users.last_name')
                        ->orderBy('groups.name')
                        ->paginate(10);

        return $groups;
    }

    /**
     * Add relationship to user
     * 
     */
    public function users()
    {
        return $this->hasMany(User::class, 'group_id');
    }

    /**
     * Add relationship with tickets
     * 
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
