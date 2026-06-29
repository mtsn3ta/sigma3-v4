<?php

namespace App\Exports\KehadiranSekolah;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapSemesterExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected string $tahun,
        protected string $semester,
        protected ?int $kelasId = null,
    ) {}

    protected function getRentangSemester(): array
    {
        [$tahunAwal, $tahunAkhir] = explode('/', $this->tahun);

        if ($this->semester === 'ganjil') {
            return [
                "{$tahunAwal}-07-01",
                "{$tahunAwal}-12-31",
            ];
        }

        return [
            "{$tahunAkhir}-01-01",
            "{$tahunAkhir}-06-30",
        ];
    }

    public function collection(): Collection
    {
        $query = Siswa::query()
            ->with(['kelasAktif.kelas'])
            ->withCount([
                'kehadiranSekolah as hadir_count' => fn($query) =>
                $query->whereBetween('tanggal', $this->getRentangSemester())->where('status', 'hadir'),

                'kehadiranSekolah as terlambat_count' => fn($query) =>
                $query->whereBetween('tanggal', $this->getRentangSemester())->where('status', 'terlambat'),

                'kehadiranSekolah as izin_count' => fn($query) =>
                $query->whereBetween('tanggal', $this->getRentangSemester())->where('status', 'izin'),

                'kehadiranSekolah as sakit_count' => fn($query) =>
                $query->whereBetween('tanggal', $this->getRentangSemester())->where('status', 'sakit'),

                'kehadiranSekolah as alfa_count' => fn($query) =>
                $query->whereBetween('tanggal', $this->getRentangSemester())->where('status', 'alfa'),
            ]);

        if ($this->kelasId) {
            $query->whereHas(
                'kelasAktif',
                fn($query) => $query->where('kelas_id', $this->kelasId)
            );
        }

        return $query
            ->orderBy('nama')
            ->get()
            ->map(function ($siswa, $index) {
                $totalHadir = $siswa->hadir_count + $siswa->terlambat_count;

                $totalHari =
                    $siswa->hadir_count +
                    $siswa->terlambat_count +
                    $siswa->izin_count +
                    $siswa->sakit_count +
                    $siswa->alfa_count;

                $persentase = $totalHari
                    ? round(($totalHadir / $totalHari) * 100)
                    : 0;

                return [
                    'No'          => $index + 1,
                    'Nama'        => $siswa->nama,
                    'Kelas'       => $siswa->kelasAktif?->kelas?->nama_kelas,
                    'Hadir'       => $siswa->hadir_count,
                    'Terlambat'   => $siswa->terlambat_count,
                    'Izin'        => $siswa->izin_count,
                    'Sakit'       => $siswa->sakit_count,
                    'Alfa'        => $siswa->alfa_count,
                    'Persentase'  => $persentase . '%',
                ];
            });
    }

    public function headings(): array
    {
        return [
            ['REKAP KEHADIRAN SISWA - SEMESTER ' . strtoupper($this->semester) . ' TA ' . $this->tahun],
            [],
            [
                'No',
                'Nama',
                'Kelas',
                'Hadir',
                'Terlambat',
                'Izin',
                'Sakit',
                'Alfa',
                '% Kehadiran',
            ]
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Menggabungkan baris judul utama
        $sheet->mergeCells('A1:' . $highestColumn . '1');

        // Membuat batas garis tipis otomatis pada area tabel data
        $sheet->getStyle('A3:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            3 => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEFEFEF'], // Latar abu-abu terang pada header kolom
                ],
            ],
            'A4:A' . $highestRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'D4:I' . $highestRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Semester ' . ucfirst($this->semester);
    }
}
