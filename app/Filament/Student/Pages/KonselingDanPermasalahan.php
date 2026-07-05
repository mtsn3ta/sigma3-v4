<?php

namespace App\Filament\Student\Pages;

use App\Models\Siswa;
use App\Models\KonselingSiswa;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use Filament\Forms\Components\Select;

class KonselingDanPermasalahan extends Page
{
    protected static ?string $navigationLabel = 'Konseling & Log';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.student.pages.konseling-dan-permasalahan';

    public Siswa $siswa;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->siswa = $user->siswa()->firstOrFail();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ajukanKonseling')
                ->label('Ajukan Konseling')
                ->icon('heroicon-o-plus')
                ->form([
                    DatePicker::make('tanggal')->required()->default(now()),

                    Select::make('pegawai_id')
                        ->label('Pilih Guru BK')
                        ->options(
                            Pegawai::whereHas('user', function ($query) {
                                $query->role('bk'); // Filter hanya yang memiliki role 'bk'
                            })->pluck('nama', 'id')
                        )
                        ->required()
                        ->searchable(), // Tambahkan ini agar mudah mencari jika gurunya banyak

                    Textarea::make('permasalahan')->required()->label('Tujuan/Permasalahan'),
                ])
                // Pastikan di dalam action ->action()
                ->action(function (array $data) {
                    KonselingSiswa::create([
                        'siswa_id' => $this->siswa->id,
                        'pegawai_id' => $data['pegawai_id'], // <--- Pastikan ini sudah ditambahkan
                        'tanggal' => $data['tanggal'],
                        'permasalahan' => $data['permasalahan'],
                        'hasil_konseling' => '-', // Placeholder
                        'tindak_lanjut' => '-',   // Placeholder
                    ]);
                    Notification::make()->title('Pengajuan konseling berhasil dikirim')->success()->send();
                }),
        ];
    }
}
