<?php

namespace App\Filament\Widgets\Pelatih;

use App\Filament\Resources\AnggotaEkskuls\AnggotaEkskulResource;
use App\Filament\Resources\Prestasis\PrestasiResource;
use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use Filament\Widgets\Widget;

class PelatihQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pelatih.quick-menu';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pelatih');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Kontrol Pelatih',
            'headerIcon' => 'heroicon-o-presentation-chart-line',
            'theme' => 'sky',
            'items' => [
                ['label' => 'Input Latihan', 'icon' => 'heroicon-o-calendar-days', 'url' => SesiKegiatanResource::getUrl('create')],
                ['label' => 'Absensi Latihan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => SesiKegiatanResource::getUrl()],
                ['label' => 'Anggota', 'icon' => 'heroicon-o-user-group', 'url' => AnggotaEkskulResource::getUrl()],
                ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => PrestasiResource::getUrl()],
            ],
        ];
    }
}
