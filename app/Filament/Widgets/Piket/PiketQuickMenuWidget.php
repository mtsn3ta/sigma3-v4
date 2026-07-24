<?php

namespace App\Filament\Widgets\Piket;

use App\Filament\Pages\VerifikasiKehadiran;
use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use App\Filament\Resources\TugasGuruIzins\TugasGuruIzinResource;
use Filament\Widgets\Widget;

class PiketQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.piket.quick-menu';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('piket');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Kontrol Piket Harian',
            'headerIcon' => 'heroicon-o-clock',
            'theme' => 'amber',
            'items' => [
                ['label' => 'Verifikasi Kehadiran', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => VerifikasiKehadiran::getUrl()],
                ['label' => 'Input Izin Keluar', 'icon' => 'heroicon-o-arrow-right-start-on-rectangle', 'url' => IzinKeluarResource::getUrl('create')],
                ['label' => 'Tugas Guru Izin', 'icon' => 'heroicon-o-academic-cap', 'url' => TugasGuruIzinResource::getUrl()],
            ],
        ];
    }
}
