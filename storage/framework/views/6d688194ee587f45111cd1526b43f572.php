<?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    
    <div
        style="
        padding: 2rem;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    ">
        
        <div
            style="
            position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%; pointer-events: none; filter: blur(20px);
        ">
        </div>

        
        <div
            style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; position: relative; z-index: 10;">
            <div
                style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #d1fae5; border-radius: 0.5rem; color: #059669; box-shadow: 0 2px 10px rgba(16, 185, 129, 0.15);">
                <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-o-building-library','style' => 'width: 20px; height: 20px;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-building-library','style' => 'width: 20px; height: 20px;']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Ekstrakurikuler yang Saya Ampu
            </h2>
        </div>

        
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; position: relative; z-index: 10;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $ekskulData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div style="border: 1px solid #f3f4f6; border-radius: 0.75rem; padding: 1.25rem; background: #ffffff;">

                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                        <div>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">
                                <?php echo e($item['ekskul']->nama); ?></h3>
                            <div style="margin-top: 0.5rem;">
                                <?php if (isset($component)) { $__componentOriginal986dce9114ddce94a270ab00ce6c273d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal986dce9114ddce94a270ab00ce6c273d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.badge','data' => ['color' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'success']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($item['jumlahAnggota']); ?> Anggota
                                    Aktif <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $attributes = $__attributesOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $component = $__componentOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__componentOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['href' => '/admin/ekstrakurikulers/'.e($item['ekskul']->id).'/edit','tag' => 'a','size' => 'sm','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/admin/ekstrakurikulers/'.e($item['ekskul']->id).'/edit','tag' => 'a','size' => 'sm','color' => 'gray']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            Detail →
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                    </div>

                    
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                        <p
                            style="margin: 0 0 0.75rem 0; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em;">
                            Tim Pengampu</p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['tim']->isNotEmpty()): ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $item['tim']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div
                                        style="display: flex; align-items: center; gap: 0.5rem; border: 1px solid #f3f4f6; border-radius: 0.5rem; padding: 0.35rem 0.75rem; background: #f9fafb;">
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700; color: #ffffff; background-color: <?php echo e($anggota->role === 'pembina' ? '#10b981' : '#0ea5e9'); ?>;">
                                            <?php echo e(substr($anggota->pegawai?->nama ?? 'U', 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p style="margin: 0; font-size: 0.75rem; font-weight: 600; color: #374151;">
                                                <?php echo e($anggota->pegawai?->nama ?? '-'); ?></p>
                                            <p
                                                style="margin: 0; font-size: 0.6rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">
                                                <?php echo e($anggota->role); ?></p>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p style="margin: 0; font-size: 0.75rem; font-style: italic; color: #9ca3af;">Belum ada
                                rekan pengampu.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p style="grid-column: 1/-1; text-align: center; color: #9ca3af; padding: 2rem;">Tidak ada ekskul yang
                    dilatih.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/filament/widgets/pembina/ekskul-list.blade.php ENDPATH**/ ?>