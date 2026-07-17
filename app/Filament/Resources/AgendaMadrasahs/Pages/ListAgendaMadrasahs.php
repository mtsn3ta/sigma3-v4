<?php

namespace App\Filament\Resources\AgendaMadrasahs\Pages;

use App\Filament\Resources\AgendaMadrasahs\AgendaMadrasahResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use App\Models\AgendaMadrasah;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;

class ListAgendaMadrasahs extends ListRecords
{
    protected static string $resource = AgendaMadrasahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tarikLibur')
                ->label('Tarik Libur Nasional')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('tahun')
                        ->label('Pilih Tahun')
                        ->options([
                            '2024' => '2024',
                            '2025' => '2025',
                            '2026' => '2026',
                            '2027' => '2027',
                        ])
                        ->required()
                        ->default(date('Y')),
                ])
                ->action(function (array $data) {
                    if (function_exists('opcache_reset')) {
                        opcache_reset();
                    }
                    $tahun = $data['tahun'];
                    $holidays = [];
                    $source = '';

                    try {
                        // ENDPOINT UTAMA: api-hari-libur.vercel.app
                        // Struktur asli: { status, code, data: [ {date, description} ], message }
                        // PERBAIKAN: ambil kunci 'data' langsung, jangan seluruh wrapper-nya.
                        $response = Http::withoutVerifying()
                            ->timeout(10)
                            ->get("https://api-hari-libur.vercel.app/api?year={$tahun}");

                        if ($response->successful() && !empty($response->json('data'))) {
                            $holidays = $response->json('data');
                            $source = 'vercel';
                        } else {
                            // ENDPOINT CADANGAN: gerinsp/dayoff-API
                            // Struktur: array langsung berisi {tanggal, keterangan, is_cuti}
                            $domain = "https://githubusercontent.com";
                            $path = "/gerinsp/dayoff-API/master/data/" . $tahun . ".json";

                            $responseFallback = Http::withoutVerifying()->timeout(10)->get($domain . $path);
                            if ($responseFallback->successful()) {
                                $holidays = $responseFallback->json();
                                $source = 'github';
                            }
                        }

                        // Jika semua API gagal merespons data array
                        if (empty($holidays) || !is_array($holidays)) {
                            Notification::make()
                                ->title('API Tidak Merespons')
                                ->body("Gagal mengambil data dari server API. Kemungkinan besar tahun {$tahun} belum dirilis oleh penyedia API.")
                                ->danger()->send();
                            return;
                        }

                        $count = 0;
                        $sudahAda = 0;
                        $gagalDipetakan = 0; // BARU: hitung entri yang formatnya tidak dikenali, agar tidak disamakan dengan "sudah ada"

                        foreach ($holidays as $holiday) {
                            // Mapping field dinamis menyesuaikan struktur JSON masing-masing API.
                            // PERBAIKAN: field vercel yang benar adalah 'date' dan 'description',
                            // bukan 'holiday_date' / 'holiday_name' (itu field API lain yang beda domain).
                            if ($source === 'vercel') {
                                $tanggalLibur = $holiday['date'] ?? null;
                                $namaLibur    = $holiday['description'] ?? 'Libur Nasional';
                                $isCuti       = str_contains(strtolower($namaLibur), 'cuti bersama');
                            } else {
                                $tanggalLibur = $holiday['tanggal'] ?? null;
                                $namaLibur    = $holiday['keterangan'] ?? 'Libur Nasional';
                                $isCuti       = $holiday['is_cuti'] ?? false;
                            }

                            if (!$tanggalLibur) {
                                $gagalDipetakan++;
                                continue;
                            }

                            $tanggalFormatted = date('Y-m-d', strtotime($tanggalLibur));

                            $exists = AgendaMadrasah::where('tanggal', $tanggalFormatted)->exists();

                            if (!$exists) {
                                AgendaMadrasah::create([
                                    'tanggal'      => $tanggalFormatted,
                                    'tipe_agenda'  => 'libur_nasional',
                                    'nama_agenda'  => $namaLibur,
                                    'kelas_id'     => null,
                                    'keterangan'   => $isCuti ? 'Cuti Bersama Nasional' : 'Libur Nasional',
                                ]);
                                $count++;
                            } else {
                                $sudahAda++;
                            }
                        }

                        // PERBAIKAN NOTIFIKASI: sekarang membedakan 3 kondisi berbeda,
                        // supaya tidak lagi bilang "Data Sudah Sinkron" saat sebenarnya
                        // API-nya yang gagal dipetakan (seperti bug field mapping tadi).
                        if ($count > 0) {
                            Notification::make()
                                ->title("Berhasil!")
                                ->body("Menambahkan {$count} agenda libur baru untuk tahun {$tahun}." .
                                    ($sudahAda > 0 ? " ({$sudahAda} lainnya sudah ada sebelumnya.)" : ''))
                                ->success()->send();
                        } elseif ($gagalDipetakan > 0 && $sudahAda === 0) {
                            // Semua entri gagal dipetakan — ini kemungkinan bug format API berubah,
                            // BUKAN berarti data sudah tersimpan. Jangan tampilkan sebagai sukses.
                            Notification::make()
                                ->title('Format Data API Tidak Dikenali')
                                ->body("{$gagalDipetakan} entri dari tahun {$tahun} tidak bisa dibaca sistem. Kemungkinan struktur API sumber sudah berubah — mohon laporkan ke pengembang.")
                                ->danger()->send();
                        } else {
                            Notification::make()
                                ->title("Data Sudah Sinkron")
                                ->body("Seluruh {$sudahAda} data libur dari API untuk tahun {$tahun} sudah tersimpan sebelumnya di database.")
                                ->info()->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gangguan cURL / Jaringan Server')
                            ->body('Gagal request ke API luar. Error: ' . $e->getMessage())
                            ->danger()->send();
                    }
                }),

            CreateAction::make()
                ->label('Tambah Manual'),
        ];
    }
}