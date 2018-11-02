<?php $player= $card->player;?>
<?php if (empty($dataType)) $dataType = null;?>
<div class="block_short" data-type="<?= $dataType;?>">
	<?php if (!empty($dajun)):?>
	<div class="short_dajun"><?= $dajun;?></div>
	<?php endif;?>
	<div class="player_block_wrap">
	<div class="player_block">
    <div class="short_image" data-card_id="<?= $card->id;?>">
    <?php
		if (file_exists(ROOT . '/webroot/img/base_player/' . $player->base_player_id . '/file')) {
			echo $this->Html->image('base_player/' . $player->base_player_id . '/file', ['width' => 60]);
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
        <div class="cost_block">★<span class="cost_number"><?= $player->status_cost;?><span></div>
    </div>
    </div>
    <div class="short_detail">
        <?= $this->Html->link('詳細', ['controller' => 'users', 'action' => 'carddetail', $card->id], ['target' => '_blank']);?>
    </div>
    </div>
	<?php if (!empty($position)):?>
	<div class="short_position short_position_<?= $position;?>"><span class="position_data" data-position="<?= $position;?>"><?= \Cake\Core\Configure::read('batterPositionName.' . $position);?></span></div>
	<?php endif;?>
</div>
