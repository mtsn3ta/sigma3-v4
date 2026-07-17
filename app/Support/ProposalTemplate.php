<?php

namespace App\Support;

use App\Models\Ekstrakurikuler;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ProposalTemplate
{
    public static function make(
        Ekstrakurikuler $ekstrakurikuler,
        string $namaKegiatan,
        string $penyelenggara,
        string $tingkat
    ): array {

        $namaEkskul = $ekstrakurikuler->nama;

        return [

            'latar_belakang' => <<<HTML
<p>
Kegiatan ekstrakurikuler <strong>{$namaEkskul}</strong> merupakan wadah pembinaan bakat dan karakter peserta didik di luar jam pelajaran intrakurikuler. Selama ini anggota ekstrakurikuler telah melaksanakan latihan secara rutin sebagai upaya meningkatkan kemampuan, disiplin, kerja sama, dan prestasi.
</p>

<p>
Sehubungan dengan akan dilaksanakannya kegiatan <strong>{$namaKegiatan}</strong> yang diselenggarakan oleh <strong>{$penyelenggara}</strong>, kegiatan ini menjadi kesempatan yang sangat baik bagi peserta didik untuk mengimplementasikan hasil latihan, memperoleh pengalaman, serta meningkatkan kompetensi melalui kegiatan tingkat <strong>{$tingkat}</strong>.
</p>

<p>
Melalui kegiatan tersebut diharapkan peserta didik mampu meningkatkan prestasi, memperluas pengalaman, menumbuhkan jiwa sportivitas, serta membawa nama baik MTsN 3 Tulungagung melalui ekstrakurikuler <strong>{$namaEkskul}</strong>.
</p>
HTML,

            'dasar_kegiatan' => <<<HTML
<ol>
<li>Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional.</li>
<li>Program Kerja MTsN 3 Tulungagung Tahun Pelajaran Berjalan.</li>
<li>Program Kerja Ekstrakurikuler <strong>{$namaEkskul}</strong>.</li>
<li>Pelaksanaan kegiatan <strong>{$namaKegiatan}</strong>.</li>
</ol>
HTML,

            'tujuan' => <<<HTML
<ol>
<li>Meningkatkan kemampuan peserta didik melalui kegiatan <strong>{$namaKegiatan}</strong>.</li>
<li>Mengukur hasil pembinaan ekstrakurikuler <strong>{$namaEkskul}</strong>.</li>
<li>Menumbuhkan karakter disiplin, tanggung jawab, sportivitas, dan kerja sama.</li>
<li>Meningkatkan prestasi serta mengharumkan nama MTsN 3 Tulungagung.</li>
</ol>
HTML,

            'penutup' => <<<HTML
<p>
Demikian proposal kegiatan ini disusun sebagai dasar pelaksanaan kegiatan <strong>{$namaKegiatan}</strong>. Besar harapan kami agar kegiatan ini memperoleh dukungan sehingga dapat terlaksana dengan baik.
</p>

<p>
Atas perhatian dan kerja sama semua pihak kami sampaikan terima kasih.
</p>
HTML,

        ];
    }

    public static function fill(Get $get, Set $set): void
    {
        if (! $get('ekstrakurikuler_id')) {
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
            $get('nama_kegiatan') ?? '',
            $get('nama_penyelenggara') ?? '',
            ucfirst($get('tingkat_kegiatan') ?? '')
        );

        $set('latar_belakang', $template['latar_belakang']);
        $set('dasar_kegiatan', $template['dasar_kegiatan']);
        $set('tujuan', $template['tujuan']);
        $set('penutup', $template['penutup']);
    }
}
