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
use App\Support\NavigationHelper;

class IzinKeluarResource extends Resource
{
    protected static ?string $model = IzinKeluar::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedArrowRightStartOnRectangle;

    protected static ?string $recordTitleAttribute = 'siswa.nama';

    protected static ?string $navigationLabel = 'Izin Keluar';

    protected static ?string $modelLabel = 'Izin Keluar';

    protected static ?string $pluralModelLabel = 'Izin Keluar';

    public static function getNavigationGroup(): ?string
    {
        return NavigationHelper::group([
            'bk'    => 'BK',
            'piket' => 'Guru Piket',
        ]);
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
        return (string) IzinKeluar::query()
            ->where('status_proses', 'keluar')
            ->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return IzinKeluar::query()
            ->where('status_proses', 'keluar')
            ->exists()
            ? 'warning'
            : 'success';
    }
}
