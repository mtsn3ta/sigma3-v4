<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintPermasalahanController extends Controller
{
    public function __invoke(Siswa $siswa)
    {
        $siswa->load([
            'kelasAktif.kelas',
        ]);

        $permasalahan = $siswa->permasalahan()

            ->with('pegawai')

            ->orderBy('tanggal')

            ->get();

        $totalPoin = $permasalahan->sum('poin');

        $pdf = Pdf::loadView(
            'pdf.permasalahan',
            [

                'siswa' => $siswa,

                'permasalahan' => $permasalahan,

                'totalPoin' => $totalPoin,

            ]
        );

        return $pdf->stream(
            'lembar-permasalahan-' . $siswa->nisn . '.pdf'
        );
    }
}
