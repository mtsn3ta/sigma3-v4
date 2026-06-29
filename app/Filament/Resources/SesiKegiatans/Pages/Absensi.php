<?php

namespace App\Filament\Resources\SesiKegiatans\Pages;

use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use App\Models\SesiKegiatan;
use Filament\Resources\Pages\Page;

class Absensi extends Page
{
    protected static string $resource = SesiKegiatanResource::class;

    protected string $view =
    'filament.resources.sesi-kegiatans.pages.absensi';

    public SesiKegiatan $record;

    public function mount(SesiKegiatan $record): void
    {
        $this->record = $record;
    }
}
