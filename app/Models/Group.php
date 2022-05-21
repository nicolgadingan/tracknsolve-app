<?php

namespace App\Models;

use App\Http\Controllers\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * Create group
     * 
     * @param   Array   $data
     * @return  Int     $result
     */
    public function createGroup($data)
    {
        $result =   0;
        $userid =   auth()->user()->id;
        $group  =   new Group();

        info($data);

        try {
            $group->name        =   $data['name'];
            $group->description =   $data['description'];
            $group->status      =   'A';
            $group->owner       =   $data['owner'];
            $group->slug        =   $data['slug'];
            $group->created_by  =   $userid;
            $group->updated_by  =   $userid;

            $group->save();

            info($group);
            
            if (!empty($group)) {
                $result =   1;
            }

        } catch (\Throwable $th) {
            $result     =   255;
            report($th);

        }

        return $result;
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
                                    'slug'          =>  Str::slug($gdata['group_name']),
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
     * Delete group
     * 
     * @param   int $id
     * @return  int $isDeleted
     */
    public function delGroup($id)
    {
        $isDeleted  =   0;

        try {
            
            $delete =   Group::find($id)
                                ->delete();

            if ($delete) {
                $isDeleted  =   1;

            }

        } catch (\Throwable $th) {
            report($th);

            $isDeleted  =   255;
            
        }

        return $isDeleted;
    }

    /**
     * Check if group has members
     * 
     * @param   int $id
     * @return  int $hasMember
     */
    public function hasMember($id)
    {
        $hasMember  =   1;
        
        try {
            $members    =   User::where('group_id', $id)
                            ->get();

            $hasMember  =   count($members);

        } catch (\Throwable $th) {
            report($th);

            $hasMember  =   255;

        }

        return $hasMember;
    }

    /**
     * Check if exists
     * 
     * @param   int $id
     * @return  Boolean $exists
     */
    public function exists($id)
    {
        $exists =   0;

        try {
            $found  =   Group::find($id);
            
            if ($found != null) {
                $exists =   1;
            }
            
        } catch (\Throwable $th) {
            report($th);

            $exists =   255;

        }
        
        return $exists;
    }

    /**
     * Add relationship to user as members
     * 
     */
    public function members()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    /**
     * Add relationship to user as owner
     * 
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'owner');
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
