@php
    use Carbon\Carbon;
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $prestasi = LaporanBulananHelper::prestasi($record);
@endphp

<div>
    <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-top: 1.5rem; margin-bottom: 0.75rem;">
        3.3 Prestasi
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
                            Nama Lomba
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">
                            Prestasi
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; width: 100px;">
                            Jenis
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; width: 130px;">
                            Tingkat
                        </th>
                        <th
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; width: 130px;">
                            Tanggal
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody>
                    @forelse($prestasi as $item)
                        <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; text-align: center; color: #6b7280;">
                                {{ $loop->iteration }}
                            </td>

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; font-weight: 500; color: #111827;">
                                {{ $item->nama_lomba }}
                            </td>

                            <td style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb;">
                                <div style="font-weight: 600; color: #111827;">
                                    {{ $item->nama_prestasi }}
                                </div>
                                @if ($item->siswa->isNotEmpty())
                                    <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                                        {{ $item->siswa->pluck('nama')->join(', ') }}
                                    </div>
                                @endif
                            </td>

                            <td style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; text-align: center;">
                                <span
                                    style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #f3f4f6; color: #4b5563;">
                                    {{ $item->jenis_label }}
                                </span>
                            </td>

                            <td
                                style="padding: 0.75rem 1rem; border-right: 1px solid #e5e7eb; text-align: center; color: #374151;">
                                {{ $item->tingkat }}
                            </td>

                            <td style="padding: 0.75rem 1rem; text-align: center; color: #374151;">
                                {{ $item->tanggal->translatedFormat('d M Y') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                style="padding: 2rem 1rem; text-align: center; color: #6b7280; font-style: italic;">
                                Belum ada prestasi yang diraih pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- Kesimpulan Prestasi --}}
    @if ($prestasi->isNotEmpty())
        @php
            $individu = $prestasi->where('jenis', 'individu')->count();
            $tim = $prestasi->where('jenis', 'tim')->count();
        @endphp

        <div
            style="margin-top: 1.25rem; padding: 1rem 1.25rem; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.5rem; color: #166534; font-size: 0.875rem; line-height: 1.5;">
            Pada bulan ini, ekstrakurikuler <strong
                style="font-weight: 600;">{{ $record->ekstrakurikuler->nama }}</strong>
            berhasil memperoleh <strong style="font-weight: 600;">{{ $prestasi->count() }}</strong> prestasi,
            yang terdiri dari <strong style="font-weight: 600;">{{ $individu }}</strong> prestasi individu
            dan <strong style="font-weight: 600;">{{ $tim }}</strong> prestasi tim.
        </div>
    @endif
</div>
