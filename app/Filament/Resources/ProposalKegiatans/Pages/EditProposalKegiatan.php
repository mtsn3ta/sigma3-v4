<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalApprovalLog;
use App\Models\RevisionLog;
use App\Models\LaporanKegiatan;
use App\Models\LaporanKegiatanPeserta;
use App\Models\LaporanKegiatanPendamping;
use App\Models\LaporanRealisasiAnggaran;
use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

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
    protected function requestRevision(string $notes): void
    {
        RevisionLog::create([

            'revisionable_type' => $this->record::class,

            'revisionable_id' => $this->record->id,

            'user_id' => Auth::id(),

            'status_from' => $this->record->status,

            'status_to' => 'revision',

            'notes' => $notes,

        ]);

        $this->updateStatus('revision');

        $this->logApproval('revision');
    }
    protected function resubmitRevision(): void
    {
        $revision = $this->record
            ->revisionLogs()
            ->latest()
            ->first();

        if (! $revision) {
            return;
        }

        $this->updateStatus(
            $revision->status_from
        );

        $this->logApproval('resubmit');
    }
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

                    $this->updateStatus(
                        $status,
                        submitted: true,
                    );

                    $this->logApproval('submit');
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

                    $this->updateStatus('pending_waka');

                    $this->logApproval('approve_pembina');
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

                    $this->updateStatus('pending_kepala');

                    $this->logApproval('approve_waka');
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

                    $this->updateStatus(
                        'approved',
                        approved: true,
                    );

                    $this->logApproval('approve_kepala');
                }),
            Action::make('revision')
                ->label('Request Revision')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->form([

                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Catatan Revisi')
                        ->rows(5)
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

                    $this->requestRevision(
                        $data['notes']
                    );
                }),
            Action::make('resubmit')
                ->label('Resubmit')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(function () {

                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! $user) {
                        return false;
                    }

                    return
                        $this->record->status === 'revision'
                        && (
                            $user->isPelatihEkskul($this->record->ekstrakurikuler_id)
                            || $user->isPembinaEkskul($this->record->ekstrakurikuler_id)
                        );
                })
                ->action(function () {

                    $this->resubmitRevision();
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

                    $this->updateStatus('rejected');

                    $this->logApproval('reject');
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
            Action::make('buatLaporan')
                ->label('Buat Laporan')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(
                    fn() =>
                    $this->record->status === 'approved'
                        && ! $this->record->hasLaporan()
                )
                ->action(function () {

                    try {

                        $laporan = null;

                        DB::transaction(function () use (&$laporan) {

                            $laporan = LaporanKegiatan::create([
                                'proposal_kegiatan_id' => $this->record->id,
                                'created_by_id' => Auth::id(),
                                'status' => 'draft',
                            ]);

                            foreach ($this->record->peserta as $peserta) {

                                LaporanKegiatanPeserta::create([
                                    'laporan_kegiatan_id' => $laporan->id,
                                    'proposal_peserta_id' => $peserta->id,
                                    'siswa_id' => $peserta->siswa_id,
                                    'status' => 'hadir',
                                ]);
                            }

                            foreach ($this->record->pendamping as $pendamping) {

                                LaporanKegiatanPendamping::create([
                                    'laporan_kegiatan_id' => $laporan->id,
                                    'proposal_pendamping_id' => $pendamping->id,
                                    'pegawai_id' => $pendamping->pegawai_id,
                                    'peran' => $pendamping->peran,
                                    'status' => 'hadir',
                                ]);
                            }

                            foreach ($this->record->rabs as $rab) {

                                LaporanRealisasiAnggaran::create([
                                    'laporan_kegiatan_id' => $laporan->id,
                                    'proposal_rab_id' => $rab->id,
                                    'uraian' => $rab->uraian,
                                    'qty' => $rab->qty,
                                    'satuan' => $rab->satuan,
                                    'harga_satuan' => $rab->harga_satuan,
                                ]);
                            }
                        });

                        Notification::make()
                            ->title('Laporan kegiatan berhasil dibuat.')
                            ->success()
                            ->send();

                        return redirect(
                            LaporanKegiatanResource::getUrl(
                                'edit',
                                [
                                    'record' => $laporan,
                                ]
                            )
                        );
                    } catch (\Throwable $e) {

                        Notification::make()
                            ->title('Gagal membuat laporan kegiatan.')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }
                }),
            Action::make('lihatLaporan')
                ->label('Lihat Laporan')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->visible(
                    fn() =>
                    $this->record->hasLaporan()
                )
                ->url(
                    fn() =>
                    LaporanKegiatanResource::getUrl(
                        'edit',
                        [
                            'record' => $this->record->laporanKegiatan,
                        ]
                    )
                ),
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
