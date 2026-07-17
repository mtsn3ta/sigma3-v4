<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #374151;">

    
    <?php
        $statuses = [
            [
                'icon' => '🟢',
                'label' => 'Hadir',
                'color' => '#059669',
                'desc' => 'Siswa hadir tetapi tidak melakukan scan RFID.',
            ],
            [
                'icon' => '🟡',
                'label' => 'Terlambat',
                'color' => '#d97706',
                'desc' => 'Siswa hadir tetapi datang melewati batas waktu.',
            ],
            [
                'icon' => '🔵',
                'label' => 'Izin',
                'color' => '#2563eb',
                'desc' => 'Ada izin resmi dari orang tua atau wali.',
            ],
            ['icon' => '🟣', 'label' => 'Sakit', 'color' => '#7c3aed', 'desc' => 'Siswa sakit.'],
            ['icon' => '🔴', 'label' => 'Alfa', 'color' => '#dc2626', 'desc' => 'Tidak hadir tanpa keterangan.'],
        ];
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <div
                style="font-weight: 600; color: <?php echo e($status['color']); ?>; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.1rem;"><?php echo e($status['icon']); ?></span> <?php echo e($status['label']); ?>

            </div>
            <p style="margin: 0; color: #4b5563; padding-left: 1.6rem; font-size: 0.85rem; line-height: 1.4;">
                <?php echo e($status['desc']); ?>

            </p>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

</div>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/filament/guru-piket/panduan-kehadiran.blade.php ENDPATH**/ ?>