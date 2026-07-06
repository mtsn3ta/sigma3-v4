<?php

namespace App\Filament\Resources\LaporanKegiatans\Pages;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanApprovalLog;

class EditLaporanKegiatan extends EditRecord
{
    protected static string $resource = LaporanKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('submit')
                ->label('Submit Laporan')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn() => in_array($this->record->status, [
                    'draft',
                    'revision',
                ]))
                ->action(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return;
                    }

                    if (
                        $user->isPembinaEkskul(
                            $this->record->proposal->ekstrakurikuler_id
                        )
                    ) {

                        $status = 'pending_waka';
                    } elseif (
                        $user->isPelatihEkskul(
                            $this->record->proposal->ekstrakurikuler_id
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

                    LaporanApprovalLog::create([
                        'laporan_kegiatan_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action' => 'submit',
                    ]);

                    Notification::make()
                        ->title('Laporan berhasil dikirim.')
                        ->success()
                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),

            Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn() => LaporanKegiatanResource::getUrl(
                    'preview',
                    [
                        'record' => $this->record,
                    ]
                ))
                ->openUrlInNewTab(),

        ];
    }
}
