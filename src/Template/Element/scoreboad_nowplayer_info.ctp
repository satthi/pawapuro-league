<?php if ($attack_team_id == $teamInfo->id):?>
<div>
	<?= $this->cell('BatterInfo', ['id' => $batterId, 'game_id' => $gameId, 'dajun' => $batter_dajun])->render();?>
</div>
<?php elseif ($attack_team_id == $vsTeamInfo->id):?>
<div>
	<?= $this->cell('PitcherInfo', ['id' => $pitcherId, 'game_id' => $gameId])->render();?>
</div>
<?php else:?>
	<div style="padding: 10px;">
		<div>
		<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'positionChange', 'game_id' => $gameId, 'team_id' => $teamInfo->id]);?>">守備</button>
		</div>
	</div>
<?php endif;?>
