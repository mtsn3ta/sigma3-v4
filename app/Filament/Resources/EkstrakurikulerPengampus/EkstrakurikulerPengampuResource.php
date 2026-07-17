<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus;

use App\Filament\Resources\EkstrakurikulerPengampus\Pages\CreateEkstrakurikulerPengampu;
use App\Filament\Resources\EkstrakurikulerPengampus\Pages\ListEkstrakurikulerPengampus;
use App\Filament\Resources\EkstrakurikulerPengampus\Schemas\EkstrakurikulerPengampuForm;
use App\Filament\Resources\EkstrakurikulerPengampus\Tables\EkstrakurikulerPengampusTable;
use App\Models\EkstrakurikulerPengampu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EkstrakurikulerPengampuResource extends Resource
{
    protected static ?string $model = EkstrakurikulerPengampu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'role';

    protected static ?string $navigationLabel = 'Pengampu';

    protected static ?string $modelLabel = 'Pengampu';

    protected static ?string $pluralModelLabel = 'Pengampu';

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return EkstrakurikulerPengampuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EkstrakurikulerPengampusTable::configure($table);
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

            'index' => ListEkstrakurikulerPengampus::route('/'),

            'create' => CreateEkstrakurikulerPengampu::route('/create'),

        ];
    }
}
