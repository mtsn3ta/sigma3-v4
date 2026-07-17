<?php

namespace App\Livewire;

use App\Models\SesiKegiatan;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\AnggotaEkskul;
use App\Models\KehadiranEkskul;
use Illuminate\Support\Facades\Auth;

class AbsensiEkskulTable extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public array $anggota = [];

    public SesiKegiatan $sesi;

    public function mount(
        SesiKegiatan $sesi,
    ): void {

        $this->sesi = $sesi;
        $this->loadAnggota();

        $this->form->fill();

        $this->form->fill();
    }
    protected function loadAnggota(): void
    {
        $anggota = AnggotaEkskul::query()

            ->where(
                'ekstrakurikuler_id',
                $this->sesi->ekstrakurikuler_id
            )

            ->where(
                'status',
                'aktif'
            )

            ->with('siswa')

            ->orderBy('tanggal_daftar')
            ->orderBy('id')

            ->get();

        $kehadiran = KehadiranEkskul::query()

            ->where(
                'sesi_kegiatan_id',
                $this->sesi->id
            )

            ->get()

            ->keyBy('siswa_id');

        $this->anggota = $anggota

            ->map(function (AnggotaEkskul $anggota) use ($kehadiran) {

                $absen = $kehadiran->get(
                    $anggota->siswa_id
                );

                return [

                    'anggota_id' => $anggota->id,

                    'siswa_id' => $anggota->siswa_id,

                    'nama' => $anggota->siswa->nama,

                    'status' => $absen?->status
                        ?? 'tidak_hadir',

                    'rfid' => $absen?->sumber === 'rfid',

                ];
            })

            ->toArray();
    }
    public function form(
        Schema $schema,
    ): Schema {

        return $schema

            ->components([

                //

            ])

            ->statePath('data');
    }

    public function submit(): void
    {
        foreach ($this->anggota as $anggota) {

            KehadiranEkskul::updateOrCreate(

                [
                    'sesi_kegiatan_id' => $this->sesi->id,
                    'siswa_id'         => $anggota['siswa_id'],
                ],

                [
                    'status'      => $anggota['status'],
                    'sumber'      => $anggota['rfid']
                        ? 'rfid'
                        : 'manual',

                    'petugas_id'  => Auth::id(),

                    'waktu_scan'  => now(),
                ]

            );
        }

        $this->loadAnggota();

        \Filament\Notifications\Notification::make()

            ->success()

            ->title('Berhasil')

            ->body('Absensi ekstrakurikuler berhasil disimpan.')

            ->send();
    }

    public function render(): View
    {
        return view(
            'livewire.absensi-ekskul-table'
        );
    }
}
