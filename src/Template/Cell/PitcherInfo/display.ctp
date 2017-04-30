<div style="padding: 10px;">
<div>
<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'positionChange', 'game_id' => $gameId, 'team_id' => $playerInfo->team->id]);?>">守備</button>
</div>
投手：[<?= $playerInfo->no;?>] <?= $playerInfo->name;?>[<?= $playerInfo->team->ryaku_name;?>]<br />
<?php
if (file_exists(ROOT . '/webroot/img/player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.jpg')) {
	echo $this->Html->image('player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.jpg', ['width' => 120]);
} elseif (file_exists(ROOT . '/webroot/img/player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.gif')) {
	echo $this->Html->image('player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.gif', ['width' => 120]);
} elseif (file_exists(ROOT . '/webroot/img/player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.png')) {
	echo $this->Html->image('player/' . $playerInfo->team->ryaku_name . '/' . $playerInfo->no . '.png', ['width' => 120]);
} else {
	echo $this->Html->image('noimage.jpg', ['width' => 120]);
}
?>
<br />
<?= $playerInfo->pitcher_player_info;?>

<br />
<table>
	<tr>
		<th>投球回</th>
		<td>
			<?= floor($todeyResult['out'] / 3);?>
    		<?php if ($todeyResult['out'] % 3 != 0) :?>
    			<?= $todeyResult['out'] % 3 . '/3'?>
    		<?php endif;?>
	</td>
		<th>被安打</th>
		<td><?= (int) $todeyResult['hit'];?></td>
	</tr>
	<tr>
		<th>四死球</th>
		<td><?= (int) $todeyResult['yontama'];?></td>
		<th>奪三振</th>
		<td><?= (int) $todeyResult['sansin'];?></td>
	</tr>
	<tr>
		<th>被本塁打</th>
		<td><?= (int) $todeyResult['hr'];?></td>
		<th>失点(参考)</th>
		<td><?= (int) $todeyResult['rbi'];?></td>
	</tr>
</table>


</div>
