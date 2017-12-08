<?php 
$plusMeat = 0;
$plusPower= 0;
$plusSpeed = 0;
$plusBant = 0;
$plusDefense = 0;
$plusMental = 0;
if (isset($card) && is_object($card)) {
$plusMeat = $card->meat_plus;
$plusPower = $card->power_plus;
$plusSpeed = $card->speed_plus;
$plusBant = $card->bant_plus;
$plusDefense = $card->defense_plus;
$plusMental = $card->mental_plus;

}
;

?>
<div class="block team-<?= $player->team->ryaku_name;?> omote">
    <div class="teamfullname">
        <?= $player->team->name_eng;?>
    </div>
    <div class="image">
    <?php
		if (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg', ['width' => 350]);
		} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.gif')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.gif', ['width' => 350]);
		} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.png')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.png', ['width' => 350]);
		} else {
			echo $this->Html->image('noimage.jpg', ['width' => 350]);
		}
		?>
    </div>
    <div class="clearfix nameset">
	    <div class="teamname">
	        <?= $player->team->ryaku_name;?>
	    </div>
	    <div class="playername length_<?= mb_strlen($player->name);?>">
	        <?= $player->no;?>.
	        <?= $player->name;?>
	    </div>
    </div>
    <div class="statusblock">
        <?php if ($player->type_p === null):?>
        <?= $this->element('status_block_parts', ['name' => '巧打力', 'point' => $player->status_meat, 'plus' => $plusMeat, 'column' => 'meat']);?>
        <?= $this->element('status_block_parts', ['name' => '長打力', 'point' => $player->status_power, 'plus' => $plusPower, 'column' => 'power']);?>
        <?= $this->element('status_block_parts', ['name' => '走　力', 'point' => $player->status_speed, 'plus' => $plusSpeed, 'column' => 'speed']);?>
        <?= $this->element('status_block_parts', ['name' => 'バント', 'point' => $player->status_bant, 'plus' => $plusBant, 'column' => 'bant']);?>
        <?= $this->element('status_block_parts', ['name' => '守備力', 'point' => $player->status_defense, 'plus' => $plusDefense, 'column' => 'defense']);?>
        <?php else:?>
        <?= $this->element('status_block_parts', ['name' => '体　力', 'point' => $player->status_meat, 'plus' => $plusMeat, 'column' => 'meat']);?>
        <?= $this->element('status_block_parts', ['name' => '球　速', 'point' => $player->status_power, 'plus' => $plusPower, 'column' => 'power']);?>
        <?= $this->element('status_block_parts', ['name' => '球　威', 'point' => $player->status_speed, 'plus' => $plusSpeed, 'column' => 'speed']);?>
        <?= $this->element('status_block_parts', ['name' => '変化球', 'point' => $player->status_bant, 'plus' => $plusBant, 'column' => 'bant']);?>
        <?= $this->element('status_block_parts', ['name' => '制球力', 'point' => $player->status_defense, 'plus' => $plusDefense, 'column' => 'defense']);?>
        <?php endif;?>
        <?= $this->element('status_block_parts', ['name' => '精神力', 'point' => $player->status_mental, 'plus' => $plusMental, 'column' => 'mental']);?>
    </div>
    <div class="costblock">
        COST
         <?php for ($i = 1;$i <= 10;$i++):?>
             <?php if ($i <= $player->status_cost):?>
            ★
            <?php else:?>
            ☆
            <?php endif;?>
         <?php endfor;?>
         <?= $player->status_cost;?>
    </div>
    <div><?= $player->team->season->name;?>  <?= $player->player_info;?></div>
</div>
