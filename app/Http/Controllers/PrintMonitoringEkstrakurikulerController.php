<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerPengampu;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintMonitoringEkstrakurikulerController extends Controller
{
    public function __invoke(Request $request)
    {
        $tahunAjaran = TahunAjaran::findOrFail(
            $request->tahun_ajaran
        );

        $semester = $request->semester;

        $data = Ekstrakurikuler::query()

            ->with([

                'pengampu.pegawai',

                'anggotaEkskul' => fn($query)

                => $query->where(
                    'tahun_ajaran_id',
                    $tahunAjaran->id
                ),

            ])

            ->orderBy('nama')

            ->get();

        $totalEkskul = $data->count();

        $totalPembina = EkstrakurikulerPengampu::query()

            ->where('role', 'pembina')

            ->distinct('pegawai_id')

            ->count('pegawai_id');

        $totalPelatih = EkstrakurikulerPengampu::query()

            ->where('role', 'pelatih')

            ->distinct('pegawai_id')

            ->count('pegawai_id');

        $totalAnggota = AnggotaEkskul::query()

            ->where(
                'tahun_ajaran_id',
                $tahunAjaran->id
            )

            ->count();

        $pdf = Pdf::loadView(

            'pdf.monitoring-ekstrakurikuler',

            [

                'tahunAjaran' => $tahunAjaran,

                'semester' => $semester,

                'data' => $data,

                'totalEkskul' => $totalEkskul,

                'totalPembina' => $totalPembina,

                'totalPelatih' => $totalPelatih,

                'totalAnggota' => $totalAnggota,

            ]

        );

        return $pdf

            ->setPaper(
                'a4',
                'landscape'
            )

            ->stream(
                'monitoring-ekstrakurikuler.pdf'
            );
    }
}
