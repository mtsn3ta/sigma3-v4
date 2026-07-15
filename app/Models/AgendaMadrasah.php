<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaMadrasah extends Model
{
    use HasFactory;

    protected $table = 'agenda_madrasahs';

    protected $fillable = [
        'tanggal',
        'tipe_agenda',
        'nama_agenda',
        'kelas_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
