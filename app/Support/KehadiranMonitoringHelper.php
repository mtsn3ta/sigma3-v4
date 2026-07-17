<?php

namespace App\Support;

use App\Models\JurnalMengajar;

class KehadiranMonitoringHelper
{
    public static function format(
        JurnalMengajar $jurnal,
    ): string {

        $list = [];

        foreach ($jurnal->kehadiranPembelajaran as $absen) {

            if ($absen->status === 'hadir') {
                continue;
            }

            $kode = match ($absen->status) {

                'izin' => 'I',

                'sakit' => 'S',

                'alpa' => 'A',

                'bolos' => 'B',

                default => '?',
            };

            $list[] =
                $absen->siswa->nama .
                " ({$kode})";
        }

        return count($list)

            ? implode(', ', $list)

            : '-';
    }
}
