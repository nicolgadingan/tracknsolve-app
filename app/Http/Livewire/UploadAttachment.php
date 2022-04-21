<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class UploadAttachment extends Component
{
    use WithFileUploads;

    public $tkey;
    public $attachment;
    public $filepath;

    public function save()
    {
        // Validate file attributes
        $this->validate([
            'attachment'    =>  'mimes:pdf,docx,xlsx,jpg,png|max:2048'
        ]);

        // Get filename
        $filename   =   $this->attachment->getClientOriginalName();

        $this->filepath = $this->attachment->storePubliclyAs('att/' . $this->tkey 
                                                            ,$filename
                                                            ,'public');

        DB::table('ticket_atts')->insert([
            'ticket_id'     =>  $this->tkey,
            'att_path'      =>  $this->filepath,
            'created_by'    =>  auth()->user()->id
        ]);

        $this->attachment   =   '';
    }

    public function render()
    {
        return view('livewire.upload-attachment');
    }
}
