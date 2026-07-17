<?php

namespace App\Filament\Resources\RekapKehadiranIbadahs\Pages;

use App\Filament\Resources\RekapKehadiranIbadahs\RekapKehadiranIbadahResource;
use Filament\Resources\Pages\Page;

class RekapKehadiranIbadahPage extends Page
{
    protected static string $resource = RekapKehadiranIbadahResource::class;

    // 1. Mengubah label di menu sidebar samping
    protected static ?string $navigationLabel = 'Rekap Kehadiran Ibadah';

    // 2. Mengubah judul besar di bagian atas halaman
    protected static ?string $title = 'Laporan Rekap Kehadiran Ibadah';

    // 3. Mengubah teks pada breadcrumb (opsional)
    protected static ?string $breadcrumb = 'Kehadiran Ibadah';

    protected string $view = 'filament.resources.rekap-kehadiran-ibadahs.pages.rekap-kehadiran-ibadah-page';
}
