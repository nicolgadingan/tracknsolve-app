<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Disable timestamps
    public $timestamps      =   false;
    protected $uid;

    protected $fillable     =   [
        'category'
    ];

    public function __construct()
    {
        $this->uid       =   isset(auth()->user()->id) == '' ? 99999 : auth()->user()->id;
    }

    /**
     * Create Event
     * 
     * @param   Array   $edata
     */
    public function create($edata)
    {
        info('MODL.TK.CREAT', [
                'user'      =>  $this->uid,
                'status'    =>  'init',
                'data'      =>  $edata
            ]);
        
            try {
                $event              =   new Event();

                $event->category    =   $edata['category'];
                $event->action      =   $edata['action'];
                $event->key_id1     =   $edata['key_id1'];
                $event->key_id2     =   isset($edata['key_id2'])        == '' ? null : $edata['key_id2'];
                $event->key_id3     =   isset($edata['key_id3'])        == '' ? null : $edata['key_id3'];
                $event->description =   isset($edata['description'])    == '' ? null : $edata['description'];
                $event->event_by    =   $this->uid;
                $event->event_at    =   \Carbon\Carbon::now();

                $event->save();

                info('MODL.TK.CREAT', [
                        'user'      =>  $this->uid,
                        'status'    =>  'created',
                    ]);

            } catch (\Throwable $th) {
                info('MODL.TK.CREAT', [
                        'user'      =>  $this->uid,
                        'status'    =>  'error',
                    ]);

                report($th);

            }
    }
}
