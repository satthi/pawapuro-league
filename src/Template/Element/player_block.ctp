<?php 
if (is_null($player)) {
    return;
}
if (empty($nolink)) {
    $nolink =false;
}
// 色の判定用
$positionInfo = [
    'main' => null,
    'sub' => []
];
$positionLists = [
    'p',
    'c',
    'i',
    'o',
];
foreach ($positionLists as $positionList) {
    if ($player->{'type_' . $positionList} == 2) {
        $positionInfo['main'] = $positionList;
    } elseif ($player->{'type_' . $positionList} == 1) {
        $positionInfo['sub'][] = $positionList;
    }
}
$positionCount = count($positionInfo['sub']) + 1;
?>

<?php //メインが一番左?>
<div class="player_background player_background_left<?php if ($positionCount == 1) echo ' player_background_right';?> color_<?= $positionInfo['main'];?>" style="width:<?= 100 / $positionCount;?>%;left:0;"></div>
<?php foreach ($positionInfo['sub'] as $subCount => $subKey) :?>
<div class="player_background<?php if ($positionCount == $subCount + 2) echo ' player_background_right';?> color_<?= $subKey;?>" style="width:<?= 100 / $positionCount;?>%;left:<?= 100 / $positionCount * ($subCount + 1);?>%;"></div>
<?php endforeach;?>
<div class="player_namebox"
	<?php if (!is_null($player->team)):?>
  data-fukidashi='<div style="background-color:#DDD;border: 1px solid #222;border-radius: 3px; padding:5px;"><table style="width:auto;">
		<tr>
		<td nowrap style="width:100px;">
		<?php
		if (file_exists(ROOT . '/webroot/img/base_player/' . $player->base_player_id . '/file')) {
			echo $this->Html->image('base_player/' . $player->base_player_id . '/file', ['width' => 60]);
		} else {
			echo $this->Html->image('noimage.jpg', ['width' => 60]);
		}
		?>
		</td>
		<td nowrap style="width:100px;">
			<?= $player->name;?>
		</td>
		<td nowrap style="width:150px;">
			<?= $player->real_batter_player_info;?>
		</td>
		</tr>
		<td colspan="3">
		<?php if (!empty($resultsSets[$player->id])) :?>
		<table>
			<tr>
		<?php foreach ($resultsSets[$player->id] as $resultsSet) :?>
			<td class="result_td position result_<?= $resultsSet->result->color_type;?>" nowrap style="width:50px;">
				<?= $resultsSet->result->name;?>
				<?php if ($resultsSet->point > 0):?>
					[<?= $resultsSet->point;?>]
				<?php endif;?>
			</td>
		<?php endforeach;?>
			</tr>
		</table>
		<?php endif;?>
		</td>
		</tr>
	</table></div>'
	<?php endif;?>
>
<?php if ($nolink == false):?>
    <?php if ($player instanceof \App\Model\Entity\Player) :?>
    <?= $this->Html->link($player->name_short, ['controller' => 'players', 'action' => 'view', $player->id]) ?>
    <?php else :?>
    <?= $this->Html->link($player->name_short, ['controller' => 'players', 'action' => 'base_player_view', $player->id]) ?>
    <?php endif;?>
<?php else:?>
    <?= $player->name_short; ?>
<?php endif;?>
</div>
	
