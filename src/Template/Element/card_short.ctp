<?php $player= $card->player;?>
<div class="block_short">
	<?php if (!empty($dajun)):?>
	<div class="short_dajun"><?= $dajun;?></div>
	<?php endif;?>
    <div class="short_image">
    <?php
		if (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg', ['width' => 60]);
		} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.gif')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.gif', ['width' => 60]);
		} elseif (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.png')) {
			echo $this->Html->image('player/' . $player->team->ryaku_name . '/' . $player->no . '.png', ['width' => 60]);
		} else {
			echo $this->Html->image('noimage.jpg', ['width' => 60]);
		}
		?>
    </div>
    <div class="short_name">
        <?= $player->name_short;?>
    </div>
    <div class="position_cost clearfix">
        <div class="position_block position_block_<?= $player->status_position;?>"><?= $statusPositionShortLists[$player->status_position];?></div>
        <div class="cost_block">★<?= $player->status_cost;?></div>
    </div>
    <div class="short_detail">
        <?= $this->Html->link('詳細', ['controller' => 'users', 'action' => 'carddetail', $card->id], ['target' => '_blank']);?>
    </div>
	<?php if (!empty($position)):?>
	<div class="short_position short_position_<?= $position;?>"><?= \Cake\Core\Configure::read('batterPositionName.' . $position);?></div>
	<?php endif;?>
</div>
