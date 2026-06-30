<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PermasalahanSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintMonitoringPermasalahanController extends Controller
{
    public function __invoke(Request $request)
    {
        $tanggalMulai = Carbon::parse(
            $request->tanggal_mulai
        );

        $tanggalSelesai = Carbon::parse(
            $request->tanggal_selesai
        );

        $kelas = Kelas::findOrFail(
            $request->kelas
        );

        $data = PermasalahanSiswa::query()

            ->with([
                'pegawai',
                'siswa.kelasAktif.kelas',
            ])

            ->whereBetween(
                'tanggal',
                [
                    $tanggalMulai,
                    $tanggalSelesai,
                ]
            )

            ->whereHas(
                'siswa.kelasAktif',
                fn($query) =>

                $query->where(
                    'kelas_id',
                    $kelas->id
                )
            )

            ->orderBy('tanggal')

            ->orderByDesc('poin')

            ->get()

            ->groupBy(
                fn($item) =>

                $item->tanggal->format('Y-m-d')
            )

            ->chunk(2);

        $totalPermasalahan = $data

            ->flatten()

            ->count();

        $totalSiswa = $data

            ->flatten()

            ->pluck('siswa_id')

            ->unique()

            ->count();

        $totalPoin = $data

            ->flatten()

            ->sum('poin');

        $pdf = Pdf::loadView(

            'pdf.monitoring-permasalahan',

            [

                'kelas' => $kelas,

                'tanggalMulai' => $tanggalMulai,

                'tanggalSelesai' => $tanggalSelesai,

                'data' => $data,

                'totalPermasalahan' => $totalPermasalahan,

                'totalSiswa' => $totalSiswa,

                'totalPoin' => $totalPoin,

            ]

        );

        return $pdf

            ->setPaper(
                'a4',
                'landscape'
            )

            ->stream(
                'monitoring-permasalahan.pdf'
            );
    }
}
