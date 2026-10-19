<?php

namespace App\Support;

use Carbon\Carbon;

class JamPelajaranHelper
{
    public static function maxJam(Carbon $tanggal): int
    {
        return match ($tanggal->dayOfWeekIso) {
            1 => 8,
            2 => 9,
            3, 4 => 8,
            5 => 5,
            6 => 8,
            default => 0,
        };
    }

    public static function parse(string $jamKe): array
    {
        $jamKe = trim($jamKe);

        if (str_contains($jamKe, '-')) {
            [$awal, $akhir] = explode('-', $jamKe);

            return range(
                (int) $awal,
                (int) $akhir
            );
        }

        return [
            (int) $jamKe,
        ];
    }

    public static function sortKey(string $jamKe): int
    {
        return self::parse($jamKe)[0];
    }

    /**
     * Mendapatkan daftar alokasi slot jam pelajaran resmi berdasarkan hari.
     */
    public static function getScheduleSlots(Carbon $tanggal): array
    {
        $day = $tanggal->dayOfWeekIso; // 1 (Senin) .. 7 (Minggu)

        return match ($day) {
            // Senin & Sabtu
            1, 6 => [
                ['jam_ke' => '0', 'start' => '07:00', 'end' => '07:30', 'type' => 'apel', 'label' => 'Apel / Literasi / Upacara'],
                ['jam_ke' => '1', 'start' => '07:30', 'end' => '08:10', 'type' => 'pelajaran', 'label' => 'Jam Ke-1'],
                ['jam_ke' => '2', 'start' => '08:10', 'end' => '08:50', 'type' => 'pelajaran', 'label' => 'Jam Ke-2'],
                ['jam_ke' => '3', 'start' => '08:50', 'end' => '09:30', 'type' => 'pelajaran', 'label' => 'Jam Ke-3'],
                ['jam_ke' => '4', 'start' => '09:30', 'end' => '10:10', 'type' => 'pelajaran', 'label' => 'Jam Ke-4'],
                ['jam_ke' => 'UBDYH', 'start' => '10:10', 'end' => '10:40', 'type' => 'ubudiyah', 'label' => 'Sholat Dhuha / Ubudiyah'],
                ['jam_ke' => 'IST', 'start' => '10:40', 'end' => '11:00', 'type' => 'istirahat', 'label' => 'Istirahat'],
                ['jam_ke' => '5', 'start' => '11:00', 'end' => '11:40', 'type' => 'pelajaran', 'label' => 'Jam Ke-5'],
                ['jam_ke' => '6', 'start' => '11:40', 'end' => '12:20', 'type' => 'pelajaran', 'label' => 'Jam Ke-6'],
                ['jam_ke' => 'ZUHUR', 'start' => '12:20', 'end' => '12:55', 'type' => 'zuhur', 'label' => 'Sholat Dzuhur Berjamaah'],
                ['jam_ke' => '7', 'start' => '12:55', 'end' => '13:35', 'type' => 'pelajaran', 'label' => 'Jam Ke-7'],
                ['jam_ke' => '8', 'start' => '13:35', 'end' => '14:15', 'type' => 'pelajaran', 'label' => 'Jam Ke-8'],
            ],

            // Selasa
            2 => [
                ['jam_ke' => '1', 'start' => '07:00', 'end' => '07:40', 'type' => 'pelajaran', 'label' => 'Jam Ke-1'],
                ['jam_ke' => '2', 'start' => '07:40', 'end' => '08:20', 'type' => 'pelajaran', 'label' => 'Jam Ke-2'],
                ['jam_ke' => '3', 'start' => '08:20', 'end' => '09:00', 'type' => 'pelajaran', 'label' => 'Jam Ke-3'],
                ['jam_ke' => '4', 'start' => '09:00', 'end' => '09:40', 'type' => 'pelajaran', 'label' => 'Jam Ke-4'],
                ['jam_ke' => 'UBDYH', 'start' => '09:40', 'end' => '10:10', 'type' => 'ubudiyah', 'label' => 'Sholat Dhuha / Ubudiyah'],
                ['jam_ke' => 'IST', 'start' => '10:10', 'end' => '10:30', 'type' => 'istirahat', 'label' => 'Istirahat'],
                ['jam_ke' => '5', 'start' => '10:30', 'end' => '11:10', 'type' => 'pelajaran', 'label' => 'Jam Ke-5'],
                ['jam_ke' => '6', 'start' => '11:10', 'end' => '11:50', 'type' => 'pelajaran', 'label' => 'Jam Ke-6'],
                ['jam_ke' => 'ZUHUR', 'start' => '11:50', 'end' => '12:15', 'type' => 'zuhur', 'label' => 'Sholat Dzuhur Berjamaah'],
                ['jam_ke' => '7', 'start' => '12:15', 'end' => '12:55', 'type' => 'pelajaran', 'label' => 'Jam Ke-7'],
                ['jam_ke' => '8', 'start' => '12:55', 'end' => '13:35', 'type' => 'pelajaran', 'label' => 'Jam Ke-8'],
                ['jam_ke' => '9', 'start' => '13:35', 'end' => '14:15', 'type' => 'pelajaran', 'label' => 'Jam Ke-9'],
            ],

            // Rabu & Kamis
            3, 4 => [
                ['jam_ke' => '1', 'start' => '07:00', 'end' => '07:40', 'type' => 'pelajaran', 'label' => 'Jam Ke-1'],
                ['jam_ke' => '2', 'start' => '07:40', 'end' => '08:20', 'type' => 'pelajaran', 'label' => 'Jam Ke-2'],
                ['jam_ke' => '3', 'start' => '08:20', 'end' => '09:00', 'type' => 'pelajaran', 'label' => 'Jam Ke-3'],
                ['jam_ke' => '4', 'start' => '09:00', 'end' => '09:40', 'type' => 'pelajaran', 'label' => 'Jam Ke-4'],
                ['jam_ke' => 'UBDYH', 'start' => '09:40', 'end' => '10:10', 'type' => 'ubudiyah', 'label' => 'Sholat Dhuha / Ubudiyah'],
                ['jam_ke' => 'IST', 'start' => '10:10', 'end' => '10:55', 'type' => 'istirahat', 'label' => 'Istirahat'],
                ['jam_ke' => '5', 'start' => '10:55', 'end' => '11:35', 'type' => 'pelajaran', 'label' => 'Jam Ke-5'],
                ['jam_ke' => '6', 'start' => '11:35', 'end' => '12:15', 'type' => 'pelajaran', 'label' => 'Jam Ke-6'],
                ['jam_ke' => 'ZUHUR', 'start' => '12:15', 'end' => '12:55', 'type' => 'zuhur', 'label' => 'Sholat Dzuhur Berjamaah'],
                ['jam_ke' => '7', 'start' => '12:55', 'end' => '13:35', 'type' => 'pelajaran', 'label' => 'Jam Ke-7'],
                ['jam_ke' => '8', 'start' => '13:35', 'end' => '14:15', 'type' => 'pelajaran', 'label' => 'Jam Ke-8'],
            ],

            // Jumat
            5 => [
                ['jam_ke' => 'UBDYH', 'start' => '06:45', 'end' => '07:30', 'type' => 'ubudiyah', 'label' => 'Ubudiyah / Sholat Dhuha'],
                ['jam_ke' => '1', 'start' => '07:30', 'end' => '08:10', 'type' => 'pelajaran', 'label' => 'Jam Ke-1'],
                ['jam_ke' => '2', 'start' => '08:10', 'end' => '08:50', 'type' => 'pelajaran', 'label' => 'Jam Ke-2'],
                ['jam_ke' => '3', 'start' => '08:50', 'end' => '09:30', 'type' => 'pelajaran', 'label' => 'Jam Ke-3'],
                ['jam_ke' => 'IST', 'start' => '09:30', 'end' => '09:50', 'type' => 'istirahat', 'label' => 'Istirahat'],
                ['jam_ke' => '4', 'start' => '09:50', 'end' => '10:30', 'type' => 'pelajaran', 'label' => 'Jam Ke-4'],
                ['jam_ke' => '5', 'start' => '10:30', 'end' => '11:10', 'type' => 'pelajaran', 'label' => 'Jam Ke-5'],
            ],

            default => [],
        };
    }

