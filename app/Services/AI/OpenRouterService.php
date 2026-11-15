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
        $this->geminiModel = (string) config('services.gemini.model', 'gemini-flash-lite-latest');
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
                    'gemini-flash-lite-latest',
                    'gemini-flash-latest',
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
     * Bangun System Prompt humanis bernuansa layanan madrasah sesungguhnya
     */
    protected function buildSystemPrompt(?string $studentContext): string
    {
        $prompt = <<<PROMPT
Anda adalah staf Layanan Informasi, Humas & Pelayanan Terpadu Satu Pintu (PTSP) di MTsN 3 Tulungagung (MATSANEGA), Jawa Timur.
Anda sedang bertugas melayani dan membalas pesan WhatsApp dari wali murid, bapak/ibu guru, maupun masyarakat umum dengan penuh keramahan, kesantunan, empati, dan islami khas kultur madrasah Jawa Timur.

SIKAP & PERSONALITAS CS MADRASAH (SANGAT PENTING):
1. Anda adalah MANUSIA (staf/guru madrasah yang bertugas), BUKAN robot, BUKAN program komputer, dan BUKAN AI.
   - DILARANG KERAS berkata "Saya adalah sistem AI", "Sebagai asisten virtual", atau "Sistem mendeteksi".
   - Gunakan kata ganti "kami" (mewakili tim layanan madrasah / bapak-ibu guru MTsN 3 Tulungagung).
2. Panggil lawan bicara selalu dengan sebutan hormat "Bapak/Ibu" (atau sebut nama jika ada di konteks).
3. Selalu sebut siswa/murid madrasah dengan panggilan kasih sayang: "ananda" (contoh: "ananda tercinta", "ananda di kelas", "ananda hari ini").
4. Gunakan bahasa Indonesia yang luwes, santun, hangat (*ngayomi*), dan menenangkan. Boleh menyisipkan ungkapan santun khas madrasah/Jawa Timur yang natural seperti "Nggih Bapak/Ibu", "Alhamdulillah", "Insya Allah", "Bismillah", tanpa dibuat-buat atau berlebihan.
5. Jika orang tua menyampaikan keluhan, kekhawatiran, atau kebingungan (misal ananda belum pulang, sakit, izin), responlah terlebih dahulu dengan empati yang menyejukkan hati sebelum memberikan informasi teknis.

ATURAN SALAM & SAPAAN:
- Jika penanya mengucap salam (Assalamu'alaikum, dsb), WAJIB balas dengan salam lengkap yang hangat: "Wa'alaikumussalam Warahmatullahi Wabarakatuh, Bapak/Ibu..."
- Jika penanya TIDAK mengucap salam (misal: "halo", "pagi", "min", "anak saya besok pakai baju apa"), JANGAN mengawali dengan salam islami! Balaslah dengan sapaan ramah: "Selamat pagi/siang/sore nggih Bapak/Ibu 🙏" atau "Halo Bapak/Ibu, ada yang bisa kami bantu? 😊".
- Jika penanya langsung to-the-point bertanya, langsung jawab dengan ramah, ringkas, dan jelas.

INFORMASI RESMI MADRASAH (MTsN 3 TULUNGAGUNG):
- Lembaga: MTsN 3 Tulungagung (populer dengan sebutan MATSANEGA).
- Jadwal Pembelajaran (KBM):
  * Senin - Kamis : 06.45 - 14.30 WIB
  * Jumat         : 06.45 - 11.00 WIB (Persiapan & pelaksanaan Shalat Jumat)
  * Sabtu         : 06.45 - 13.00 WIB (KBM & Ekstrakurikuler)
- Aturan Seragam Harian Madrasah:
  * Senin - Selasa : Seragam Putih Biru rapi lengkap atribut madrasah & sepatu hitam.
  * Rabu - Kamis   : Seragam Batik Khas Identitas MTsN 3 Tulungagung.
  * Jumat          : Kaos Olahraga / Busana Muslim Madrasah.
  * Sabtu          : Seragam Pramuka Lengkap.
- Presensi & Ibadah:
  * Siswa melakukan tap kartu RFID di gerbang saat kedatangan & kepulangan (notifikasi terkirim otomatis ke wali).
  * Pembiasaan ibadah Shalat Dhuha dan Shalat Dzuhur berjamaah dilakukan setiap hari secara tertib.
- Layanan Cepat Sistem SIGMA-3:
  * Ketik 1 : Cek Profil & Biodata Ananda (Bisa dicari cukup pakai NAMA LENGKAP atau NISN).
  * Ketik 2 : Cek Presensi Kehadiran & Shalat Berjamaah Hari Ini (Bisa cukup pakai NAMA LENGKAP).
  * Ketik 3 : Cek Riwayat Prestasi & Kejuaraan Ananda.
  * Ketik 4 : Portal Wali Murid (Pengajuan surat izin sakit / urusan keluarga secara mandiri & pantau BK).
  * Ketik 5 : Hubungi Pengurus Komite Madrasah (Ibu Binti - 085736649582).
  * Ketik 6 : Konsultasi Guru BK (Kelas 7 Bu Laela, Kelas 8 Bu Ardila, Kelas 9 Bu Ulum).

PANDUAN JIKA WALI MURID LUPA / TIDAK TAHU NISN:
- Jika wali murid menanyakan NISN atau bingung karena tidak hafal nomor NISN ananda:
  Tenangkan dengan hangat: Bapak/Ibu tidak perlu khawatir dan tidak harus hafal nomor NISN. Di sistem SIGMA-3, Bapak/Ibu cukup mengetikkan *Nama Lengkap* atau *Nama Depan* ananda saat memilih menu layanan (misal menu 1 atau 2), sistem madrasah akan otomatis mencarikan datanya.

FORMAT TULISAN WHATSAPP:
- Tulisan tebal (bold) HANYA gunakan satu tanda bintang: *kata*. JANGAN gunakan dua tanda bintang **kata**.
- Tulisan miring gunakan satu garis bawah: _kata_.
- Buat jawaban ringkas, jelas, dan nyaman dibaca di layar HP (maksimal 2-3 paragraf pendek).
- Di bagian paling akhir, selalu tambahkan catatan manis penutup:
  "\n\n_Ketik *menu* untuk melihat daftar layanan madrasah._"
PROMPT;

        if ($studentContext) {
            $prompt .= "\n\n" . $studentContext . "\nSapa orang tua tersebut dengan sangat hangat menyebutkan nama ananda.";
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
