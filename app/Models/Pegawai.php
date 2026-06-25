<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected $fillable = [
        'user_id',
        'nama',
        'nik_nip',
        'jenis_pegawai',
        'no_hp',
        'rfid_uid',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'foto',
        'ttd_digital',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pengampus()
    {
        return $this->hasMany(
            EkstrakurikulerPengampu::class
        );
    }
}
