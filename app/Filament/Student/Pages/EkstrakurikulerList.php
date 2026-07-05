<?php

namespace App\Filament\Student\Pages;

use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\Ekstrakurikuler\RegisterEkskulService;

class EkstrakurikulerList extends Page
{
    protected static ?string $navigationLabel = 'Ekstrakurikuler';
    protected static ?string $title = 'Ekstrakurikuler Saya';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected string $view = 'filament.student.pages.ekstrakurikuler-list';
    protected static ?int $navigationSort = 2;
    public Siswa $siswa;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->siswa = $user->siswa()
            ->with([
                'anggotaEkskul.ekstrakurikuler',
                'anggotaEkskul.tahunAjaran'
            ])
            ->firstOrFail();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('daftarEkstra')
                ->label('Daftar Ekstrakurikuler')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    Select::make('ekstrakurikuler_id')
                        ->label('Pilih Ekstrakurikuler')
                        ->options(Ekstrakurikuler::where('status', 'aktif')->pluck('nama', 'id'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    app(RegisterEkskulService::class)->execute($this->siswa, $data['ekstrakurikuler_id']);

                    $this->mount(); // Refresh data setelah daftar
                    Notification::make()->title('Berhasil mendaftar!')->success()->send();
                }),
        ];
    }
}
