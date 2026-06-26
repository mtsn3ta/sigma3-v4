<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [

        'laporan_kegiatan_id',

        'user_id',

        'action',

        'catatan',

    ];

    public function laporan()
    {
        return $this->belongsTo(
            LaporanKegiatan::class,
            'laporan_kegiatan_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
