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

                Grid::make([
                    'default' => 1,
                    'xl' => 3,
                ])
                    ->schema([

                        Section::make('Verifikasi Kehadiran')
                            ->columnSpan(2)
                            ->schema([

                                DatePicker::make('tanggal')
                                    // PERBAIKAN: Mengunci tanggal default hari ini ke zona waktu Asia/Jakarta (WIB)
                                    ->default(now('Asia/Jakarta')->toDateString())
                                    ->required(),
                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(KelasHelper::options())
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    // Reset ke array kosong, bukan null, karena siswa_id sekarang multi-pilih.
                                    ->afterStateUpdated(fn(callable $set) => $set('siswa_id', []))
                                    ->required(),

                                // PERBAIKAN UX: diganti dari Select (single, dropdown) menjadi CheckboxList
                                // (multi-pilih) supaya guru piket bisa centang banyak siswa berstatus sama
                                // sekaligus (mis. centang semua yang sakit) tanpa submit satu-satu.
                                CheckboxList::make('siswa_id')
                                    ->label('Siswa')
                                    ->options(fn(callable $get) => SiswaHelper::belumRfidOptions(
                                        $get('kelas_id')
                                    ))
                                    ->searchable()
                                    // Tombol "select all / deselect all" — mempercepat kasus
                                    // "semua hadir kecuali beberapa" atau sebaliknya.
                                    ->bulkToggleable()
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->live()
                                    ->required()
                                    ->helperText('Centang siswa yang berstatus sama, lalu simpan. Ulangi untuk status yang berbeda.'),

                                Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'terlambat' => 'Terlambat',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alfa' => 'Alfa',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {

                                        if ($state === 'hadir') {
                                            $set('jam_datang', '06:45');
                                        }

                                        if ($state === 'terlambat') {
                                            $set('jam_datang', '07:01');
                                        }

                                        if (in_array($state, ['izin', 'sakit', 'alfa'])) {
                                            $set('jam_datang', null);
                                        }
                                    })
                                    ->required(),

                                TimePicker::make('jam_datang')
                                    ->label('Jam Datang')
                                    ->seconds(false)
                                    ->visible(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['hadir', 'terlambat']
                                    ))
                                    ->required(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['hadir', 'terlambat']
                                    )),

                                Textarea::make('keterangan')
                                    ->label('Keterangan / Catatan')
                                    ->placeholder(fn(callable $get) => match ($get('status')) {
                                        'izin' => 'Contoh: Izin mengikuti acara keluarga.',
                                        'sakit' => 'Contoh: Demam, izin dari orang tua.',
                                        'alfa' => 'Contoh: Tidak hadir tanpa pemberitahuan.',
                                        default => '',
                                    })
                                    ->rows(3)
                                    ->visible(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['izin', 'sakit', 'alfa']
                                    ))
                                    ->required(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['izin', 'sakit', 'alfa']
                                    )),

                            ]),

                        Section::make('Panduan')
                            ->columnSpan(1)
                            ->schema([
                                SchemaView::make('filament.guru-piket.panduan-kehadiran'),
                            ]),

                    ]),

            ])
            ->statePath('data');
    }

    public function submit(
        KehadiranSekolahService $service,
    ): void {
        $data = $this->form->getState();

        $siswaIds = $data['siswa_id'] ?? [];

        // Guard tambahan di luar validasi form, jaga-jaga state kosong.
        if (empty($siswaIds)) {
            Notification::make()
                ->warning()
                ->title('Pilih minimal satu siswa')
                ->send();

            return;
        }

        $user = Auth::user();

        $petugas = Pegawai::where(
            'user_id',
            $user->id,
        )->firstOrFail();

        // Terapkan status/jam/keterangan yang sama ke setiap siswa yang dicentang.
        foreach ($siswaIds as $siswaId) {
            $siswa = Siswa::findOrFail($siswaId);

            match ($data['status']) {
                'hadir' => $service->markManualHadir(
                    siswa: $siswa,
                    jamDatang: $data['jam_datang'],
                    petugas: $petugas,
                    tanggal: $data['tanggal'],
                ),
                'terlambat' => $service->markManualTerlambat(
                    siswa: $siswa,
                    jamDatang: $data['jam_datang'],
                    petugas: $petugas,
                    tanggal: $data['tanggal'],
                ),
                'izin' => $service->markIzin(
                    siswa: $siswa,
                    keterangan: $data['keterangan'],
                    petugas: $petugas,
                    tanggal: $data['tanggal'],
                ),
                'sakit' => $service->markSakit(
                    siswa: $siswa,
                    keterangan: $data['keterangan'],
                    petugas: $petugas,
                    tanggal: $data['tanggal'],
                ),
                'alfa' => $service->markAlfa(
                    siswa: $siswa,
                    keterangan: $data['keterangan'],
                    petugas: $petugas,
                    tanggal: $data['tanggal'],
                ),
                default => null,
            };
        }

        Notification::make()
            ->success()
            ->title('Berhasil')
            ->body(count($siswaIds) . ' siswa berhasil diverifikasi sebagai "' . ucfirst($data['status']) . '".')
            ->send();

        $kelas = $data['kelas_id'];

        $tanggal = $data['tanggal'];

        $this->form->fill([

            'tanggal' => $tanggal,

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

        if ($request->jenis === 'izin') {
            $service->markIzin(
                siswa: $request->siswa,
                keterangan: $request->keterangan,
                petugas: $petugas,
            );
        } else {
            $service->markSakit(
                siswa: $request->siswa,
                keterangan: $request->keterangan,
                petugas: $petugas,
            );
        }

        $request->update([
            'status' => 'approved',
            'verified_by' => $petugas->id,
            // PERBAIKAN 1: Kunci zona waktu persetujuan ke Asia/Jakarta (WIB)
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
            // PERBAIKAN 2: Kunci zona waktu penolakan ke Asia/Jakarta (WIB)
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
        $this->previewRequest = ParentLeaveRequest::with(
            'siswa.kelasAktif.kelas'
        )->findOrFail($id);

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