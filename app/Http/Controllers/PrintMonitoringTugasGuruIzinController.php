<?php

namespace App\Http\Controllers;

use App\Models\TugasGuruIzin;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintMonitoringTugasGuruIzinController extends Controller
{
    public function __invoke(Request $request)
    {
        $tanggalMulai = Carbon::parse(
            $request->tanggal_mulai
        );

        $tanggalSelesai = Carbon::parse(
            $request->tanggal_selesai
        );

        $data = TugasGuruIzin::query()

            ->with([
                'guru',
                'kelas',
                'petugas',
            ])

            ->whereBetween(
                'tanggal',
                [
                    $tanggalMulai->copy(),
                    $tanggalSelesai->copy(),
                ]
            )

            ->orderBy('tanggal')

            ->orderBy('kelas_id')

            ->orderBy('jam_ke')

            ->get()

            ->groupBy(
                fn($item) =>

                $item->tanggal->format('Y-m-d')

            )

            ->chunk(2);

        $pdf = Pdf::loadView(

            'pdf.monitoring-tugas-guru-izin',

            [

                'tanggalMulai'   => $tanggalMulai,

                'tanggalSelesai' => $tanggalSelesai,

                'data'           => $data,

            ]

        );

        return $pdf

            ->setPaper(
                'a4',
                'landscape'
            )

            ->stream(
                'monitoring-tugas-guru-izin.pdf'
            );
    }
}
