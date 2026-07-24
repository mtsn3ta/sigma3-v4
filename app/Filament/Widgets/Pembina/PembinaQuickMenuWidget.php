<?php

namespace App\Filament\Widgets\Pembina;

use App\Filament\Resources\AnggotaEkskuls\AnggotaEkskulResource;
use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use Filament\Widgets\Widget;

class PembinaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pembina.quick-menu';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pembina');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Kontrol Ekskul',
            'headerIcon' => 'heroicon-o-command-line',
            'theme' => 'emerald',
            'items' => [
                ['label' => 'Input Kegiatan', 'icon' => 'heroicon-o-calendar-days', 'url' => SesiKegiatanResource::getUrl('create')],
                ['label' => 'Anggota Ekskul', 'icon' => 'heroicon-o-user-group', 'url' => AnggotaEkskulResource::getUrl()],
                ['label' => 'Proposal', 'icon' => 'heroicon-o-document-text', 'url' => ProposalKegiatanResource::getUrl()],
                ['label' => 'Laporan Bulanan', 'icon' => 'heroicon-o-document-chart-bar', 'url' => LaporanBulananResource::getUrl()],
            ],
        ];
    }
}
