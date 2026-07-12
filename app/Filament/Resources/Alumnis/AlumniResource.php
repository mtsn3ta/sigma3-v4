<?php

namespace App\Filament\Resources\Alumnis;

use App\Filament\Resources\Alumnis\Pages\ListAlumnis;
use App\Filament\Resources\Alumnis\Tables\AlumnisTable;
use App\Models\Siswa;
use App\Support\ActiveRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AlumniResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Alumni';

    protected static ?string $pluralModelLabel = 'Alumni';

    protected static ?string $modelLabel = 'Alumni';
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 15;

    protected static array $hiddenForRoles = [
        'guru',
        'pembina',
        'pelatih',
        'piket',
    ];

    public static function shouldRegisterNavigation(): bool
    {
        foreach (static::$hiddenForRoles as $role) {
            if (ActiveRole::is($role)) {
                return false;
            }
        }

        return true;
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function table(Table $table): Table
    {
        return AlumnisTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->where(
                'status',
                'lulus',
            );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlumnis::route('/'),
        ];
    }
}
