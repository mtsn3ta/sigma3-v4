<?php

namespace App\Services;

use App\Models\Ekstrakurikuler;
use App\Models\LaporanBulanan;
use App\Support\LaporanBulananTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LaporanBulananGenerator
{
    public static function generate(
        int $ekstrakurikulerId,
        int $bulan,
        int $tahun,
        int $createdById,
    ): LaporanBulanan {

        $exists = LaporanBulanan::query()

            ->where('ekstrakurikuler_id', $ekstrakurikulerId)

            ->where('bulan', $bulan)

            ->where('tahun', $tahun)

            ->exists();

        if ($exists) {

            throw ValidationException::withMessages([

                'bulan' =>
                'Laporan bulanan pada periode tersebut sudah ada.',

            ]);
        }

        return DB::transaction(function () use (

            $ekstrakurikulerId,
            $bulan,
            $tahun,
            $createdById

        ) {

            $ekstrakurikuler = Ekstrakurikuler::findOrFail(
                $ekstrakurikulerId
            );

            $template = LaporanBulananTemplate::make(

                $ekstrakurikuler,

                $bulan,

                $tahun

            );

            return LaporanBulanan::create([

                /*
                |--------------------------------------------------------------------------
                | Identitas
                |--------------------------------------------------------------------------
                */

                'ekstrakurikuler_id' => $ekstrakurikulerId,

                'bulan' => $bulan,

                'tahun' => $tahun,

                'created_by_id' => $createdById,

                /*
                |--------------------------------------------------------------------------
                | BAB I
                |--------------------------------------------------------------------------
                */

                'pendahuluan' => $template['pendahuluan'],

                'tujuan' => $template['tujuan'],

                /*
                |--------------------------------------------------------------------------
                | BAB IV
                |--------------------------------------------------------------------------
                */

                'evaluasi' => $template['evaluasi'],

                'kendala' => $template['kendala'],

                'solusi' => $template['solusi'],

                /*
                |--------------------------------------------------------------------------
                | BAB V
                |--------------------------------------------------------------------------
                */

                'penutup' => $template['penutup'],

                /*
                |--------------------------------------------------------------------------
                | Workflow
                |--------------------------------------------------------------------------
                */

                'status' => 'draft',

            ]);
        });
    }
}
