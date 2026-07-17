<?php

namespace App\Http\Controllers;

use App\Models\LaporanBulanan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBulananPdfController extends Controller
{
    public function __invoke(
        LaporanBulanan $laporanBulanan
    ) {

        $laporanBulanan->load([

            'ekstrakurikuler',

            'createdBy',

            'approvalLogs.user',

        ]);

        $pdf = Pdf::loadView(

            'pdf.laporan-bulanan',

            [

                'record' => $laporanBulanan,

            ]

        )

            ->setPaper(
                'a4',
                'portrait'
            );

        return $pdf->stream(

            sprintf(

                'Laporan Bulanan %s %s %s.pdf',

                $laporanBulanan->ekstrakurikuler->nama,

                \Carbon\Carbon::create()
                    ->month($laporanBulanan->bulan)
                    ->translatedFormat('F'),

                $laporanBulanan->tahun,

            )

        );
    }
}
