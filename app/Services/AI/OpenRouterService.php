<?php

namespace App\Services\AI;

use App\Models\Siswa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.openrouter.api_key');
        $this->model = (string) config('services.openrouter.model', 'minimax/minimax-m3.1-flash:free');
    }

    /**
     * Kirim pertanyaan wali murid ke AI OpenRouter
     */
    public function ask(string $phone, string $userMessage): ?string
    {
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            // 1. Cari data siswa berdasarkan nomor telepon wali/ortu jika ada
            $studentContext = $this->getStudentContext($phone);

            // 2. Susun System Prompt resmi madrasah
            $systemPrompt = $this->buildSystemPrompt($studentContext);

            // 3. Panggil OpenRouter API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url', 'https://sigma3.mtsn3tulungagung.sch.id'),
                'X-Title' => 'SIGMA-3 MTsN 3 Tulungagung',
                'Content-Type' => 'application/json',
            ])->timeout(20)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? null;

                if (! empty($reply)) {
                    return trim($reply);
                }
            }

            Log::warning('OPENROUTER AI GAGAL: ' . $response->body());
            return null;

        } catch (\Throwable $e) {
            Log::error('OPENROUTER EXCEPTION: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Deteksi apakah nomor HP pengirim terdaftar sebagai orang tua/wali siswa
     */
    protected function getStudentContext(string $phone): ?string
    {
        try {
            // Bersihkan format nomor: 6281234 -> 081234 atau sebaliknya
            $cleaned = preg_replace('/\D+/', '', $phone);
            $local = preg_replace('/^62/', '0', $cleaned);

            $siswa = Siswa::query()
                ->where('telpon_ortu', 'LIKE', "%{$local}%")
                ->orWhere('telpon_wali', 'LIKE', "%{$local}%")
                ->orWhere('telpon_ortu', 'LIKE', "%{$cleaned}%")
                ->orWhere('telpon_wali', 'LIKE', "%{$cleaned}%")
                ->with('kelas')
                ->first();

            if ($siswa) {
                $namaSiswa = $siswa->nama_siswa ?? $siswa->nama ?? 'Siswa';
                $kelas = $siswa->kelas->nama_kelas ?? $siswa->kelas->nama ?? '-';
                $nisn = $siswa->nisn ?? '-';
                $namaOrtu = $siswa->nama_ayah ?: ($siswa->nama_ibu ?: ($siswa->nama_wali ?: 'Bapak/Ibu'));

                return "KONTEKS PENELPON:\nNomor ini teridentifikasi sebagai: {$namaOrtu}, orang tua/wali dari ananda {$namaSiswa} (Kelas {$kelas}, NISN: {$nisn}).";
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel atau kolom belum cocok
        }

        return null;
    }

    /**
     * Bangun System Prompt cerdas bernuansa madrasah
     */
    protected function buildSystemPrompt(?string $studentContext): string
    {
        $prompt = <<<PROMPT
Anda adalah "CS AI SIGMA-3 MATSANEGA", Asisten Virtual resmi MTsN 3 Tulungagung, Jawa Timur.
Tugas Anda adalah melayani dan menjawab pertanyaan wali murid atau masyarakat dengan ramah, santun, solutif, dan islami.

INFORMASI MADRASAH:
- Lembaga: MTsN 3 Tulungagung (MATSANEGA).
- Jadwal Masuk & Pulang KBM:
  * Senin - Kamis: 06.45 - 14.30 WIB
  * Jumat: 06.45 - 11.00 WIB (Persiapan Shalat Jumat)
  * Sabtu: 06.45 - 13.00 WIB (KBM & Ekstrakurikuler)
- Aturan Seragam Madrasah:
  * Senin - Selasa: Seragam Putih Biru rapi lengkap dengan atribut madrasah & sepatu hitam.
  * Rabu - Kamis: Seragam Batik Khas Identitas MTsN 3 Tulungagung.
  * Jumat: Kaos Olahraga / Busana Muslim Madrasah.
  * Sabtu: Seragam Pramuka Lengkap.
- Presensi & Ibadah Siswa:
  * Siswa menggunakan Kartu Pintar RFID untuk presensi kedatangan/kepulangan di gerbang madrasah.
  * Presensi Shalat Dhuha dan Shalat Dzuhur berjamaah dilakukan setiap hari secara tertib.
- Fitur Menu Mandiri SIGMA-3 (Arahkan wali murid ke menu ini bila memerlukan data otomatis):
  * Ketik 1 : Cek Profil Siswa (butuh NISN)
  * Ketik 2 : Cek Absensi Hari Ini & Ibadah Dhuha/Dzuhur (butuh NISN)
  * Ketik 3 : Cek Riwayat Prestasi Siswa (butuh NISN)
  * Ketik 4 : Portal Wali Murid (Pengajuan Izin Sakit/Keperluan Keluarga secara online)
  * Ketik 5 : Hubungi Komite Madrasah (Ibu Binti - 085736649582)
  * Ketik 6 : Hubungi Guru BK sesuai tingkatan kelas (Kelas 7: Bu Laela, Kelas 8: Bu Ardila, Kelas 9: Bu Ulum)

GAYA & PANDUAN MENJAWAB:
- Awali balasan dengan salam islami bila penanya menyapa (Waalaikumsalam Warahmatullahi Wabarakatuh).
- Bersikap ramah, mengayomi, dan doakan yang baik (misal: "Semoga ananda lekas sembuh" jika izin sakit).
- Jawab maksimal 2-3 paragraf pendek, jelas, dan rapi menggunakan format *bold* WhatsApp.
- Jangan mengarang data spesifik kehadiran/nilai anak. Arahkan mereka mengetik nomor menu SIGMA-3 yang sesuai.
- Di akhir pesan, selalu sertakan footer: "\n\n_Ketik *menu* untuk kembali ke menu utama._"
PROMPT;

        if ($studentContext) {
            $prompt .= "\n\n" . $studentContext . "\nSapa orang tua tersebut dengan hangat menyebutkan nama ananda bila relevan.";
        }

        return $prompt;
    }
}
