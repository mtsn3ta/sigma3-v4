<?php

namespace App\Filament\Widgets\Guru;

use App\Models\JurnalMengajar;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class GuruJurnalWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('guru') ?? false;
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        return $table
            ->heading('Jurnal Mengajar Terbaru')
            ->description('Rekam jejak aktivitas pembelajaran di kelas.')
            ->query(
                JurnalMengajar::query()
                    ->with('kelas')
                    ->where('pegawai_id', $pegawaiId)
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Tanggal & Materi (Digabung agar lebih lega)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Jurnal')
                    ->icon('heroicon-m-calendar-days')
                    ->weight(FontWeight::Bold)
                    ->description(fn(JurnalMengajar $record): string => str($record->materi)->limit(30))
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d M Y'))
                    ->searchable(),

                // 2. Kelas & Jam Ke (Digabung dalam format digital)
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas/Jam')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-academic-cap')
                    ->description(fn(JurnalMengajar $record): string => "Jam Ke-{$record->jam_ke}"),

                // 3. Mata Pelajaran
                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->fontFamily(FontFamily::Mono)
                    ->color('primary')
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            // Tampilan saat guru belum mengisi jurnal
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateHeading('Belum Ada Jurnal')
            ->emptyStateDescription('Mulai catat aktivitas mengajar Anda hari ini.');
    }
}
