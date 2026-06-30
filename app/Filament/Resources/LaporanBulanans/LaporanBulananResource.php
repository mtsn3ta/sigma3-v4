<?php

namespace App\Filament\Resources\LaporanBulanans;

use App\Filament\Resources\LaporanBulanans\Pages\EditLaporanBulanan;
use App\Filament\Resources\LaporanBulanans\Pages\ListLaporanBulanans;
use App\Filament\Resources\LaporanBulanans\Pages\PreviewLaporanBulanan;
use App\Filament\Resources\LaporanBulanans\Schemas\LaporanBulananForm;
use App\Filament\Resources\LaporanBulanans\Tables\LaporanBulanansTable;
use App\Models\LaporanBulanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LaporanBulanans\RelationManagers\ApprovalLogsRelationManager;

class LaporanBulananResource extends Resource
{
    protected static ?string $model = LaporanBulanan::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel =
    'Laporan Bulanan';

    protected static ?string $modelLabel =
    'Laporan Bulanan';

    protected static ?string $pluralModelLabel =
    'Laporan Bulanan';

    protected static string|\UnitEnum|null $navigationGroup =
    'Ekstrakurikuler';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return LaporanBulananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanBulanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

            ApprovalLogsRelationManager::class,

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with([
            'ekstrakurikuler',
            'createdBy',
        ]);

        /** @var \App\Models\User|null $user */
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

            'index' => ListLaporanBulanans::route('/'),

            'edit' => EditLaporanBulanan::route('/{record}/edit'),

            'preview' => PreviewLaporanBulanan::route('/{record}/preview'),

        ];
    }
}
