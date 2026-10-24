<?php

namespace App\Filament\Resources\TugasGuruIzins;

use App\Filament\Resources\TugasGuruIzins\Pages\ListTugasGuruIzins;
use App\Filament\Resources\TugasGuruIzins\Tables\TugasGuruIzinsTable;
use App\Models\TugasGuruIzin;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TugasGuruIzinResource extends Resource
{
    protected static ?string $model = TugasGuruIzin::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel =
    'Tugas Guru Izin';

    protected static string|\UnitEnum|null $navigationGroup =
    'Guru Piket';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return TugasGuruIzinsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTugasGuruIzins::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('piket') ?? false;
    }
}
