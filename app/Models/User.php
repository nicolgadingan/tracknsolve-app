<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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

}
