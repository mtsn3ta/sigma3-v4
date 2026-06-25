<?php

namespace App\Filament\Resources\AnggotaEkskuls;

use App\Filament\Resources\AnggotaEkskuls\Pages\CreateAnggotaEkskul;
use App\Filament\Resources\AnggotaEkskuls\Pages\EditAnggotaEkskul;
use App\Filament\Resources\AnggotaEkskuls\Pages\ListAnggotaEkskuls;
use App\Filament\Resources\AnggotaEkskuls\Schemas\AnggotaEkskulForm;
use App\Filament\Resources\AnggotaEkskuls\Tables\AnggotaEkskulsTable;
use App\Models\AnggotaEkskul;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AnggotaEkskulResource extends Resource
{
    protected static ?string $model = AnggotaEkskul::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Anggota Ekskul';

    protected static ?string $modelLabel = 'Anggota Ekskul';

    protected static ?string $pluralModelLabel = 'Anggota Ekskul';

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return AnggotaEkskulForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnggotaEkskulsTable::configure($table);
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

        $user = Auth::user();
        /** @var User|null $user */
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
            return $query->whereIn(
                'ekstrakurikuler_id',
                $user->pegawai
                    ? $user->pegawai
                    ->pengampus()
                    ->pluck('ekstrakurikuler_id')
                    : []
            );
        }

        return $query->whereRaw('1 = 0');
    }
    public static function getPages(): array
    {
        return [
            'index' => ListAnggotaEkskuls::route('/'),
            'create' => CreateAnggotaEkskul::route('/create'),
            'edit' => EditAnggotaEkskul::route('/{record}/edit'),
        ];
    }
}
