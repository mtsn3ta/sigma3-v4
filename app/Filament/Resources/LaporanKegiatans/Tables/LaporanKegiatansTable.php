<?php

namespace App\Filament\Resources\LaporanKegiatans\Tables;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class LaporanKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('proposal.nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposal.ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposal.tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y'),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending_pembina' => 'warning',
                        'pending_waka' => 'warning',
                        'pending_kepala' => 'warning',
                        'revision' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'draft' => 'Draft',
                        'pending_pembina' => 'Menunggu Pembina',
                        'pending_waka' => 'Menunggu Waka',
                        'pending_kepala' => 'Menunggu Kepala',
                        'revision' => 'Perlu Revisi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('open')

                    ->visible(function ($record) {

                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        if (! $user) {
                            return false;
                        }

                        // Draft / Revision hanya Pelatih & Pembina
                        if (
                            in_array($record->status, ['draft', 'revision']) &&
                            (
                                $user->isPelatihEkskul($record->proposal->ekstrakurikuler_id)
                                || $user->isPembinaEkskul($record->proposal->ekstrakurikuler_id)
                            )
                        ) {
                            return true;
                        }

                        // Setelah submit semua role yang memiliki akses boleh melihat Preview
                        return ! in_array($record->status, ['draft', 'revision']);
                    })

                    ->label(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? 'Edit'
                            : 'Preview';
                    })

                    ->icon(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? 'heroicon-o-pencil-square'
                            : 'heroicon-o-eye';
                    })

                    ->url(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? LaporanKegiatanResource::getUrl('edit', [
                                'record' => $record,
                            ])
                            : LaporanKegiatanResource::getUrl('preview', [
                                'record' => $record,
                            ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
