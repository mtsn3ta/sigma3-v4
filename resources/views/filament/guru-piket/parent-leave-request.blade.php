@php
    use App\Models\ParentLeaveRequest;

    $requests = ParentLeaveRequest::query()
        ->with(['siswa.kelasAktif.kelas'])
        ->where('status', 'pending')
        ->latest()
        ->get();

    $historyRequests = ParentLeaveRequest::query()
        ->with(['siswa.kelasAktif.kelas'])
        ->whereIn('status', ['approved', 'rejected'])
        ->latest('verified_at')
        ->limit(20)
        ->get();
@endphp

<div style="display:flex;flex-direction:column;gap:2rem;">

    {{-- ===== DAFTAR PENDING ===== --}}
    <div>
        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
            <h2 style="margin:0;font-size:1.2rem;font-weight:700;color:#0f172a;">Pengajuan Menunggu Verifikasi</h2>
            @if ($requests->count())
                <span
                    style="background:#fef3c7;color:#92400e;font-size:.8rem;font-weight:700;padding:.15rem .6rem;border-radius:9999px;">
                    {{ $requests->count() }}
                </span>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            @forelse($requests as $request)
                @php $siswa = $request->siswa; @endphp

                <div
                    style="border:1px solid #e2e8f0;background:#ffffff;border-radius:1rem;padding:1.25rem 1.5rem;box-shadow:0 1px 2px rgba(0,0,0,.04);">

                    <div
                        style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1rem;">

                        <div>
                            <div style="font-size:1.1rem;font-weight:700;color:#0f172a;">{{ $siswa->nama }}</div>
                            <div style="font-size:.9rem;color:#64748b;margin-top:.15rem;">{{ $siswa->nama_kelas_aktif }}
                            </div>
                        </div>

                        <div style="text-align:right;">
                            <span
                                style="display:inline-block;padding:.3rem .75rem;border-radius:9999px;font-size:.8rem;font-weight:700;
                                {{ $request->jenis === 'sakit' ? 'background:#ede9fe;color:#6d28d9;' : 'background:#dbeafe;color:#1d4ed8;' }}">
                                {{ ucfirst($request->jenis) }}
                            </span>
                            <div style="font-size:.85rem;color:#64748b;margin-top:.4rem;">
                                {{ $request->tanggal_mulai->format('d M Y') }}
                                @if ($request->tanggal_mulai != $request->tanggal_selesai)
                                    <br>s/d {{ $request->tanggal_selesai->format('d M Y') }}
                                @endif
                            </div>
                        </div>

                    </div>

                    <div
                        style="display:flex;flex-wrap:wrap;gap:1.25rem;margin-top:1rem;padding-top:1rem;border-top:1px dashed #e2e8f0;font-size:.85rem;color:#475569;">
                        <div>📷 {{ $request->parent_selfie ? 'Selfie tersedia' : 'Tidak ada selfie' }}</div>
                        <div>✍️ Tanda tangan tersedia</div>
                        <div>📄 {{ $request->doctor_letter ? 'Surat dokter tersedia' : 'Tanpa surat dokter' }}</div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem;">

                        <button type="button" wire:click="preview({{ $request->id }})"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#f1f5f9;color:#334155;border:none;border-radius:.75rem;font-weight:600;font-size:.95rem;cursor:pointer;">
                            🔍 Lihat Detail
                        </button>

                        <button type="button" wire:click="approve({{ $request->id }})"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#16a34a;color:#ffffff;border:none;border-radius:.75rem;font-weight:700;font-size:.95rem;cursor:pointer;box-shadow:0 3px 8px rgba(22,163,74,.25);">
                            ✅ Setujui
                        </button>

                        <button type="button" wire:click="openReject({{ $request->id }})"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#dc2626;color:#ffffff;border:none;border-radius:.75rem;font-weight:700;font-size:.95rem;cursor:pointer;box-shadow:0 3px 8px rgba(220,38,38,.25);">
                            ❌ Tolak
                        </button>

                    </div>

                </div>

            @empty

                <div
                    style="padding:2.5rem 1rem;text-align:center;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:1rem;color:#94a3b8;font-size:.95rem;">
                    Belum ada pengajuan izin dari orang tua.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ===== TABEL RIWAYAT ===== --}}
    <div>
        <h2 style="margin:0 0 1rem;font-size:1.2rem;font-weight:700;color:#0f172a;">Riwayat Pengajuan</h2>

        @if ($historyRequests->count())
            <div style="overflow-x:auto;border:1px solid #e2e8f0;border-radius:1rem;">
                <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Siswa</th>
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Kelas</th>
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Jenis</th>
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Tanggal</th>
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Status</th>
                            <th
                                style="text-align:left;padding:.85rem 1rem;font-weight:700;color:#475569;border-bottom:1px solid #e2e8f0;">
                                Diverifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historyRequests as $request)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:.85rem 1rem;font-weight:600;color:#0f172a;">
                                    {{ $request->siswa->nama }}</td>
                                <td style="padding:.85rem 1rem;color:#475569;">{{ $request->siswa->nama_kelas_aktif }}
                                </td>
                                <td style="padding:.85rem 1rem;color:#475569;">{{ ucfirst($request->jenis) }}</td>
                                <td style="padding:.85rem 1rem;color:#475569;">
                                    {{ $request->tanggal_mulai->format('d M Y') }}
                                    @if ($request->tanggal_mulai != $request->tanggal_selesai)
                                        - {{ $request->tanggal_selesai->format('d M Y') }}
                                    @endif
                                </td>
                                <td style="padding:.85rem 1rem;">
                                    @if ($request->status === 'approved')
                                        <span
                                            style="padding:.25rem .65rem;border-radius:9999px;background:#dcfce7;color:#15803d;font-weight:700;font-size:.8rem;">Disetujui</span>
                                    @else
                                        <span
                                            style="padding:.25rem .65rem;border-radius:9999px;background:#fee2e2;color:#b91c1c;font-weight:700;font-size:.8rem;">Ditolak</span>
                                    @endif
                                </td>
                                <td style="padding:.85rem 1rem;color:#64748b;">
                                    {{ $request->verified_at?->format('d M Y, H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div
                style="padding:2rem 1rem;text-align:center;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:1rem;color:#94a3b8;font-size:.9rem;">
                Belum ada riwayat pengajuan yang diverifikasi.
            </div>
        @endif
    </div>

</div>
