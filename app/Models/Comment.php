<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable =   [
        'ticket_id'
    ];

    /**
     * Add relationship with Users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'comment_by', 'id');
    }
}
