<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Jurnal Mengajar</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .summary {
            margin: 6px 0 10px;
            font-size: 10px;
        }

        .summary td {
            padding: 4px;
            border: 1px solid #444;
            text-align: center;
            font-weight: bold;
        }

        table.timeline {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table.timeline th,
        table.timeline td {
            border: 1px solid #444;
            padding: 4px;
            vertical-align: top;
        }

        table.timeline th {
            background: #efefef;
            text-align: center;
        }

        .judul-hari {
            margin-top: 10px;
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-mengajar {
            color: green;
            font-weight: bold;
        }

        .status-izin {
            color: #d97706;
            font-weight: bold;
        }

        .status-kosong {
            color: red;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $monitoring; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $halaman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php echo $__env->make('pdf.components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <h2>
            MONITORING JURNAL MENGAJAR
        </h2>

        <div class="info">

            <table>

                <tr>

                    <td width="15%">
                        <strong>Periode</strong>
                    </td>

                    <td width="35%">
                        :
                        <?php echo e($tanggalMulai->translatedFormat('d F Y')); ?>

                        s/d
                        <?php echo e($tanggalSelesai->translatedFormat('d F Y')); ?>

                    </td>

                    <td width="10%">
                        <strong>Kelas</strong>
                    </td>

                    <td>
                        :
                        <?php echo e($kelas->nama_kelas); ?>

                    </td>

                </tr>

            </table>

        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $halaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="judul-hari">

                <?php echo e($hari['tanggal']->translatedFormat('l, d F Y')); ?>


            </div>

            <table class="summary">

                <tr>

                    <td>

                        Mengajar

                        <br>

                        <?php echo e($hari['totalMengajar']); ?>


                    </td>

                    <td>

                        Guru Izin

                        <br>

                        <?php echo e($hari['totalIzin']); ?>


                    </td>

                    <td>

                        Belum Isi

                        <br>

                        <?php echo e($hari['totalKosong']); ?>


                    </td>

                </tr>

            </table>

            <table class="timeline">

                <thead>

                    <tr>

                        <th width="7%">
                            Jam
                        </th>

                        <th width="12%">
                            Status
                        </th>

                        <th width="18%">
                            Guru
                        </th>

                        <th width="12%">
                            Mapel
                        </th>

                        <th width="23%">
                            Materi / Tugas
                        </th>

                        <th width="18%">
                            Tidak Hadir
                        </th>

                        <th width="10%">
                            Catatan
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $hari['timeline']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>

                            <td align="center">

                                <?php echo e($row['jam']); ?>


                            </td>

                            <td align="center">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($row['status']):
                                    case ('mengajar'): ?>
                                        <span class="status-mengajar">

                                            Mengajar

                                        </span>
                                    <?php break; ?>

                                    <?php case ('izin'): ?>
                                        <span class="status-izin">

                                            Guru Izin

                                        </span>
                                    <?php break; ?>

                                    <?php default: ?>
                                        <span class="status-kosong">

                                            Belum Isi

                                        </span>
                                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                            <td>

                                <?php echo e($row['guru']); ?>


                            </td>

                            <td>

                                <?php echo e($row['mapel']); ?>


                            </td>

                            <td>

                                <?php echo nl2br(e($row['materi'])); ?>


                            </td>

                            <td>

                                <?php echo nl2br(e($row['tidak_hadir'])); ?>


                            </td>

                            <td>

                                <?php echo nl2br(e($row['catatan'])); ?>


                            </td>

                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                </tbody>

            </table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($loop->last)): ?>
            <div class="page-break"></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo $__env->make('pdf.components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

</body>

</html>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/pdf/monitoring-jurnal.blade.php ENDPATH**/ ?>