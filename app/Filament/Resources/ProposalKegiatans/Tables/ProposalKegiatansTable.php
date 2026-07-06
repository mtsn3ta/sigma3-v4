<?php

namespace App\Filament\Resources\ProposalKegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ProposalKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_anggaran')
                    ->label('Anggaran')
                    ->money('IDR', divideBy: 1)
                    ->sortable(),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'draft' => 'gray',
                        'pending_pembina' => 'warning',
                        'pending_waka' => 'warning',
                        'pending_kepala' => 'warning',
                        'revision' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->filters([])

            ->recordActions([
                Action::make('open')

                    ->visible(function ($record) {

                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        if (! $user) {
                            return false;
                        }

                        // Draft & Revision hanya Pelatih dan Pembina
                        if (
                            in_array($record->status, ['draft', 'revision']) &&
                            (
                                $user->isPelatihEkskul($record->ekstrakurikuler_id)
                                || $user->isPembinaEkskul($record->ekstrakurikuler_id)
                            )
                        ) {
                            return true;
                        }

                        // Setelah submit semua role yang memiliki akses dapat membuka Preview
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
                            ? ProposalKegiatanResource::getUrl('edit', [
                                'record' => $record,
                            ])
                            : ProposalKegiatanResource::getUrl('preview', [
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
