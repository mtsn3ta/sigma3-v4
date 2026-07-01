<?php

namespace App\Filament\Resources\LaporanKegiatans;

use App\Filament\Resources\LaporanKegiatans\Pages\EditLaporanKegiatan;
use App\Filament\Resources\LaporanKegiatans\Pages\ListLaporanKegiatans;
use App\Filament\Resources\LaporanKegiatans\Schemas\LaporanKegiatanForm;
use App\Filament\Resources\LaporanKegiatans\Tables\LaporanKegiatansTable;
use App\Models\LaporanKegiatan;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LaporanKegiatans\RelationManagers\PesertaRelationManager;
use App\Filament\Resources\LaporanKegiatans\RelationManagers\PendampingRelationManager;
use App\Filament\Resources\LaporanKegiatans\RelationManagers\RealisasiAnggaranRelationManager;
use App\Filament\Resources\LaporanKegiatans\RelationManagers\ApprovalLogsRelationManager;
use App\Filament\Resources\LaporanKegiatans\Pages\PreviewLaporanKegiatan;

class LaporanKegiatanResource extends Resource
{
    protected static ?string $model = LaporanKegiatan::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel =
    'Laporan Kegiatan';

    protected static ?string $modelLabel =
    'Laporan Kegiatan';

    protected static ?string $pluralModelLabel =
    'Laporan Kegiatan';

    protected static string|\UnitEnum|null $navigationGroup =
    'Ekstrakurikuler';

    protected static ?int $navigationSort = 7;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('laporan_kegiatan.view') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return LaporanKegiatanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanKegiatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

            PesertaRelationManager::class,

            PendampingRelationManager::class,

            RealisasiAnggaranRelationManager::class,

            ApprovalLogsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [

            'index' => ListLaporanKegiatans::route('/'),

            'edit' => EditLaporanKegiatan::route('/{record}/edit'),

            'preview' => PreviewLaporanKegiatan::route('/{record}/preview'),

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
            $user->hasRole('super_admin')
            || $user->hasRole('waka_kesiswaan')
            || $user->hasRole('kepala_madrasah')
        ) {
            return $query;
        }

        if (
            $user->hasRole('pembina')
            || $user->hasRole('pelatih')
        ) {

            return $query->whereHas('proposal', function ($query) use ($user) {

                $query->whereIn(
                    'ekstrakurikuler_id',
                    $user->pegawai
                        ? $user->pegawai
                        ->pengampus()
                        ->pluck('ekstrakurikuler_id')
                        : []
                );
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
