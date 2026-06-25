<?php

namespace App\Services;

use App\Models\ProposalKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalPdfService
{
    public static function download(
        ProposalKegiatan $proposal
    ) {
        $proposal->load([
            'ekstrakurikuler',
            'createdBy',
            'peserta.siswa.kelasAktif.kelas',
            'rabs',
        ]);

        return Pdf::loadView(
            'pdf.proposal',
            [
                'record' => $proposal,
            ]
        )
            ->setPaper('a4', 'portrait')
            ->download(
                'Proposal-' .
                    str($proposal->nama_kegiatan)
                    ->slug('-')
                    . '.pdf'
            );
    }
}
