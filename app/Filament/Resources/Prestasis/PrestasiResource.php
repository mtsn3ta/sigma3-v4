<?php

namespace App\Filament\Resources\Prestasis;

use App\Filament\Resources\Prestasis\Pages\CreatePrestasi;
use App\Filament\Resources\Prestasis\Pages\EditPrestasi;
use App\Filament\Resources\Prestasis\Pages\ListPrestasis;
use App\Filament\Resources\Prestasis\Schemas\PrestasiForm;
use App\Filament\Resources\Prestasis\Tables\PrestasisTable;
use App\Models\Prestasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrestasiResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_prestasi';
    protected static ?string $navigationLabel = 'Prestasi';

    protected static ?string $pluralModelLabel = 'Prestasi';

    protected static ?string $modelLabel = 'Prestasi';

    protected static string|\UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        /** @var User|null $user */
        $user = Auth::user();

        $query = parent::getEloquentQuery();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (
            $user->hasRole('super_admin')
            || $user->hasRole('waka_kesiswaan')
            || $user->hasRole('kepala_madrasah')
        ) {
            return $query;
        }

        return $query->whereIn(
            'ekstrakurikuler_id',
            $user->pegawai
                ? $user->pegawai
                ->pengampus()
                ->pluck('ekstrakurikuler_id')
                : []
        );
    }

    public static function form(Schema $schema): Schema
    {
        return PrestasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrestasisTable::configure($table);
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
            'index' => ListPrestasis::route('/'),
            'create' => CreatePrestasi::route('/create'),
            'edit' => EditPrestasi::route('/{record}/edit'),
        ];
    }
}
