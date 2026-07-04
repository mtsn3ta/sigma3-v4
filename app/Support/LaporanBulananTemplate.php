<?php

namespace App\Support;

use App\Models\Ekstrakurikuler;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class LaporanBulananTemplate
{
    public static function make(
        Ekstrakurikuler $ekstrakurikuler,
        int $bulan,
        int $tahun
    ): array {

        $namaEkskul = $ekstrakurikuler->nama;

        $periode = now()
            ->setMonth($bulan)
            ->setYear($tahun)
            ->translatedFormat('F Y');

        // Style presets untuk menjaga konsistensi
        $h4Style = 'font-size: 1.125rem; font-weight: 600; color: #111827; margin-top: 1.5rem; margin-bottom: 0.75rem;';
        $h4FirstStyle = 'font-size: 1.125rem; font-weight: 600; color: #111827; margin-top: 0; margin-bottom: 0.75rem;';
        $pStyle  = 'text-align: justify; line-height: 1.6; color: #374151; margin-bottom: 1rem;';
        $ulStyle = 'line-height: 1.6; color: #374151; margin-bottom: 1rem; padding-left: 1.5rem;';
        $liStyle = 'margin-bottom: 0.5rem; text-align: justify;';

        return [

            /*
            |--------------------------------------------------------------------------
            | BAB I
            |--------------------------------------------------------------------------
            */

            'pendahuluan' => <<<HTML
<h4 style="{$h4FirstStyle}">1.1 Latar Belakang</h4>

<p style="{$pStyle}">
Pendidikan nasional bertujuan untuk mengembangkan potensi peserta didik agar menjadi manusia yang beriman, bertakwa kepada Tuhan Yang Maha Esa, berakhlak mulia, sehat, berilmu, cakap, kreatif, mandiri, serta menjadi warga negara yang demokratis dan bertanggung jawab. Salah satu upaya untuk mewujudkan tujuan tersebut adalah melalui kegiatan ekstrakurikuler sebagai sarana pengembangan minat, bakat, karakter, dan prestasi peserta didik di luar kegiatan pembelajaran intrakurikuler.
</p>

<p style="{$pStyle}">
Ekstrakurikuler <strong>{$namaEkskul}</strong> di MTsN 3 Tulungagung merupakan salah satu wadah pembinaan peserta didik yang dilaksanakan secara terencana dan berkesinambungan. Selama periode <strong>{$periode}</strong>, berbagai kegiatan latihan, pembinaan, pendampingan, serta kegiatan pendukung lainnya telah dilaksanakan sesuai dengan program kerja ekstrakurikuler yang telah disusun.
</p>

<p style="{$pStyle}">
Sebagai bentuk akuntabilitas dan pertanggungjawaban pelaksanaan kegiatan tersebut, disusun Laporan Bulanan Ekstrakurikuler <strong>{$namaEkskul}</strong> periode <strong>{$periode}</strong>. Laporan ini memuat pelaksanaan program latihan, tingkat kehadiran anggota dan pengampu, kegiatan yang telah dilaksanakan, prestasi yang diperoleh, serta hasil evaluasi sebagai dasar penyempurnaan program pada periode berikutnya.
</p>

<h4 style="{$h4Style}">1.2 Landasan Kegiatan</h4>

<ol style="{$ulStyle}">
<li style="{$liStyle}">Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional.</li>
<li style="{$liStyle}">Peraturan Menteri Pendidikan mengenai Kegiatan Ekstrakurikuler pada jenjang Pendidikan Dasar dan Menengah.</li>
<li style="{$liStyle}">Program Kerja MTsN 3 Tulungagung Tahun Pelajaran Berjalan.</li>
<li style="{$liStyle}">Program Kerja Ekstrakurikuler <strong>{$namaEkskul}</strong>.</li>
</ol>
HTML,

            'tujuan' => <<<HTML
<h4 style="{$h4FirstStyle}">1.3 Maksud Penyusunan Laporan</h4>

<p style="{$pStyle}">
Laporan Bulanan ini disusun sebagai bentuk pertanggungjawaban pelaksanaan kegiatan ekstrakurikuler selama periode <strong>{$periode}</strong>, sekaligus sebagai bahan informasi bagi madrasah mengenai perkembangan pelaksanaan program pembinaan peserta didik.
</p>

<h4 style="{$h4Style}">1.4 Tujuan</h4>

<ol style="{$ulStyle}">
<li style="{$liStyle}">Memberikan gambaran mengenai pelaksanaan kegiatan ekstrakurikuler selama satu bulan.</li>
<li style="{$liStyle}">Mengevaluasi tingkat kehadiran anggota dan pengampu dalam setiap sesi latihan.</li>
<li style="{$liStyle}">Mendokumentasikan pelaksanaan program latihan dan kegiatan ekstrakurikuler.</li>
<li style="{$liStyle}">Menyampaikan capaian prestasi yang diperoleh selama periode laporan.</li>
<li style="{$liStyle}">Menjadi bahan evaluasi dan penyusunan program kerja pada bulan berikutnya.</li>
<li style="{$liStyle}">Sebagai bentuk pertanggungjawaban kepada madrasah atas pelaksanaan program ekstrakurikuler.</li>
</ol>
HTML,

            /*
            |--------------------------------------------------------------------------
            | BAB IV
            |--------------------------------------------------------------------------
            */

            'evaluasi' => <<<HTML
<h4 style="{$h4FirstStyle}">4.1 Evaluasi Pelaksanaan Program</h4>

<p style="{$pStyle}">
Berdasarkan pelaksanaan kegiatan ekstrakurikuler <strong>{$namaEkskul}</strong> selama periode <strong>{$periode}</strong>, secara umum seluruh program latihan telah terlaksana sesuai dengan jadwal yang direncanakan.
</p>

<p style="{$pStyle}">
Rekapitulasi kehadiran anggota dan pengampu menunjukkan tingkat partisipasi yang menjadi salah satu indikator keberhasilan pelaksanaan program. Data kehadiran tersebut digunakan sebagai bahan evaluasi untuk mengetahui tingkat kedisiplinan, konsistensi latihan, serta efektivitas pelaksanaan kegiatan ekstrakurikuler selama periode laporan.
</p>

<p style="{$pStyle}">
Secara keseluruhan pelaksanaan program berjalan dengan baik. Apabila terdapat program yang belum terlaksana secara optimal ataupun tingkat kehadiran yang masih perlu ditingkatkan, maka hal tersebut menjadi bahan evaluasi untuk penyusunan program latihan pada periode berikutnya.
</p>
HTML,

            'kendala' => <<<HTML
<h4 style="{$h4FirstStyle}">4.2 Kendala yang Dihadapi</h4>

<ul style="{$ulStyle}">
<li style="{$liStyle}">Kehadiran sebagian anggota belum maksimal pada beberapa sesi latihan.</li>
<li style="{$liStyle}">Penyesuaian jadwal latihan dengan kegiatan akademik maupun agenda madrasah.</li>
<li style="{$liStyle}">Keterbatasan sarana dan prasarana latihan sesuai kebutuhan ekstrakurikuler.</li>
<li style="{$liStyle}">Faktor cuaca atau kondisi tertentu yang mempengaruhi pelaksanaan kegiatan di lapangan.</li>
</ul>
HTML,

            'solusi' => <<<HTML
<h4 style="{$h4FirstStyle}">4.3 Solusi dan Tindak Lanjut</h4>

<ul style="{$ulStyle}">
<li style="{$liStyle}">Meningkatkan koordinasi antara pembina, pelatih, dan anggota ekstrakurikuler.</li>
<li style="{$liStyle}">Menyusun kembali jadwal latihan apabila terdapat perubahan agenda madrasah.</li>
<li style="{$liStyle}">Mengoptimalkan penggunaan sarana yang tersedia serta mengusulkan kebutuhan sarana baru apabila diperlukan.</li>
<li style="{$liStyle}">Meningkatkan motivasi dan kedisiplinan anggota agar kehadiran pada setiap sesi latihan semakin baik.</li>
<li style="{$liStyle}">Menjadikan hasil evaluasi periode ini sebagai dasar penyusunan program latihan pada bulan berikutnya.</li>
</ul>
HTML,

            /*
            |--------------------------------------------------------------------------
            | BAB V
            |--------------------------------------------------------------------------
            */

            'penutup' => <<<HTML
<h4 style="{$h4FirstStyle}">5.1 Penutup</h4>

<p style="{$pStyle}">
Puji syukur ke hadirat Allah Swt. atas segala rahmat dan karunia-Nya sehingga seluruh rangkaian kegiatan Ekstrakurikuler <strong>{$namaEkskul}</strong> selama periode <strong>{$periode}</strong> dapat dilaksanakan dengan baik. Laporan Bulanan ini disusun sebagai bentuk pertanggungjawaban pelaksanaan program kerja sekaligus sebagai dokumentasi kegiatan pembinaan peserta didik yang telah dilaksanakan.
</p>

<p style="{$pStyle}">
Diharapkan laporan ini dapat menjadi bahan evaluasi dalam meningkatkan kualitas pembinaan ekstrakurikuler pada periode berikutnya. Seluruh kekurangan yang masih terdapat dalam pelaksanaan program akan menjadi perhatian untuk dilakukan perbaikan secara berkelanjutan sehingga tujuan pembinaan peserta didik dapat tercapai secara optimal.
</p>

<p style="{$pStyle}">
Demikian Laporan Bulanan Ekstrakurikuler <strong>{$namaEkskul}</strong> periode <strong>{$periode}</strong> ini disusun. Atas perhatian, dukungan, dan kerja sama dari semua pihak, kami menyampaikan terima kasih.
</p>
HTML,

        ];
    }

    public static function fill(Get $get, Set $set): void
    {
        if (! $get('ekstrakurikuler_id')) {
            return;
        }

        if (! $get('bulan') || ! $get('tahun')) {
            return;
        }

        $ekstrakurikuler = Ekstrakurikuler::find(
            $get('ekstrakurikuler_id')
        );

        if (! $ekstrakurikuler) {
            return;
        }

        $template = self::make(
            $ekstrakurikuler,
            (int) $get('bulan'),
            (int) $get('tahun')
        );

        $set('pendahuluan', $template['pendahuluan']);
        $set('tujuan', $template['tujuan']);
        $set('evaluasi', $template['evaluasi']);
        $set('kendala', $template['kendala']);
        $set('solusi', $template['solusi']);
        $set('penutup', $template['penutup']);
    }
}
