<?php
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
?>

<div style="display:flex;flex-direction:column;gap:2rem;">

    
    <div>
        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
            <h2 style="margin:0;font-size:1.2rem;font-weight:700;color:#0f172a;">Pengajuan Menunggu Verifikasi</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->count()): ?>
                <span
                    style="background:#fef3c7;color:#92400e;font-size:.8rem;font-weight:700;padding:.15rem .6rem;border-radius:9999px;">
                    <?php echo e($requests->count()); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $siswa = $request->siswa; ?>

                <div
                    style="border:1px solid #e2e8f0;background:#ffffff;border-radius:1rem;padding:1.25rem 1.5rem;box-shadow:0 1px 2px rgba(0,0,0,.04);">

                    <div
                        style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1rem;">

                        <div>
                            <div style="font-size:1.1rem;font-weight:700;color:#0f172a;"><?php echo e($siswa->nama); ?></div>
                            <div style="font-size:.9rem;color:#64748b;margin-top:.15rem;"><?php echo e($siswa->nama_kelas_aktif); ?>

                            </div>
                        </div>

                        <div style="text-align:right;">
                            <span
                                style="display:inline-block;padding:.3rem .75rem;border-radius:9999px;font-size:.8rem;font-weight:700;
                                <?php echo e($request->jenis === 'sakit' ? 'background:#ede9fe;color:#6d28d9;' : 'background:#dbeafe;color:#1d4ed8;'); ?>">
                                <?php echo e(ucfirst($request->jenis)); ?>

                            </span>
                            <div style="font-size:.85rem;color:#64748b;margin-top:.4rem;">
                                <?php echo e($request->tanggal_mulai->format('d M Y')); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->tanggal_mulai != $request->tanggal_selesai): ?>
                                    <br>s/d <?php echo e($request->tanggal_selesai->format('d M Y')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div
                        style="display:flex;flex-wrap:wrap;gap:1.25rem;margin-top:1rem;padding-top:1rem;border-top:1px dashed #e2e8f0;font-size:.85rem;color:#475569;">
                        <div>📷 <?php echo e($request->parent_selfie ? 'Selfie tersedia' : 'Tidak ada selfie'); ?></div>
                        <div>✍️ Tanda tangan tersedia</div>
                        <div>📄 <?php echo e($request->doctor_letter ? 'Surat dokter tersedia' : 'Tanpa surat dokter'); ?></div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem;">

                        <button type="button" wire:click="preview(<?php echo e($request->id); ?>)"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#f1f5f9;color:#334155;border:none;border-radius:.75rem;font-weight:600;font-size:.95rem;cursor:pointer;">
                            🔍 Lihat Detail
                        </button>

                        <button type="button" wire:click="approve(<?php echo e($request->id); ?>)"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#16a34a;color:#ffffff;border:none;border-radius:.75rem;font-weight:700;font-size:.95rem;cursor:pointer;box-shadow:0 3px 8px rgba(22,163,74,.25);">
                            ✅ Setujui
                        </button>

                        <button type="button" wire:click="openReject(<?php echo e($request->id); ?>)"
                            style="flex:1;min-width:120px;padding:.85rem 1rem;background:#dc2626;color:#ffffff;border:none;border-radius:.75rem;font-weight:700;font-size:.95rem;cursor:pointer;box-shadow:0 3px 8px rgba(220,38,38,.25);">
                            ❌ Tolak
                        </button>

                    </div>

                </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                <div
                    style="padding:2.5rem 1rem;text-align:center;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:1rem;color:#94a3b8;font-size:.95rem;">
                    Belum ada pengajuan izin dari orang tua.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div>
        <h2 style="margin:0 0 1rem;font-size:1.2rem;font-weight:700;color:#0f172a;">Riwayat Pengajuan</h2>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($historyRequests->count()): ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $historyRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:.85rem 1rem;font-weight:600;color:#0f172a;">
                                    <?php echo e($request->siswa->nama); ?></td>
                                <td style="padding:.85rem 1rem;color:#475569;"><?php echo e($request->siswa->nama_kelas_aktif); ?>

                                </td>
                                <td style="padding:.85rem 1rem;color:#475569;"><?php echo e(ucfirst($request->jenis)); ?></td>
                                <td style="padding:.85rem 1rem;color:#475569;">
                                    <?php echo e($request->tanggal_mulai->format('d M Y')); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->tanggal_mulai != $request->tanggal_selesai): ?>
                                        - <?php echo e($request->tanggal_selesai->format('d M Y')); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td style="padding:.85rem 1rem;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'approved'): ?>
                                        <span
                                            style="padding:.25rem .65rem;border-radius:9999px;background:#dcfce7;color:#15803d;font-weight:700;font-size:.8rem;">Disetujui</span>
                                    <?php else: ?>
                                        <span
                                            style="padding:.25rem .65rem;border-radius:9999px;background:#fee2e2;color:#b91c1c;font-weight:700;font-size:.8rem;">Ditolak</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td style="padding:.85rem 1rem;color:#64748b;">
                                    <?php echo e($request->verified_at?->format('d M Y, H:i') ?? '-'); ?>

                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div
                style="padding:2rem 1rem;text-align:center;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:1rem;color:#94a3b8;font-size:.9rem;">
                Belum ada riwayat pengajuan yang diverifikasi.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</div>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/filament/guru-piket/parent-leave-request.blade.php ENDPATH**/ ?>