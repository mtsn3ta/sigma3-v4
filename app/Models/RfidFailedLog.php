<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidFailedLog extends Model
{
    protected $table = 'rfid_failed_logs';

    public $timestamps = false;

    protected $fillable = [
        'mode',
        'uid',
        'siswa_id',
        'error',
        'message',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
