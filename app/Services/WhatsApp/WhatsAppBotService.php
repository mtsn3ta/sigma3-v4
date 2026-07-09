<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;

class WhatsAppBotService
{
    public function __construct(
        protected ConversationService $conversation,
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
    | Sapaan (robust terhadap variasi ejaan & typo umum)
    |--------------------------------------------------------------------------
    */

        $salamPattern = '/assalamu[\s\'`]*alaikum|askum|aslkm|assalamualaikum/i';

        if (preg_match($salamPattern, $message)) {

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

            $menuMap = [
                '1' => 'profil',
                '2' => 'absensi',
                '3' => 'prestasi',
                '4' => 'portal',
            ];

            if (! isset($menuMap[$message])) {

                $this->reply(
                    $phone,
                    "Mohon maaf, Bapak/Ibu 🙏 Pesan belum kami mengerti.\n\nSilakan balas dengan angka *1* sampai *4* sesuai menu di bawah, atau ketik *menu* untuk melihat pilihan kembali."
                        . "\n\n" . $this->mainMenu()
                );

                return;
            }

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

        /*
    |--------------------------------------------------------------------------
    | Input NISN
    |--------------------------------------------------------------------------
    */

        if ($conversation->state === 'waiting_nisn') {

            if (!preg_match('/^\d{10}$/', $message)) {

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

                default:
                    $text = "Mohon maaf, menu tidak dikenali. Ketik *menu* untuk kembali ke menu utama.";
            }

            $this->conversation->reset($phone);

            $this->reply($phone, $text);
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Fallback terakhir: state tidak dikenal / pesan random
    |--------------------------------------------------------------------------
    */

        $this->conversation->reset($phone);

        $this->reply(
            $phone,
            "Mohon maaf, Bapak/Ibu 🙏 Pesan belum kami mengerti.\n\n"
                . $this->mainMenu()
        );
    }

    protected function reply(string $phone, string $text): void
    {
        WhatsApp::web('main')
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

Balas angka *1-4* untuk melanjutkan 🙏
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
                $hasil .= "• {$a['jenis']} : {$a['jam']} ({$a['status']})\n";
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
}
