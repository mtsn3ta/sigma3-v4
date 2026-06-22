<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Users
            'users.view',
            'users.manage',

            // Roles
            'roles.view',
            'roles.manage',

            // Master Data
            'pegawai.view',
            'pegawai.manage',

            'siswa.view',
            'siswa.manage',

            // Akademik
            'tahun_ajaran.view',
            'tahun_ajaran.manage',

            'kelas.view',
            'kelas.manage',

            'kelas_siswa.view',
            'kelas_siswa.manage',

            // Guru
            'jurnal.view',
            'jurnal.create',
            'jurnal.edit',
            'jurnal.delete',

            'tugas_izin.view',
            'tugas_izin.create',
            'tugas_izin.edit',
            'tugas_izin.distribute',

            // Kehadiran Sekolah
            'kehadiran_sekolah.view',
            'kehadiran_sekolah.manual',
            'kehadiran_sekolah.export',

            // Kehadiran Ibadah
            'kehadiran_ibadah.view',
            'kehadiran_ibadah.manual',
            'kehadiran_ibadah.export',

            // Kehadiran Ekskul
            'kehadiran_ekskul.view',
            'kehadiran_ekskul.manage',

            // Rekap Kehadiran
            'rekap_kehadiran.view',
            'rekap_kehadiran.export',

            // BK
            'konseling.view',
            'konseling.create',
            'konseling.edit',
            'konseling.delete',

            'masalah.view',
            'masalah.create',
            'masalah.edit',
            'masalah.delete',

            'izin_keluar.view',
            'izin_keluar.create',
            'izin_keluar.edit',
            'izin_keluar.print',

            // Ekstrakurikuler
            'ekskul.view',
            'ekskul.manage',

            'pengampu.view',
            'pengampu.manage',

            'anggota.view',
            'anggota.create',
            'anggota.edit',
            'anggota.nonaktif',

            'sesi.view',
            'sesi.create',
            'sesi.edit',
            'sesi.delete',

            // Proposal
            'proposal.view',
            'proposal.create',
            'proposal.edit_draft',
            'proposal.submit',
            'proposal.review',
            'proposal.approve',
            'proposal.reject',

            // Laporan
            'laporan.view',
            'laporan.create',
            'laporan.edit_draft',
            'laporan.submit',
            'laporan.review',
            'laporan.approve',
            'laporan.reject',

            // Publikasi
            'prestasi.view',
            'prestasi.create',
            'prestasi.edit',
            'prestasi.delete',

            'pengumuman.view',
            'pengumuman.create',
            'pengumuman.edit',
            'pengumuman.delete',

            'galeri.view',
            'galeri.create',
            'galeri.edit',
            'galeri.delete',

            // System
            'settings.view',
            'settings.manage',

            'activity_logs.view',

            'approval_logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
