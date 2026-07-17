<?php

namespace App\Services;

use App\Models\LaporanKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKegiatanPdfService
{
    public static function download(
        LaporanKegiatan $laporan
    ) {
        $laporan->load([

            'proposal',
            'proposal.ekstrakurikuler',

            'createdBy',

            'peserta.siswa.kelasAktif.kelas',

            'pendamping.pegawai',

            'realisasiAnggaran.proposalRab',

            'approvalLogs.user',

        ]);

        return Pdf::loadView(
            'pdf.laporan-kegiatan',
            [
                'record' => $laporan,
            ]
        )
            ->setPaper('a4', 'portrait')
            ->download(
                'Laporan-' .
                    str($laporan->proposal->nama_kegiatan)
                    ->slug('-')
                    . '.pdf'
            );
    }
}
