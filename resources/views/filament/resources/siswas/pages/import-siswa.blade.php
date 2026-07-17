<div>
    <x-filament-panels::page>
        {{-- Wrapper utama dengan gap --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Header Section --}}
            <x-filament::section>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div>
                        <h2 style="font-size: 1.125rem; font-weight: 700; margin: 0; color: #111827;">
                            Import Biodata Siswa
                        </h2>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                            Upload file Excel (.xlsx/.csv) untuk melihat preview sebelum data diimport ke database.
                        </p>
                    </div>
                </div>
            </x-filament::section>

            {{-- Form Section --}}
            <x-filament::section>
                <div style="width: 100%;">
                    {{ $this->form }}
                </div>
            </x-filament::section>

            {{-- Preview Section --}}
            @if (count($this->preview))
                @php
                    $valid = collect($this->preview)->where('status', 'valid')->count();
                    $error = collect($this->preview)->where('status', 'error')->count();
                @endphp

                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>Preview Data Import</span>
                        </div>
                    </x-slot>

                    {{-- Summary Badges --}}
                    <div
                        style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; padding: 1rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.875rem; font-weight: 500; color: #4b5563;">Total Data:</span>
                            <x-filament::badge color="gray">
                                {{ count($this->preview) }} Baris
                            </x-filament::badge>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.875rem; font-weight: 500; color: #4b5563;">Siap Import:</span>
                            <x-filament::badge color="success">
                                {{ $valid }} Valid
                            </x-filament::badge>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.875rem; font-weight: 500; color: #4b5563;">Perlu
                                Diperbaiki:</span>
                            <x-filament::badge color="danger">
                                {{ $error }} Error
                            </x-filament::badge>
                        </div>
                    </div>

                    {{-- Table Container --}}
                    <div
                        style="border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);">
                        <div style="overflow-x: auto;">
                            <table
                                style="width: 100%; text-align: left; font-size: 0.875rem; border-collapse: collapse;">

                                {{-- Table Header --}}
                                <thead
                                    style="background-color: #f3f4f6; color: #374151; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">
                                    <tr>
                                        <th
                                            style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; width: 100px;">
                                            Status</th>
                                        <th style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb;">NISN</th>
                                        <th style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb;">Nama
                                            Lengkap</th>
                                        <th
                                            style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; width: 80px;">
                                            JK</th>
                                        <th style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb;">Keterangan
                                        </th>
                                    </tr>
                                </thead>

                                {{-- Table Body --}}
                                <tbody>
                                    @foreach ($this->preview as $row)
                                        <tr style="background-color: #ffffff;">
                                            <td
                                                style="padding: 1rem; border-bottom: 1px solid #f3f4f6; text-align: center;">
                                                @if ($row['status'] === 'valid')
                                                    <x-filament::badge color="success">Valid</x-filament::badge>
                                                @else
                                                    <x-filament::badge color="danger">Error</x-filament::badge>
                                                @endif
                                            </td>

                                            <td
                                                style="padding: 1rem; border-bottom: 1px solid #f3f4f6; font-family: monospace; color: #4b5563;">
                                                {{ $row['nisn'] ?? '-' }}
                                            </td>

                                            <td
                                                style="padding: 1rem; border-bottom: 1px solid #f3f4f6; font-weight: 500; color: #111827;">
                                                {{ $row['nama'] ?? '-' }}
                                            </td>

                                            <td
                                                style="padding: 1rem; border-bottom: 1px solid #f3f4f6; text-align: center;">
                                                <x-filament::badge
                                                    color="{{ ($row['jenis_kelamin'] ?? '') === 'L' ? 'info' : 'warning' }}">
                                                    {{ $row['jenis_kelamin'] ?? '-' }}
                                                </x-filament::badge>
                                            </td>

                                            <td style="padding: 1rem; border-bottom: 1px solid #f3f4f6;">
                                                @if (empty($row['errors']))
                                                    <span
                                                        style="color: #16a34a; font-size: 0.8125rem; font-weight: 500;">
                                                        ✓ Data sesuai
                                                    </span>
                                                @else
                                                    <ul
                                                        style="margin: 0; padding-left: 1.25rem; color: #dc2626; font-size: 0.8125rem;">
                                                        @foreach ($row['errors'] as $message)
                                                            <li style="margin-bottom: 0.25rem;">{{ $message }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </x-filament::section>
            @endif

        </div>
    </x-filament-panels::page>
</div>
