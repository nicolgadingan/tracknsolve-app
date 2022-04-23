<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadAttachment extends Component
{
    use WithFileUploads;

    public $tkey;
    public $attachment;
    public $filepath;
    public $files;
    public $xfile;
    public $notif;

    public function mount()
    {
        $this->files    =   [];
        $this->xfile    =   '';
    }

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

        $this->retrieve();
    }

    /**
     * Retrieves attachments
     * 
     */
    public function retrieve()
    {
        $this->files  =   DB::table('ticket_atts')
                            ->where('ticket_id', $this->tkey)
                            ->get();
    }

    /**
     * Delete attachment
     * 
     */
    public function delatt()
    {
        // Check if the $xfile is empty
        if ($this->xfile != '') {
            // Delete from storage
            Storage::delete($this->xfile);

            // Delete from database
            DB::table('ticket_atts')
                ->where('id', $this->xfile)
                ->where('ticket_id', $this->tkey)
                ->delete();
        } else {
            $this->notif    =   'No file to delete.';
        }

        $this->retrieve();
    }

    /**
     * Renders the data to blade
     * 
     */
    public function render()
    {
        $this->retrieve();

        return view('livewire.upload-attachment');
    }
}
