@php
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $proposals = LaporanBulananHelper::proposal($record);
@endphp

<div>
    <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem;">
        3.1 Proposal Kegiatan
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
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; width: 50px;">
                            No
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">
                            Nama Kegiatan
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; width: 150px;">
                            Jenis
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; width: 150px;">
                            Tanggal
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; width: 120px;">
                            Status
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody>
                    @forelse($proposals as $proposal)
                        <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; text-align: center; color: #6b7280;">
                                {{ $loop->iteration }}
                            </td>

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; font-weight: 500; color: #111827;">
                                {{ $proposal->nama_kegiatan }}
                            </td>

                            <td style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; color: #374151;">
                                {{ $proposal->jenis_kegiatan_label }}
                            </td>

                            <td style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; color: #374151;">
                                {{ $proposal->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                            </td>

                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span
                                    style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #f3f4f6; color: #4b5563;">
                                    {{ $proposal->status_label }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                style="padding: 2rem 1rem; text-align: center; color: #6b7280; font-style: italic;">
                                Tidak ada proposal kegiatan yang diajukan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
