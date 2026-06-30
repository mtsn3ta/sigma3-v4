<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\Monitoring\MonitoringJurnalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintMonitoringJurnalController extends Controller
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

        /** @var MonitoringJurnalService $service */
        $service = app(
            MonitoringJurnalService::class
        );

        $monitoring = [];

        $tanggal = $tanggalMulai->copy();

        while ($tanggal->lte($tanggalSelesai)) {

            $timeline = $service->buildTimeline(

                $tanggal,

                $kelas->id,

            );

            $monitoring[] = [

                'tanggal' => $tanggal->copy(),

                'timeline' => $timeline,

                'totalMengajar' => collect($timeline)

                    ->where('status', 'mengajar')

                    ->count(),

                'totalIzin' => collect($timeline)

                    ->where('status', 'izin')

                    ->count(),

                'totalKosong' => collect($timeline)

                    ->where('status', 'kosong')

                    ->count(),

            ];

            $tanggal->addDay();
        }

        $pdf = Pdf::loadView(

            'pdf.monitoring-jurnal',

            [

                'kelas' => $kelas,

                'tanggalMulai' => $tanggalMulai,

                'tanggalSelesai' => $tanggalSelesai,

                'monitoring' => collect($monitoring)->chunk(2),

            ]

        );

        return $pdf

            ->setPaper(
                'a4',
                'landscape'
            )

            ->stream(
                'monitoring-jurnal.pdf'
            );
    }
}
