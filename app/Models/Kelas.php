<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'status',
    ];

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    // Tambahkan relasi ini
    public function siswa()
    {
        // Menyambungkan Kelas ke Siswa melalui tabel kelas_siswa
        return $this->belongsToMany(Siswa::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            // Opsional: Hanya mengambil siswa yang statusnya aktif di kelas tersebut
            ->wherePivot('status', 'aktif');
    }

    public function getNamaAttribute(): string
    {
        return $this->nama_kelas;
    }
}
