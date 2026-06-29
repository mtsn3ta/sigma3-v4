<?php

namespace App\Filament\Resources\SesiKegiatans;

use App\Filament\Resources\SesiKegiatans\Pages\CreateSesiKegiatan;
use App\Filament\Resources\SesiKegiatans\Pages\EditSesiKegiatan;
use App\Filament\Resources\SesiKegiatans\Pages\ListSesiKegiatans;
use App\Filament\Resources\SesiKegiatans\Schemas\SesiKegiatanForm;
use App\Filament\Resources\SesiKegiatans\Tables\SesiKegiatansTable;
use App\Models\SesiKegiatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SesiKegiatanResource extends Resource
{
    protected static ?string $model = SesiKegiatan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'tanggal';

    protected static ?string $navigationLabel = 'Sesi Kegiatan';

    protected static ?string $modelLabel = 'Sesi Kegiatan';

    protected static ?string $pluralModelLabel = 'Sesi Kegiatan';

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return SesiKegiatanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SesiKegiatansTable::configure($table);
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
            'index' => ListSesiKegiatans::route('/'),
            'create' => CreateSesiKegiatan::route('/create'),
            'edit' => EditSesiKegiatan::route('/{record}/edit'),
            'absensi' => Pages\Absensi::route('/{record}/absensi'),
        ];
    }
}
