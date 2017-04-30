<table style="width:auto;">
	<tr>
		<th colspan="3"><?= $teamInfo->name;?></th>
	</tr>
	<?php foreach ($members as $member) :?>
	<?php if (is_object($member)) {
		$memberEntity = $member;
		$member['player'] = $memberEntity->player;
		$member['dajun'] = $memberEntity->dajun;
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
		><?= $member['dajun'];?></td>
		<td class="position color_<?= $positionColors[$member['position']];?>"><?= $positionLists[$member['position']];?></td>
        <td class="player_box_td">
            <?= $this->element('player_block', ['player' => $member['player']]);?>
        </td>

		</td>
		<td>
			<?= $member['player']->real_avg;?>
		</td>
	</tr>
	<?php endforeach;?>
</table>
