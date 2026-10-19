<?php

namespace App\Exports\DaftarNilai;

use App\Models\DaftarNilai;
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

class DaftarNilaiSiswaExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        protected DaftarNilai $daftarNilai,
    ) {}

    public function collection(): Collection
    {
        $this->daftarNilai->loadMissing(['nilaiSiswa.siswa', 'kelas', 'pegawai', 'tahunAjaran']);

        return $this->daftarNilai->nilaiSiswa
            ->sortBy(fn ($item) => $item->siswa?->nama ?? '')
            ->values()
            ->map(fn ($item, $index) => [
                'No'      => $index + 1,
                'NISN'    => $item->siswa?->nisn,
                'Nama'    => $item->siswa?->nama,
                'Nilai'   => $item->nilai !== null ? (float) $item->nilai : null,
                'Catatan' => $item->catatan,
            ]);
    }

    public function headings(): array
    {
        $nilai = $this->daftarNilai;

        return [
            ['DAFTAR NILAI SISWA'],
            ['Kelas: ' . ($nilai->kelas?->nama_kelas ?? '-') . '   |   Mata Pelajaran: ' . $nilai->mapel],
            ['Jenis: ' . ($nilai->jenis_nilai ?? '-') . '   |   Penilaian: ' . $nilai->judul . '   |   Tanggal: ' . ($nilai->tanggal?->translatedFormat('d F Y') ?? '-')],
            ['Guru: ' . ($nilai->pegawai?->nama ?? '-') . '   |   Tahun Ajaran: ' . ($nilai->tahunAjaran?->tahun ?? '-')],
            [],
            ['No', 'NISN', 'Nama', 'Nilai', 'Catatan'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $headerRow = 6;

        foreach ([1, 2, 3, 4] as $row) {
            $sheet->mergeCells("A{$row}:{$highestColumn}{$row}");
        }

        if ($highestRow >= $headerRow) {
            $sheet->getStyle("A{$headerRow}:{$highestColumn}{$highestRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);
        }

        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            3 => [
                'font' => ['bold' => true],
            ],
            $headerRow => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEFEFEF'],
                ],
            ],
        ];

        if ($highestRow > $headerRow) {
            $firstDataRow = $headerRow + 1;

            // Nomor urut rata tengah
            $styles['A' . $firstDataRow . ':A' . $highestRow] = [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ];

            // Kolom Nilai rata tengah
            $styles['D' . $firstDataRow . ':D' . $highestRow] = [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ];
        }

        return $styles;
    }

    public function title(): string
    {
        return 'Daftar Nilai';
    }
}
