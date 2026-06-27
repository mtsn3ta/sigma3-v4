<?php

namespace App\Services;

use App\Models\Ekstrakurikuler;
use App\Models\SesiKegiatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SesiKegiatanGenerator
{
    public static function generate(
        int $ekstrakurikulerId,
        int $bulan,
        int $tahun,
        ?int $pelatihId = null,
    ): int {

        $ekstrakurikuler = Ekstrakurikuler::findOrFail(
            $ekstrakurikulerId
        );

        $jadwal = $ekstrakurikuler->jadwal ?? [];

        if (empty($jadwal)) {
            return 0;
        }

        $hariMap = [

            'minggu' => Carbon::SUNDAY,
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY,

        ];

        $dibuat = 0;

        DB::transaction(function () use (

            $bulan,
            $tahun,
            $jadwal,
            $hariMap,
            $ekstrakurikulerId,
            $pelatihId,
            &$dibuat

        ) {

            $tanggal = Carbon::create(
                $tahun,
                $bulan,
                1
            );

            while ($tanggal->month == $bulan) {

                foreach ($jadwal as $hari) {

                    if (
                        $tanggal->dayOfWeek !==
                        $hariMap[$hari]
                    ) {
                        continue;
                    }

                    $exists = SesiKegiatan::query()

                        ->where(
                            'ekstrakurikuler_id',
                            $ekstrakurikulerId
                        )

                        ->whereDate(
                            'tanggal',
                            $tanggal
                        )

                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    SesiKegiatan::create([

                        'ekstrakurikuler_id' =>
                        $ekstrakurikulerId,

                        'pelatih_id' =>
                        $pelatihId,

                        'tanggal' =>
                        $tanggal->toDateString(),

                        'status' =>
                        'draft',

                    ]);

                    $dibuat++;
                }

                $tanggal->addDay();
            }
        });

        return $dibuat;
    }
}
