<?php

namespace App\Filament\Resources\PermasalahanSiswas;

use App\Filament\Resources\PermasalahanSiswas\Pages\CreatePermasalahanSiswa;
use App\Filament\Resources\PermasalahanSiswas\Pages\EditPermasalahanSiswa;
use App\Filament\Resources\PermasalahanSiswas\Pages\ListPermasalahanSiswas;
use App\Filament\Resources\PermasalahanSiswas\Schemas\PermasalahanSiswaForm;
use App\Filament\Resources\PermasalahanSiswas\Tables\PermasalahanSiswasTable;
use App\Models\PermasalahanSiswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PermasalahanSiswaResource extends Resource
{
    protected static ?string $model = PermasalahanSiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'BK';

    protected static ?string $navigationLabel = 'Permasalahan';

    protected static ?string $modelLabel = 'Permasalahan';

    protected static ?string $pluralModelLabel = 'Permasalahan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PermasalahanSiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermasalahanSiswasTable::configure($table);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->with([

                'siswa.kelasAktif.kelas',

                'pegawai',

            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermasalahanSiswas::route('/'),
            'create' => CreatePermasalahanSiswa::route('/create'),
            'edit' => EditPermasalahanSiswa::route('/{record}/edit'),
        ];
    }
}
