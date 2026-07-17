@php
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $data = LaporanBulananHelper::anggota($record);
    $sessions = $data['sessions'];
    $members = $data['members'];
    $attendance = $data['attendance'];
@endphp

<div>
    <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem;">
        2.1 Rekap Kehadiran Anggota
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
                            style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; min-width: 200px;">
                            Nama Anggota
                        </th>

                        @forelse ($sessions as $session)
                            <th
                                style="padding: 0.75rem 0.5rem; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; font-size: 0.75rem; white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($session->tanggal)->translatedFormat('d M') }}
                            </th>
                        @empty
                            <th
                                style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-weight: 400; font-style: italic;">
                                Belum ada sesi
                            </th>
                        @endforelse
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody>
                    @forelse ($members as $member)
                        <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">

                            <td
                                style="padding: 0.5rem 1rem; border-right: 1px solid #e5e7eb; text-align: center; color: #6b7280;">
                                {{ $loop->iteration }}
                            </td>

                            <td
                                style="padding: 0.5rem 1rem; border-right: 1px solid #e5e7eb; font-weight: 500; color: #111827;">
                                {{ $member->siswa->nama ?? 'Anonim' }}
                            </td>

                            @forelse ($sessions as $session)
                                @php
                                    $hadir = $attendance->get($member->siswa_id)?->get($session->id);
                                @endphp

                                <td
                                    style="padding: 0.5rem; border-right: 1px solid #e5e7eb; text-align: center; font-weight: 600;">
                                    @switch($hadir?->status)
                                        @case('hadir')
                                            <span style="color: #16a34a;">✔</span>
                                        @break

                                        @case('izin')
                                            <span style="color: #0284c7;">I</span>
                                        @break

                                        @case('sakit')
                                            <span style="color: #ca8a04;">S</span>
                                        @break

                                        @case('alpha')
                                            <span style="color: #dc2626;">A</span>
                                        @break

                                        @default
                                            <span style="color: #d1d5db; font-weight: 400;">-</span>
                                    @endswitch
                                </td>
                                @empty
                                    <td style="padding: 0.5rem; text-align: center; background-color: #f9fafb;"></td>
                                @endforelse
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 2 + max($sessions->count(), 1) }}"
                                        style="padding: 2rem 1rem; text-align: center; color: #6b7280; font-style: italic;">
                                        Belum ada data anggota pada ekstrakurikuler ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Keterangan / Legenda --}}
            <div style="margin-top: 0.75rem; display: flex; gap: 1rem; font-size: 0.75rem; color: #6b7280;">
                <span style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="color: #16a34a; font-weight: bold;">✔</span> Hadir
                </span>
                <span style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="color: #0284c7; font-weight: bold;">I</span> Izin
                </span>
                <span style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="color: #ca8a04; font-weight: bold;">S</span> Sakit
                </span>
                <span style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="color: #dc2626; font-weight: bold;">A</span> Alpha
                </span>
                <span style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="color: #d1d5db; font-weight: bold;">-</span> Belum Diisi
                </span>
            </div>
        </div>
