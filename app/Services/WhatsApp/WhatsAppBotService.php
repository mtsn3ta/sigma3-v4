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

            $this->reply(
                $phone,
                "⚠️ Mohon maaf, sistem sedang mengalami gangguan.\nSilakan coba lagi beberapa saat, atau ketik *menu* untuk mengulang."
            );

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

        $pureSalamPattern = '/^(assalamu[\s\'`]*alaikum|askum|aslkm|assalamualaikum|halo|hai|p|selamat\s+(pagi|siang|sore|malam))[\s.!,?]*$/i';

        if (preg_match($pureSalamPattern, $message)) {

            $this->conversation->reset($phone);

            $this->reply(
                $phone,
                "Waalaikumsalam Warahmatullahi Wabarakatuh 🙏\n\n"
                    . $this->mainMenu()
            );

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
    | Input NISN
    |--------------------------------------------------------------------------
    */

        if ($conversation->state === 'waiting_nisn') {

            if (!preg_match('/^\d{10}$/', $message)) {

                // Jika pengguna malah bertanya kalimat / kata (misal: "lupa nisn", "pakai nama bisa?")
                if (preg_match('/[a-zA-Z]/', $message)) {
                    $aiReply = $this->ai->ask($phone, $message);
                    if (! empty($aiReply)) {
                        $this->conversation->reset($phone);
                        $this->reply($phone, $aiReply);
                        return;
                    }
                }

                $this->reply(
                    $phone,
                    "Mohon maaf, NISN yang dimasukkan belum sesuai 🙏\n\nNISN terdiri dari *10 digit angka*, contoh: 3106123456\n\nSilakan coba masukkan kembali, atau ketik *menu* untuk kembali ke menu utama."
                );

                return;
            }

            $response = Http::baseUrl(config('app.url'))
                ->timeout(10)
                ->get('/api/student-intelligence', [
                    'nisn' => $message,
                ]);

            if (!$response->successful()) {

                $this->reply(
                    $phone,
                    "Mohon maaf, sistem sedang mengalami gangguan 🙏\n\nSilakan coba lagi beberapa saat, atau ketik *menu* untuk mengulang."
                );

                return;
            }

            $json = $response->json();

            if (!$json['status']) {

                $this->reply(
                    $phone,
                    "Mohon maaf, data siswa dengan NISN tersebut tidak ditemukan 🙏\n\nMohon periksa kembali NISN yang dimasukkan, atau ketik *menu* untuk kembali ke menu utama."
                );

                return;
            }

            $data = $json['data'];

            switch ($conversation->menu) {

                case 'profil':
                    $text = $this->profil($data);
                    break;

                case 'absensi':
                    $text = $this->absensi($data);
                    break;

                case 'prestasi':
                    $text = $this->prestasi($data);
                    break;

                case 'portal':
                    $text = $this->portal($data);
                    break;

                case 'bk_contact': // <-- TAMBAHKAN BLOK INI
                    $text = $this->guruBk($data);
                    break;

                default:
                    $text = "Mohon maaf, menu tidak dikenali. Ketik *menu* untuk kembali ke menu utama.";
            }

            $this->conversation->reset($phone);

            $this->reply($phone, $text);
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

    private function footer(): string
    {
        return "\n\n_Ketik *menu* untuk kembali ke menu utama._";
    }

    private function mainMenu(): string
    {
        return <<<TEXT
🏫 *SIGMA-3 Assistant*
_Layanan Informasi Siswa MTsN 3 Tulungagung_

Silakan pilih layanan yang Bapak/Ibu butuhkan dengan membalas angka:

1️⃣ Profil Siswa
2️⃣ Absensi Hari Ini
3️⃣ Prestasi Siswa
4️⃣ Portal Wali Murid
5️⃣ Hubungi Komite Sekolah
6️⃣ Hubungi Guru BK

Balas angka *1-6* untuk melanjutkan 🙏
TEXT;
    }

    private function askNisn(): string
    {
        return <<<TEXT
Baik, mohon masukkan *NISN* siswa (10 digit angka).

Contoh: 3106123456

_Ketik *menu* jika ingin kembali ke menu utama._
TEXT;
    }

    private function portal(array $data): string
    {
        $url = rtrim(config('app.url'), '/') . '/wali_murid/' . $data['nisn'];

        return <<<TEXT
🌐 *PORTAL WALI MURID*

Assalamualaikum, Bapak/Ibu wali dari:

👤 Nama : {$data['nama']}
🏫 Kelas : {$data['kelas']}

Silakan buka Portal Wali Murid melalui tautan berikut:
{$url}

Di dalam portal, Bapak/Ibu dapat:
📄 Mengajukan Surat Izin
📋 Melihat Riwayat Surat Izin
👨‍🏫 Memantau Perkembangan BK Siswa
TEXT . $this->footer();
    }

    private function profil(array $d): string
    {
        return <<<TEXT
👤 *PROFIL SISWA*

Nama : {$d['nama']}
NISN : {$d['nisn']}
Kelas : {$d['kelas']}
Status : {$d['status']}
TEXT . $this->footer();
    }

    private function absensi(array $d): string
    {
        $hasil = "📅 *ABSENSI HARI INI*\n\n";
        $hasil .= "{$d['nama']} — {$d['kelas']}\n\n";

        if (count($d['absensi'])) {
            foreach ($d['absensi'] as $a) {
                // Hapus tulisan ({$a['status']}) di ujungnya
                $hasil .= "• {$a['jenis']} : {$a['jam']}\n"; 
            }
        } else {
            $hasil .= "Belum ada catatan absensi untuk hari ini.";
        }

        return $hasil . $this->footer();
    }

    private function prestasi(array $d): string
    {
        $hasil = "🏆 *PRESTASI SISWA*\n\n";
        $hasil .= "{$d['nama']}\n\n";

        if (count($d['prestasi'])) {

            foreach ($d['prestasi'] as $p) {
                $hasil .= "• {$p['nama_lomba']}\n";
                $hasil .= "  {$p['tingkat']} • {$p['tahun']}\n\n";
            }
        } else {

            $hasil .= "Belum ada catatan prestasi yang tercatat.";
        }

        return $hasil . $this->footer();
    }
    private function komite(): string
    {
        // Teks otomatis: "Assalamualaikum Warahmatullahi Wabarakatuh"
        return <<<TEXT
📞 *HUBUNGI KOMITE SEKOLAH*

Untuk keperluan atau informasi terkait Komite MTsN 3 Tulungagung, Bapak/Ibu dapat langsung menghubungi:

👤 *Bu Binti (Komite)*
📱 WA: 0857-3664-9582

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
            $namaGuru = "Bu Laela";
            $waNumber = "6282231846743";
            $tingkat = "7";
        } elseif (str_contains($kelas, '8')) {
            $namaGuru = "Bu Ardila";
            $waNumber = "6281220301140";
            $tingkat = "8";
        } elseif (str_contains($kelas, '9')) {
            $namaGuru = "Bu Ulum";
            $waNumber = "6282232260065";
            $tingkat = "9";
        } else {
            return "Mohon maaf, data kelas siswa tidak dikenali ({$kelas}) sehingga sistem tidak dapat menentukan Guru BK yang bertugas. Silakan ketik *menu* untuk mengulang.";
        }

        // Menyusun draf pesan otomatis: "Assalamualaikum Warahmatullahi Wabarakatuh, Bu [Nama]. Mohon maaf mengganggu waktunya, saya wali murid dari [Nama Siswa] kelas [Kelas] ingin..."
        $templatePesan = "Assalamualaikum Warahmatullahi Wabarakatuh, {$namaGuru}. Mohon maaf mengganggu waktunya, saya wali murid dari {$namaSiswa} kelas {$kelas} ingin...";
        $encodedText = rawurlencode($templatePesan);

        $url = "https://wa.me/{$waNumber}?text={$encodedText}";

        return <<<TEXT
👨‍🏫 *HUBUNGI GURU BK*

Berdasarkan data kelas dari *{$namaSiswa}*, berikut adalah Guru BK yang bertugas:

👤 *{$namaGuru} (Guru BK Kelas {$tingkat})*
📱 WA: {$waNumber}

Silakan klik tautan di bawah ini untuk langsung memulai chat dengan beliau:
👉 {$url}
TEXT . $this->footer();
    }
}
