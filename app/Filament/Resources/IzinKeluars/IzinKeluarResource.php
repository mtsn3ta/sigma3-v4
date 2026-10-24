<?php

namespace App\Filament\Resources\IzinKeluars;

use App\Filament\Resources\IzinKeluars\Pages\CreateIzinKeluar;
use App\Filament\Resources\IzinKeluars\Pages\EditIzinKeluar;
use App\Filament\Resources\IzinKeluars\Pages\ListIzinKeluars;
use App\Filament\Resources\IzinKeluars\Schemas\IzinKeluarForm;
use App\Filament\Resources\IzinKeluars\Tables\IzinKeluarsTable;
use App\Models\IzinKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IzinKeluarResource extends Resource
{
    protected static ?string $model = IzinKeluar::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedArrowRightStartOnRectangle;

    protected static ?string $recordTitleAttribute = 'siswa.nama';

    protected static ?string $navigationLabel = 'Izin Keluar';

    protected static ?string $modelLabel = 'Izin Keluar';

    protected static ?string $pluralModelLabel = 'Izin Keluar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (\App\Support\ActiveRole::is('bk') || ($user?->hasRole('bk') && !$user?->hasRole('piket'))) {
            return 'BK';
        }

        if (\App\Support\ActiveRole::is('piket') || $user?->hasRole('piket')) {
            return 'Guru Piket';
        }

        if ($user?->hasRole('waka_kesiswaan')) {
            return 'Kesiswaan';
        }

        return 'Guru Piket';
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('izin_keluar.view') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return IzinKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IzinKeluarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [

            'index' => ListIzinKeluars::route('/'),

            'create' => CreateIzinKeluar::route('/create'),

            'edit' => EditIzinKeluar::route('/{record}/edit'),

        ];
    }
    public static function getNavigationBadge(): ?string
    {
        $count = IzinKeluar::query()
            ->where('status_proses', 'keluar')
            ->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
