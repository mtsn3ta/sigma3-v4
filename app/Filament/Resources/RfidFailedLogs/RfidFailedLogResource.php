<?php

namespace App\Filament\Resources\RfidFailedLogs;

use App\Filament\Resources\RfidFailedLogs\Pages\ListRfidFailedLogs;
use App\Filament\Resources\RfidFailedLogs\Tables\RfidFailedLogsTable;
use App\Models\RfidFailedLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RfidFailedLogResource extends Resource
{
    protected static ?string $model = RfidFailedLog::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'error';

    protected static ?string $navigationLabel = 'Failed Scan Logs';

    protected static ?string $pluralModelLabel = 'Failed Scan Logs';

    protected static ?string $modelLabel = 'Failed Scan Log';

    protected static string|\UnitEnum|null $navigationGroup =
    'Pengaturan';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return RfidFailedLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRfidFailedLogs::route('/'),
        ];
    }
}
