<?php //debug($cardSkill);exit;?>
<?php $skill_block_mapping = \Cake\Core\Configure::read('skill_block.' . $cardSkill->shape_type);?>
<div class="skill-parts-small skill-parts-<?= $cardSkill->skill_type;?>">
    <table>
        <?php for ($i = 0;$i <= 3;$i++):?>
        <tr>
            <?php for ($j = 1;$j <= 4;$j++):?>
			<?php $class = 'noneblock';?>
			<?php if (in_array($i * 4 +$j, $skill_block_mapping)) $class = 'setblock';?>
            <td class="<?= $class;?>"></td>
        	<?php endfor;?>
        </tr>
    	<?php endfor;?>
    </table>
</div>
