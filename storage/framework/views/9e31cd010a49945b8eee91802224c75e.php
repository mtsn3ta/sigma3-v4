<table width="100%" style="border-bottom:2px solid #000;padding-bottom:10px;margin-bottom:15px;">

    <tr>

        <td width="85" style="text-align:center;vertical-align:top;">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\App\Support\SettingHelper::schoolOfficialLogo()): ?>
                <img src="<?php echo e(public_path('storage/' . \App\Support\SettingHelper::schoolOfficialLogo())); ?>"
                    style="width:70px;height:auto;">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </td>

        <td style="vertical-align:top;">

            <div style="
                    font-size:18px;
                    font-weight:bold;
                ">

                <?php echo e(\App\Support\SettingHelper::schoolName()); ?>


            </div>

            <div
                style="
                    font-size:13px;
                    font-weight:bold;
                    margin-top:2px;
                ">

                Sistem Informasi Global MTsN 3 Tulungagung (SIGMA 3)

            </div>

            <div style="
                    font-size:11px;
                    margin-top:6px;
                ">

                <?php echo e(\App\Support\SettingHelper::schoolAddress()); ?>


            </div>

            <div style="
                    font-size:11px;
                ">

                Telp.
                <?php echo e(\App\Support\SettingHelper::schoolPhone()); ?>


                |

                Email :
                <?php echo e(\App\Support\SettingHelper::schoolEmail()); ?>


            </div>

        </td>

    </tr>

</table>
<?php /**PATH /home/mtsntul1/sigma3.mtsn3tulungagung.sch.id/resources/views/pdf/components/header.blade.php ENDPATH**/ ?>