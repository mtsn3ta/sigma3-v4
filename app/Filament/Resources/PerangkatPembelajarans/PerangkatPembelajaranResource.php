<?php

namespace App\Filament\Resources\PerangkatPembelajarans;

use App\Filament\Resources\PerangkatPembelajarans\Pages\CreatePerangkatPembelajaran;
use App\Filament\Resources\PerangkatPembelajarans\Pages\EditPerangkatPembelajaran;
use App\Filament\Resources\PerangkatPembelajarans\Pages\ListPerangkatPembelajarans;
use App\Filament\Resources\PerangkatPembelajarans\Schemas\PerangkatPembelajaranForm;
use App\Filament\Resources\PerangkatPembelajarans\Tables\PerangkatPembelajaransTable;
use App\Models\PerangkatPembelajaran;
use App\Support\ActiveRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PerangkatPembelajaranResource extends Resource
{
    protected static ?string $model = PerangkatPembelajaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Guru';

    protected static ?string $modelLabel = 'Perangkat Pembelajaran';

    protected static ?string $pluralModelLabel = 'Perangkat Pembelajaran';

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
        return PerangkatPembelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerangkatPembelajaransTable::configure($table);
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
            'index' => ListPerangkatPembelajarans::route('/'),
            'create' => CreatePerangkatPembelajaran::route('/create'),
            'edit' => EditPerangkatPembelajaran::route('/{record}/edit'),
        ];
    }
}
