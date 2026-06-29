<?php

namespace App\Exports\KehadiranSekolah;

use App\Models\Siswa;
use Carbon\Carbon;
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

class RekapBulananExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected int $bulan,
        protected int $tahun,
        protected ?int $kelasId = null,
    ) {}

    public function collection(): Collection
    {
        $query = Siswa::query()
            ->with(['kelasAktif.kelas'])
            ->withCount([
                'kehadiranSekolah as hadir_count' => fn($q) =>
                $q->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status', 'hadir'),

                'kehadiranSekolah as terlambat_count' => fn($q) =>
                $q->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status', 'terlambat'),

                'kehadiranSekolah as izin_count' => fn($q) =>
                $q->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status', 'izin'),

                'kehadiranSekolah as sakit_count' => fn($q) =>
                $q->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status', 'sakit'),

                'kehadiranSekolah as alfa_count' => fn($q) =>
                $q->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status', 'alfa'),
            ]);

        if ($this->kelasId) {
            $query->whereHas(
                'kelasAktif',
                fn($q) => $q->where('kelas_id', $this->kelasId)
            );
        }

        return $query
            ->orderBy('nama')
            ->get()
            ->map(function ($siswa, $index) {
                $totalHari =
                    $siswa->hadir_count +
                    $siswa->terlambat_count +
                    $siswa->izin_count +
                    $siswa->sakit_count +
                    $siswa->alfa_count;

                $persentase = $totalHari
                    ? round((($siswa->hadir_count + $siswa->terlambat_count) / $totalHari) * 100)
                    : 0;

                return [
                    'No'          => $index + 1, // Menambahkan kolom Nomor urut
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
        // Mendapatkan nama bulan dalam bahasa Indonesia (opsional jika locale sudah id)
        $namaBulan = Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F');

        return [
            // Baris 1: Judul Utama
            ['REKAP KEHADIRAN SISWA - BULAN ' . strtoupper($namaBulan) . ' ' . $this->tahun],
            // Baris 2: Kosong sebagai jarak
            [],
            // Baris 3: Header Tabel
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

    public function styles(Worksheet $sheet)
    {
        // Mencari baris terakhir yang ada datanya
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // 1. Menggabungkan sel untuk Judul (Merge Cells A1 sampai I1)
        $sheet->mergeCells('A1:' . $highestColumn . '1');

        // 2. Menerapkan Garis Tabel (Borders) dari baris header (3) sampai data terakhir
        $sheet->getStyle('A3:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [
            // Styling Baris 1 (Judul Utama)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Styling Baris 3 (Header Tabel)
            3 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEFEFEF'], // Warna abu-abu muda
                ],
            ],

            // Rata Tengah (Center) untuk Kolom Nomor urut (A)
            'A4:A' . $highestRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],

            // Rata Tengah (Center) untuk angka kehadiran (Mulai kolom D sampai I)
            'D4:I' . $highestRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    // (Opsional) Mengubah nama tab sheet di dalam file Excel
    public function title(): string
    {
        return 'Rekap Bulan ' . $this->bulan;
    }
}
