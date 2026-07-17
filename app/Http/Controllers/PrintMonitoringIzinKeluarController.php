<?php

namespace App\Http\Controllers;

use App\Models\IzinKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintMonitoringIzinKeluarController extends Controller
{
    public function __invoke(Request $request)
    {
        $tanggalMulai = Carbon::parse(
            $request->tanggal_mulai
        );

        $tanggalSelesai = Carbon::parse(
            $request->tanggal_selesai
        );

        $data = IzinKeluar::query()

            ->with([
                'siswa.kelasAktif.kelas',
                'pegawai',
            ])

            ->whereBetween(
                'tanggal',
                [
                    $tanggalMulai,
                    $tanggalSelesai,
                ]
            )

            ->orderBy('tanggal')

            ->orderBy('jam_keluar')

            ->get()

            ->groupBy(
                fn($item) =>

                $item->tanggal->format('Y-m-d')

            )

            ->chunk(2);

        $pdf = Pdf::loadView(

            'pdf.monitoring-izin-keluar',

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
                'monitoring-izin-keluar.pdf'
            );
    }
}
