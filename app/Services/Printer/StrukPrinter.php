<?php

namespace App\Services\Printer;

use App\Models\IzinKeluar;
use App\Models\TugasGuruIzin;
use Carbon\Carbon;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\Printer;

class StrukPrinter
{
    // Lebar kertas 58mm ~ 32 karakter per baris pakai Font A (default escpos-php).
    private const LEBAR_KERTAS = 32;

    public static function izinKeluar(IzinKeluar $izinKeluar): string
    {
        // ob_start() DI LUAR RawbtPrintConnector supaya echo "intent:base64,..."
        // dari finalize() (dipanggil oleh $printer->close()) tertangkap ke sini,
        // bukan langsung ikut tercetak ke response Laravel.
        ob_start();

        $connector = new Sigma3PrintConnector();
        $printer = new Printer($connector);

        self::header($printer, 'IZIN KELUAR SISWA');

        self::baris($printer, 'Tanggal', $izinKeluar->tanggal->translatedFormat('d F Y'));
        self::baris($printer, 'Nama', $izinKeluar->siswa->nama);
        self::baris($printer, 'Kelas', $izinKeluar->siswa->kelasAktif?->kelas?->nama_kelas ?? '-');
        self::baris($printer, 'Jam Keluar', Carbon::parse($izinKeluar->jam_keluar)->format('H:i'));

        if ($izinKeluar->jam_kembali) {
            self::baris($printer, 'Jam Kembali', Carbon::parse($izinKeluar->jam_kembali)->format('H:i'));
        }

        self::baris($printer, 'Tujuan', $izinKeluar->tujuan);

        self::garis($printer);
        self::judulSeksi($printer, 'Keperluan');
        self::teksWrap($printer, $izinKeluar->keperluan);

        self::garis($printer);
        self::baris($printer, 'Petugas', $izinKeluar->pegawai->nama);
        self::baris($printer, 'Status', strtoupper($izinKeluar->status_proses));

        self::garis($printer);
        self::footer($printer, [
            'Saat kembali ke madrasah,',
            'surat ini wajib diserahkan',
            'kepada Guru BK / Guru Piket.',
        ]);
        self::waktuCetak($printer);

        $printer->close();

        return ob_get_clean();
    }

    public static function tugasGuruIzin(TugasGuruIzin $tugas): string
    {
        ob_start();

        $connector = new Sigma3PrintConnector();
        $printer = new Printer($connector);

        self::header($printer, 'TUGAS GURU BERHALANGAN');

        self::baris($printer, 'Tanggal', $tugas->tanggal->translatedFormat('d F Y'));
        self::baris($printer, 'Guru', $tugas->guru->nama);
        self::baris($printer, 'Kelas', $tugas->kelas->nama_kelas);
        self::baris($printer, 'Mapel', $tugas->mapel);
        self::baris($printer, 'Jam', (string) $tugas->jam_ke);

        self::garis($printer);
        self::judulSeksi($printer, 'Tugas Pembelajaran');
        self::teksWrap($printer, $tugas->isi_tugas);

        self::garis($printer);
        self::baris($printer, 'Status', strtoupper($tugas->status_label));
        self::baris($printer, 'Disampaikan', $tugas->petugas?->nama ?? '-');

        self::garis($printer);
        self::footer($printer, [
            'Tugas ini disampaikan oleh',
            'Guru Piket kepada kelas',
            'yang bersangkutan.',
        ]);

        if ($tugas->updated_at) {
            self::waktuCetak($printer);
        } else {
            $printer->feed(2);
            $printer->cut();
        }

        $printer->close();

        return ob_get_clean();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper layout — meniru struktur thermal/layout.blade.php yang sudah ada,
    | supaya isi struk Bluetooth (RawBT) konsisten dengan versi HTML/browser.
    |--------------------------------------------------------------------------
    */

    private static function header(Printer $printer, string $judul): void
    {
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->text("{$judul}\n");
        $printer->setEmphasis(false);
        $printer->text("MTsN 3 Tulungagung\n");
        self::garis($printer);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
    }

    private static function baris(Printer $printer, string $label, string $nilai): void
    {
        $printer->text(str_pad($label, 12) . ": {$nilai}\n");
    }

    private static function judulSeksi(Printer $printer, string $judul): void
    {
        $printer->setEmphasis(true);
        $printer->text("{$judul}\n");
        $printer->setEmphasis(false);
    }

    private static function teksWrap(Printer $printer, ?string $teks): void
    {
        $printer->text(wordwrap((string) $teks, self::LEBAR_KERTAS, "\n", true) . "\n");
    }

    private static function garis(Printer $printer): void
    {
        $printer->text(str_repeat('-', self::LEBAR_KERTAS) . "\n");
    }

    private static function footer(Printer $printer, array $baris): void
    {
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        foreach ($baris as $line) {
            $printer->text("{$line}\n");
        }
    }

    private static function waktuCetak(Printer $printer): void
    {
        $printer->text("\nDicetak\n");
        $printer->text(now('Asia/Jakarta')->format('d-m-Y H:i') . " WIB\n");
        $printer->feed(2);
        $printer->cut();
    }
}
