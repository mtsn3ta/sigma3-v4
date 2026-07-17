<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.jsx']); ?>

    <?php $__inertiaSsrResponse = app(\Inertia\Ssr\SsrState::class)->setPage($page)->dispatch();  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>

</head>

<body>

    <?php $__inertiaSsrResponse = app(\Inertia\Ssr\SsrState::class)->setPage($page)->dispatch();  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><script data-page="app" type="application/json"><?php echo json_encode($page); ?></script><div id="app"></div><?php } ?>

</body>

</html>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/app.blade.php ENDPATH**/ ?>