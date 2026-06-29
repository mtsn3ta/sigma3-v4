<?php

namespace App\Filament\Resources\RekapKehadiranIbadahs\Pages;

use App\Filament\Resources\RekapKehadiranIbadahs\RekapKehadiranIbadahResource;
use Filament\Resources\Pages\Page;

class RekapKehadiranIbadahPage extends Page
{
    protected static string $resource =
    RekapKehadiranIbadahResource::class;

    protected string $view =
    'filament.resources.rekap-kehadiran-ibadahs.pages.rekap-kehadiran-ibadah-page';
}
