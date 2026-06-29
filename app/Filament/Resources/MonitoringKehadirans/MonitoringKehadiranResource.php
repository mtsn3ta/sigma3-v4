<?php

namespace App\Filament\Resources\MonitoringKehadirans;

use App\Filament\Resources\MonitoringKehadirans\Pages\ListMonitoringKehadirans;
use App\Filament\Resources\MonitoringKehadirans\Tables\MonitoringKehadiransTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MonitoringKehadiranResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel =
    'Kehadiran Sekolah';

    protected static ?string $pluralModelLabel =
    'Monitoring Kehadiran';

    protected static ?string $modelLabel =
    'Monitoring Kehadiran';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function table(Table $table): Table
    {
        return MonitoringKehadiransTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonitoringKehadirans::route('/'),
        ];
    }
}
