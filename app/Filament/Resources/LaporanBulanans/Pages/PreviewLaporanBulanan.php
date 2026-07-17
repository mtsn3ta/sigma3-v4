<?php

namespace App\Filament\Resources\LaporanBulanans\Pages;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Models\LaporanBulanan;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Textarea;
use App\Models\RevisionLog;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Actions\Action;
use App\Models\LaporanBulananApprovalLog;
use Filament\Notifications\Notification;

class PreviewLaporanBulanan extends Page
{
    protected static string $resource = LaporanBulananResource::class;

    protected string $view =
    'filament.resources.laporan-bulanans.pages.preview-laporan-bulanan';

    public LaporanBulanan $record;

    public function mount(LaporanBulanan $record): void
    {
        $this->record = $record->load([

            'ekstrakurikuler',

            'createdBy.pegawai',

            'approvalLogs.user.pegawai',

            'revisionLogs.user',

        ]);
    }
    protected function getHeaderActions(): array
    {
        return [
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

                    return redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
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
                        ->title('Laporan disetujui Waka Kesiswaan.')
                        ->success()
                        ->send();

                    return redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
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

                    return redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
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

                    $statusFrom = $this->record->status;

                    $this->record->update([
                        'status' => 'revision',
                    ]);

                    RevisionLog::create([
                        'revisionable_type' => LaporanBulanan::class,
                        'revisionable_id'   => $this->record->id,
                        'user_id'           => Auth::id(),
                        'status_from'       => $statusFrom,
                        'status_to'         => 'revision',
                        'notes'             => $data['catatan'],
                    ]);

                    LaporanBulananApprovalLog::create([

                        'laporan_bulanan_id' => $this->record->id,

                        'user_id' => Auth::id(),

                        'aksi' => 'revision',

                        'catatan' => $data['catatan'],

                    ]);

                    Notification::make()
                        ->title('Permintaan revisi berhasil dikirim.')
                        ->warning()
                        ->send();

                    return redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
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
                        ->title('Laporan berhasil ditolak.')
                        ->danger()
                        ->send();

                    return redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );
                }),
            Action::make('pdf')

                ->label('Download PDF')

                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn() => $this->record->status === 'approved')
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
    public function getTitle(): string
    {
        return 'Preview Laporan Bulanan';
    }
}
