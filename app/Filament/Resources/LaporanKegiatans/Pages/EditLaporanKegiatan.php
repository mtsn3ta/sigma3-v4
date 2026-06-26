<?php

namespace App\Filament\Resources\LaporanKegiatans\Pages;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanApprovalLog;
use App\Models\RevisionLog;
use App\Models\LaporanKegiatan;
use Filament\Forms\Components\Textarea;

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

                ->visible(fn() => $this->record->status === 'draft')

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
                            $this->record->proposal->ekstrakurikuler_id
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

                    LaporanApprovalLog::create([

                        'laporan_kegiatan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'action' => 'approve_pembina',

                    ]);

                    Notification::make()
                        ->title('Laporan disetujui Pembina.')
                        ->success()
                        ->send();
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

                    LaporanApprovalLog::create([

                        'laporan_kegiatan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'action' => 'approve_waka',

                    ]);

                    Notification::make()
                        ->title('Laporan disetujui Waka.')
                        ->success()
                        ->send();
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

                    LaporanApprovalLog::create([

                        'laporan_kegiatan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'action' => 'approve_kepala',

                    ]);

                    Notification::make()
                        ->title('Laporan disetujui Kepala Madrasah.')
                        ->success()
                        ->send();
                }),
            Action::make('requestRevision')
                ->label('Request Revision')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->form([

                    Textarea::make('catatan')
                        ->label('Catatan Revisi')
                        ->required(),

                ])
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    if (
                        $user->isPembinaEkskul(
                            $this->record->proposal->ekstrakurikuler_id
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
                ->action(function (array $data) {

                    $this->record->update([
                        'status' => 'revision',
                    ]);

                    RevisionLog::create([

                        'revisionable_type' => LaporanKegiatan::class,

                        'revisionable_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'catatan' => $data['catatan'],

                    ]);

                    LaporanApprovalLog::create([

                        'laporan_kegiatan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'action' => 'revision',

                        'catatan' => $data['catatan'],

                    ]);

                    Notification::make()
                        ->title('Revisi berhasil dikirim.')
                        ->warning()
                        ->send();
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
                            $this->record->proposal->ekstrakurikuler_id
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

                    $this->record->update([
                        'status' => 'rejected',
                    ]);

                    LaporanApprovalLog::create([

                        'laporan_kegiatan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'action' => 'reject',

                    ]);

                    Notification::make()
                        ->title('Laporan ditolak.')
                        ->danger()
                        ->send();
                }),
            Action::make('preview')

                ->label('Preview')

                ->icon('heroicon-o-eye')

                ->color('gray')

                ->url(
                    fn() =>

                    LaporanKegiatanResource::getUrl(
                        'preview',
                        [
                            'record' => $this->record,
                        ]
                    )

                )

                ->openUrlInNewTab(),
            Action::make('downloadPdf')

                ->label('Download PDF')

                ->icon('heroicon-o-arrow-down-tray')

                ->color('danger')

                ->url(
                    fn() =>

                    route(
                        'laporan-kegiatan.pdf',
                        $this->record
                    )

                )

                ->openUrlInNewTab(),
        ];
    }
}
