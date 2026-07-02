<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
    public function kelasAktif()
    {
        return $this->hasOne(KelasSiswa::class)

            ->where('status', 'aktif')

            ->whereHas('tahunAjaran', function ($query) {

                $query->where('aktif', true);
            });
    }
    public function getNamaKelasAktifAttribute(): ?string
    {
        return $this->kelasAktif?->kelas?->nama_kelas;
    }
    public function prestasi()
    {
        return $this->belongsToMany(

            Prestasi::class,

            'prestasi_siswa'

        )->withTimestamps();
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
    public function kehadiranSekolah()
    {
        return $this->hasMany(
            KehadiranSekolah::class
        );
    }
    public function kehadiranIbadah()
    {
        return $this->hasMany(
            KehadiranIbadah::class
        );
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where(
            'status',
            'aktif',
        );
    }
}
