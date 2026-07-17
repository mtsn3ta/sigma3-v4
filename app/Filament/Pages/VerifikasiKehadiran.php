<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerifikasiKehadiran extends Page
{
    protected string $view = 'filament.pages.verifikasi-kehadiran';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel =
    'Verifikasi Kehadiran';

    protected static string|\UnitEnum|null $navigationGroup =
    'Guru Piket';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('piket') ?? false;
    }
}
