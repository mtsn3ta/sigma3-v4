<?php

namespace App\Filament\Resources\KonselingSiswas;

use App\Filament\Resources\KonselingSiswas\Pages\CreateKonselingSiswa;
use App\Filament\Resources\KonselingSiswas\Pages\EditKonselingSiswa;
use App\Filament\Resources\KonselingSiswas\Pages\ListKonselingSiswas;
use App\Filament\Resources\KonselingSiswas\Schemas\KonselingSiswaForm;
use App\Filament\Resources\KonselingSiswas\Tables\KonselingSiswasTable;
use App\Models\KonselingSiswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KonselingSiswaResource extends Resource
{
    protected static ?string $model = KonselingSiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Konseling';

    protected static ?string $pluralModelLabel = 'Konseling';

    protected static ?string $modelLabel = 'Konseling';

    protected static string|\UnitEnum|null $navigationGroup = 'BK';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->with([

                'siswa.kelasAktif.kelas',

                'pegawai',

            ]);
    }
    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('bk') ?? false;
    }
    public static function form(Schema $schema): Schema
    {
        return KonselingSiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KonselingSiswasTable::configure($table);
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [

            'siswa.nama',

            'pegawai.nama',

            'permasalahan',

        ];
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKonselingSiswas::route('/'),
            'create' => CreateKonselingSiswa::route('/create'),
            'edit' => EditKonselingSiswa::route('/{record}/edit'),
        ];
    }
}
