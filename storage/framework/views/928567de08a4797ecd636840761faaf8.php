<div
    style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
    <table style="width: 100%; font-size: 0.875rem; text-align: left; border-collapse: collapse;">
        <thead style="background-color: rgba(249, 250, 251, 0.7); border-bottom: 1px solid #e5e7eb;">
            <tr>
                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                    Nama Siswa
                </th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center; color: #111827;">
                    Status
                </th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                    Keterangan
                </th>
            </tr>
        </thead>
        <tbody style="color: #374151;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $kehadiran = $s->kehadiranSekolah->first();
                    $status = $kehadiran ? strtolower($kehadiran->status) : 'belum ada data';
                    $keterangan = $kehadiran && $kehadiran->keterangan ? $kehadiran->keterangan : null;
                ?>

                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#f9fafb'"
                    onmouseout="this.style.backgroundColor='transparent'">

                    <td style="padding: 0.75rem 1rem; font-weight: 500; color: #111827;">
                        <?php echo e($s->nama); ?>

                    </td>

                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <?php if (isset($component)) { $__componentOriginal986dce9114ddce94a270ab00ce6c273d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal986dce9114ddce94a270ab00ce6c273d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.badge','data' => ['color' => match ($status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'info',
                            'sakit' => 'gray',
                            'alfa' => 'danger',
                            default => 'gray',
                        }]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(match ($status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'info',
                            'sakit' => 'gray',
                            'alfa' => 'danger',
                            default => 'gray',
                        })]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(ucfirst($status)); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $attributes = $__attributesOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $component = $__componentOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__componentOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
                    </td>

                    <td style="padding: 0.75rem 1rem; color: #4b5563;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($keterangan): ?>
                            <?php echo e($keterangan); ?>

                        <?php else: ?>
                            <span style="font-style: italic; color: #9ca3af;">-</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #6b7280;">
                        Belum ada data siswa di kelas ini.
                    </td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/livewire/detail-kehadiran-kelas.blade.php ENDPATH**/ ?>