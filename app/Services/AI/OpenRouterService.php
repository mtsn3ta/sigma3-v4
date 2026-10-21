<?php

namespace App\Services\AI;

use App\Models\Siswa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected ?string $geminiApiKey;
    protected string $geminiModel;
    protected ?string $openRouterApiKey;
    protected string $openRouterModel;

    public function __construct()
    {
        $this->geminiApiKey = config('services.gemini.api_key');
        $this->geminiModel = (string) config('services.gemini.model', 'gemini-3.5-flash-lite');
        $this->openRouterApiKey = config('services.openrouter.api_key');
        $this->openRouterModel = (string) config('services.openrouter.model', 'minimax/minimax-m3.1-flash:free');
    }

    /**
     * Kirim pertanyaan wali murid ke AI:
     * 1. Prioritas Utama (Tier 1): Google Gemini AI (Super Cepat & Natural)
     * 2. Prioritas Cadangan (Tier 2): OpenRouter Free Models Auto-Fallback
     */
    public function ask(string $phone, string $userMessage): ?string
    {
        try {
            // 1. Cari data siswa berdasarkan nomor telepon wali/ortu jika ada
            $studentContext = $this->getStudentContext($phone);

            // 2. Susun System Prompt resmi madrasah
            $systemPrompt = $this->buildSystemPrompt($studentContext);

            // -----------------------------------------------------------------
            // TIER 1: GOOGLE GEMINI AI (PRIMARY)
            // -----------------------------------------------------------------
            if (! empty($this->geminiApiKey)) {
                $geminiCandidateModels = array_unique(array_filter([
                    $this->geminiModel,
                    'gemini-3.5-flash-lite',
                    'gemini-3.5-flash',
                ]));

                foreach ($geminiCandidateModels as $model) {
                    try {
                        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->geminiApiKey}";

                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                        ])->timeout(8)->post($url, [
                            'system_instruction' => [
                                'parts' => [
                                    ['text' => $systemPrompt],
                                ],
                            ],
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $userMessage],
                                    ],
                                ],
                            ],
                            'generationConfig' => [
                                'temperature' => 0.5,
                                'maxOutputTokens' => 600,
                            ],
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                            if (! empty($reply)) {
                                Log::info("Gemini AI dijawab sukses oleh: {$model}");
                                return $this->sanitizeWhatsAppText(trim($reply));
                            }
                        }

                        Log::warning("Gemini {$model} HTTP {$response->status()} - mencoba model cadangan...");
                    } catch (\Throwable $e) {
                        Log::warning("Gemini {$model} timeout/error ({$e->getMessage()}) - mencoba model cadangan...");
                    }
                }
            }

            // -----------------------------------------------------------------
            // TIER 2: FALLBACK OPENROUTER (FREE MODELS)
            // -----------------------------------------------------------------
            if (! empty($this->openRouterApiKey)) {
                $candidateModels = array_unique(array_filter([
                    $this->openRouterModel,
                    'minimax/minimax-m3.1-flash:free',
                    'liquid/lfm-2.5-2.6b:free',
                    'nvidia/nemotron-3.5-lightning:free',
                    'google/gemma-4-26b-a4b-it:free',
                    'google/gemma-4-31b-it:free',
                ]));

                foreach ($candidateModels as $candidate) {
                    try {
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $this->openRouterApiKey,
                            'HTTP-Referer' => config('app.url', 'https://sigma3.mtsn3tulungagung.sch.id'),
                            'X-Title' => 'SIGMA-3 MTsN 3 Tulungagung',
                            'Content-Type' => 'application/json',
                        ])->timeout(10)->post('https://openrouter.ai/api/v1/chat/completions', [
                            'model' => $candidate,
                            'messages' => [
                                ['role' => 'system', 'content' => $systemPrompt],
                                ['role' => 'user', 'content' => $userMessage],
                            ],
                            'temperature' => 0.6,
                            'max_tokens' => 350,
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $reply = $data['choices'][0]['message']['content'] ?? null;

                            if (! empty($reply)) {
                                Log::info("OpenRouter AI (Fallback) dijawab sukses oleh: {$candidate}");
                                return $this->sanitizeWhatsAppText(trim($reply));
                            }
                        }

                        Log::warning("OpenRouter {$candidate} gagal HTTP {$response->status()} - mencoba model cadangan...");
                    } catch (\Throwable $e) {
                        Log::warning("OpenRouter {$candidate} timeout/error ({$e->getMessage()}) - mencoba model cadangan...");
                    }
                }
            }

            return null;

        } catch (\Throwable $e) {
            Log::error('AI SERVICE EXCEPTION: ' . $e->getMessage());
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
                ->first();

            if ($siswa) {
                $namaSiswa = $siswa->nama_siswa ?? $siswa->nama ?? 'Siswa';
                $kelas = $siswa->nama_kelas_aktif ?? '-';
                $nisn = $siswa->nisn ?? '-';
                $namaOrtu = $siswa->nama_ayah ?: ($siswa->nama_ibu ?: ($siswa->nama_wali ?: 'Bapak/Ibu'));

                return "KONTEKS PENELPON:\nNomor ini teridentifikasi sebagai: {$namaOrtu}, orang tua/wali dari ananda {$namaSiswa} (Kelas {$kelas}, NISN: {$nisn}).";
            }
        } catch (\Throwable $e) {
            // Abaikan jika tidak cocok
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
- Aturan Seragam Madrasah Harian:
  * Senin - Selasa: Seragam Putih Biru rapi lengkap dengan atribut madrasah & sepatu hitam.
  * Rabu - Kamis: Seragam Batik Khas Identitas MTsN 3 Tulungagung.
  * Jumat: Baju Olahraga / Busana Muslim Madrasah.
  * Sabtu: Seragam Pramuka Lengkap.
- Presensi & Ibadah Siswa:
  * Siswa tap kartu RFID di gerbang madrasah untuk presensi kedatangan/kepulangan.
  * Presensi Shalat Dhuha dan Shalat Dzuhur berjamaah dilakukan setiap hari secara tertib.
- Menu Otomatis Database SIGMA-3:
  * Ketik 1 : Cek Profil Siswa (bisa pakai NISN atau Nama Siswa)
  * Ketik 2 : Cek Absensi Hari Ini & Ibadah Dhuha/Dzuhur (bisa pakai NISN atau Nama Siswa)
  * Ketik 3 : Cek Riwayat Prestasi Siswa (bisa pakai NISN atau Nama Siswa)
  * Ketik 4 : Portal Wali Murid (Pengajuan Izin Sakit/Keperluan Keluarga secara online)
  * Ketik 5 : Hubungi Komite Madrasah (Ibu Binti - 085736649582)
  * Ketik 6 : Hubungi Guru BK sesuai tingkatan kelas

- PANDUAN PENCARIAN SISWA & JIKA LUPA/TIDAK TAHU NISN:
  * Jika wali murid bertanya "bagaimana jika saya tidak tahu NISN anak saya?", "lupa NISN", atau "apakah bisa cari pakai nama anak?":
    Jawab dengan sangat ramah, hangat, dan menenangkan bahwa Bapak/Ibu BISA mencari data ananda menggunakan NAMA LENGKAP atau NAMA DEPAN siswa secara langsung tanpa harus tahu atau hafal NISN.
    Berikan panduan singkat:
    1. Balas dengan angka layanan yang diinginkan (misal ketik *1* untuk Profil atau *2* untuk Absensi).
    2. Saat bot meminta input, cukup ketikkan Nama Siswa (contoh: *Ahmad Fauzi*).
    3. Sistem SIGMA-3 akan otomatis mencari data ananda dan memberitahukan nomor NISN-nya.

GAYA & PANDUAN MENJAWAB:
- ATURAN SALAM & SAPAAN:
  * HANYA ucapkan salam (Waalaikumsalam) JIKA penanya mengucap salam (Assalamualaikum) terlebih dahulu!
  * Jika penanya TIDAK mengucap salam (misal: "halo", "min", "besok seragam apa"), JANGAN awali dengan Waalaikumsalam! Jawab langsung secara ramah dan wajar.
- ATURAN FORMATTING WHATSAPP:
  * Tulisan TEBAL (bold) HANYA memakai SATU tanda bintang: *kata*.
  * DILARANG KERAS menggunakan dua bintang **kata**.
  * Tulisan miring memakai satu garis bawah: _kata_.
- Jawab maksimal 2-3 paragraf pendek, padat, dan nyaman dibaca di layar HP.
- Di akhir pesan, selalu sertakan footer: "\n\n_Ketik *menu* untuk kembali ke menu utama._"
PROMPT;

        if ($studentContext) {
            $prompt .= "\n\n" . $studentContext . "\nSapa orang tua tersebut dengan hangat menyebutkan nama ananda bila relevan.";
        }

        return $prompt;
    }

    /**
     * Bersihkan teks agar 100% rapi di WhatsApp (ubah **teks** menjadi *teks*)
     */
    protected function sanitizeWhatsAppText(string $text): string
    {
        // Ubah markdown header ### Judul menjadi *Judul*
        $text = preg_replace('/^#{1,6}\s*(.+)$/m', '*$1*', $text);

        // Ubah double asterisks **bold** menjadi single asterisk *bold* WhatsApp
        $text = preg_replace('/\*\*(.*?)\*\*/s', '*$1*', $text);

        // Bersihkan triple asterisks jika ada
        $text = preg_replace('/\*\*\*(.*?)\*\*\*/s', '*$1*', $text);

        return trim($text);
    }
}
