<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalApprovalLog;
use Filament\Notifications\Notification;

class EditProposalKegiatan extends EditRecord
{
    protected static string $resource = ProposalKegiatanResource::class;
    protected function logApproval(string $action): void
    {
        ProposalApprovalLog::create([
            'proposal_kegiatan_id' => $this->record->id,
            'user_id' => Auth::id(),
            'action' => $action,
        ]);
    }

    protected function updateStatus(
        string $status,
        bool $submitted = false,
        bool $approved = false,
    ): void {

        $data = [
            'status' => $status,
        ];

        if ($submitted) {
            $data['submitted_at'] = now();
        }

        if ($approved) {
            $data['approved_at'] = now();
        }

        $this->record->update($data);
    }
    protected function getHeaderActions(): array
    {
        return [

            Action::make('submit')
                ->label('Submit Proposal')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn() => in_array(
                    $this->record->status,
                    ['draft', 'revision']
                ))
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

                    $this->updateStatus(
                        $status,
                        submitted: true,
                    );

                    $this->logApproval('submit');
                    Notification::make()
                        ->title('Proposal berhasil dikirim.')
                        ->success()
                        ->send();

                    return redirect(
                        ProposalKegiatanResource::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
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
