<?php

namespace App\Filament\Widgets\Waka;

use App\Filament\Resources\Ekstrakurikulers\EkstrakurikulerResource;
use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use App\Filament\Resources\Prestasis\PrestasiResource;
use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Filament\Resources\RekapKehadiranSekolahs\RekapKehadiranSekolahResource;
use Filament\Widgets\Widget;

class WakaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.waka.quick-menu';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('waka_kesiswaan');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Manajemen Kesiswaan',
            'headerIcon' => 'heroicon-o-puzzle-piece',
            'theme' => 'violet',
            'items' => [
                ['label' => 'Monitoring Kehadiran', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => RekapKehadiranSekolahResource::getUrl()],
                ['label' => 'Approval Proposal', 'icon' => 'heroicon-o-document-check', 'url' => ProposalKegiatanResource::getUrl()],
                ['label' => 'Approval Laporan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => LaporanKegiatanResource::getUrl()],
                ['label' => 'Laporan Bulanan', 'icon' => 'heroicon-o-calendar-days', 'url' => LaporanBulananResource::getUrl()],
                ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => PrestasiResource::getUrl()],
                ['label' => 'Ekstrakurikuler', 'icon' => 'heroicon-o-puzzle-piece', 'url' => EkstrakurikulerResource::getUrl()],
            ],
        ];
    }
}