    /**
     * Mendeteksi slot jam KBM yang sedang berlangsung saat ini.
     */
    public static function getCurrentSlot(?Carbon $now = null): array
    {
        $now = $now ? $now->copy() : Carbon::now('Asia/Jakarta');

        if ($now->isSunday()) {
            return [
                'type' => 'libur',
                'jam_ke' => null,
                'label' => 'Libur Akhir Pekan (Minggu)',
                'is_kbm' => false,
                'is_break' => false,
                'start' => null,
                'end' => null,
            ];
        }

        $currentTimeStr = $now->format('H:i');
        $slots = self::getScheduleSlots($now);

        if (empty($slots)) {
            return [
                'type' => 'luar_kbm',
                'jam_ke' => null,
                'label' => 'Di Luar Jam Operasional',
                'is_kbm' => false,
                'is_break' => false,
                'start' => null,
                'end' => null,
            ];
        }

        foreach ($slots as $slot) {
            if ($currentTimeStr >= $slot['start'] && $currentTimeStr < $slot['end']) {
                $startTime = $now->copy()->setTimeFromTimeString($slot['start']);
                $endTime = $now->copy()->setTimeFromTimeString($slot['end']);

                return [
                    'type' => $slot['type'],
                    'jam_ke' => $slot['jam_ke'],
                    'label' => $slot['label'],
                    'is_kbm' => $slot['type'] === 'pelajaran',
                    'is_break' => in_array($slot['type'], ['istirahat', 'ubudiyah', 'zuhur', 'apel']),
                    'start' => $slot['start'],
                    'end' => $slot['end'],
                    'elapsed_minutes' => max(0, (int) $startTime->diffInMinutes($now)),
                    'remaining_minutes' => max(0, (int) $now->diffInMinutes($endTime)),
                ];
            }
        }

        // Jika sebelum jam KBM dimulai
        $firstSlot = $slots[0];
        if ($currentTimeStr < $firstSlot['start']) {
            return [
                'type' => 'sebelum_kbm',
                'jam_ke' => null,
                'label' => 'Menjelang KBM (Mulai pukul ' . $firstSlot['start'] . ')',
                'is_kbm' => false,
                'is_break' => false,
                'start' => null,
                'end' => $firstSlot['start'],
            ];
        }

        // Jika setelah jam KBM selesai
        $lastSlot = end($slots);
        return [
            'type' => 'selesai_kbm',
            'jam_ke' => null,
            'label' => 'KBM Hari Ini Selesai',
            'is_kbm' => false,
            'is_break' => false,
            'start' => $lastSlot['end'],
            'end' => null,
        ];
    }
}
