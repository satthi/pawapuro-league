<?php 
$plusPower= 0;
$plusBant = 0;
$plusDefense = 0;
if (isset($card) && is_object($card)) {
$plusPower = $card->power_plus;
$plusBant = $card->bant_plus;
$plusDefense = $card->defense_plus;

}
;

?><div class="block team-<?= $player->team->ryaku_name;?> ura">
    <div class="clearfix back-body">
        <div class="back-left">
	    	<?php
			if (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg')) {
				echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg', ['width' => 100]);
			} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.gif')) {
				echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.gif', ['width' => 100]);
			} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.png')) {
				echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.png', ['width' => 100]);
			} else {
				echo $this->Html->image('noimage.jpg', ['width' => 100]);
			}
			?>
			<div><?= \Cake\Core\Configure::read('HandOptoins.' . $player->throw);?>投<?= \Cake\Core\Configure::read('HandOptoins.' . $player->bat);?>打</div>
			<div><?= $player->team->season->name;?></div>
			<div>★<?= $player->status_cost;?></div>
			<div><?= $statusPositionLists[$player->status_position];?></div>
        </div>
        <div class="back-right">
        	<div class="back-name playername length_<?= mb_strlen($player->name);?>"><?= $player->name;?></div>
        	<?php if ($player->type_p === null):?>
        	<div class="position_block">
        	<?= $this->Html->image('card/card_back_b.png');?>
        	<?= $this->element('player_card_back_b', ['player' => $player, 'plus' => $plusDefense]);?>
        	</div>
        	<?php else:?>
        	<div class="speed_block"><?= floor(($player->status_power + $plusPower) / 2 + 115);?>km</div>
        	<div class="sphere_block">
        	<?= $this->element('player_card_back_p', ['type' => 'l', 'player' => $player, 'plus' => $plusBant]);?>
        	<?= $this->element('player_card_back_p', ['type' => 'ld', 'player' => $player, 'plus' => $plusBant]);?>
        	<?= $this->element('player_card_back_p', ['type' => 'd', 'player' => $player, 'plus' => $plusBant]);?>
        	<?= $this->element('player_card_back_p', ['type' => 'rd', 'player' => $player, 'plus' => $plusBant]);?>
        	<?= $this->element('player_card_back_p', ['type' => 'r', 'player' => $player, 'plus' => $plusBant]);?>
        	<?= $this->Html->image('card/card_back_p_base.png');?>
        	</div>
        	<?php endif;?>
        </div>
    
    
    </div>
    <div class="back-score">
        <?php if ($player->type_p === null):?>
            <?= $this->element('player_card_back_record_b', ['player' => $player]);?>
        <?php else :?>
            <?= $this->element('player_card_back_record_p', ['player' => $player]);?>
        <?php endif;?>
    </div>
    	
    <?php 
    $card_mappings = [];
    if ($card->card_mappings != '') {
        $card_mappings = unserialize($card->card_mappings);
    }
    ?>
	<?php $skill_mapping = \Cake\Core\Configure::read('skill_mapping.' . $player->skill_type);?>
    <div class="back-skill-block clearfix">
        <div class="main-skill-block">
	        <table>
                <?php for ($i = 0;$i <= 3;$i++):?>
	            <tr>
	                <?php for ($j = 1;$j <= 4;$j++):?>
	    			<?php $class = 'inactive';?>
	    			<?php if (in_array($i * 4 +$j, $skill_mapping)) $class = 'active';?>
	    			<?php if (!empty($card_mappings[$i * 4 +$j])):?>
	    				<?php $thisclass = $card_mappings[$i * 4 +$j];?>
	  				<?php else:?>
	    				<?php $thisclass = $class . ' block_no_' . ($i * 4 +$j);?>
	  				<?php endif;?>
	                <td class="<?= $thisclass;?>" data-no="<?= $i * 4 +$j;?>"></td>
	            	<?php endfor;?>
	            </tr>
            	<?php endfor;?>
	        </table>
        </div>
        <div class="detail-skill-block">
	        <table>
                <?php for ($i = 1;$i <= 4;$i++):?>
	            <tr>
	                <td></td>
	            </tr>
            	<?php endfor;?>
	        </table>
        </div>
    </div>


</div>
