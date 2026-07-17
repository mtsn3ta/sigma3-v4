<?php

namespace App\Support;

use App\Models\AnggotaEkskul;
use App\Models\KehadiranEkskul;
use App\Models\LaporanBulanan;
use App\Models\LaporanKegiatan;
use App\Models\ProposalKegiatan;
use App\Models\SesiKegiatan;
use App\Models\Prestasi;

class LaporanBulananHelper
{
    /*
    |--------------------------------------------------------------------------
    | BAB II
    |--------------------------------------------------------------------------
    */

    public static function sesi(
        LaporanBulanan $laporan
    ) {
        return SesiKegiatan::query()

            ->with('pelatih')

            ->where(
                'ekstrakurikuler_id',
                $laporan->ekstrakurikuler_id
            )

            ->whereMonth(
                'tanggal',
                $laporan->bulan
            )

            ->whereYear(
                'tanggal',
                $laporan->tahun
            )

            ->orderBy('tanggal')

            ->get();
    }

    public static function anggota(
        LaporanBulanan $laporan
    ): array {

        $sessions = self::sesi($laporan);

        $members = AnggotaEkskul::query()

            ->with('siswa')

            ->where(
                'ekstrakurikuler_id',
                $laporan->ekstrakurikuler_id
            )

            ->where(
                'status',
                'aktif'
            )

            ->orderBy('id')

            ->get();

        $attendance = KehadiranEkskul::query()

            ->whereIn(
                'sesi_kegiatan_id',
                $sessions->pluck('id')
            )

            ->get()

            ->groupBy('siswa_id')

            ->map(
                fn($rows) => $rows->keyBy('sesi_kegiatan_id')
            );

        return [

            'sessions'   => $sessions,

            'members'    => $members,

            'attendance' => $attendance,

            'statistik'  => self::statistik($laporan),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BAB III
    |--------------------------------------------------------------------------
    */

    public static function proposal(
        LaporanBulanan $laporan
    ) {
        return ProposalKegiatan::query()

            ->where(
                'ekstrakurikuler_id',
                $laporan->ekstrakurikuler_id
            )

            ->whereMonth(
                'tanggal_mulai',
                $laporan->bulan
            )

            ->whereYear(
                'tanggal_mulai',
                $laporan->tahun
            )

            ->where(
                'status',
                'approved'
            )

            ->orderBy('tanggal_mulai')

            ->get();
    }

    public static function kegiatan(
        LaporanBulanan $laporan
    ) {
        return LaporanKegiatan::query()

            ->whereHas(
                'proposal',
                function ($query) use ($laporan) {

                    $query

                        ->where(
                            'ekstrakurikuler_id',
                            $laporan->ekstrakurikuler_id
                        )

                        ->whereMonth(
                            'tanggal_mulai',
                            $laporan->bulan
                        )

                        ->whereYear(
                            'tanggal_mulai',
                            $laporan->tahun
                        )

                        ->where(
                            'status',
                            'approved'
                        );
                }
            )

            ->with('proposal')

            ->get();
    }

    public static function prestasi(
        LaporanBulanan $laporan
    ) {
        return Prestasi::query()

            ->with('siswa')

            ->where(
                'ekstrakurikuler_id',
                $laporan->ekstrakurikuler_id
            )

            ->whereMonth(
                'tanggal',
                $laporan->bulan
            )

            ->whereYear(
                'tanggal',
                $laporan->tahun
            )

            ->orderBy('tanggal')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    public static function statistik(
        LaporanBulanan $laporan
    ): array {

        $sessions = self::sesi($laporan);

        $jumlahAnggota = AnggotaEkskul::query()

            ->where(
                'ekstrakurikuler_id',
                $laporan->ekstrakurikuler_id
            )

            ->where(
                'status',
                'aktif'
            )

            ->count();

        $attendance = KehadiranEkskul::query()

            ->whereIn(
                'sesi_kegiatan_id',
                $sessions->pluck('id')
            );

        $hadir = (clone $attendance)

            ->where(
                'status',
                'hadir'
            )

            ->count();

        $totalKehadiran = $attendance->count();

        return [

            'jumlah_anggota' => $jumlahAnggota,

            'jumlah_sesi' => $sessions->count(),

            'persentase_kehadiran' => $totalKehadiran > 0
                ? round(($hadir / $totalKehadiran) * 100, 2)
                : 0,

            'jumlah_proposal' => self::proposal($laporan)->count(),

            'jumlah_kegiatan' => self::kegiatan($laporan)->count(),

            'jumlah_prestasi' => self::prestasi($laporan)->count(),

        ];
    }
}
