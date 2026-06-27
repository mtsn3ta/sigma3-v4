<?php

namespace App\Filament\Resources\Galeris;

use App\Filament\Resources\Galeris\Pages\CreateGaleri;
use App\Filament\Resources\Galeris\Pages\EditGaleri;
use App\Filament\Resources\Galeris\Pages\ListGaleris;
use App\Filament\Resources\Galeris\Schemas\GaleriForm;
use App\Filament\Resources\Galeris\Tables\GalerisTable;
use App\Models\Galeri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';
    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $pluralModelLabel = 'Galeri';

    protected static ?string $modelLabel = 'Galeri';

    protected static string|\UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 3;
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
        return GaleriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalerisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

            RelationManagers\FotoRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGaleris::route('/'),
            'create' => CreateGaleri::route('/create'),
            'edit' => EditGaleri::route('/{record}/edit'),
        ];
    }
}
