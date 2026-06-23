<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EkstrakurikulerPengampu extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler_pengampu';

    protected $fillable = [
        'ekstrakurikuler_id',
        'pegawai_id',
        'role',
    ];

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
