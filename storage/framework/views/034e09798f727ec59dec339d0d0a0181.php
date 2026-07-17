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

    
    <style>
        .kesiswaan-menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .premium-kesiswaan-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.85rem;
            padding: 1.25rem 1rem;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
            position: relative;
            overflow: hidden;
        }

        .premium-kesiswaan-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #8b5cf6, #d946ef);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .premium-kesiswaan-link:hover {
            transform: translateY(-4px);
            border-color: #ddd6fe;
            background: linear-gradient(180deg, #ffffff 0%, #f5f3ff 100%);
            box-shadow: 0 12px 20px -8px rgba(139, 92, 246, 0.15), 0 4px 6px -4px rgba(139, 92, 246, 0.05);
        }

        .premium-kesiswaan-link:hover::before {
            opacity: 1;
        }

        .icon-wrapper-violet {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .premium-kesiswaan-link:hover .icon-wrapper-violet {
            background: #f5f3ff;
            color: #7c3aed;
            border-color: #ddd6fe;
            transform: scale(1.05);
        }

        .label-violet {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            transition: color 0.3s ease;
            text-align: center;
            line-height: 1.3;
        }

        .premium-kesiswaan-link:hover .label-violet {
            color: #1e293b;
        }
    </style>

    
    <div
        style="
        padding: 2rem;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    ">
        
        <div
            style="
            position: absolute; top: -50px; right: -50px; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.08) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%; pointer-events: none; filter: blur(20px);
        ">
        </div>

        
        <div
            style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; position: relative; z-index: 10;">
            <div
                style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f5f3ff; border-radius: 0.5rem; color: #7c3aed; box-shadow: 0 2px 10px rgba(139, 92, 246, 0.15);">
                <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-o-puzzle-piece','style' => 'width: 20px; height: 20px;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-puzzle-piece','style' => 'width: 20px; height: 20px;']); ?>
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
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Manajemen Kesiswaan</h2>
        </div>

        
        <div class="kesiswaan-menu-grid" style="position: relative; z-index: 10;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['label' => 'Monitoring Kehadiran', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => '/admin/rekap-kehadiran-sekolahs'], ['label' => 'Approval Proposal', 'icon' => 'heroicon-o-document-check', 'url' => '/admin/proposal-kegiatans'], ['label' => 'Approval Laporan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => '/admin/laporan-kegiatans'], ['label' => 'Laporan Bulanan', 'icon' => 'heroicon-o-calendar-days', 'url' => '/admin/laporan-bulanans'], ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => '/admin/prestasis'], ['label' => 'Ekstrakurikuler', 'icon' => 'heroicon-o-puzzle-piece', 'url' => '/admin/ekstrakurikulers']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e($menu['url']); ?>" class="premium-kesiswaan-link">
                    <div class="icon-wrapper-violet">
                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $menu['icon'],'style' => 'width: 24px; height: 24px;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu['icon']),'style' => 'width: 24px; height: 24px;']); ?>
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
                    <span class="label-violet"><?php echo e($menu['label']); ?></span>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/filament/widgets/waka/quick-menu.blade.php ENDPATH**/ ?>