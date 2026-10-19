<?php

namespace App\Filament\Resources\DaftarNilais\Schemas;

use App\Models\Kelas;
use App\Models\PlotingGuru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DaftarNilaiForm
{
    /**
     * Cache nama siswa per request agar Placeholder tidak query berulang.
     *
     * @var array<int, string>
     */
    protected static array $namaSiswa = [];

    public static function namaSiswa(?int $siswaId): string
    {
        if (blank($siswaId)) {
            return '-';
        }

        return static::$namaSiswa[$siswaId] ??= (string) (Siswa::query()
            ->whereKey($siswaId)
            ->value('nama') ?? '-');
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Penilaian')
                    ->description('Tentukan kelas, mata pelajaran, dan jenis penilaian terlebih dahulu.')
                    ->icon('heroicon-o-document-chart-bar')
                    ->columns(2)
                    ->schema([
                        Select::make('pegawai_id')
                            ->label('Guru')
                            ->relationship('pegawai', 'nama')
                            ->hiddenOn('create')
                            ->required(),

                        Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->relationship('tahunAjaran', 'tahun')
                            ->default(fn (): ?int => TahunAjaran::aktifId())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                if (filled($get('kelas_id'))) {
                                    $set('nilaiSiswa', self::siswaUntukKelas($get('kelas_id'), $state, $get('nilaiSiswa') ?? []));
                                }
                            }),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(fn () => Kelas::query()
                                ->orderBy('tingkat')
                                ->orderBy('nama_kelas')
                                ->pluck('nama_kelas', 'id'))
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->live()
                            ->helperText('Daftar siswa di bawah akan dimuat ulang setiap kali kelas atau tahun ajaran diubah.')
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                $set('nilaiSiswa', self::siswaUntukKelas($state, $get('tahun_ajaran_id'), $get('nilaiSiswa') ?? []));
                            }),

                        Select::make('mapel')
                            ->label('Mata Pelajaran')
                            ->options(function (Get $get): array {
                                $pegawaiId = auth()->user()?->pegawai?->id;
                                $tahunAjaranId = $get('tahun_ajaran_id') ?: TahunAjaran::aktifId();

                                $query = PlotingGuru::query()
                                    ->when($pegawaiId, fn ($q) => $q->where('pegawai_id', $pegawaiId))
                                    ->whereNotNull('mapel');

                                $options = (clone $query)
                                    ->when($tahunAjaranId, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                                    ->distinct()
                                    ->orderBy('mapel')
                                    ->pluck('mapel', 'mapel')
                                    ->toArray();

                                // Jika guru belum punya ploting di tahun ajaran terpilih,
                                // tetap tampilkan mapel dari tahun ajaran lain agar form tidak macet.
                                if (empty($options)) {
                                    $options = (clone $query)
                                        ->distinct()
                                        ->orderBy('mapel')
                                        ->pluck('mapel', 'mapel')
                                        ->toArray();
                                }

                                // Fallback terakhir: daftar mapel madrasah secara keseluruhan.
                                if (empty($options)) {
                                    $options = PlotingGuru::query()
                                        ->whereNotNull('mapel')
                                        ->distinct()
                                        ->orderBy('mapel')
                                        ->pluck('mapel', 'mapel')
                                        ->toArray();
                                }

                                $mapelSaatIni = $get('mapel');

                                if (filled($mapelSaatIni) && ! isset($options[$mapelSaatIni])) {
                                    $options[$mapelSaatIni] = $mapelSaatIni;
                                }

                                return $options;
                            })
                            ->searchable()
                            ->native(false)
                            ->required(),

                        TextInput::make('judul')
                            ->label('Nama Penilaian')
                            ->placeholder('Contoh: Ulangan Harian Bab 1')
                            ->required(),

                        Select::make('jenis_nilai')
                            ->label('Jenis Penilaian')
                            ->options([
                                'Harian' => 'Ulangan Harian',
                                'PTS' => 'PTS / UTS',
                                'PAS' => 'PAS / UAS',
                                'Tugas' => 'Tugas',
                                'Praktik' => 'Praktik',
                                'Sikap' => 'Sikap',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->default('Harian')
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Penilaian')
                            ->default(fn () => now('Asia/Jakarta')->toDateString())
                            ->native(false)
                            ->displayFormat('d F Y'),

                        FileUpload::make('file_path')
                            ->label('Lampiran (opsional)')
                            ->helperText('Unggah rekap nilai (PDF/Excel) bila ada. Nilai per siswa tetap diisi pada tabel di bawah.')
                            ->disk('public')
                            ->directory('daftar-nilai')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi (opsional)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Daftar Nilai Siswa')
                    ->description('Pilih kelas terlebih dahulu. Daftar siswa muncul otomatis sesuai kelas dan tahun ajaran.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        Repeater::make('nilaiSiswa')
                            ->relationship()
                            ->label('')
                            ->schema([
                                Hidden::make('siswa_id'),

                                Placeholder::make('nama_siswa')
                                    ->label('Nama Siswa')
                                    ->content(fn (Get $get): string => self::namaSiswa($get('siswa_id')))
                                    ->dehydrated(false)
                                    ->columnSpan(6),

                                TextInput::make('nilai')
                                    ->label('Nilai')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->placeholder('0–100')
                                    ->columnSpan(2),

                                TextInput::make('catatan')
                                    ->label('Catatan')
                                    ->placeholder('Opsional')
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull(),

                        Placeholder::make('petunjuk_pilih_kelas')
                            ->hiddenLabel()
                            ->content('Pilih kelas terlebih dahulu agar daftar siswa muncul di sini.')
                            ->visible(fn (Get $get): bool => blank($get('kelas_id')))
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Ambil siswa berdasarkan kelas dan tahun ajaran, lalu jadikan state awal repeater.
     * Nilai yang sudah terisi dipertahankan (dicocokkan berdasarkan siswa_id).
     *
     * @param  array<int|string, array<string, mixed>>  $nilaiTersimpan
     * @return array<string, array{siswa_id: int, nilai: mixed, catatan: mixed}>
     */
    public static function siswaUntukKelas(?string $kelasId, ?string $tahunAjaranId, array $nilaiTersimpan = []): array
    {
        if (blank($kelasId)) {
            return [];
        }

        $nilaiLama = collect($nilaiTersimpan)->keyBy('siswa_id');

        return Siswa::query()
            ->whereHas('kelasSiswa', function ($query) use ($kelasId, $tahunAjaranId): void {
                $query->where('kelas_id', $kelasId);

                if (filled($tahunAjaranId)) {
                    $query->where('tahun_ajaran_id', $tahunAjaranId);
                }
            })
            ->orderBy('nama')
            ->get()
            ->mapWithKeys(function (Siswa $siswa) use ($nilaiLama): array {
                $lama = $nilaiLama->get($siswa->id, []);

                return [
                    (string) Str::uuid() => [
                        'siswa_id' => $siswa->id,
                        'nilai' => $lama['nilai'] ?? null,
                        'catatan' => $lama['catatan'] ?? null,
                    ],
                ];
            })
            ->toArray();
    }
}
