<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Filament\Resources\Settings\Schemas\SettingForm;
use App\Filament\Resources\Settings\Tables\SettingsTable;
use App\Models\Setting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'nama_madrasah';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $pluralModelLabel = 'Settings';

    protected static ?string $modelLabel = 'Setting';

    protected static string|\UnitEnum|null $navigationGroup =
    'Pengaturan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // Hubungkan ke file SettingsTable yang sudah Anda buat
        return SettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // Langsung arahkan ke halaman Edit (Mode Single Record)
            'index' => EditSetting::route('/'),
        ];
    }
}
