<?php

namespace App\Filament\Resources\KehadiranEkskuls;

use App\Filament\Resources\KehadiranEkskuls\Pages\EditKehadiranEkskul;
use App\Filament\Resources\KehadiranEkskuls\Pages\ListKehadiranEkskuls;
use App\Filament\Resources\KehadiranEkskuls\Schemas\KehadiranEkskulForm;
use App\Filament\Resources\KehadiranEkskuls\Tables\KehadiranEkskulsTable;
use App\Models\KehadiranEkskul;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class KehadiranEkskulResource extends Resource
{
    protected static ?string $model = KehadiranEkskul::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Kehadiran Ekskul';

    protected static ?string $modelLabel = 'Kehadiran Ekskul';

    protected static ?string $pluralModelLabel = 'Kehadiran Ekskul';

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return KehadiranEkskulForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KehadiranEkskulsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (
            $user->hasRole('super_admin') ||
            $user->hasRole('waka_kesiswaan') ||
            $user->hasRole('kepala_madrasah')
        ) {
            return $query;
        }

        if (
            $user->hasRole('pembina') ||
            $user->hasRole('pelatih')
        ) {

            $ekskulIds = $user->pegawai
                ? $user->pegawai
                ->pengampus()
                ->pluck('ekstrakurikuler_id')
                : [];

            return $query->whereHas(
                'sesiKegiatan',
                fn($q) =>
                $q->whereIn(
                    'ekstrakurikuler_id',
                    $ekskulIds
                )
            );
        }

        return $query->whereRaw('1 = 0');
    }
    public static function getPages(): array
    {
        return [
            'index' => ListKehadiranEkskuls::route('/'),
            'edit' => EditKehadiranEkskul::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
