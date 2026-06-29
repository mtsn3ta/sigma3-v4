<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintKonselingController extends Controller
{
    public function __invoke(Siswa $siswa)
    {
        $siswa->load([
            'kelasAktif.kelas',
        ]);

        $konseling = $siswa->konseling()

            ->with('pegawai')

            ->orderBy('tanggal')

            ->get();

        $pdf = Pdf::loadView(
            'pdf.konseling',
            [
                'siswa' => $siswa,
                'konseling' => $konseling,
            ]
        );

        return $pdf->stream(
            'lembar-konseling-' . $siswa->nisn . '.pdf'
        );
    }
}
