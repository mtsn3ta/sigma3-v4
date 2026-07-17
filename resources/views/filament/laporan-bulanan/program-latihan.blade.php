@php
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $sessions = LaporanBulananHelper::sesi($record);
@endphp

<div>
    <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-top: 1.5rem; margin-bottom: 0.75rem;">
        2.2 Program Latihan
    </h3>

    {{-- Table Container --}}
    <div
        style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; text-align: left; font-size: 0.875rem; border-collapse: collapse;">

                {{-- Table Header --}}
                <thead style="background-color: #f9fafb; color: #4b5563; font-weight: 600;">
                    <tr>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; width: 60px;">
                            No
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; width: 140px;">
                            Tanggal
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; width: 200px;">
                            Pelatih
                        </th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb;">
                            Program Latihan
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody>
                    @forelse ($sessions as $session)
                        <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; text-align: center; color: #6b7280;">
                                {{ $loop->iteration }}
                            </td>

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; font-weight: 500; color: #111827;">
                                {{ \Carbon\Carbon::parse($session->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <td style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; color: #374151;">
                                {{ $session->pelatih?->nama ?? '-' }}
                            </td>

                            <td style="padding: 0.75rem 1rem; color: #374151; line-height: 1.6;">
                                {!! $session->program_latihan ?: '<span style="color: #9ca3af; font-style: italic;">Belum diisi</span>' !!}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                style="padding: 2rem 1rem; text-align: center; color: #6b7280; font-style: italic;">
                                Belum ada sesi kegiatan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
