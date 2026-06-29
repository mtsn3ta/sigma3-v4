<?php

namespace App\Filament\Resources\RekapKehadiranIbadahs;

use App\Filament\Resources\RekapKehadiranIbadahs\Pages\RekapKehadiranIbadahPage;
use App\Models\KehadiranIbadah;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class RekapKehadiranIbadahResource extends Resource
{
    protected static ?string $model = KehadiranIbadah::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel =
    'Rekap Kehadiran Ibadah';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 3;

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [

            'index' => RekapKehadiranIbadahPage::route('/'),

        ];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasAnyRole([
            'super_admin',
            'bk',
            'piket',
            'waka_kesiswaan',
            'kepala_madrasah',
        ]) ?? false;
    }
}
