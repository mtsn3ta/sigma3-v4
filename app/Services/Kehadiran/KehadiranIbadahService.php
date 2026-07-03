<?php

namespace App\Services\Kehadiran;

use App\Models\KehadiranIbadah;
use App\Models\Pegawai;
use App\Models\Siswa;

class KehadiranIbadahService
{
    public function markManualHadir(
        Siswa $siswa,
        string $jenis,
        Pegawai $petugas,
        ?string $tanggal = null,
    ): KehadiranIbadah {

        return KehadiranIbadah::updateOrCreate(

            [
                'siswa_id' => $siswa->id,
                'tanggal'  => $tanggal ?? today()->toDateString(),
                'jenis'    => $jenis,
            ],

            [
                'status'      => 'hadir',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                'waktu_scan'  => now(),
            ]

        );
    }

    public function markMenstruasi(
        Siswa $siswa,
        string $jenis,
        Pegawai $petugas,
        ?string $tanggal = null,
    ): KehadiranIbadah {

        return KehadiranIbadah::updateOrCreate(

            [
                'siswa_id' => $siswa->id,
                'tanggal'  => $tanggal ?? today()->toDateString(),
                'jenis'    => $jenis,
            ],

            [
                'status'      => 'menstruasi',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                'waktu_scan'  => now(),
            ]

        );
    }

    public function markTidakHadir(
        Siswa $siswa,
        string $jenis,
        Pegawai $petugas,
        ?string $tanggal = null,
    ): KehadiranIbadah {

        return KehadiranIbadah::updateOrCreate(

            [
                'siswa_id' => $siswa->id,
                'tanggal'  => $tanggal ?? today()->toDateString(),
                'jenis'    => $jenis,
            ],

            [
                'status'      => 'tidak_hadir',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                'waktu_scan'  => now(),
            ]

        );
    }

    public function findToday(
        Siswa $student,
        string $jenis,
    ): ?KehadiranIbadah {

        return KehadiranIbadah::query()

            ->where('siswa_id', $student->id)

            ->whereDate('tanggal', today())

            ->where('jenis', $jenis)

            ->first();
    }
    public function markRfidHadir(
        Siswa $student,
        string $jenis,
    ): KehadiranIbadah {

        return KehadiranIbadah::create([

            'siswa_id' => $student->id,

            'tanggal' => today(),

            'jenis' => $jenis,

            'status' => 'hadir',

            'jam_scan' => now(),

            'metode' => 'rfid',

        ]);
    }
}
