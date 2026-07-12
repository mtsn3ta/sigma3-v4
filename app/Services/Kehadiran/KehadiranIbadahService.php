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
                // PERBAIKAN 1: Fallback tanggal default menggunakan Asia/Jakarta (WIB)
                'tanggal'  => $tanggal ?? now('Asia/Jakarta')->toDateString(),
                'jenis'    => $jenis,
            ],
            [
                'status'      => 'hadir',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                // PERBAIKAN 2: Mengunci waktu rekam jam scan ke Asia/Jakarta (WIB)
                'waktu_scan'  => now('Asia/Jakarta'),
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
                // PERBAIKAN 3: Fallback tanggal default menggunakan Asia/Jakarta (WIB)
                'tanggal'  => $tanggal ?? now('Asia/Jakarta')->toDateString(),
                'jenis'    => $jenis,
            ],
            [
                'status'      => 'menstruasi',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                // PERBAIKAN 4: Mengunci waktu rekam jam scan ke Asia/Jakarta (WIB)
                'waktu_scan'  => now('Asia/Jakarta'),
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
                // PERBAIKAN 5: Fallback tanggal default menggunakan Asia/Jakarta (WIB)
                'tanggal'  => $tanggal ?? now('Asia/Jakarta')->toDateString(),
                'jenis'    => $jenis,
            ],
            [
                'status'      => 'tidak_hadir',
                'sumber'      => 'manual',
                'petugas_id'  => $petugas->id,
                // PERBAIKAN 6: Mengunci waktu rekam jam scan ke Asia/Jakarta (WIB)
                'waktu_scan'  => now('Asia/Jakarta'),
            ]
        );
    }

    public function findToday(
        Siswa $student,
        string $jenis,
    ): ?KehadiranIbadah {

        return KehadiranIbadah::query()
            ->where('siswa_id', $student->id)
            // PERBAIKAN 7: Pengecekan data harian wajib dikunci ke Asia/Jakarta (WIB)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->where('jenis', $jenis)
            ->first();
    }

    public function markRfidHadir(
        Siswa $student,
        string $jenis,
    ): KehadiranIbadah {

        return KehadiranIbadah::create([
            'siswa_id' => $student->id,
            // PERBAIKAN 8: Tanggal pencatatan RFID wajib dikunci ke Asia/Jakarta (WIB)
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jenis' => $jenis,
            'status' => 'hadir',
            // PERBAIKAN 9: Jam scan RFID wajib dikunci ke Asia/Jakarta (WIB)
            'jam_scan' => now('Asia/Jakarta'),
            'metode' => 'rfid',
        ]);
    }
}
