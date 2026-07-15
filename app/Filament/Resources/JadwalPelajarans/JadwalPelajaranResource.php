<?php

namespace App\Filament\Resources\JadwalPelajarans;

use App\Filament\Resources\JadwalPelajarans\Pages\CreateJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Pages\EditJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Schemas\JadwalPelajaranForm;
use App\Filament\Resources\JadwalPelajarans\Tables\JadwalPelajaransTable;
use App\Models\JadwalPelajaran;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    // SAMAKAN GRUP NAVIGASI
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    // UBAH IKON AGAR LEBIH RELEVAN
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    // BERI URUTAN (Agar tampil setelah Agenda atau Kelas)
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Jadwal Pelajaran';

    /**
     * BARU: Batasi akses resource ini hanya untuk super_admin dan waka_kurikulum,
     * konsisten dengan AgendaMadrasahResource. Menyembunyikan dari sidebar
     * sekaligus memblokir akses URL langsung untuk role lain.
     */
    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasAnyRole(['super_admin', 'waka_kurikulum']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return JadwalPelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalPelajaransTable::configure($table);
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
            'index' => \App\Filament\Resources\JadwalPelajaranResource\Pages\KelolaJadwalPelajaran::route('/'),
            'create' => CreateJadwalPelajaran::route('/create'),
            'edit' => EditJadwalPelajaran::route('/{record}/edit'),
        ];
    }
}
