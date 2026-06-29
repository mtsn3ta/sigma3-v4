<?php

namespace App\Filament\Resources\RekapKehadiranSekolahs\Pages;

use App\Filament\Resources\RekapKehadiranSekolahs\RekapKehadiranSekolahResource;
use Filament\Resources\Pages\Page;

class RekapKehadiranPage extends Page
{
    protected static string $resource = RekapKehadiranSekolahResource::class;

    protected string $view = 'filament.resources.rekap-kehadiran-sekolahs.pages.rekap-kehadiran-page';
}
