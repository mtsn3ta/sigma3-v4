<?php

namespace App\Filament\Actions;

use App\Models\DaftarNilai;
use App\Models\JurnalMengajar;
use App\Models\Pegawai;
use App\Models\PerangkatPembelajaran;
use App\Models\UmpanBalikGuru;
use App\Support\ActiveRole;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class BeriUmpanBalikAction
{
    /**
     * Action untuk Kepala Madrasah memberi catatan/evaluasi kepada guru.
     *
     * @param  string|null  $kategori  Kategori tetap (perangkat/jurnal/nilai/kbm/umum).
     * @param  bool  $pilihKategori  Tampilkan pilihan kategori (untuk feedback umum per guru).
     */
    public static function make(?string $kategori = null, bool $pilihKategori = false): Action
    {
        return Action::make('beriUmpanBalik')
            ->label('Beri Umpan Balik')
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->color('warning')
            ->modalHeading('Beri Umpan Balik / Evaluasi')
            ->modalDescription('Catatan ini akan muncul di halaman Umpan Balik guru yang bersangkutan.')
            ->modalSubmitActionLabel('Kirim Umpan Balik')
            ->visible(fn (): bool => ActiveRole::is('kepala_madrasah'))
            ->form([
                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'umum'      => 'Catatan Umum',
                        'perangkat' => 'Perangkat Pembelajaran',
                        'jurnal'    => 'Jurnal Mengajar',
                        'nilai'     => 'Daftar Nilai',
                        'kbm'       => 'Dokumentasi KBM',
                    ])
                    ->default($kategori ?? 'umum')
                    ->visible($pilihKategori)
                    ->required(),

                Textarea::make('catatan')
                    ->label('Catatan / Evaluasi')
                    ->placeholder('Tuliskan catatan, saran perbaikan, atau apresiasi untuk guru...')
                    ->rows(5)
                    ->maxLength(2000)
                    ->required(),
            ])
            ->action(function (Model $record, array $data) use ($kategori): void {
                $pegawaiId = $record instanceof Pegawai
                    ? $record->getKey()
                    : $record->getAttribute('pegawai_id');

                $referensiType = match (true) {
                    $record instanceof PerangkatPembelajaran => PerangkatPembelajaran::class,
                    $record instanceof JurnalMengajar         => JurnalMengajar::class,
                    $record instanceof DaftarNilai            => DaftarNilai::class,
                    default                                   => null,
                };

                UmpanBalikGuru::create([
                    'pegawai_id'     => $pegawaiId,
                    'user_id'        => auth()->id(),
                    'kategori'       => $data['kategori'] ?? $kategori ?? 'umum',
                    'referensi_type' => $referensiType,
                    'referensi_id'   => $referensiType ? $record->getKey() : null,
                    'catatan'        => $data['catatan'],
                ]);

                Notification::make()
                    ->title('Umpan balik berhasil dikirim.')
                    ->success()
                    ->send();
            });
    }
}
