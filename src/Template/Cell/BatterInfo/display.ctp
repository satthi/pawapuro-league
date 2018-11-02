<div style="padding: 10px;">
<div>
<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'pinchHitter', 'game_id' => $gameId, 'team_id' => $playerInfo->team->id, 'dajun' => $dajun]);?>">代打</button>
<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'pinchRunner', 'game_id' => $gameId, 'team_id' => $playerInfo->team->id]);?>">代走</button>
<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'stealCheck', 'game_id' => $gameId, 'team_id' => $playerInfo->team->id]);?>">盗塁</button>
</div>
打者：[<?= $playerInfo->no;?>] <?= $playerInfo->name;?>[<?= $playerInfo->team->ryaku_name;?>]
<br />
<?php
if (file_exists(ROOT . '/webroot/img/base_player/' . $playerInfo->base_player_id . '/file')) {
	echo $this->Html->image('base_player/' . $playerInfo->base_player_id . '/file', ['width' => 120]);
} else {
	echo $this->Html->image('noimage.jpg', ['width' => 120]);
}
?>
<br />
<?= $playerInfo->real_batter_player_info;?><br />

<p>ここ10試合</p>
<?= $playerInfo->getRecentBatterPlayerInfo();?><br />

<p>本日の打席</p>
<table>
<?php foreach ($todayResults as $todayResult):?>
	<tr>
		<td class="result_td position result_<?= $todayResult->result->color_type;?>" >
			<?= $todayResult->result->name;?>
			<?php if ($todayResult->point > 0):?>
				[<?= $todayResult->point;?>]
			<?php endif;?>
		</td>
		<td><?= $todayResult->pitcher->name_short;?></td>
	</tr>
<?php endforeach;?>
</table>

</div>
