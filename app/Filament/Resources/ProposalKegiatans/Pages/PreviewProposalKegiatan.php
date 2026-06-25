<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Models\ProposalKegiatan;
use Filament\Resources\Pages\Page;

class PreviewProposalKegiatan extends Page
{
    protected static string $resource =
    ProposalKegiatanResource::class;

    protected string $view =
    'filament.proposal.preview';

    public ProposalKegiatan $record;

    public function mount(
        ProposalKegiatan $record
    ): void {

        $this->record = $record->load([

            'ekstrakurikuler',

            'createdBy',

            'peserta.siswa.kelasAktif.kelas',

            'rabs',

        ]);
    }
}
