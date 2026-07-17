<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class VerifikasiKehadiranIbadah extends Page
{
    protected string $view = 'filament.pages.verifikasi-kehadiran-ibadah';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel =
    'Verifikasi Ibadah Siswa';

    protected static string|\UnitEnum|null $navigationGroup =
    'Guru';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('guru') ?? false;
    }
}
