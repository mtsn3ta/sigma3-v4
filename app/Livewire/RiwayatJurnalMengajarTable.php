<?php

namespace App\Livewire;

use App\Models\JurnalMengajar;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatJurnalMengajarTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[On('refresh-riwayat-jurnal')]
    public function refreshTable(): void
    {
        // sengaja kosong
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JurnalMengajar::query()
                    ->with(['kelas', 'kehadiranPembelajaran'])
                    ->where('pegawai_id', Auth::user()->pegawai->id)
                    ->latest('tanggal')
                    ->latest('jam_ke')
            )

            // === Kelompokkan per tanggal, biar guru langsung lihat batas antar hari ===
            // SESUDAH (fix)
            ->groups([
                Group::make('tanggal')
                    ->label('Tanggal')
                    // TIDAK pakai ->date() lagi — biarkan grouping berdasarkan raw value kolom 'tanggal',
                    // judul yang ditampilkan sepenuhnya dari getTitleFromRecordUsing di bawah.
                    ->getTitleFromRecordUsing(
                        fn(JurnalMengajar $record) =>
                        $record->tanggal->isToday()
                            ? 'Hari Ini — ' . $record->tanggal->locale('id')->translatedFormat('l, d F Y')
                            : $record->tanggal->locale('id')->translatedFormat('l, d F Y')
                    )
                    ->collapsible(),
            ])
            ->defaultGroup('tanggal')

            ->columns([

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // sudah terwakili oleh grup, sembunyikan default biar tidak dobel

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jam_ke')
                    ->label('Jam')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn($state) => "Jam ke-{$state}")
                    ->alignCenter(),

                // === Mapel + preview materi di bawahnya, biar tidak perlu buka Edit ===
                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mapel & Materi')
                    ->weight('bold')
                    ->searchable()
                    ->description(
                        fn(JurnalMengajar $record) =>
                        \Illuminate\Support\Str::limit($record->materi ?: '(materi belum diisi)', 70)
                    )
                    ->wrap(),

                // === Diubah dari angka mentah jadi status yang bermakna ===
                Tables\Columns\TextColumn::make('kehadiran_pembelajaran_count')
                    ->label('Kehadiran')
                    ->counts('kehadiranPembelajaran')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state == 0 ? 'Semua Hadir' : "{$state} Tidak Ikut")
                    ->color(fn($state) => $state == 0 ? 'success' : 'danger')
                    ->icon(fn($state) => $state == 0 ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle')
                    ->alignCenter(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Kelas')
                    ->native(false),

                // === Diubah dari 1 titik tanggal jadi rentang, plus shortcut umum ===
                Tables\Filters\Filter::make('rentang_tanggal')
                    ->label('Rentang Tanggal')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn($q, $v) => $q->whereDate('tanggal', '>=', $v))
                            ->when($data['sampai'], fn($q, $v) => $q->whereDate('tanggal', '<=', $v));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indikator = [];
                        if ($data['dari'] ?? null) {
                            $indikator[] = 'Dari: ' . Carbon::parse($data['dari'])->translatedFormat('d M Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indikator[] = 'Sampai: ' . Carbon::parse($data['sampai'])->translatedFormat('d M Y');
                        }
                        return $indikator;
                    }),

                // Shortcut satu-klik untuk kasus paling umum
                Tables\Filters\Filter::make('minggu_ini')
                    ->label('Minggu Ini')
                    ->toggle()
                    ->query(fn($query) => $query->whereBetween('tanggal', [
                        now('Asia/Jakarta')->startOfWeek(),
                        now('Asia/Jakarta')->endOfWeek(),
                    ])),

                Tables\Filters\Filter::make('ada_tidak_hadir')
                    ->label('Hanya yang Ada Siswa Tidak Ikut')
                    ->toggle()
                    ->query(fn($query) => $query->has('kehadiranPembelajaran')),

            ])
            ->filtersFormColumns(3)

            ->actions([

                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->button()
                    ->tooltip('Ubah jurnal ini')
                    ->action(function (JurnalMengajar $record) {
                        $this->dispatch('edit-jurnal-mengajar', id: $record->id);
                    }),

                Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->button()
                    ->tooltip('Hapus jurnal ini')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Jurnal Mengajar')
                    ->modalDescription('Jurnal mengajar beserta data ketidakhadiran pembelajaran akan dihapus. Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->action(function (JurnalMengajar $record) {
                        $record->delete();
                        $this->dispatch('refresh-riwayat-jurnal');
                    }),

            ])

            // === Entry point isi jurnal baru langsung dari halaman riwayat ===
            ->headerActions([
                Action::make('tambahJurnal')
                    ->label('Isi Jurnal Baru')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->action(fn() => $this->dispatch('tambah-jurnal')),
            ])

            // === Empty state yang mengarahkan aksi, bukan cuma teks kosong ===
            ->emptyStateHeading('Belum Ada Jurnal Mengajar')
            ->emptyStateDescription('Jurnal yang sudah Anda isi akan muncul di sini, dikelompokkan per tanggal.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Action::make('isiSekarang')
                    ->label('Isi Jurnal Sekarang')
                    ->icon('heroicon-o-plus-circle')
                    ->action(fn() => $this->dispatch('tambah-jurnal')),
            ])

            ->striped()
            ->paginated([10, 25, 50])
            ->poll('60s'); // BARU: auto-refresh ringan tiap 1 menit, biar hasil isi jurnal terbaru langsung muncul tanpa reload manual
    }

    public function render(): View
    {
        return view('livewire.riwayat-jurnal-mengajar-table');
    }
}
