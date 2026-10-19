<?php

namespace App\Filament\Resources\DaftarNilais;

use App\Filament\Resources\DaftarNilais\Pages\CreateDaftarNilai;
use App\Filament\Resources\DaftarNilais\Pages\EditDaftarNilai;
use App\Filament\Resources\DaftarNilais\Pages\ListDaftarNilais;
use App\Filament\Resources\DaftarNilais\Schemas\DaftarNilaiForm;
use App\Filament\Resources\DaftarNilais\Tables\DaftarNilaisTable;
use App\Models\DaftarNilai;
use App\Support\ActiveRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DaftarNilaiResource extends Resource
{
    protected static ?string $model = DaftarNilai::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|\UnitEnum|null $navigationGroup = 'Guru';

    protected static ?string $navigationLabel = 'Daftar Nilai Siswa';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Daftar Nilai';

    protected static ?string $pluralModelLabel = 'Daftar Nilai';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (ActiveRole::is('guru')) {
            $query->where('pegawai_id', auth()->user()->pegawai?->id);
        }

        return $query;
    }

    public static function canAccess(): bool
    {
        return ActiveRole::is('guru') || ActiveRole::is('kepala_madrasah') || ActiveRole::is('super_admin');
    }

    public static function canCreate(): bool
    {
        return ActiveRole::is('guru');
    }

    public static function canEdit(Model $record): bool
    {
        if (ActiveRole::is('super_admin')) {
            return true;
        }

        return ActiveRole::is('guru')
            && $record->pegawai_id === auth()->user()?->pegawai?->id;
    }

    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }

    public static function form(Schema $schema): Schema
    {
        return DaftarNilaiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DaftarNilaisTable::configure($table);
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
            'index' => ListDaftarNilais::route('/'),
            'create' => CreateDaftarNilai::route('/create'),
            'edit' => EditDaftarNilai::route('/{record}/edit'),
        ];
    }
}
