@php
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $statistik = LaporanBulananHelper::statistik($record);
@endphp

<div>
    <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-top: 1.5rem; margin-bottom: 0.75rem;">
        2.3 Statistik Pelaksanaan
    </h3>

    {{-- Table Container --}}
    <div
        style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <table style="width: 100%; text-align: left; font-size: 0.875rem; border-collapse: collapse;">
            <tbody>

                {{-- Baris 1: Anggota --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb; width: 70%;">
                        Jumlah Anggota Aktif
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center; width: 30%;">
                        {{ $statistik['jumlah_anggota'] }}
                    </td>
                </tr>

                {{-- Baris 2: Sesi --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb;">
                        Jumlah Sesi Latihan
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center;">
                        {{ $statistik['jumlah_sesi'] }}
                    </td>
                </tr>

                {{-- Baris 3: Kehadiran --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb;">
                        Persentase Kehadiran
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center;">
                        <span
                            style="color: {{ $statistik['persentase_kehadiran'] >= 75 ? '#16a34a' : ($statistik['persentase_kehadiran'] >= 50 ? '#ca8a04' : '#dc2626') }};">
                            {{ $statistik['persentase_kehadiran'] }}%
                        </span>
                    </td>
                </tr>

                {{-- Baris 4: Proposal --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb;">
                        Proposal Kegiatan Diajukan
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center;">
                        {{ $statistik['jumlah_proposal'] }}
                    </td>
                </tr>

                {{-- Baris 5: Laporan --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb;">
                        Laporan Kegiatan Selesai
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center;">
                        {{ $statistik['jumlah_kegiatan'] }}
                    </td>
                </tr>

                {{-- Baris 6: Prestasi --}}
                <tr>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #f9fafb; color: #4b5563; font-weight: 500; border-right: 1px solid #e5e7eb;">
                        Prestasi Diraih
                    </td>
                    <td
                        style="padding: 0.75rem 1.25rem; background-color: #ffffff; color: #111827; font-weight: 600; text-align: center;">
                        {{ $statistik['jumlah_prestasi'] }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
