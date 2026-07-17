<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildSchema()); ?>

</div>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/vendor/filament/schemas/resources/views/components/grid.blade.php ENDPATH**/ ?>