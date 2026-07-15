<?php

namespace App\Filament\Resources\AgendaMadrasahs;

use App\Filament\Resources\AgendaMadrasahs\Pages\CreateAgendaMadrasah;
use App\Filament\Resources\AgendaMadrasahs\Pages\EditAgendaMadrasah;
use App\Filament\Resources\AgendaMadrasahs\Pages\ListAgendaMadrasahs;
use App\Filament\Resources\AgendaMadrasahs\Schemas\AgendaMadrasahForm;
use App\Filament\Resources\AgendaMadrasahs\Tables\AgendaMadrasahsTable;
use App\Models\AgendaMadrasah;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AgendaMadrasahResource extends Resource
{
    protected static ?string $model = AgendaMadrasah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Agenda Madrasah';
    protected static ?string $pluralModelLabel = 'Agenda Madrasah';
    protected static ?string $modelLabel = 'Agenda Madrasah';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasAnyRole(['super_admin', 'waka_kurikulum']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AgendaMadrasahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgendaMadrasahsTable::configure($table);
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
            'index' => ListAgendaMadrasahs::route('/'),
            'create' => CreateAgendaMadrasah::route('/create'),
            'edit' => EditAgendaMadrasah::route('/{record}/edit'),
        ];
    }
}
