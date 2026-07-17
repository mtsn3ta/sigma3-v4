<?php

namespace App\Filament\Resources\PlotingGurus;

use App\Filament\Resources\PlotingGurus\Pages\CreatePlotingGuru;
use App\Filament\Resources\PlotingGurus\Pages\EditPlotingGuru;
use App\Filament\Resources\PlotingGurus\Pages\ListPlotingGurus;
use App\Filament\Resources\PlotingGurus\Schemas\PlotingGuruForm;
use App\Filament\Resources\PlotingGurus\Tables\PlotingGurusTable;
use App\Models\PlotingGuru;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlotingGuruResource extends Resource
{
    protected static ?string $model = PlotingGuru::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return PlotingGuruForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlotingGurusTable::configure($table);
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
            'index' => ListPlotingGurus::route('/'),
            'create' => CreatePlotingGuru::route('/create'),
            'edit' => EditPlotingGuru::route('/{record}/edit'),
        ];
    }
}
