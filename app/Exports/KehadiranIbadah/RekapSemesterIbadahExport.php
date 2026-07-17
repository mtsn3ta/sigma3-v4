<?php

namespace App\Exports\KehadiranIbadah;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapSemesterIbadahExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
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

                'kehadiranIbadah as dhuha_hadir' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dhuha')
                    ->where('status', 'hadir'),

                'kehadiranIbadah as dhuha_menstruasi' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dhuha')
                    ->where('status', 'menstruasi'),

                'kehadiranIbadah as dhuha_tidak_hadir' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dhuha')
                    ->where('status', 'tidak_hadir'),

                'kehadiranIbadah as dzuhur_hadir' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dzuhur')
                    ->where('status', 'hadir'),

                'kehadiranIbadah as dzuhur_menstruasi' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dzuhur')
                    ->where('status', 'menstruasi'),

                'kehadiranIbadah as dzuhur_tidak_hadir' => fn($q) =>

                $q
                    ->whereBetween('tanggal', $this->getRentangSemester())
                    ->where('jenis', 'dzuhur')
                    ->where('status', 'tidak_hadir'),

            ]);

        if ($this->kelasId) {

            $query->whereHas(

                'kelasAktif',

                fn($q) => $q->where(
                    'kelas_id',
                    $this->kelasId
                )

            );
        }

        return $query

            ->orderBy('nama')

            ->get()

            ->map(function ($siswa, $index) {

                return [

                    'No' => $index + 1,

                    'Nama' => $siswa->nama,

                    'Kelas' => $siswa->kelasAktif?->kelas?->nama_kelas,

                    'Dhuha Hadir' => $siswa->dhuha_hadir,

                    'Dhuha Menstruasi' => $siswa->dhuha_menstruasi,

                    'Dhuha Tidak Hadir' => $siswa->dhuha_tidak_hadir,

                    'Dzuhur Hadir' => $siswa->dzuhur_hadir,

                    'Dzuhur Menstruasi' => $siswa->dzuhur_menstruasi,

                    'Dzuhur Tidak Hadir' => $siswa->dzuhur_tidak_hadir,

                ];
            });
    }

    public function headings(): array
    {
        return [

            [

                'REKAP KEHADIRAN IBADAH - SEMESTER '
                    . strtoupper($this->semester)
                    . ' TA '
                    . $this->tahun,

            ],

            [],

            [

                'No',

                'Nama',

                'Kelas',

                'Dhuha Hadir',

                'Dhuha Menstruasi',

                'Dhuha Tidak Hadir',

                'Dzuhur Hadir',

                'Dzuhur Menstruasi',

                'Dzuhur Tidak Hadir',

            ],

        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();

        $highestColumn = $sheet->getHighestColumn();

        $sheet->mergeCells(
            'A1:' . $highestColumn . '1'
        );

        $sheet

            ->getStyle(
                'A3:' . $highestColumn . $highestRow
            )

            ->applyFromArray([

                'borders' => [

                    'allBorders' => [

                        'borderStyle' => Border::BORDER_THIN,

                        'color' => [

                            'argb' => 'FF000000',

                        ],

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

                    'startColor' => [

                        'argb' => 'FFEFEFEF',

                    ],

                ],

            ],

            'A4:A' . $highestRow => [

                'alignment' => [

                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],

            ],

            'D4:I' . $highestRow => [

                'alignment' => [

                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                ],

            ],

        ];
    }

    public function title(): string
    {
        return 'Semester ' . ucfirst($this->semester);
    }
}
