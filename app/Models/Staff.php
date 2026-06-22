<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'nama',
        'nik_nip',
        'no_hp',
        'rfid_uid',
        'ttd_pad',
        'is_active',
    ];
}
