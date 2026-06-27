<?php

namespace App\Filament\Resources\Ekstrakurikulers;

use App\Filament\Resources\Ekstrakurikulers\Pages\CreateEkstrakurikuler;
use App\Filament\Resources\Ekstrakurikulers\Pages\EditEkstrakurikuler;
use App\Filament\Resources\Ekstrakurikulers\Pages\ListEkstrakurikulers;
use App\Filament\Resources\Ekstrakurikulers\Schemas\EkstrakurikulerForm;
use App\Filament\Resources\Ekstrakurikulers\Tables\EkstrakurikulersTable;
use App\Models\Ekstrakurikuler;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EkstrakurikulerResource extends Resource
{
    protected static ?string $model = Ekstrakurikuler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Ekstrakurikuler';

    protected static ?string $pluralModelLabel = 'Ekstrakurikuler';

    protected static ?string $modelLabel = 'Ekstrakurikuler';

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EkstrakurikulerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EkstrakurikulersTable::configure($table);
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        /** @var User|null $user */
        $user = Auth::user();

        if (
            $user->hasRole('super_admin')
            || $user->hasRole('waka_kesiswaan')
            || $user->hasRole('kepala_madrasah')
        ) {
            return $query;
        }

        if ($user->pegawai) {

            $ekstrakurikulerIds = $user->pegawai
                ->pengampus()
                ->pluck('ekstrakurikuler_id');

            return $query->whereIn('id', $ekstrakurikulerIds);
        }

        return $query->whereRaw('1 = 0');
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
            'index' => ListEkstrakurikulers::route('/'),
            'create' => CreateEkstrakurikuler::route('/create'),
            'edit' => EditEkstrakurikuler::route('/{record}/edit'),
        ];
    }
}
