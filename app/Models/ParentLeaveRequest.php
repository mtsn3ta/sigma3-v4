<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentLeaveRequest extends Model
{
    protected $fillable = [

        'siswa_id',

        'jenis',

        'tanggal_mulai',

        'tanggal_selesai',

        'keterangan',

        'nama_pengirim',

        'hubungan',

        'punya_surat_dokter',

        'doctor_letter',

        'parent_selfie',

        'parent_signature',

        'status',

        'verified_by',

        'verified_at',

        'verification_note',

    ];

    protected $casts = [

        'tanggal_mulai' => 'date',

        'tanggal_selesai' => 'date',

        'verified_at' => 'datetime',

        'punya_surat_dokter' => 'boolean',

    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class
        );
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            Pegawai::class,
            'verified_by',
        );
    }
}
