<?php

namespace App\Models;

use App\Http\Controllers\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    protected $utils;

    public $fillable    =   [
        'name'
    ];

    public function __construct()
    {
        $this->utils    =   new Utils;
    }

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
     * Update Group details
     * 
     * @param   Array   $gdata
     * @return  Boolean $isUpdated
     */
    public function updGroup($gdata)
    {
        $isUpdated      =   false;

        try {
            
            $isUpdated  =   Group::where('id', $gdata['group_id'])
                                ->update([
                                    'name'          =>  ucwords($gdata['group_name']),
                                    'owner'         =>  $gdata['manager_id'],
                                    'updated_by'    =>  auth()->user()->id,
                                    'updated_at'    =>  \Carbon\Carbon::now()
                                ]);

            $this->utils->loggr(json_encode([
                    'data'      =>  $gdata,
                    'isUpdated' =>  $isUpdated
                ]), 0);

        } catch (\Throwable $th) {
            
            report($th);

            $this->utils->loggr(json_encode([
                    'data'      =>  $gdata,
                    'isUpdated' =>  false
                ]), 0);

        }

        return $isUpdated;
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
