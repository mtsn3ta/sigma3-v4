<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View as ViewContract;
use Filament\Schemas\Components\View as SchemaView;
use Livewire\Component;
use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Services\Kehadiran\KehadiranSekolahService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentLeaveRequest;
use Carbon\CarbonPeriod;

class VerifikasiKehadiranForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    public string $tab = 'parent';
    public ?ParentLeaveRequest $previewRequest = null;

    public bool $showPreview = false;
    public bool $showRejectModal = false;

    public ?int $rejectId = null;

    public string $verificationNote = '';
    public ?array $data = [];

    public function mount(): void
    {
        // PERBAIKAN PASTI: Memastikan form pertama kali dibuka langsung mengunci tanggal 13 (WIB)
        $this->form->fill([
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'kelas_id' => null,
            // siswa_id sekarang array karena CheckboxList mendukung multi-pilih.
            'siswa_id' => [],
            'status' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Verifikasi Presensi Manual')
                    ->description('Pencatatan status kehadiran siswa secara manual oleh Guru Piket.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 3,
                        ])->schema([
                            DatePicker::make('tanggal')
                                ->label('Tanggal')
                                ->required()
                                ->default(now('Asia/Jakarta')->toDateString())
                                ->native(false),

                            Select::make('kelas_id')
                                ->label('Pilih Kelas')
                                ->options(KelasHelper::options())
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', [])),

                            Radio::make('status')
                                ->label('Status Kehadiran')
                                ->options([
                                    'hadir' => 'Hadir',
                                    'terlambat' => 'Terlambat',
                                    'sakit' => 'Sakit',
                                    'izin' => 'Izin',
                                    'alfa' => 'Alfa',
                                ])
                                ->inline()
                                ->required()
                                ->live(),
                        ]),

                        TimePicker::make('jam_datang')
                            ->label('Jam Datang')
                            ->seconds(false)
                            ->visible(fn ($get) => in_array($get('status'), ['hadir', 'terlambat'])),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(2)
                            ->columnSpanFull(),

                        CheckboxList::make('siswa_id')
                            ->label('Pilih Siswa')
                            ->options(function ($get) {
                                $kelasId = $get('kelas_id');
                                if (! $kelasId) {
                                    return [];
                                }
                                return SiswaHelper::optionsByKelas((int) $kelasId);
                            })
                            ->searchable()
                            ->bulkToggleable()
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                            ])
                            ->required()
                            ->visible(fn ($get) => (bool) $get('kelas_id')),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(KehadiranSekolahService $service): void
    {
        $formData = $this->form->getState();

        $user = Auth::user();
        $petugas = Pegawai::where('user_id', $user->id)->firstOrFail();

        $siswaIds = (array) ($formData['siswa_id'] ?? []);
        $tanggal = $formData['tanggal'];
        $status = $formData['status'];
        $keterangan = $formData['keterangan'] ?? null;
        $jamDatang = $formData['jam_datang'] ?? null;

        $siswaList = Siswa::whereIn('id', $siswaIds)->get();

        foreach ($siswaList as $siswa) {
            match ($status) {
                'hadir' => $service->markHadir(
                    siswa: $siswa,
                    jamDatang: $jamDatang ?? now('Asia/Jakarta')->format('H:i:s'),
                    petugas: $petugas,
                    tanggal: $tanggal
                ),
                'terlambat' => $service->markTerlambat(
                    siswa: $siswa,
                    jamDatang: $jamDatang ?? now('Asia/Jakarta')->format('H:i:s'),
                    petugas: $petugas,
                    tanggal: $tanggal
                ),
                'sakit' => $service->markSakit(
                    siswa: $siswa,
                    keterangan: $keterangan,
                    petugas: $petugas,
                    tanggal: $tanggal
                ),
                'izin' => $service->markIzin(
                    siswa: $siswa,
                    keterangan: $keterangan,
                    petugas: $petugas,
                    tanggal: $tanggal
                ),
                'alfa' => $service->markAlfa(
                    siswa: $siswa,
                    keterangan: $keterangan,
                    petugas: $petugas,
                    tanggal: $tanggal
                ),
            };
        }

        Notification::make()
            ->success()
            ->title('Presensi Berhasil Disimpan')
            ->body(count($siswaIds) . ' siswa telah diperbarui status kehadirannya.')
            ->send();

        $kelas = $this->data['kelas_id'] ?? null;

        $this->form->fill([
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'status' => null,
            'keterangan' => null,
            'jam_datang' => null,
            'kelas_id' => $kelas,
            'siswa_id' => [],
        ]);
    }

    public function approve(
        int $id,
        KehadiranSekolahService $service,
    ) {
        $request = ParentLeaveRequest::with('siswa')->findOrFail($id);

        if ($request->status !== 'pending') {
            Notification::make()
                ->warning()
                ->title('Pengajuan sudah diproses')
                ->send();

            return;
        }
        $user = Auth::user();

        $petugas = Pegawai::where(
            'user_id',
            $user->id
        )->firstOrFail();

        // Loop setiap hari dalam rentang tanggal izin/sakit
        $period = CarbonPeriod::create(
            $request->tanggal_mulai->format('Y-m-d'),
            $request->tanggal_selesai->format('Y-m-d')
        );

        foreach ($period as $date) {
            $tgl = $date->format('Y-m-d');
            if ($request->jenis === 'izin') {
                $service->markIzin(
                    siswa: $request->siswa,
                    keterangan: $request->keterangan,
                    petugas: $petugas,
                    tanggal: $tgl,
                );
            } else {
                $service->markSakit(
                    siswa: $request->siswa,
                    keterangan: $request->keterangan,
                    petugas: $petugas,
                    tanggal: $tgl,
                );
            }
        }

        $request->update([
            'status' => 'approved',
            'verified_by' => $petugas->id,
            'verified_at' => now('Asia/Jakarta'),
        ]);

        Notification::make()
            ->success()
            ->title('Berhasil')
            ->body('Pengajuan berhasil disetujui.')
            ->send();

        $this->dispatch('$refresh');
    }

    public function reject(): void
    {
        $request = ParentLeaveRequest::findOrFail(
            $this->rejectId
        );

        $user = Auth::user();

        $petugas = Pegawai::where(
            'user_id',
            $user->id
        )->firstOrFail();

        $request->update([
            'status' => 'rejected',
            'verified_by' => $petugas->id,
            'verified_at' => now('Asia/Jakarta'),
            'verification_note' => $this->verificationNote,
        ]);

        Notification::make()
            ->danger()
            ->title('Pengajuan ditolak')
            ->send();

        $this->showRejectModal = false;
        $this->rejectId = null;
        $this->verificationNote = '';
        $this->dispatch('$refresh');
    }

    public function preview(int $id): void
    {
        $this->previewRequest = ParentLeaveRequest::with([
            'siswa.kelasAktif.kelas',
            'verifier',
        ])->findOrFail($id);

        $this->showPreview = true;
    }

    public function openReject(
        int $id,
    ): void {
        $this->rejectId = $id;
        $this->verificationNote = '';
        $this->showRejectModal = true;
    }

    public function render(): ViewContract
    {
        return view('livewire.verifikasi-kehadiran-form');
    }
}