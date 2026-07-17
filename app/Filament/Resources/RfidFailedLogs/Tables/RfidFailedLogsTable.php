<?php

namespace App\Filament\Resources\RfidFailedLogs\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class RfidFailedLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                TextColumn::make('mode')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'school' => 'primary',
                        'ibadah' => 'success',
                        'ekskul' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'school' => 'Sekolah',
                        'ibadah' => 'Ibadah',
                        'ekskul' => 'Ekstrakurikuler',
                        default => $state,
                    }),

                TextColumn::make('uid')
                    ->label('UID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->placeholder('-'),

                // PERBAIKAN 1: Cara memanggil nama kelas aktif siswa yang benar di tabel Filament
                TextColumn::make('siswa.kelasAktif.kelas.nama_kelas')
                    ->label('Kelas')
                    ->placeholder('-'),

                TextColumn::make('error')
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'unknown_card'      => 'Kartu Tidak Terdaftar',
                        'not_member'        => 'Bukan Anggota Ekskul',
                        'no_session'        => 'Tidak Ada Sesi Kegiatan',
                        'outside_time'      => 'Di Luar Jam Operasional',
                        'already_scanned'   => 'Sudah Melakukan Scan',
                        'checkin_required'  => 'Belum Absen Datang',
                        'disabled_today'    => 'Layanan Nonaktif Hari Ini',
                        default             => ucwords(str_replace('_', ' ', $state)),
                    }),

                TextColumn::make('message')
                    ->label('Pesan')
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('mode')
                    ->label('Mode')
                    ->options([
                        'school' => 'Sekolah',
                        'ibadah' => 'Ibadah',
                        'ekskul' => 'Ekstrakurikuler',
                    ]),

                Filter::make('tanggal')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        // PERBAIKAN 2: Amankan filter tanggal agar sinkron dengan timezone Asia/Jakarta
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn($query, $tanggal) => $query->whereDate('created_at', $tanggal)
                        );
                    }),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
