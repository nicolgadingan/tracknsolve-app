<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;

class TicketsComments extends Component
{
    public $tkey;
    public $comment;
    protected $comments;

    /**
     * Pull comments from table with $tkey
     */
    public function retrieve()
    {
        $getComments    =   Comment::where('ticket_id', $this->tkey)
                                ->orderBy('created_at', 'desc')
                                ->paginate(5);
        
        $this->comments = ($getComments == null) ? [] : $getComments;
    }

    /**
     * Validate and post comment
     */
    public function post()
    {
        $this->validate([
            'comment'   =>  'required'
        ]);

        Comment::insert([
            'ticket_id'     =>  $this->tkey,
            'comments'      =>  $this->comment,
            'comment_by'    =>  auth()->user()->id,
            'created_at'    =>  \Carbon\Carbon::now()
        ]);

        $this->comment =    '';
    }

    public function render()
    {
        $this->retrieve();

        return view('livewire.tickets-comments')->with([
            'comments'  =>  $this->comments
        ]);
    }
}
