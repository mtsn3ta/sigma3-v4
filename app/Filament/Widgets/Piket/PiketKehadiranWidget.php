<?php

namespace App\Filament\Widgets\Piket;

use App\Models\KehadiranSekolah;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PiketKehadiranWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('piket') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Log Kedatangan Siswa')
            ->description('Pantauan aktivitas presensi siswa secara real-time.')
            ->query(
                KehadiranSekolah::query()
                    ->with('siswa')
                    ->whereDate('tanggal', today())
                    ->latest('jam_datang')
                    ->limit(15)
            )
            ->columns([
                // 1. Nama Siswa & Jam Datang (Digabung agar ringkas)
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->description(
                        fn(KehadiranSekolah $record): string =>
                        $record->jam_datang ? Carbon::parse($record->jam_datang)->format('H:i') : '--:--'
                    )
                    ->searchable(),

                // 2. Status & Sumber (Badge premium)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status & Sumber')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'hadir'     => 'heroicon-m-check-badge',
                        'terlambat' => 'heroicon-m-clock',
                        'izin'      => 'heroicon-m-envelope-open',
                        'sakit'     => 'heroicon-m-heart',
                        'alfa'      => 'heroicon-m-x-circle',
                        default     => 'heroicon-m-minus',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'hadir'     => 'success',
                        'terlambat' => 'warning',
                        'izin'      => 'info',
                        'sakit'     => 'primary',
                        'alfa'      => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'hadir'     => 'Hadir',
                        'terlambat' => 'Terlambat',
                        'izin'      => 'Izin',
                        'sakit'     => 'Sakit',
                        'alfa'      => 'Alfa',
                        default     => '-',
                    })
                    ->description(
                        fn(KehadiranSekolah $record): string =>
                        $record->sumber === 'rfid' ? 'Sistem: RFID' : 'Sistem: Manual'
                    )
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('Belum Ada Log Masuk')
            ->emptyStateDescription('Data presensi siswa hari ini akan muncul di sini saat mereka melakukan check-in.');
    }
}
