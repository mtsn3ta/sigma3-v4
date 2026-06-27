<?php

namespace App\Filament\Resources\Pengumumen;

use App\Filament\Resources\Pengumumen\Pages\CreatePengumuman;
use App\Filament\Resources\Pengumumen\Pages\EditPengumuman;
use App\Filament\Resources\Pengumumen\Pages\ListPengumumen;
use App\Filament\Resources\Pengumumen\Schemas\PengumumanForm;
use App\Filament\Resources\Pengumumen\Tables\PengumumenTable;
use App\Models\Pengumuman;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?string $pluralModelLabel = 'Pengumuman';

    protected static ?string $modelLabel = 'Pengumuman';

    protected static string|\UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 4;
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
        return PengumumanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengumumenTable::configure($table);
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
            'index' => ListPengumumen::route('/'),
            'create' => CreatePengumuman::route('/create'),
            'edit' => EditPengumuman::route('/{record}/edit'),
        ];
    }
}
