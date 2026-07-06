<?php

namespace App\Filament\Resources\LaporanKegiatans\Pages;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Resources\Pages\Page;
use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\LaporanApprovalLog;
use App\Models\RevisionLog;
use Filament\Forms\Components\Textarea;

class PreviewLaporanKegiatan extends Page
{
    protected static string $resource = LaporanKegiatanResource::class;

    protected string $view =
    'filament.resources.laporan-kegiatans.pages.preview-laporan-kegiatan';

    public LaporanKegiatan $record;

    public function mount(LaporanKegiatan $record): void
    {
        $this->record = $record->load([
            'proposal',
            'proposal.ekstrakurikuler',

            'peserta',
            'peserta.siswa',
            'peserta.siswa.kelasAktif.kelas',

            'pendamping',
            'pendamping.pegawai',

            'realisasiAnggaran',

            'approvalLogs.user',
        ]);
    }
    protected function getHeaderActions(): array
    {
        return [

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
                    $this->redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );

                    return;
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
                    $this->redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );

                    return;
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
                    $this->redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );

                    return;
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
                        'revisionable_id'   => $this->record->id,
                        'user_id'           => Auth::id(),
                        'status_from'       => $this->record->getOriginal('status'),
                        'status_to'         => 'revision',
                        'notes'             => $data['catatan'],
                    ]);;

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
                    $this->redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );

                    return;
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
                    $this->redirect(
                        static::getResource()::getUrl('preview', [
                            'record' => $this->record,
                        ])
                    );

                    return;
                }),

            Action::make('pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(fn() => $this->record->status === 'approved')
                ->url(fn() => route(
                    'laporan-kegiatan.pdf',
                    $this->record
                ))
                ->openUrlInNewTab(),
        ];
    }
    public function getTitle(): string
    {
        return 'Preview Laporan Kegiatan';
    }
}
