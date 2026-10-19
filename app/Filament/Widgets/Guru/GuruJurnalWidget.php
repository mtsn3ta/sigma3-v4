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
use Filament\Actions\Action;

class GuruJurnalWidget extends BaseWidget
{
    protected static ?int $sort = 2;

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
            ->heading('Jurnal Mengajar Terbaru')
            ->description('Rekam jejak aktivitas pembelajaran di kelas.')
            ->query(
                JurnalMengajar::query()
                    ->with('kelas')
                    ->where('pegawai_id', $pegawaiId)
                    ->latest('tanggal')
                    ->latest('jam_ke')
                    ->limit(8)
            )
            ->headerActions([
                Action::make('tambah_jurnal')
                    ->label('+ Isi Jurnal Baru')
                    ->icon('heroicon-m-plus-circle')
                    ->color('primary')
                    ->url(fn() => \App\Filament\Pages\JurnalMengajar::getUrl(['action' => 'tambah'])),

                Action::make('kelola_jurnal')
                    ->label('Lihat Semua')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->color('gray')
                    ->url(fn() => \App\Filament\Pages\JurnalMengajar::getUrl()),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Jurnal')
                    ->icon('heroicon-m-calendar-days')
                    ->weight(FontWeight::Bold)
                    ->description(fn(JurnalMengajar $record): string => str($record->materi)->limit(30))
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d M Y'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas/Jam')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-academic-cap')
                    ->description(fn(JurnalMengajar $record): string => "Jam Ke-{$record->jam_ke}"),

                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->fontFamily(FontFamily::Mono)
                    ->color('primary')
                    ->alignEnd(),
            ])
            ->actions([
                // PERBAIKAN: Ubah tombol baris menjadi jalan pintas ke halaman Jurnal juga
                Action::make('buka_halaman')
                    ->label('Buka')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('warning')
                    ->iconButton()
                    ->tooltip('Buka halaman Jurnal untuk mengedit')
                    ->url(fn() => \App\Filament\Pages\JurnalMengajar::getUrl()),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateHeading('Belum Ada Jurnal')
            ->emptyStateDescription('Mulai catat aktivitas mengajar Anda hari ini.')
            ->emptyStateActions([
                Action::make('isi_sekarang')
                    ->label('Isi Jurnal Sekarang')
                    ->icon('heroicon-m-plus-circle')
                    ->button()
                    ->color('primary')
                    ->url(fn() => \App\Filament\Pages\JurnalMengajar::getUrl(['action' => 'tambah'])),
            ]);
    }
}
