<?php

namespace App\Filament\Resources\LaporanKegiatans\Pages;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Resources\Pages\Page;
use App\Models\LaporanKegiatan;

class PreviewLaporanKegiatan extends Page
{
    protected static string $resource = LaporanKegiatanResource::class;

    protected string $view =
    'filament.resources.laporan-kegiatans.pages.preview-laporan-kegiatan';

    public LaporanKegiatan $record;

    public function mount(LaporanKegiatan $record): void
    {
        $this->record = $record->load([
            'proposal',
            'proposal.ekstrakurikuler',

            'peserta',
            'peserta.siswa',
            'peserta.siswa.kelasAktif.kelas',

            'pendamping',
            'pendamping.pegawai',

            'realisasiAnggaran',

            'approvalLogs.user',
        ]);
    }

    public function getTitle(): string
    {
        return 'Preview Laporan Kegiatan';
    }
}
