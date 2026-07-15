<?php

namespace App\Filament\Resources\AgendaMadrasahs\Pages;

use App\Filament\Resources\AgendaMadrasahs\AgendaMadrasahResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CreateAgendaMadrasah extends CreateRecord
{
    protected static string $resource = AgendaMadrasahResource::class;

    // Fungsi ini akan mengambil alih proses penyimpanan ke database
    protected function handleRecordCreation(array $data): Model
    {
        $tanggalMulai = Carbon::parse($data['tanggal']);
        $tanggalSelesai = !empty($data['tanggal_selesai']) ? Carbon::parse($data['tanggal_selesai']) : null;

        // Buang kunci 'tanggal_selesai' dari array karena kolom ini tidak ada di database
        unset($data['tanggal_selesai']);

        // Skenario A: Hanya 1 hari (tanggal selesai kosong atau sama dengan tanggal mulai)
        if (!$tanggalSelesai || $tanggalMulai->equalTo($tanggalSelesai)) {
            return static::getModel()::create($data);
        }

        // Skenario B: Rentang waktu (Berhari-hari)
        $period = CarbonPeriod::create($tanggalMulai, $tanggalSelesai);
        $firstRecord = null;

        foreach ($period as $date) {
            $recordData = $data;
            $recordData['tanggal'] = $date->format('Y-m-d'); // Ganti tanggal sesuai urutan hari

            // Simpan per hari ke database
            $record = static::getModel()::create($recordData);

            // Filament butuh 1 model untuk dikembalikan agar tidak error, kita ambil yang hari pertama
            if (!$firstRecord) {
                $firstRecord = $record;
            }
        }

        return $firstRecord;
    }
}
