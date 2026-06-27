<?php

namespace App\Filament\Resources\LaporanBulanans\Pages;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Models\LaporanBulanan;
use App\Models\LaporanBulananApprovalLog;
use App\Models\RevisionLog;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLaporanBulanan extends EditRecord
{
    protected static string $resource = LaporanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

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
                }),

            /*
            |--------------------------------------------------------------------------
            | Approve Pembina
            |--------------------------------------------------------------------------
            */

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

                        &&

                        $this->record->status === 'pending_pembina';
                })

                ->action(function () {

                    if ($this->record->status !== 'pending_pembina') {
                        return;
                    }

                    $this->record->update([

                        'status' => 'pending_waka',

                    ]);

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'approve_pembina',

                    ]);

                    Notification::make()

                        ->title('Laporan disetujui Pembina.')

                        ->success()

                        ->send();
                }),

            /*
            |--------------------------------------------------------------------------
            | Approve Waka
            |--------------------------------------------------------------------------
            */

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

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'approve_waka',

                    ]);

                    Notification::make()

                        ->title('Laporan disetujui Waka.')

                        ->success()

                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),

            /*
            |--------------------------------------------------------------------------
            | Approve Kepala
            |--------------------------------------------------------------------------
            */

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

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'approve_kepala',

                    ]);

                    Notification::make()

                        ->title('Laporan disetujui Kepala Madrasah.')

                        ->success()

                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),

            /*
            |--------------------------------------------------------------------------
            | Request Revision
            |--------------------------------------------------------------------------
            */

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

                ->action(function (array $data) {

                    $this->record->update([
                        'status' => 'revision',
                    ]);

                    RevisionLog::create([

                        'revisionable_type' => LaporanBulanan::class,

                        'revisionable_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'catatan' => $data['catatan'],

                    ]);

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'revision',

                        'catatan' => $data['catatan'],

                    ]);

                    Notification::make()

                        ->title('Revisi berhasil dikirim.')

                        ->warning()

                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
                }),

            /*
            |--------------------------------------------------------------------------
            | Reject
            |--------------------------------------------------------------------------
            */

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

                    $this->record->update([
                        'status' => 'rejected',
                    ]);

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'reject',

                    ]);

                    Notification::make()

                        ->title('Laporan ditolak.')

                        ->danger()

                        ->send();

                    $this->refreshFormData([
                        'status',
                    ]);
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

            Action::make('pdf')

                ->label('Download PDF')

                ->icon('heroicon-o-arrow-down-tray')

                ->color('gray')

                ->url(
                    fn() => route(
                        'laporan-bulanan.pdf',
                        $this->record
                    )
                )

                ->openUrlInNewTab(),

        ];
    }
}
