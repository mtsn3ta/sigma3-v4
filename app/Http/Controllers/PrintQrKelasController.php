<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Support\SettingHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class PrintQrKelasController extends Controller
{
    /**
     * Cetak PDF QR Code Kelas dengan pilihan ukuran:
     * - a4: 1 lembar penuh A4 per kelas (Poster Pintu/Dinding Kelas)
     * - a5: 2 kelas per lembar A4 / format A5 (Standing Akrilik Meja Guru)
     * - stiker: Ukuran stiker kecil grid
     */
    public function __invoke(Request $request)
    {
        $kelasId = $request->query('kelas_id');
        $format = strtolower($request->query('format', 'a4')); // 'a4', 'a5', 'stiker'

        $kelasQuery = Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas');
        if ($kelasId) {
            $kelasQuery->where('id', $kelasId);
        }

        $kelasList = $kelasQuery->get();

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => QRCode::ECC_M,
        ]);

        $items = [];
        foreach ($kelasList as $kelas) {
            if (empty($kelas->qr_token)) {
                $kelas->qr_token = \Illuminate\Support\Str::random(32);
                $kelas->save();
            }

            $url = url('/scan-kelas?token=' . $kelas->qr_token);
            // Buat instance QRCode baru per kelas agar segmen buffer tidak menumpuk
            $qrSvg = (new QRCode($options))->render($url);

            $items[] = [
                'kelas' => $kelas,
                'qr_data_uri' => $qrSvg,
                'url' => $url,
            ];
        }

        $schoolName = SettingHelper::schoolName();
        if ($schoolName === '-') {
            $schoolName = 'MTsN 3 TULUNGAGUNG';
        }

        $viewName = match ($format) {
            'a5' => 'pdf.qr-kelas-a5',
            'stiker' => 'pdf.qr-kelas-stickers',
            default => 'pdf.qr-kelas-a4', // Default A4 Poster Besar
        };

        $paper = ($format === 'a5') ? 'a5' : 'a4';
        $orientation = 'portrait';

        $pdf = Pdf::loadView($viewName, [
            'items' => $items,
            'schoolName' => $schoolName,
            'format' => $format,
        ])->setPaper($paper, $orientation);

        $targetLabel = $kelasId
            ? 'Kelas-' . ($kelasList->first()?->nama_kelas ?? 'Detail')
            : 'Semua-Kelas';

        $fileName = "QR-{$targetLabel}-Ukuran-" . strtoupper($format) . ".pdf";

        return $pdf->stream($fileName);
    }
}
