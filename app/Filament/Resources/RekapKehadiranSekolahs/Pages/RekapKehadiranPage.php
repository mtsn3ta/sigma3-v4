<?php

namespace App\Filament\Resources\RekapKehadiranSekolahs\Pages;

use App\Filament\Resources\RekapKehadiranSekolahs\RekapKehadiranSekolahResource;
use Filament\Resources\Pages\Page;

class RekapKehadiranPage extends Page
{
    protected static string $resource = RekapKehadiranSekolahResource::class;

    // 1. Mengubah teks yang muncul di menu sidebar samping
    protected static ?string $navigationLabel = 'Rekap Kehadiran';

    // 2. Mengubah judul besar di atas halaman utama saat dibuka
    protected static ?string $title = 'Laporan Rekap Kehadiran Sekolah';

    // 3. Mengubah teks di breadcrumb (opsional, jika ingin beda dengan title)
    protected static ?string $breadcrumb = 'Rekap Kehadiran';

    protected string $view = 'filament.resources.rekap-kehadiran-sekolahs.pages.rekap-kehadiran-page';
}
