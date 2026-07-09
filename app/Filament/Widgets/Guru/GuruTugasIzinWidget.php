<?php

namespace App\Filament\Widgets\Guru;

use App\Models\TugasGuruIzin;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class GuruTugasIzinWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('guru');
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        return $table
            ->heading('Riwayat Tugas Izin (Inval)')
            ->description('Daftar instruksi tugas yang Anda titipkan saat berhalangan hadir.')
            ->query(
                TugasGuruIzin::query()
                    ->with('kelas')
                    ->where('pegawai_id', $pegawaiId)
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Tanggal & Mapel (Digabung)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Waktu & Mapel')
                    ->icon('heroicon-m-calendar-days')
                    ->weight(FontWeight::Bold)
                    ->description(fn(TugasGuruIzin $record): string => $record->mapel)
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d M Y'))
                    ->searchable(),

                // 2. Kelas & Jam (Digabung)
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas/Jam')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-academic-cap')
                    ->description(fn(TugasGuruIzin $record): string => "Jam Ke-{$record->jam_ke}"),

                // 3. Status (Badge Dinamis)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'baru'        => 'heroicon-m-sparkles',
                        'disampaikan' => 'heroicon-m-paper-airplane',
                        'selesai'     => 'heroicon-m-check-badge',
                        default       => 'heroicon-m-minus',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'baru'        => 'warning',
                        'disampaikan' => 'info',
                        'selesai'     => 'success',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'baru'        => 'Baru',
                        'disampaikan' => 'Disampaikan',
                        'selesai'     => 'Selesai',
                        default       => '-',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            // Empty State
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateHeading('Belum Ada Riwayat')
            ->emptyStateDescription('Tugas inval yang Anda buat akan muncul di sini.');
    }
}
