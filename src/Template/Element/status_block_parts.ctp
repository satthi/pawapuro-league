        <div class="statusblockparts clearfix <?= $column;?>">
            <div class="statusname">
                <?= $name;?>
            </div>
            <div class="statusbar">
                <div class="statusbarbody" style="width: <?= $point;?>%;"></div>
                <div class="statusbarplus" style="width: <?= (int) $plus;?>%;"></div>
            </div>
            <div class="statuspoint<?php if ($point + $plus >= 90) {
                echo ' pointred';
            } elseif ($point + $plus >= 80) {
                echo ' pointorange';
            } elseif ($point + $plus >= 70) {
                echo ' pointyellow';
            }?>">
                <?= $point + $plus;?>
            </div>
            <div class="statusplus">
                <?php if ($plus > 0):?>
                	+<?= $plus;?>
                <?php endif;?>
            </div>
        </div>
