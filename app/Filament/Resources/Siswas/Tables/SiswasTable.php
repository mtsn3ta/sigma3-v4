<?php

namespace App\Filament\Resources\Siswas\Tables;

use App\Services\ActivityLogService;
use Filament\Actions\Action; // Disatukan di Filament v5
use Filament\Actions\BulkActionGroup; // Disatukan di Filament v5
use Filament\Actions\EditAction; // Disatukan di Filament v5
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section; // Menggunakan Schema Section untuk v5
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto') // ✨ Cukup 'foto', jangan 'siswa.foto'
                    ->label('Foto')
                    ->circular() // Membuat bentuk lingkaran sempurna
                    ->disk('public') // Membaca folder public/storage
                    ->visibility('public')
                    ->placeholder(function ($record) {
                        // Jika kolom foto kosong/null di DB, tampilkan inisial huruf depan namanya (Ahmad -> A)
                        return strtoupper(substr($record->nama, 0, 1));
                    }),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->badge(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('user.roles.name')
                    ->label('Role')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'mutasi' => 'Mutasi',
                        'lulus' => 'Lulus',
                    ]),
            ])
            ->actions([
                EditAction::make(),

                Action::make('status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(function () {

                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        return $user?->hasRole('super_admin') ?? false;
                    })
                    ->modalWidth('md')
                    ->schema([

                        Placeholder::make('nama')
                            ->label('Nama Siswa')
                            ->content(fn($record) => $record->nama),

                        Placeholder::make('status_saat_ini')
                            ->label('Status Saat Ini')
                            ->content(fn($record) => ucfirst($record->status)),

                        Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'aktif' => 'Aktif',
                                'mutasi' => 'Mutasi',
                                'lulus' => 'Lulus',
                            ])
                            ->required(),

                    ])
                    ->action(function ($record, array $data) {

                        if ($record->status === $data['status']) {

                            Notification::make()
                                ->title('Tidak Ada Perubahan')
                                ->warning()
                                ->send();

                            return;
                        }

                        $statusLama = ucfirst($record->status);
                        $statusBaru = ucfirst($data['status']);

                        $record->update([
                            'status' => $data['status'],
                        ]);

                        ActivityLogService::log(
                            'Perubahan Status Siswa',
                            sprintf(
                                '%s | %s → %s',
                                $record->nama,
                                $statusLama,
                                $statusBaru,
                            )
                        );

                        Notification::make()
                            ->title('Status Berhasil Diubah')
                            ->body(
                                sprintf(
                                    'Status %s berhasil diubah menjadi %s.',
                                    $record->nama,
                                    $statusBaru,
                                )
                            )
                            ->success()
                            ->send();
                    }),

                Action::make('riwayatAkademik')
                    ->label('Riwayat Akademik')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->modalHeading('Riwayat Akademik')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->infolist(function (Schema $infolist) {
                        return $infolist->schema([
                            Section::make('Riwayat Kelas')
                                ->schema([
                                    RepeatableEntry::make('kelasSiswa')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('tahunAjaran.tahun')
                                                ->label('Tahun Ajaran'),

                                            TextEntry::make('kelas.nama_kelas')
                                                ->label('Kelas'),
                                        ])
                                        ->columns(2),
                                ]),
                            Section::make('Prestasi')
                                ->schema([
                                    RepeatableEntry::make('prestasi')
                                        ->hiddenLabel()
                                        ->schema([
                                            TextEntry::make('nama_prestasi')
                                                ->label('Prestasi'),

                                            TextEntry::make('nama_lomba')
                                                ->label('Lomba'),

                                            TextEntry::make('tingkat')
                                                ->label('Tingkat'),

                                            TextEntry::make('tanggal')
                                                ->label('Tanggal')
                                                ->date('d M Y'),

                                        ])
                                        ->columns(2),
                                ]),

                            Section::make('Ekstrakurikuler')
                                ->schema([
                                    RepeatableEntry::make('anggotaEkskul')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('ekstrakurikuler.nama')
                                                ->label('Ekstrakurikuler'),
                                        ]),
                                ]),
                            Section::make('Permasalahan')
                                ->schema([
                                    RepeatableEntry::make('permasalahan')
                                        ->hiddenLabel()
                                        ->schema([

                                            TextEntry::make('tanggal')
                                                ->label('Tanggal')
                                                ->date('d M Y'),

                                            TextEntry::make('permasalahan')
                                                ->label('Permasalahan'),

                                            TextEntry::make('poin')
                                                ->label('Poin'),

                                            TextEntry::make('penyelesaian')
                                                ->label('Penyelesaian'),

                                        ])
                                        ->columns(2),
                                ]),
                        ]);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Aksi massal ditaruh di sini
                ]),
            ]);
    }
}
