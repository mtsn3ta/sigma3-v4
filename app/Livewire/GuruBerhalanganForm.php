<?php

namespace App\Livewire;

use App\Models\Kelas;
use App\Models\TugasGuruIzin;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class GuruBerhalanganForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public ?TugasGuruIzin $record = null;

    private const MODAL_ID = 'guru-berhalangan-modal';

    public function mount(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->record = null;

        $this->form->fill([
            // PERBAIKAN 1: Mengunci nilai default tanggal ke Asia/Jakarta (WIB)
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'kelas_id' => null,
            'mapel' => null,
            'jam_ke' => null,
            'isi_tugas' => null,
        ]);
    }

    #[On('tambah-guru-berhalangan')]
    public function tambah(): void
    {
        $this->resetForm();

        $this->dispatch(
            'open-modal',
            id: self::MODAL_ID
        );
    }

    #[On('edit-guru-berhalangan')]
    public function edit(int $id): void
    {
        $this->record = TugasGuruIzin::findOrFail($id);

        $this->form->fill([
            'tanggal' => $this->record->tanggal->toDateString(),
            'kelas_id' => $this->record->kelas_id,
            'mapel' => $this->record->mapel,
            'jam_ke' => $this->record->jam_ke,
            'isi_tugas' => $this->record->isi_tugas,
        ]);

        $this->dispatch(
            'open-modal',
            id: self::MODAL_ID
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        // PERBAIKAN: Menampilkan SEMUA kelas secara aman, lalu membuang nama yang kembar lewat PHP ->unique()
                        Kelas::query()
                            ->orderBy('tingkat')
                            ->orderBy('nama_kelas')
                            ->get()
                            ->unique('nama_kelas') // Menghilangkan duplikasi jika ada nama kelas sama dari tahun lalu
                            ->pluck('nama_kelas', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('mapel')
                    ->label('Mata Pelajaran')
                    ->required(),

                Select::make('jam_ke')
                    ->label('Jam Ke')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        // Opsi gabungan bawaan awal
                        '1-2' => '1 - 2',
                        '3-4' => '3 - 4',
                        '5-6' => '5 - 6',
                        '7-8' => '7 - 8',
                        // PERBAIKAN 3: Menambahkan opsi jam pelajaran silang
                        '2-3' => '2 - 3',
                        '4-5' => '4 - 5',
                        '6-7' => '6 - 7',
                        '8-9' => '8 - 9',
                    ])
                    ->required(),

                Textarea::make('isi_tugas')
                    ->label('Isi Tugas')
                    ->rows(8)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $isEdit = $this->record !== null;

        DB::transaction(function () use ($data) {
            if ($this->record) {
                $this->record->update([
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'mapel' => $data['mapel'],
                    'jam_ke' => $data['jam_ke'],
                    'isi_tugas' => $data['isi_tugas'],
                ]);
            } else {
                TugasGuruIzin::create([
                    'pegawai_id' => Auth::user()->pegawai->id,
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'mapel' => $data['mapel'],
                    'jam_ke' => $data['jam_ke'],
                    'isi_tugas' => $data['isi_tugas'],
                    'status' => 'baru',
                ]);
            }
        });

        Notification::make()
            ->title($isEdit ? 'Pengajuan berhasil diperbarui.' : 'Pengajuan berhasil dikirim.')
            ->success()
            ->send();

        $this->dispatch(
            'close-modal',
            id: self::MODAL_ID
        );

        $this->resetForm();

        $this->dispatch('refresh-riwayat-guru-berhalangan');
    }

    public function render(): View
    {
        return view('livewire.guru-berhalangan-form');
    }
}
