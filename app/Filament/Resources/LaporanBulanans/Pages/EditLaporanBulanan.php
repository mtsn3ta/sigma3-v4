<?php

namespace App\Filament\Resources\LaporanBulanans\Pages;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Models\LaporanBulananApprovalLog;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLaporanBulanan extends EditRecord
{
    protected static string $resource = LaporanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('submit')

                ->label('Submit Laporan')

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

                    $this->record->update([

                        'status' => $status,

                        'submitted_at' => now(),

                    ]);

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'submit',

                    ]);

                    Notification::make()
                        ->title('Laporan berhasil dikirim.')
                        ->success()
                        ->send();

                    return redirect(
                        LaporanBulananResource::getUrl(
                            'preview',
                            [
                                'record' => $this->record,
                            ]
                        )
                    );
                }),

            Action::make('preview')

                ->label('Preview')

                ->icon('heroicon-o-eye')

                ->color('gray')

                ->url(
                    fn() =>

                    LaporanBulananResource::getUrl(
                        'preview',
                        [
                            'record' => $this->record,
                        ]
                    )

                )

                ->openUrlInNewTab(),
        ];
    }
}
