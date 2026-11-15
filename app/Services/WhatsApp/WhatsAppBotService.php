<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;

class WhatsAppBotService
{
    public function __construct(
        protected ConversationService $conversation,
        protected \App\Services\AI\OpenRouterService $ai,
    ) {}

    public function handle(string $phone, string $message): void
    {
        try {
            $this->processMessage($phone, $message);
        } catch (\Throwable $th) {
            report($th);

            try {
                $this->reply(
                    $phone,
                    "⚠️ Mohon maaf, sistem sedang mengalami gangguan.\nSilakan coba lagi beberapa saat, atau ketik *menu* untuk mengulang."
                );
            } catch (\Throwable $replyError) {
                \Illuminate\Support\Facades\Log::warning('WA: Gagal kirim pesan fallback error: ' . $replyError->getMessage());
            }

            $this->conversation->reset($phone);
        }
    }

    protected function processMessage(string $phone, string $message): void
    {
        $message = trim($message);
        $lower = strtolower($message);

        $conversation = $this->conversation->get($phone);

        /*
        |--------------------------------------------------------------------------
        | Sapaan Murni (Hanya salam / sapaan singkat tanpa pertanyaan tambahan)
        |--------------------------------------------------------------------------
        */

        $pureSalamPattern = '/^(assalamu[\s\'`]*alaikum|askum|aslkm|assalamualaikum|salam|salim)[\s.!,?]*$/i';
        $pureGreetingPattern = '/^(halo+|hallo+|hello+|helo+|hai+|hi+|tes+|test+|ping+|p+|selamat\s+(pagi|siang|sore|malam)|kulonuwun|sampurasun|permisi)[\s.!,?]*$/i';

        if (preg_match($pureSalamPattern, $message)) {
            $this->conversation->reset($phone);
            $parent = $this->getParentGreeting($phone);
            $salam = $parent 
                ? "Wa'alaikumussalam Warahmatullahi Wabarakatuh, Bapak/Ibu {$parent} 🙏\nSelamat datang di Layanan Informasi MTsN 3 Tulungagung (MATSANEGA).\n\n"
                : "Wa'alaikumussalam Warahmatullahi Wabarakatuh, Bapak/Ibu 🙏\nSelamat datang di Layanan Informasi MTsN 3 Tulungagung (MATSANEGA).\n\n";

            $this->reply($phone, $salam . $this->mainMenu());
            return;
        }

        if (preg_match($pureGreetingPattern, $message)) {
            $this->conversation->reset($phone);
            $parent = $this->getParentGreeting($phone);
            $sapaan = $parent 
                ? "Halo, selamat datang Bapak/Ibu {$parent} di Layanan Informasi MTsN 3 Tulungagung (MATSANEGA) 🙏\n\n"
                : "Halo, selamat datang Bapak/Ibu di Layanan Informasi MTsN 3 Tulungagung (MATSANEGA) 🙏\n\n";

            $this->reply($phone, $sapaan . $this->mainMenu());
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Kata kunci global: kembali ke menu / batalkan proses
    |--------------------------------------------------------------------------
    */

        if (in_array($lower, ['menu', 'batal', 'cancel', 'keluar', 'reset'])) {

            $this->conversation->reset($phone);

            $this->reply(
                $phone,
                $this->mainMenu()
            );

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Main Menu
    |--------------------------------------------------------------------------
    */

        if ($conversation->state === 'menu') {

            // JALUR PINTAS UNTUK MENU 5 (Tidak Butuh NISN)
            if ($message === '5') {
                $this->conversation->reset($phone);
                $this->reply(
                    $phone,
                    $this->komite()
                );
                return;
            }

            // MENU 1-4 & 6 MEMBUTUHKAN NISN
            $menuMap = [
                '1' => 'profil',
                '2' => 'absensi',
                '3' => 'prestasi',
                '4' => 'portal',
                '6' => 'bk_contact',
            ];

            if (isset($menuMap[$message])) {
                $this->conversation->update(
                    $phone,
                    'waiting_nisn',
                    $menuMap[$message],
                );

                $this->reply(
                    $phone,
                    $this->askNisn()
                );

                return;
            }

            // JIKA BUKAN ANGKA 1-6, JANGAN TOLAK: TANYAKAN KE AI OPENROUTER TERLEBIH DAHULU
            $aiReply = $this->ai->ask($phone, $message);

            if (! empty($aiReply)) {
                $this->conversation->reset($phone);
                $this->reply($phone, $aiReply);
                return;
            }

            // Fallback jika AI tidak merespon
            $this->reply(
                $phone,
                "Mohon maaf, Bapak/Ibu 🙏 Pesan belum kami mengerti.\n\nSilakan balas dengan angka *1* sampai *6* sesuai menu di bawah, atau tanyakan informasi madrasah yang Anda butuhkan."
                    . "\n\n" . $this->mainMenu()
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Input NISN atau Nama Siswa
        |--------------------------------------------------------------------------
        */

        if ($conversation->state === 'waiting_nisn') {
            $keyword = trim($message);

            // Cek apakah pesan berupa pertanyaan eksplisit (misal: "tidak tahu nisn", "lupa nisn", "gimana kalau ga tahu")
            $lowerKeyword = strtolower($keyword);
            $isExplicitQuestion = str_contains($lowerKeyword, 'tidak tahu') ||
                                  str_contains($lowerKeyword, 'nggak tahu') ||
                                  str_contains($lowerKeyword, 'lupa') ||
                                  str_contains($lowerKeyword, 'gimana') ||
                                  str_contains($lowerKeyword, 'bagaimana');

            if ($isExplicitQuestion && !preg_match('/^\d{10}$/', $keyword)) {
                $aiReply = $this->ai->ask($phone, $message);
                if (! empty($aiReply)) {
                    $this->conversation->reset($phone);
                    $this->reply($phone, $aiReply);
                    return;
                }
            }

            // Panggil API Student Intelligence (Mendukung pencarian via NISN maupun Nama)
            $response = Http::baseUrl(config('app.url'))
                ->timeout(10)
                ->get('/api/student-intelligence', [
                    'keyword' => $keyword,
                ]);

            if (!$response->successful()) {
                $this->reply(
                    $phone,
                    "Mohon maaf, sistem sedang mengalami gangguan 🙏\n\nSilakan coba lagi beberapa saat, atau ketik *menu* untuk mengulang."
                );
                return;
            }

            $json = $response->json();

            // Jika tidak ditemukan
            if (! ($json['status'] ?? false)) {
                // Tanyakan ke AI jika mungkin itu pertanyaan kalimat panjang
                if (preg_match('/[a-zA-Z]{4,}/', $keyword) && str_word_count($keyword) > 3) {
                    $aiReply = $this->ai->ask($phone, $message);
                    if (! empty($aiReply)) {
                        $this->conversation->reset($phone);
                        $this->reply($phone, $aiReply);
                        return;
                    }
                }

                $this->reply(
                    $phone,
                    "Mohon maaf, data siswa dengan kata kunci *\"{$message}\"* tidak ditemukan di database MTsN 3 Tulungagung 🙏\n\n"
                    . "💡 *Tips Pencarian:*\n"
                    . "• Masukkan *Nama Lengkap* atau *Nama Depan* siswa sesuai raport.\n"
                    . "• Atau gunakan *10 digit NISN* (contoh: 3106123456).\n\n"
                    . "Silakan ketik nama/NISN kembali, atau ketik *menu* untuk kembali ke menu utama."
                );
                return;
            }

            // KASUS 1: Ditemukan Tepat 1 Siswa (Single)
            if (($json['type'] ?? '') === 'single') {
                $data = $json['data'];
                $prefix = '';
                if (!empty($json['searched_by_name'])) {
                    $prefix = "✅ Ditemukan siswa: *{$data['nama']}* (Kelas {$data['kelas']} — NISN: *{$data['nisn']}*)\n\n";
                }

                $text = match ($conversation->menu) {
                    'profil' => $this->profil($data),
                    'absensi' => $this->absensi($data),
                    'prestasi' => $this->prestasi($data),
                    'portal' => $this->portal($data),
                    'bk_contact' => $this->guruBk($data),
                    default => "Mohon maaf, menu tidak dikenali. Ketik *menu* untuk kembali ke menu utama.",
                };

                $this->conversation->reset($phone);
                $this->reply($phone, $prefix . $text);
                return;
            }

            // KASUS 2: Ditemukan Lebih Dari 1 Siswa (Multiple / Nama Mirip)
            if (($json['type'] ?? '') === 'multiple' && !empty($json['candidates'])) {
                $candidates = $json['candidates'];

                $this->conversation->update(
                    $phone,
                    'waiting_candidate_selection',
                    $conversation->menu,
                    ['candidates' => $candidates]
                );

                $replyText = "Ditemukan *" . count($candidates) . " siswa* dengan nama mirip *\"{$message}\"*:\n\n";
                foreach ($candidates as $idx => $c) {
                    $no = $idx + 1;
                    $replyText .= "*{$no}️⃣* {$c['nama']} (Kelas {$c['kelas']} — NISN: *{$c['nisn']}*)\n";
                }
                $replyText .= "\nSilakan balas dengan angka urutan (*1*, *2*, dst) sesuai ananda Bapak/Ibu.\n\n_Ketik *menu* untuk membatalkan._";

                $this->reply($phone, $replyText);
                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pemilihan Kandidat Siswa (Jika ada lebih dari 1 siswa dengan nama serupa)
        |--------------------------------------------------------------------------
        */

        if ($conversation->state === 'waiting_candidate_selection') {
            $candidates = $conversation->data['candidates'] ?? [];
            $input = trim($message);

            // Jika orang tua membalas dengan nomor urutan (1, 2, 3...)
            if (is_numeric($input) && isset($candidates[(int)$input - 1])) {
                $selected = $candidates[(int)$input - 1];
                $nisn = $selected['nisn'];

                $response = Http::baseUrl(config('app.url'))
                    ->timeout(10)
                    ->get('/api/student-intelligence', [
                        'nisn' => $nisn,
                    ]);

                if ($response->successful() && ($response->json()['status'] ?? false)) {
                    $data = $response->json()['data'];
                    $prefix = "✅ Ditemukan siswa: *{$data['nama']}* (Kelas {$data['kelas']} — NISN: *{$data['nisn']}*)\n\n";

                    $text = match ($conversation->menu) {
                        'profil' => $this->profil($data),
                        'absensi' => $this->absensi($data),
                        'prestasi' => $this->prestasi($data),
                        'portal' => $this->portal($data),
                        'bk_contact' => $this->guruBk($data),
                        default => "Mohon maaf, menu tidak dikenali. Ketik *menu* untuk kembali ke menu utama.",
                    };

                    $this->conversation->reset($phone);
                    $this->reply($phone, $prefix . $text);
                    return;
                }
            }

            // Jika orang tua memasukkan NISN 10 digit secara langsung
            if (preg_match('/^\d{10}$/', $input)) {
                $response = Http::baseUrl(config('app.url'))
                    ->timeout(10)
                    ->get('/api/student-intelligence', [
                        'nisn' => $input,
                    ]);

                if ($response->successful() && ($response->json()['status'] ?? false)) {
                    $data = $response->json()['data'];
                    $text = match ($conversation->menu) {
                        'profil' => $this->profil($data),
                        'absensi' => $this->absensi($data),
                        'prestasi' => $this->prestasi($data),
                        'portal' => $this->portal($data),
                        'bk_contact' => $this->guruBk($data),
                        default => "Mohon maaf, menu tidak dikenali. Ketik *menu* untuk kembali ke menu utama.",
                    };

                    $this->conversation->reset($phone);
                    $this->reply($phone, $text);
                    return;
                }
            }

            $maxNum = count($candidates);
            $this->reply(
                $phone,
                "Mohon maaf, pilihan belum sesuai 🙏\nSilakan balas dengan angka urutan antara *1 sampai {$maxNum}*, atau ketik *menu* untuk kembali ke menu utama."
            );
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Fallback terakhir: tanyakan ke AI OpenRouter / fallback menu
    |--------------------------------------------------------------------------
    */

        $this->conversation->reset($phone);

        $aiReply = $this->ai->ask($phone, $message);

        if (! empty($aiReply)) {
            $this->reply($phone, $aiReply);
            return;
        }

        $this->reply(
            $phone,
            "Mohon maaf, Bapak/Ibu 🙏 Pesan belum kami mengerti.\n\n"
                . $this->mainMenu()
        );
    }

    protected function reply(string $phone, string $text): void
    {
        WhatsApp::web('default') // <-- Ubah 'main' menjadi 'default'
            ->messages()
            ->sendText($phone, $text);
    }

    /**
     * Dapatkan sapaan nama siswa jika nomor telepon terdaftar sebagai wali murid
     */
    protected function getParentGreeting(string $phone): ?string
    {
        try {
            $cleaned = preg_replace('/\D+/', '', $phone);
            $local = preg_replace('/^62/', '0', $cleaned);

            $siswa = \App\Models\Siswa::query()
                ->where('telpon_ortu', 'LIKE', "%{$local}%")
                ->orWhere('telpon_wali', 'LIKE', "%{$local}%")
                ->orWhere('telpon_ortu', 'LIKE', "%{$cleaned}%")
                ->orWhere('telpon_wali', 'LIKE', "%{$cleaned}%")
                ->first();

            if ($siswa) {
                $namaSiswa = $siswa->nama_siswa ?? $siswa->nama ?? 'Siswa';
                $kelas = $siswa->nama_kelas_aktif ?? '-';
                return "wali dari ananda *{$namaSiswa}* (Kelas {$kelas})";
            }
        } catch (\Throwable $e) {
            // Abaikan jika tidak cocok
        }

        return null;
    }

    private function footer(): string
    {
        return "\n\n_Ketik *menu* untuk melihat daftar layanan madrasah._";
    }

    private function mainMenu(): string
    {
        return <<<TEXT
🏫 *Layanan Informasi MTsN 3 Tulungagung (MATSANEGA)*
_Bismillah, ada yang bisa kami bantu Bapak/Ibu?_

Berikut pilihan layanan cepat yang dapat kami berikan:

1️⃣ *Profil Ananda* (Biodata & status kelas)
2️⃣ *Presensi Hari Ini* (Kehadiran & shalat berjamaah)
3️⃣ *Prestasi Ananda* (Catatan lomba & kejuaraan)
4️⃣ *Portal Wali Murid* (Pengajuan izin sakit & catatan BK)
5️⃣ *Hubungi Komite Madrasah* (Ibu Binti)
6️⃣ *Konsultasi Guru BK*

Silakan balas dengan angka *1* sampai *6*, atau Bapak/Ibu bisa langsung menanyakan informasi madrasah yang ingin diketahui nggih. Kami siap membantu 🙏
TEXT;
    }

    private function askNisn(): string
    {
        return <<<TEXT
Nggih Bapak/Ibu, mohon sebutkan *Nama Lengkap/Nama Panggilan Ananda* atau *10 digit NISN* ya.

Contoh: *Ahmad Fauzi* atau *3106123456*

Biar kami bantu carikan datanya di sistem madrasah 🙏
_Ketik *menu* jika ingin kembali ke menu utama._
TEXT;
    }

    private function portal(array $data): string
    {
        $url = rtrim(config('app.url'), '/') . '/wali_murid/' . $data['nisn'];

        return <<<TEXT
🌐 *PORTAL WALI MURID MADRASAH*

Bapak/Ibu wali dari ananda *{$data['nama']}* (Kelas {$data['kelas']}), 

Untuk memantau perkembangan ananda atau mengajukan surat izin secara online mandiri, silakan kunjungi portal kami di:
{$url}

Di dalam portal ini, Bapak/Ibu dapat:
📄 Mengajukan Surat Izin Sakit / Keperluan Keluarga
📋 Memeriksa Riwayat Izin
👨‍🏫 Memantau Catatan Bimbingan Konseling (BK)
TEXT . $this->footer();
    }

    private function profil(array $d): string
    {
        return <<<TEXT
👤 *BIODATA ANANDA*

• *Nama:* {$d['nama']}
• *NISN:* {$d['nisn']}
• *Kelas:* {$d['kelas']}
• *Status Siswa:* {$d['status']}

Alhamdulillah, ananda tercatat aktif belajar di MTsN 3 Tulungagung.
TEXT . $this->footer();
    }

    private function absensi(array $d): string
    {
        $hasil = "📅 *LAPORAN PRESENSI HARI INI*\n";
        $hasil .= "Ananda *{$d['nama']}* — Kelas {$d['kelas']}\n\n";

        if (count($d['absensi'])) {
            $hasil .= "Catatan kehadiran & ibadah ananda:\n";
            foreach ($d['absensi'] as $a) {
                $hasil .= "• {$a['jenis']} : *{$a['jam']}*\n"; 
            }
        } else {
            $hasil .= "Belum ada catatan presensi tap kartu atau ibadah ananda untuk hari ini.";
        }

        return $hasil . $this->footer();
    }

    private function prestasi(array $d): string
    {
        $hasil = "🏆 *CATATAN PRESTASI ANANDA*\n";
        $hasil .= "*{$d['nama']}*\n\n";

        if (count($d['prestasi'])) {
            $hasil .= "Alhamdulillah, berikut torehan prestasi membanggakan ananda:\n\n";
            foreach ($d['prestasi'] as $p) {
                $hasil .= "🏅 *{$p['nama_lomba']}*\n";
                $hasil .= "   {$p['tingkat']} • Tahun {$p['tahun']}\n\n";
            }
        } else {
            $hasil .= "Belum ada catatan prestasi resmi yang tercatat di sistem madrasah.";
        }

        return $hasil . $this->footer();
    }

    private function komite(): string
    {
        return <<<TEXT
📞 *HUBUNGI KOMITE MADRASAH*

Untuk keperluan koordinasi, aspirasi, atau informasi terkait Komite MTsN 3 Tulungagung, Bapak/Ibu dapat langsung menghubungi pengurus komite kami:

👤 *Ibu Binti (Pengurus Komite)*
📱 Kontak WA: 0857-3664-9582

Silakan klik tautan di bawah ini untuk memulai chat WhatsApp dengan beliau:
👉 https://wa.me/6285736649582?text=Assalamualaikum%20Warahmatullahi%20Wabarakatuh%2C%20Ibu%20Binti.%20Mohon%20maaf%20mengganggu%20waktunya%2C%20saya%20wali%20murid%20ingin...
TEXT . $this->footer();
    }

    private function guruBk(array $data): string
    {
        $kelas = $data['kelas'] ?? '-';
        $namaSiswa = $data['nama'] ?? '-';

        // Deteksi tingkat kelas berdasarkan isi string kelas (7, 8, atau 9)
        if (str_contains($kelas, '7')) {
            $namaGuru = "Ibu Laela";
            $waNumber = "6282231846743";
            $tingkat = "7";
        } elseif (str_contains($kelas, '8')) {
            $namaGuru = "Ibu Ardila";
            $waNumber = "6281220301140";
            $tingkat = "8";
        } elseif (str_contains($kelas, '9')) {
            $namaGuru = "Ibu Ulum";
            $waNumber = "6282232260065";
            $tingkat = "9";
        } else {
            return "Mohon maaf nggih Bapak/Ibu, data kelas ananda belum teridentifikasi ({$kelas}) sehingga kami belum dapat mengarahkan ke Guru BK terkait. Silakan ketik *menu* untuk mencoba kembali.";
        }

        $templatePesan = "Assalamualaikum Warahmatullahi Wabarakatuh, {$namaGuru}. Mohon maaf mengganggu waktunya, saya wali murid dari ananda {$namaSiswa} kelas {$kelas} ingin berkonsultasi mengenai...";
        $encodedText = rawurlencode($templatePesan);

        $url = "https://wa.me/{$waNumber}?text={$encodedText}";

        return <<<TEXT
👨‍🏫 *KONSULTASI GURU BIMBINGAN KONSELING (BK)*

Berdasarkan kelas ananda *{$namaSiswa}*, berikut adalah Guru BK yang mendampingi ananda:

👤 *{$namaGuru} (Guru BK Kelas {$tingkat})*
📱 Kontak WA: {$waNumber}

Silakan klik tautan di bawah ini untuk langsung terhubung dan berkonsultasi dengan beliau:
👉 {$url}
TEXT . $this->footer();
    }
}
