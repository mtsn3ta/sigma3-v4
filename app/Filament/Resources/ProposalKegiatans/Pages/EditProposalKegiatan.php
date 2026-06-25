<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalApprovalLog;

class EditProposalKegiatan extends EditRecord
{
    protected static string $resource = ProposalKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('submit')
                ->label('Submit Proposal')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn() => $this->record->status === 'draft')
                ->action(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return;
                    }

                    if (
                        $user->isPembinaEkskul(
                            $this->record->ekstrakurikuler_id
                        )
                    ) {

                        $status = 'pending_waka';
                    } elseif (
                        $user->isPelatihEkskul(
                            $this->record->ekstrakurikuler_id
                        )
                    ) {

                        $status = 'pending_pembina';
                    } else {

                        return;
                    }

                    $this->record->update([
                        'status' => $status,
                        'submitted_at' => now(),
                    ]);
                    ProposalApprovalLog::create([
                        'proposal_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'submit',
                    ]);
                }),
            Action::make('approvePembina')
                ->label('Approve Pembina')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    return
                        $user->isPembinaEkskul(
                            $this->record->ekstrakurikuler_id
                        )
                        && $this->record->status === 'pending_pembina';
                })
                ->action(function () {

                    if ($this->record->status !== 'pending_pembina') {
                        return;
                    }

                    $this->record->update([
                        'status' => 'pending_waka',
                    ]);
                    ProposalApprovalLog::create([
                        'proposal_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'approve_pembina',
                    ]);
                }),
            Action::make('approveWaka')
                ->label('Approve Waka')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    return
                        $user->hasRole('waka_kesiswaan')
                        && $this->record->status === 'pending_waka';
                })
                ->action(function () {

                    if ($this->record->status !== 'pending_waka') {
                        return;
                    }

                    $this->record->update([
                        'status' => 'pending_kepala',
                    ]);
                    ProposalApprovalLog::create([
                        'proposal_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'approve_waka',
                    ]);
                }),
            Action::make('approveKepala')
                ->label('Approve Kepala')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    return
                        $user->hasRole('kepala_madrasah')
                        && $this->record->status === 'pending_kepala';
                })
                ->action(function () {

                    if ($this->record->status !== 'pending_kepala') {
                        return;
                    }

                    $this->record->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                    ]);
                    ProposalApprovalLog::create([
                        'proposal_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'approve_kepala',
                    ]);
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    if (
                        $user->isPembinaEkskul(
                            $this->record->ekstrakurikuler_id
                        )
                        && $this->record->status === 'pending_pembina'
                    ) {
                        return true;
                    }

                    if (
                        $user->hasRole('waka_kesiswaan')
                        && $this->record->status === 'pending_waka'
                    ) {
                        return true;
                    }

                    if (
                        $user->hasRole('kepala_madrasah')
                        && $this->record->status === 'pending_kepala'
                    ) {
                        return true;
                    }

                    return false;
                })
                ->action(function () {

                    if (! in_array(
                        $this->record->status,
                        [
                            'pending_pembina',
                            'pending_waka',
                            'pending_kepala',
                        ]
                    )) {
                        return;
                    }

                    $this->record->update([
                        'status' => 'rejected',
                    ]);
                    ProposalApprovalLog::create([
                        'proposal_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'reject',
                    ]);
                }),
            Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(
                    fn() =>
                    ProposalKegiatanResource::getUrl(
                        'preview',
                        ['record' => $this->record]
                    )
                )
                ->openUrlInNewTab(),
            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('danger')
                ->url(fn() => route(
                    'proposal.pdf',
                    $this->record
                ))
                ->openUrlInNewTab(),
            DeleteAction::make()
                ->visible(
                    fn() =>
                    in_array(
                        $this->record->status,
                        ['draft', 'rejected']
                    )
                ),

        ];
    }
}
