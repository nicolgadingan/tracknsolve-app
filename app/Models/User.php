<?php

namespace App\Models;

use App\Http\Controllers\Utils;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $utils;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function __construct()
    {
        $this->utils    =   new Utils;
    }

    /**
     * Get all users that can manage a group
     */
    public function canManage()
    {
        $users  =   User::whereIn('role', array('admin', 'manager'))
                        ->where('status', 'A')
                        ->select('id'
                                ,'first_name'
                                ,'last_name')
                        ->orderBy('first_name')
                        ->get();

        return $users;
    }

    /**
     * Get All users under managed Groups
     * 
     * @return object
     */
    public function getManangedUsers()
    {
        $uId    =   auth()->user()->id;

        $users  =   DB::table('users')
                        ->join('groups', 'groups.id', '=', 'users.group_id')
                        ->where('groups.owner', $uId)
                        ->select('users.id'
                                ,'users.role'
                                ,'users.status'
                                ,'users.first_name'
                                ,'users.last_name'
                                ,'users.username'
                                ,'users.email'
                                ,'users.contact_no'
                                ,'groups.name'
                                ,'users.slug'
                                ,'users.created_at')
                        ->orderBy('users.first_name')
                        ->paginate(15);

        return $users;
    }

    /**
     * Get All users within the organization
     * 
     * @return object
     */
    public function getAllUsers()
    {
        $users  =   DB::table('users')
                        ->leftJoin('groups', 'groups.id', '=', 'users.group_id')
                        ->select('users.id'
                                ,'users.role'
                                ,'users.status'
                                ,'users.first_name'
                                ,'users.last_name'
                                ,'users.username'
                                ,'users.email'
                                ,'users.contact_no'
                                ,'groups.name'
                                ,'users.slug'
                                ,'users.created_at')
                        ->orderBy('users.first_name')
                        ->paginate(10);

        return $users;
    }

    /**
     * Add relationship to EmailVerify
     * 
     */
    public function emailVerify()
    {
        return $this->hasOne(EmailVerify::class);
    }

    /**
     * Add relationship to Group
     * 
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Add relationship to Comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Add relationship to Tickets
     */
    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Delete user account
     * 
     * @param   object $user
     * @return  Response
     */
    public function deleteUser($user)
    {
        $delete =   false;
        $backup =   '';

        $this->utils->loggr('Action > Archiving user.', 0);

        try {
            
            // Backup user data
            $backup =   DB::table('users_archs')
                            ->insert([
                                'user_id'       =>  $user->id,
                                'role'          =>  $user->role,
                                'first_name'    =>  $user->first_name,
                                'last_name'     =>  $user->last_name,
                                'username'      =>  $user->username,
                                'group_id'      =>  $user->group_id,
                                'email'         =>  $user->email,
                                'contact_no'    =>  $user->contact_no,
                                'deleted_by'    =>  auth()->user()->id,
                                'deleted_at'    =>  \Carbon\Carbon::now()
                            ]);

        } catch (\Throwable $th) {

            report($th);
         
            $this->utils->loggr('Result > ' . $backup, 0);

        }

        $this->utils->loggr('Action > Deleting user.', 0);

        try {
            
            // Delete user data
            $delete    =   $user->delete();

        } catch (\Throwable $th) {

            report($th);
            
            $this->utils->loggr('Result > ' . $delete, 0);

        }
        
        return $delete;
    }

    /**
     * Check if account is deleted
     * 
     * @param   int $id
     * @return  object
     */
    public function isDeleted($id)
    {
        $check  =   DB::table('deleted_users')
                        ->where('user_id',  $id)
                        ->select('user_id')
                        ->first();
        
        return $check;
        
    }

    /**
     * Check if user is managing a group
     * 
     * @param   int $id
     * @return  int $managing
     */
    public function isManaging($id)
    {
        $managing   =   0;

        try {
            $groups     =   Group::where('owner', $id)
                            ->get();

        } catch (\Throwable $th) {
            report($th);

        }

        if (count($groups) > 0) {
            $managing   =   1;

        }
        
        return $managing;
    }

    /**
     * Update user account
     * 
     * @param   Array   $udata
     * @return  Boolean $updated
     */
    public function updateUser($udata)
    {
        $updated        =   false;

        try {
            
            $updated    =   User::where('id', $udata['user_id'])
                                ->update([
                                    'group_id'      =>  $udata['group_id'],
                                    'role'          =>  $udata['role'],
                                    'first_name'    =>  $udata['first_name'],
                                    'last_name'     =>  $udata['last_name'],
                                    'contact_no'    =>  $udata['contact_no'],
                                    'updated_by'    =>  auth()->user()->id,
                                    'updated_at'    =>  \Carbon\Carbon::now()
                                ]);

            $this->utils->loggr('Result > Update has been processed.', 0);

        } catch (\Throwable $th) {
            
            report($th);
            $this->utils->loggr('Result > Update failed. See logs for more details.', 0);

        }
        
        return $updated;
    }

}
