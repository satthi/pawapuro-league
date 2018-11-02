<table style="width:auto;">
	<tr>
		<th colspan="3"><?= $teamInfo->name;?></th>
		<th>AVG</th>
		<th>HR</th>
	</tr>
	<?php foreach ($members as $member) :?>
	<?php if (is_object($member)) {
		$memberEntity = $member;
		$member['player'] = $memberEntity->player;
		$member['dajun'] = $memberEntity->dajun;
		// DH解除後のポジションは表示しない
		if ($member['position'] == '') {
			continue;
		}
	}?>
	<tr>
		<td
			<?php if (
				$member['player']->id == $batterId
			):?>
			id="batter"
			<?php endif;?>
			data-player_id = "<?= $member['player']->id;?>"
			class="player"
			data-position="<?= $member['position'];?>"
			data-dajun="<?= $member['dajun'];?>"
			style="white-space: nowrap;"
		>
		<?php if ($member['dajun'] != 10):?>
			<?= $member['dajun'];?>
		<?php endif;?>
		</td>
		<td class="position color_<?= $positionColors[$member['position']];?>"><?= $positionLists[$member['position']];?></td>
        <td class="player_box_td">
            <?= $this->element('player_block', ['player' => $member['player']]);?>
        </td>

		</td>
		<td style="white-space: nowrap;">
			<?php if (empty($playerData[$member['player']->id]['avg'])):?>
				<?= $member['player']->real_avg;?>
			<?php else:?>
				<?php // デモ用?>
				<?= $playerData[$member['player']->id]['avg'];?>
			<?php endif;?>
		</td>
		<td style="white-space: nowrap;">
			<?php if (empty($playerData[$member['player']->id]['hr'])):?>
				<?= $member['player']->real_hr;?>
			<?php else:?>
				<?php // デモ用?>
				<?= $playerData[$member['player']->id]['hr'];?>
			<?php endif;?>
		</td>
	</tr>
	<?php endforeach;?>
</table>
