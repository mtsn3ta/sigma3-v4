<?php

namespace App\Filament\Widgets\BK;

use App\Filament\Pages\MonitoringPermasalahan;
use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use App\Filament\Resources\PermasalahanSiswas\PermasalahanSiswaResource;
use App\Filament\Resources\Siswas\SiswaResource;
use App\Support\ActiveRole;
use Filament\Widgets\Widget;

class BKQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.bk.quick-menu';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return ActiveRole::is('bk');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Menu Cepat Guru BK',
            'headerIcon' => 'heroicon-o-bolt',
            'theme' => 'violet',
            'items' => [
                [
                    'label' => 'Catat Masalah Siswa',
                    'icon' => 'heroicon-o-exclamation-triangle',
                    'url' => PermasalahanSiswaResource::getUrl('create'),
                ],
                [
                    'label' => 'Bimbingan Konseling',
                    'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                    'url' => KonselingSiswaResource::getUrl('create'),
                ],
                [
                    'label' => 'Izin Keluar Siswa',
                    'icon' => 'heroicon-o-arrow-right-start-on-rectangle',
                    'url' => IzinKeluarResource::getUrl(),
                ],
                [
                    'label' => 'Monitoring Kasus',
                    'icon' => 'heroicon-o-clipboard-document-list',
                    'url' => MonitoringPermasalahan::getUrl(),
                ],
                [
                    'label' => 'Daftar Siswa',
                    'icon' => 'heroicon-o-user-group',
                    'url' => SiswaResource::getUrl(),
                ],
            ],
        ];
    }
}
