<?php

namespace App\Filament\Resources\ProposalKegiatans\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\AnggotaEkskul;

class PesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'peserta';

    protected static ?string $title = 'Peserta Kegiatan';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('siswa_id')
                    ->label('Pilih Siswa Peserta')
                    ->placeholder('Ketik nama siswa atau pilih dari anggota ekskul...')
                    ->options(function () {

                        $proposal = $this->ownerRecord;

                        return AnggotaEkskul::query()
                            ->where(
                                'ekstrakurikuler_id',
                                $proposal->ekstrakurikuler_id
                            )
                            ->where('status', 'aktif')
                            ->whereHas('tahunAjaran', fn($query) => $query->where('aktif', true))
                            ->whereNotIn(
                                'siswa_id',
                                $proposal->peserta()->pluck('siswa_id')
                            )
                            ->with(['siswa.kelasAktif.kelas'])
                            ->get()
                            ->mapWithKeys(function ($anggota) {
                                $siswa = $anggota->siswa;
                                $kelas = $siswa?->kelasAktif?->kelas?->nama ?? '-';
                                $nisn = $siswa?->nisn ?? '-';
                                return [
                                    $anggota->siswa_id => ($siswa?->nama ?? 'Siswa') . " (Kelas: {$kelas} | NISN: {$nisn})",
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([

                TextColumn::make('siswa.nama')
                    ->label('Nama Lengkap')
                    ->weight('medium')
                    ->searchable(),

                TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('siswa.jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'L' => 'primary',
                        'P' => 'warning',
                        default => 'gray',
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-user-plus')
                    ->modalWidth('lg'),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
