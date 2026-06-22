<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [

            'super_admin' => [],

            'kepala_madrasah' => [

                'users.view',

                'pegawai.view',
                'siswa.view',

                'tahun_ajaran.view',
                'kelas.view',
                'kelas_siswa.view',

                'jurnal.view',
                'tugas_izin.view',

                'kehadiran_sekolah.view',
                'kehadiran_sekolah.export',

                'kehadiran_ibadah.view',
                'kehadiran_ibadah.export',

                'kehadiran_ekskul.view',

                'rekap_kehadiran.view',
                'rekap_kehadiran.export',

                'konseling.view',
                'masalah.view',

                'izin_keluar.view',

                'ekskul.view',
                'anggota.view',
                'sesi.view',

                'proposal.view',
                'proposal.approve',
                'proposal.reject',

                'laporan.view',
                'laporan.approve',
                'laporan.reject',

                'prestasi.view',
                'pengumuman.view',
                'galeri.view',

                'settings.view',
                'activity_logs.view',
                'approval_logs.view',
            ],

            'guru' => [

                'siswa.view',

                'jurnal.view',
                'jurnal.create',
                'jurnal.edit',

                'tugas_izin.view',
                'tugas_izin.create',
                'tugas_izin.edit',
            ],

            'waka_kesiswaan' => [

                'siswa.view',
                'pegawai.view',

                'kehadiran_sekolah.view',
                'kehadiran_sekolah.export',

                'kehadiran_ibadah.view',
                'kehadiran_ibadah.export',

                'rekap_kehadiran.view',
                'rekap_kehadiran.export',

                'konseling.view',
                'masalah.view',

                'izin_keluar.view',

                'ekskul.view',
                'ekskul.manage',

                'pengampu.view',
                'pengampu.manage',

                'anggota.view',

                'proposal.view',
                'proposal.review',
                'proposal.approve',
                'proposal.reject',

                'laporan.view',
                'laporan.review',
                'laporan.approve',
                'laporan.reject',

                'pengumuman.view',
                'pengumuman.create',
                'pengumuman.edit',

                'settings.view',
                'approval_logs.view',
            ],

            'waka_kurikulum' => [

                'pegawai.view',

                'tahun_ajaran.view',
                'kelas.view',
                'kelas_siswa.view',

                'jurnal.view',

                'tugas_izin.view',

                'settings.view',
                'approval_logs.view',
            ],

            'waka_humas' => [

                'pegawai.view',

                'prestasi.view',
                'prestasi.create',
                'prestasi.edit',

                'pengumuman.view',
                'pengumuman.create',
                'pengumuman.edit',

                'galeri.view',
                'galeri.create',
                'galeri.edit',

                'settings.view',
                'approval_logs.view',
            ],

            'waka_sarpras' => [

                'pegawai.view',

                'pengumuman.view',
                'pengumuman.create',
                'pengumuman.edit',

                'settings.view',
                'approval_logs.view',
            ],

            'bk' => [

                'kehadiran_sekolah.view',

                'kehadiran_ibadah.view',

                'rekap_kehadiran.view',
                'rekap_kehadiran.export',

                'konseling.view',
                'konseling.create',
                'konseling.edit',

                'masalah.view',
                'masalah.create',
                'masalah.edit',

                'izin_keluar.view',
                'izin_keluar.create',
                'izin_keluar.edit',
                'izin_keluar.print',
            ],

            'piket' => [

                'kehadiran_sekolah.view',
                'kehadiran_sekolah.manual',
                'kehadiran_sekolah.export',

                'kehadiran_ibadah.view',
                'kehadiran_ibadah.manual',
                'kehadiran_ibadah.export',

                'rekap_kehadiran.view',
                'rekap_kehadiran.export',

                'tugas_izin.distribute',

                'izin_keluar.view',
                'izin_keluar.create',
                'izin_keluar.edit',
                'izin_keluar.print',
            ],

            'pembina' => [

                'siswa.view',

                'ekskul.view',

                'pengampu.view',

                'anggota.view',
                'anggota.create',
                'anggota.edit',
                'anggota.nonaktif',

                'sesi.view',

                'kehadiran_ekskul.view',
                'kehadiran_ekskul.manage',

                'proposal.view',
                'proposal.create',
                'proposal.edit_draft',
                'proposal.submit',
                'proposal.review',
                'proposal.approve',
                'proposal.reject',

                'laporan.view',
                'laporan.create',
                'laporan.edit_draft',
                'laporan.submit',
                'laporan.review',
                'laporan.approve',
                'laporan.reject',

                'prestasi.view',
                'prestasi.create',
                'prestasi.edit',

                'pengumuman.view',
                'pengumuman.create',
                'pengumuman.edit',

                'galeri.view',
                'galeri.create',
                'galeri.edit',
            ],

            'pelatih' => [

                'siswa.view',

                'ekskul.view',

                'anggota.view',
                'anggota.create',
                'anggota.edit',
                'anggota.nonaktif',

                'sesi.view',
                'sesi.create',
                'sesi.edit',
                'sesi.delete',

                'kehadiran_ekskul.view',
                'kehadiran_ekskul.manage',

                'proposal.view',
                'proposal.create',
                'proposal.edit_draft',
                'proposal.submit',

                'laporan.view',
                'laporan.create',
                'laporan.edit_draft',
                'laporan.submit',

                'prestasi.view',
                'prestasi.create',
                'prestasi.edit',

                'pengumuman.view',
                'pengumuman.create',
                'pengumuman.edit',

                'galeri.view',
                'galeri.create',
                'galeri.edit',
            ],

            'siswa' => [

                'siswa.view',

                'prestasi.view',
                'pengumuman.view',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {

            $role = Role::findByName($roleName);

            if ($roleName === 'super_admin') {
                continue;
            }

            $role->syncPermissions($permissions);
        }
    }
}
