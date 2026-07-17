<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ParentLeaveRequest;

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
    public function konseling()
    {
        return $this->hasMany(
            KonselingSiswa::class
        );
    }
    public function permasalahan()
    {
        return $this->hasMany(
            PermasalahanSiswa::class
        );
    }
    public function izinKeluar()
    {
        return $this->hasMany(
            IzinKeluar::class
        );
    }
    public function tugasGuruIzin()
    {
        return $this->hasMany(
            TugasGuruIzin::class,
            'pegawai_id'
        );
    }

    public function tugasGuruDisampaikan()
    {
        return $this->hasMany(
            TugasGuruIzin::class,
            'petugas_id'
        );
    }
    public function verifiedParentLeaveRequests(): HasMany
    {
        return $this->hasMany(
            ParentLeaveRequest::class,
            'verified_by'
        );
    }
    public function ploting()
    {
        // Menggunakan hasMany agar bisa menampilkan semua mapel yang diajar
        return $this->hasMany(PlotingGuru::class, 'pegawai_id');
    }
}
