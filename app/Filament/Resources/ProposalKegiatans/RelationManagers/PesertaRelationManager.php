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

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('siswa_id')
                    ->label('Peserta')
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
                            ->with('siswa')
                            ->get()
                            ->mapWithKeys(fn($anggota) => [
                                $anggota->siswa_id => $anggota->siswa?->nama,
                            ])
                            ->toArray();
                    })
                    ->searchable()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([

                TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('siswa.nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas'),

                TextColumn::make('siswa.jenis_kelamin')
                    ->label('JK')
                    ->badge(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
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
