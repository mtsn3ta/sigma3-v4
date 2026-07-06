<?php

namespace App\Filament\Widgets\Pembina;

use App\Models\EkstrakurikulerPengampu;
use App\Models\SesiKegiatan;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PembinaKegiatanWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('pembina') ?? false;
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $ekskulIds = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pembina')
            ->pluck('ekstrakurikuler_id');

        return $table
            ->heading('Log Kegiatan Ekskul')
            ->description('Pantauan sesi latihan terbaru.')
            ->query(
                SesiKegiatan::query()
                    ->with('ekstrakurikuler')
                    ->whereIn('ekstrakurikuler_id', $ekskulIds)
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Tanggal & Ekskul (Digabung)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Jurnal Sesi')
                    ->icon('heroicon-m-calendar-days')
                    ->weight(FontWeight::Bold)
                    ->description(fn(SesiKegiatan $record): string => $record->ekstrakurikuler->nama)
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d M Y'))
                    ->searchable(),

                // 2. Waktu & Status (Digabung)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status / Jam')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'selesai'     => 'success',
                        'berlangsung' => 'warning',
                        'terjadwal'   => 'info',
                        default       => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'selesai'     => 'heroicon-m-check-badge',
                        'berlangsung' => 'heroicon-m-arrow-path',
                        'terjadwal'   => 'heroicon-m-clock',
                        default       => 'heroicon-m-minus',
                    })
                    ->description(
                        fn(SesiKegiatan $record): string =>
                        $record->waktu_mulai ? Carbon::parse($record->waktu_mulai)->format('H:i') : '--:--'
                    )
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('Belum Ada Sesi')
            ->emptyStateDescription('Data sesi kegiatan akan muncul setelah dibuat.');
    }
}
