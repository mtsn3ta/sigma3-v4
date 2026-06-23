<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',

        'nisn',
        'nama',

        'jenis_kelamin',

        'tempat_lahir',
        'tanggal_lahir',

        'alamat',
        'no_hp',

        'foto',

        'nama_ayah',
        'nama_ibu',

        'pekerjaan_ayah',
        'pekerjaan_ibu',

        'nik_ayah',
        'nik_ibu',

        'alamat_ortu',
        'telpon_ortu',

        'nama_wali',
        'pekerjaan_wali',
        'alamat_wali',
        'telpon_wali',

        'anak_ke',
        'sekolah_asal',

        'rfid_uid',

        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }
    public function anggotaEkskul()
    {
        return $this->hasMany(AnggotaEkskul::class);
    }
    public function kehadiranEkskul()
    {
        return $this->hasMany(KehadiranEkskul::class);
    }
}
