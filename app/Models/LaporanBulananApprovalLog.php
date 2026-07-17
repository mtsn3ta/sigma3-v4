<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanBulananApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [

        'laporan_bulanan_id',

        'user_id',

        'aksi',

        'catatan',

    ];

    public function laporanBulanan()
    {
        return $this->belongsTo(
            LaporanBulanan::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
