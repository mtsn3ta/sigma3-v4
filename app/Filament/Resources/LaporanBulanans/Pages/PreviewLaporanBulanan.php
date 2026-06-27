<?php

namespace App\Filament\Resources\LaporanBulanans\Pages;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Models\LaporanBulanan;
use Filament\Resources\Pages\Page;

class PreviewLaporanBulanan extends Page
{
    protected static string $resource = LaporanBulananResource::class;

    protected string $view =
    'filament.resources.laporan-bulanans.pages.preview-laporan-bulanan';

    public LaporanBulanan $record;

    public function mount(LaporanBulanan $record): void
    {
        $this->record = $record->load([

            'ekstrakurikuler',

            'createdBy.pegawai',

            'approvalLogs.user.pegawai',

            'revisionLogs.user',

        ]);
    }

    public function getTitle(): string
    {
        return 'Preview Laporan Bulanan';
    }
}
