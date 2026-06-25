<?php

namespace App\Filament\Resources\ProposalKegiatans;

use App\Filament\Resources\ProposalKegiatans\Pages\CreateProposalKegiatan;
use App\Filament\Resources\ProposalKegiatans\Pages\EditProposalKegiatan;
use App\Filament\Resources\ProposalKegiatans\Pages\ListProposalKegiatans;
use App\Filament\Resources\ProposalKegiatans\Schemas\ProposalKegiatanForm;
use App\Filament\Resources\ProposalKegiatans\Tables\ProposalKegiatansTable;
use App\Models\ProposalKegiatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\ProposalKegiatans\RelationManagers\PesertaRelationManager;
use App\Filament\Resources\ProposalKegiatans\RelationManagers\RabsRelationManager;
use App\Filament\Resources\ProposalKegiatans\RelationManagers\ApprovalLogsRelationManager;
use App\Filament\Resources\ProposalKegiatans\RelationManagers\RevisionLogsRelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ProposalKegiatans\Pages\PreviewProposalKegiatan;

class ProposalKegiatanResource extends Resource
{
    protected static ?string $model = ProposalKegiatan::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute =
    'nama_kegiatan';

    protected static ?string $navigationLabel =
    'Proposal Kegiatan';

    protected static ?string $modelLabel =
    'Proposal Kegiatan';

    protected static ?string $pluralModelLabel =
    'Proposal Kegiatan';

    protected static string|\UnitEnum|null $navigationGroup =
    'Ekstrakurikuler';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return ProposalKegiatanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProposalKegiatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PesertaRelationManager::class,
            RabsRelationManager::class,
            ApprovalLogsRelationManager::class,
            RevisionLogsRelationManager::class,
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
            'index' => ListProposalKegiatans::route('/'),
            'create' => CreateProposalKegiatan::route('/create'),
            'edit' => EditProposalKegiatan::route('/{record}/edit'),

            'preview' => PreviewProposalKegiatan::route(
                '/{record}/preview'
            ),
        ];
    }
}
