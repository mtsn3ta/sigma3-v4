<?php
    $availableRoles = \App\Support\ActiveRole::availableRoles();
    $activeRole = \App\Support\ActiveRole::get();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($availableRoles) > 1): ?>

    <div
        style="
        margin:0px 12px;
        padding-top:12px;
        border-top:1px solid rgba(148,163,184,.18);
    ">

        
        <div
            style="
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:10px;
        ">

            <span
                style="
                font-size:10px;
                font-weight:700;
                letter-spacing:.12em;
                text-transform:uppercase;
                color:#2d2e30;
            ">
                Mode Aktif
            </span>

            <span
                style="
                width:6px;
                height:6px;
                border-radius:999px;
                background:#22c55e;
                box-shadow:0 0 10px rgba(34,197,94,.5);
            ">
            </span>

        </div>

        <div style="
            display:flex;
            flex-direction:column;
            gap:4px;
        ">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $isActive = $activeRole === $role;
                ?>

                <form method="POST" action="<?php echo e(route('active-role.switch')); ?>" style="margin:0;">

                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="role" value="<?php echo e($role); ?>">

                    <button type="submit"
                        style="
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        background: <?php echo e($isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : 'transparent'); ?>;
        color: <?php echo e($isActive ? '#15803d' : '#475569'); ?>;
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: <?php echo e($isActive ? '0 6px 18px rgba(34,197,94,.08), inset 0 1px 0 rgba(255,255,255,.8)' : 'none'); ?>;
        border: 1px solid <?php echo e($isActive ? 'rgba(34,197,94,.2)' : 'transparent'); ?>;
    "
                        onmouseover="
        this.style.background = '<?php echo e($isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : '#f8fafc'); ?>';
        this.style.transform = 'translateX(2px)';
    "
                        onmouseout="
        this.style.background = '<?php echo e($isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : 'transparent'); ?>';
        this.style.transform = 'translateX(0)';
    ">


                        

                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => \App\Support\ActiveRole::icon($role),'style' => '
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: '.e($isActive ? '#16a34a' : '#64748b').';
    ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\ActiveRole::icon($role)),'style' => '
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: '.e($isActive ? '#16a34a' : '#64748b').';
    ']); ?>
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


                        

                        <span
                            style="
                            flex:1;
                            text-align:left;
                            font-size:13px;
                            font-weight:
                                <?php echo e($isActive ? '600' : '500'); ?>;
                        ">

                            <?php echo e(\App\Support\ActiveRole::label($role)); ?>


                        </span>

                        

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isActive): ?>
                            <span
                                style="
                                width:8px;
                                height:8px;
                                border-radius:999px;
                                background:#22c55e;
                                box-shadow:0 0 12px rgba(37,99,235,.45);
                                flex-shrink:0;
                            ">
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </button>

                </form>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        </div>

    </div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div id="sigma3-edit-profil-block"
    style="
    margin:0.5px 6px 0;
    padding-top:0.5px;
    padding-bottom:8px;
    border-top:1px solid rgba(148,163,184,.18);
">

    <a href="<?php echo e(\App\Filament\Pages\EditProfile::getUrl()); ?>"
        style="
        width:100%;
        display:flex;
        align-items:center;
        gap:12px;
        padding:10px 12px;
        border-radius:12px;
        background:transparent;
        color:#475569;
        text-decoration:none;
        cursor:pointer;
        transition:all .2s ease;
        border:1px solid transparent;
        box-sizing:border-box;
    "
        onmouseover="
        this.style.background='#f8fafc';
        this.style.transform='translateX(2px)';
    "
        onmouseout="
        this.style.background='transparent';
        this.style.transform='translateX(0)';
    ">

        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-o-user-circle','style' => '
            width:18px;
            height:18px;
            flex-shrink:0;
            color:#64748b;
        ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-user-circle','style' => '
            width:18px;
            height:18px;
            flex-shrink:0;
            color:#64748b;
        ']); ?>
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

        <span style="flex:1;text-align:left;font-size:13px;font-weight:500;">
            Edit Profil
        </span>

    </a>

</div>

<style>
    #sigma3-edit-profil-block+div,
    #sigma3-edit-profil-block+nav,
    #sigma3-edit-profil-block+ul {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
</style>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/filament/sidebar/role-switcher.blade.php ENDPATH**/ ?>