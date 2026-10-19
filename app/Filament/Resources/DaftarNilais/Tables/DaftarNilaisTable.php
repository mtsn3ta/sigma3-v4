<?php

namespace App\Filament\Resources\DaftarNilais\Tables;

use App\Exports\DaftarNilai\DaftarNilaiSiswaExport;
use App\Filament\Actions\BeriUmpanBalikAction;
use App\Models\DaftarNilai;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DaftarNilaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->withCount('nilaiSiswa')
                ->withAvg('nilaiSiswa', 'nilai'))
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('pegawai.nama')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->searchable(),

                TextColumn::make('judul')
                    ->label('Nama Penilaian')
                    ->searchable(),

                TextColumn::make('jenis_nilai')
                    ->label('Jenis')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                TextColumn::make('nilai_siswa_count')
                    ->label('Siswa Dinilai')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'warning')
                    ->icon('heroicon-m-users')
                    ->alignCenter(),

                TextColumn::make('nilai_siswa_avg_nilai')
                    ->label('Rata-rata')
                    ->getStateUsing(fn (DaftarNilai $record): ?float => $record->nilai_siswa_avg_nilai !== null
                        ? round((float) $record->nilai_siswa_avg_nilai, 2)
                        : null)
                    ->formatStateUsing(fn ($state): string => $state !== null
                        ? number_format((float) $state, 2, ',', '.')
                        : '-')
                    ->alignCenter(),

                TextColumn::make('file_path')
                    ->label('Lampiran')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => filled($state) ? 'Ada' : '-')
                    ->color(fn ($state): string => filled($state) ? 'info' : 'gray')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('jenis_nilai')
                    ->label('Jenis Penilaian')
                    ->options([
                        'Harian' => 'Ulangan Harian',
                        'PTS' => 'PTS / UTS',
                        'PAS' => 'PAS / UAS',
                        'Tugas' => 'Tugas',
                        'Praktik' => 'Praktik',
                        'Sikap' => 'Sikap',
                        'Lainnya' => 'Lainnya',
                    ]),

                SelectFilter::make('mapel')
                    ->label('Mata Pelajaran')
                    ->options(fn (): array => DaftarNilai::query()
                        ->whereNotNull('mapel')
                        ->distinct()
                        ->orderBy('mapel')
                        ->pluck('mapel', 'mapel')
                        ->toArray())
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('lihatNilai')
                    ->label('Lihat Nilai')
                    ->icon('heroicon-o-table-cells')
                    ->color('info')
                    ->modalHeading(fn (DaftarNilai $record): string => 'Nilai — ' . $record->judul)
                    ->modalContent(fn (DaftarNilai $record) => view('filament.modals.daftar-nilai-detail', [
                        'record' => $record->load(['nilaiSiswa.siswa', 'kelas', 'pegawai']),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (DaftarNilai $record) {
                        $namaFile = Str::slug(
                            ($record->kelas?->nama_kelas ?? '') . '-' . $record->mapel . '-' . $record->judul
                        ) ?: 'daftar-nilai';

                        return Excel::download(
                            new DaftarNilaiSiswaExport($record),
                            'daftar-nilai-' . $namaFile . '-' . now()->format('YmdHis') . '.xlsx'
                        );
                    }),

                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (DaftarNilai $record) => Storage::disk('public')->url($record->file_path), true)
                    ->visible(fn (DaftarNilai $record) => filled($record->file_path)),

                BeriUmpanBalikAction::make('nilai'),

                EditAction::make()
                    ->visible(fn (DaftarNilai $record): bool => \App\Support\ActiveRole::is('super_admin')
                        || (\App\Support\ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),

                DeleteAction::make()
                    ->visible(fn (DaftarNilai $record): bool => \App\Support\ActiveRole::is('super_admin')
                        || (\App\Support\ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => \App\Support\ActiveRole::is('guru') || \App\Support\ActiveRole::is('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
