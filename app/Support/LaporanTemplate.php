<?php

namespace App\Support;

use App\Models\LaporanKegiatan;

class LaporanTemplate
{
    public static function make(
        LaporanKegiatan $laporan
    ): array {

        $proposal = $laporan->proposal;

        $jumlahPeserta = $laporan->peserta()->count();

        $jumlahPendamping = $laporan->pendamping()->count();

        return [

            'uraian_pelaksanaan' => <<<HTML
<p>
Kegiatan <strong>{$proposal->nama_kegiatan}</strong> dilaksanakan pada tanggal <strong>{$proposal->tanggal_mulai?->translatedFormat('d F Y')}</strong> sampai dengan <strong>{$proposal->tanggal_selesai?->translatedFormat('d F Y')}</strong> bertempat di <strong>{$proposal->tempat}</strong>.
</p>

<p>
Kegiatan ini diikuti oleh <strong>{$jumlahPeserta} peserta</strong> dan didampingi oleh <strong>{$jumlahPendamping} pendamping</strong>. Seluruh peserta mengikuti kegiatan sesuai jadwal yang telah ditetapkan oleh penyelenggara.
</p>

<p>
Selama pelaksanaan kegiatan, peserta mengikuti seluruh rangkaian kegiatan dengan tertib, disiplin, dan penuh semangat. Pendamping melaksanakan tugas pembinaan, pendampingan, serta pengawasan sehingga kegiatan dapat berjalan dengan aman, tertib, dan lancar.
</p>

<p>
Secara umum pelaksanaan kegiatan berlangsung sesuai dengan rencana. Apabila terdapat perubahan jadwal, kendala, maupun hambatan selama kegiatan berlangsung, dapat dijelaskan pada bagian ini sesuai kondisi sebenarnya.
</p>
HTML,

            'pencapaian' => <<<HTML
<ul>
<li>Kegiatan terlaksana sesuai jadwal.</li>
<li>Peserta mengikuti kegiatan dengan baik.</li>
<li>Tujuan kegiatan secara umum telah tercapai.</li>
<li>Kerja sama antara peserta dan pendamping berjalan dengan baik.</li>
</ul>
HTML,

            'prestasi' => <<<HTML
<p>
Apabila dalam kegiatan ini memperoleh kejuaraan, penghargaan, atau prestasi tertentu, silakan dijelaskan pada bagian ini. Jika tidak ada prestasi yang diperoleh, bagian ini dapat dikosongkan atau diberi keterangan "Tidak ada".
</p>
HTML,

            'evaluasi' => <<<HTML
<ul>
<li>Persiapan kegiatan sudah berjalan dengan baik.</li>
<li>Koordinasi antar pendamping perlu terus ditingkatkan.</li>
<li>Kedisiplinan peserta perlu dipertahankan dan ditingkatkan.</li>
<li>Perencanaan kegiatan berikutnya diharapkan lebih optimal.</li>
</ul>
HTML,

            'penutup' => <<<HTML
<p>
Demikian laporan kegiatan ini disusun sebagai bentuk pertanggungjawaban atas pelaksanaan kegiatan <strong>{$proposal->nama_kegiatan}</strong>. Besar harapan kami agar laporan ini dapat menjadi bahan evaluasi dan dokumentasi untuk pelaksanaan kegiatan berikutnya.
</p>

<p>
Atas perhatian dan kerja sama semua pihak kami sampaikan terima kasih.
</p>
HTML,

        ];
    }
}
