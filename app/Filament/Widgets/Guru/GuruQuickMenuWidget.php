<?php

namespace App\Filament\Widgets\Guru;

use App\Filament\Pages\GuruBerhalangan;
use App\Filament\Pages\JurnalMengajar;
use App\Filament\Pages\UmpanBalik;
use App\Filament\Resources\DaftarNilais\DaftarNilaiResource;
use App\Filament\Resources\PerangkatPembelajarans\PerangkatPembelajaranResource;
use App\Support\ActiveRole;
use Filament\Widgets\Widget;

class GuruQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.guru.quick-menu';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return ActiveRole::is('guru');
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Menu Cepat Guru',
            'headerIcon' => 'heroicon-o-bolt',
            'theme' => 'emerald',
            'items' => [
                [
                    'label' => 'Scan Masuk Kelas',
                    'icon' => 'heroicon-o-qr-code',
                    'url' => url('/scan-kelas'),
                ],
                [
                    'label' => 'Isi Jurnal Hari Ini',
                    'icon' => 'heroicon-o-clipboard-document-check',
                    'url' => JurnalMengajar::getUrl(['action' => 'tambah']),
                ],
                [
                    'label' => 'Riwayat Jurnal',
                    'icon' => 'heroicon-o-book-open',
                    'url' => JurnalMengajar::getUrl(),
                ],
                [
                    'label' => 'Input Nilai Siswa',
                    'icon' => 'heroicon-o-table-cells',
                    'url' => DaftarNilaiResource::getUrl('create'),
                ],
                [
                    'label' => 'Upload Perangkat',
                    'icon' => 'heroicon-o-folder-arrow-down',
                    'url' => PerangkatPembelajaranResource::getUrl('create'),
                ],
                [
                    'label' => 'Buat Tugas Inval',
                    'icon' => 'heroicon-o-document-plus',
                    'url' => GuruBerhalangan::getUrl(['action' => 'tambah']),
                ],
                [
                    'label' => 'Umpan Balik KepMad',
                    'icon' => 'heroicon-o-chat-bubble-left-ellipsis',
                    'url' => UmpanBalik::getUrl(),
                ],
            ],
        ];
    }
}
