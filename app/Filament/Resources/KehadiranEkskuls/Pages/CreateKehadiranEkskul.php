<?php

namespace App\Filament\Resources\KehadiranEkskuls\Pages;

use App\Filament\Resources\KehadiranEkskuls\KehadiranEkskulResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKehadiranEkskul extends CreateRecord
{
    protected static string $resource = KehadiranEkskulResource::class;

    public function mount(): void
    {
        abort(404);
    }
}
