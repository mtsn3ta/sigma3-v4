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

    public static function getPages(): array
    {
        return [
            'index' => ListSesiKegiatans::route('/'),
            'create' => CreateSesiKegiatan::route('/create'),
            'edit' => EditSesiKegiatan::route('/{record}/edit'),
        ];
    }
}
