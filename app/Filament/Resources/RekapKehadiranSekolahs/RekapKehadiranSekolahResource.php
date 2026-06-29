<?php

namespace App\Filament\Resources\RekapKehadiranSekolahs;

// Hapus import ListRekapKehadiranSekolahs & RekapKehadiranSekolahsTable
// Ganti dengan import Custom Page Anda
use App\Filament\Resources\RekapKehadiranSekolahs\Pages\RekapKehadiranPage;
use App\Models\KehadiranSekolah;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class RekapKehadiranSekolahResource extends Resource
{
    protected static ?string $model = KehadiranSekolah::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel =
    'Rekap Kehadiran';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 2;

    // Method table() dihapus karena tabel sudah dipisah ke komponen Livewire

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // Aktifkan kembali!
            'index' => RekapKehadiranPage::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasAnyRole([
            'super_admin',
            'bk',
            'piket',
            'waka_kesiswaan',
            'kepala_madrasah',
        ]) ?? false;
    }
}
