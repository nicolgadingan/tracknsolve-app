<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;

class TicketsComments extends Component
{
    public $tkey;
    public $comment;
    public $status;
    protected $comments;

    public function mount()
    {
        // $this->ckey  =   $this->ckey;
    }

    /**
     * Validate and post comment
     */
    public function postComment()
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
        return view('livewire.tickets-comments', [
            'comments'  =>  Comment::where('ticket_id', $this->tkey)
                                ->orderBy('created_at', 'desc')
                                ->paginate(5)
        ]);
    }
}
