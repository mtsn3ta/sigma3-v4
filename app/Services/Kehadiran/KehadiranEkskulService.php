<?php

namespace App\Services\Kehadiran;

use App\Models\KehadiranEkskul;
use App\Models\SesiKegiatan;
use App\Models\Siswa;

class KehadiranEkskulService
{
    public function find(
        SesiKegiatan $session,
        Siswa $student,
    ): ?KehadiranEkskul {

        return KehadiranEkskul::query()

            ->where(
                'sesi_kegiatan_id',
                $session->id,
            )

            ->where(
                'siswa_id',
                $student->id,
            )

            ->first();
    }

    public function markRfidHadir(
        KehadiranEkskul $attendance,
    ): KehadiranEkskul {

        $attendance->update([

            'status' => 'hadir',

            'sumber' => 'rfid',

            'waktu_scan' => now(),

        ]);

        return $attendance->fresh();
    }
}
