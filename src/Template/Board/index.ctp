<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>score board</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('board.css') ?>
    <?= $this->Html->script('jquery') ?>
    <?= $this->Html->script('board') ?>
</head>
<body>
<div class="clearfix body">
	<div class="fleft member team_<?= $game->home_team->ryaku_name;?>">
		<div class="member_logo_div">
			<?= $this->Html->image('logo_mini/' . $game->home_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
		</div>
		<?php for ($i = 1;$i <= 9;$i++):?>
		<?php
			$options = [
				'type' => 'home',
				'dajun' => $i,
				'position' => $players[$game->home_team->id][$i]->position,
				'name' => $players[$game->home_team->id][$i]->player->name_short,
				'avg' => $scores[$players[$game->home_team->id][$i]->player_id]['avg'],
				'hr' => $scores[$players[$game->home_team->id][$i]->player_id]['hr'],
				'rbi' => $scores[$players[$game->home_team->id][$i]->player_id]['rbi'],
			];
			if ($players[$game->home_team->id][$i]->position == 1) {
				$options['era'] = $scores[$players[$game->home_team->id][$i]->player_id]['era'];
			}
		?>
		<?= $this->element('board/player', $options);?>
		<?php endfor;?>
	</div>
	<div class="fleft main">
		<div class="score clearfix">
			<div class="fleft score_main">
				<table>
					<tr>
						<th></th>
						<?php for($i = 1;$i <= 9; $i++):?>
						<th class="score_num"><?= $i;?></th>
						<?php endfor;?>
						<th class="score_num">R</th>
						<th class="score_num">H</th>
						<th class="score_num">E</th>
					</tr>
					<tr class="num_tr">
						<td><?= $this->Html->image('logo_mini/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'score_logo']);?></td>
						<?php for($i = 1;$i <= 9; $i++):?>
						<td></td>
						<?php endfor;?>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr class="num_tr">
						<td><?= $this->Html->image('logo_mini/' . $game->home_team->ryaku_name . '.png', ['class' => 'score_logo']);?></td>
						<?php for($i = 1;$i <= 9; $i++):?>
						<td></td>
						<?php endfor;?>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="fright">
				<table class="bso">
					<tr class="tr_b">
						<th>B</th>
						<td class="active">●</td>
						<td>●</td>
						<td>●</td>
					</tr>
					<tr class="tr_s">
						<th>S</th>
						<td class="active">●</td>
						<td>●</td>
						<td></td>
					</tr>
					<tr class="tr_o">
						<th>O</th>
						<td class="active">●</td>
						<td>●</td>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="screen">
			<!-- main -->
			<div class="vs_screen">
				<div class="vs_screen_home"><?= $this->Html->image('logo_big/' . $game->home_team->ryaku_name . '.png', ['class' => 'vs_logo']);?></div>
				<div class="vs_screen_vs">VS</div>
				<div class="vs_screen_visitor"><?= $this->Html->image('logo_big/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'vs_logo']);?></div>
			</div>
			<div class="mark_home">
				<?= $this->Html->image('logo_big/' . $game->home_team->ryaku_name . '.png', ['class' => 'mark_logo']);?>
			</div>
			<div class="mark_visitor">
				<?= $this->Html->image('logo_big/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'mark_logo']);?>
			</div>
			
			<?php for ($i = 1;$i <= 9;$i++):?>
			<?php
				$options = [
					'type' => 'visitor',
					'dajun' => $i,
					'name' => $players[$game->visitor_team->id][$i]->player->name_eng,
					'number' => $players[$game->visitor_team->id][$i]->player->no,
					'team_ryaku' => $game->visitor_team->ryaku_name,
					'img_path' => $scores[$players[$game->visitor_team->id][$i]->player_id]['img_path'],
					'avg' => $scores[$players[$game->visitor_team->id][$i]->player_id]['avg'],
					'hr' => $scores[$players[$game->visitor_team->id][$i]->player_id]['hr'],
					'rbi' => $scores[$players[$game->visitor_team->id][$i]->player_id]['rbi'],
					'steal' => $scores[$players[$game->visitor_team->id][$i]->player_id]['steal'],
					'dasu' => $scores[$players[$game->visitor_team->id][$i]->player_id]['dasu'],
					'hit' => $scores[$players[$game->visitor_team->id][$i]->player_id]['hit'],
				];
				if ($players[$game->visitor_team->id][$i]->position == 1) {
					$options['era'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['era'];
					$options['game_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['game_sum'];
					$options['inning_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['inning_sum'];
					$options['win_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['win_sum'];
					$options['lose_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['lose_sum'];
					$options['save_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['save_sum'];
					$options['hold_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['hold_sum'];
					$options['sansin_sum'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['sansin_sum'];
				}
			?>
			<?= $this->element('board/main_player', $options);?>
			<?php endfor;?>
			<?php for ($i = 1;$i <= 9;$i++):?>
			<?php
				$options = [
					'type' => 'home',
					'dajun' => $i,
					'name' => $players[$game->home_team->id][$i]->player->name_eng,
					'number' => $players[$game->home_team->id][$i]->player->no,
					'team_ryaku' => $game->home_team->ryaku_name,
					'img_path' => $scores[$players[$game->home_team->id][$i]->player_id]['img_path'],
					'avg' => $scores[$players[$game->home_team->id][$i]->player_id]['avg'],
					'hr' => $scores[$players[$game->home_team->id][$i]->player_id]['hr'],
					'rbi' => $scores[$players[$game->home_team->id][$i]->player_id]['rbi'],
					'steal' => $scores[$players[$game->home_team->id][$i]->player_id]['steal'],
					'dasu' => $scores[$players[$game->home_team->id][$i]->player_id]['dasu'],
					'hit' => $scores[$players[$game->home_team->id][$i]->player_id]['hit'],
				];
				if ($players[$game->home_team->id][$i]->position == 1) {
					$options['era'] = $scores[$players[$game->home_team->id][$i]->player_id]['era'];
					$options['game_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['game_sum'];
					$options['inning_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['inning_sum'];
					$options['win_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['win_sum'];
					$options['lose_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['lose_sum'];
					$options['save_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['save_sum'];
					$options['hold_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['hold_sum'];
					$options['sansin_sum'] = $scores[$players[$game->home_team->id][$i]->player_id]['sansin_sum'];
				}
			?>
			<?= $this->element('board/main_player', $options);?>
			<?php endfor;?>
		</div>
	</div>
	<div class="fleft member team_<?= $game->visitor_team->ryaku_name;?>">
		<div class="member_logo_div">
			<?= $this->Html->image('logo_mini/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
		</div>
		<?php for ($i = 1;$i <= 9;$i++):?>
		<?php
			$options = [
				'type' => 'visitor',
				'dajun' => $i,
				'position' => $players[$game->visitor_team->id][$i]->position,
				'name' => $players[$game->visitor_team->id][$i]->player->name_short,
				'avg' => $scores[$players[$game->visitor_team->id][$i]->player_id]['avg'],
				'hr' => $scores[$players[$game->visitor_team->id][$i]->player_id]['hr'],
				'rbi' => $scores[$players[$game->visitor_team->id][$i]->player_id]['rbi'],
			];
			if ($players[$game->visitor_team->id][$i]->position == 1) {
				$options['era'] = $scores[$players[$game->visitor_team->id][$i]->player_id]['era'];
			}
		?>
		<?= $this->element('board/player', $options);?>
		<?php endfor;?>
	</div>
</div>

<div>
<?php // 大変お待たせいたしました?>
<audio src="<?= $this->Url->build('/voice/other/taihenomatase.wav');?>" controls></audio>
<?php // ホームチーム名?>
<audio src="<?= $this->Url->build('/voice/other/team_' . $game->home_team->ryaku_name . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/other/bgm_' . $game->home_team->ryaku_name . '.mp3');?>" controls></audio>
<?php // 対?>
<audio src="<?= $this->Url->build('/voice/other/vs.wav');?>" controls></audio>
<?php // ビジターチーム名?>
<audio src="<?= $this->Url->build('/voice/other/team_' . $game->visitor_team->ryaku_name . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/other/bgm_' . $game->visitor_team->ryaku_name . '.mp3');?>" controls></audio>
<?php // のスターティングラインナップをご紹介?>
<audio src="<?= $this->Url->build('/voice/other/nostamen.wav');?>" controls></audio>
<?php // 先攻 ?>
<audio src="<?= $this->Url->build('/voice/other/visitor_team.wav');?>" controls></audio>
<?php // 後攻 ?>
<audio src="<?= $this->Url->build('/voice/other/home_team.wav');?>" controls></audio>
<?php // それでは試合開始まで ?>
<audio src="<?= $this->Url->build('/voice/other/soredehashiai.wav');?>" controls></audio>
<?php // 背番号?>
<audio src="<?= $this->Url->build('/voice/common/back_number.wav');?>" controls></audio>
<?php // 歓声?>
<audio src="<?= $this->Url->build('/voice/common/kansei.wav');?>" controls></audio>
<?php // 1番～9番?>
<?php for($i = 1;$i <= 9;$i++):?>
<audio src="<?= $this->Url->build('/voice/common/dajun_' . $i . '.wav');?>" controls></audio>
<?php endfor;?>
<?php // ポジション?>
<?php for($i = 1;$i <= 9;$i++):?>
<audio src="<?= $this->Url->build('/voice/common/position_' . $i . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/common/positiond_' . $i . '.wav');?>" controls></audio>
<?php endfor;?>
<?php // 各選手情報?>
<?php // ビジター?>
<?php for($i = 1;$i <= 9;$i++):?>
<audio src="<?= $this->Url->build('/voice/number/' . $players[$game->visitor_team->id][$i]->player->no . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/team/' . $game->visitor_team->ryaku_name . '/' . $players[$game->visitor_team->id][$i]->player->no . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/team/' . $game->visitor_team->ryaku_name . '/' . $players[$game->visitor_team->id][$i]->player->no . 'd.wav');?>" controls></audio>
<?php endfor;?>
<?php // ホーム?>
<?php for($i = 1;$i <= 9;$i++):?>
<audio src="<?= $this->Url->build('/voice/number/' . $players[$game->home_team->id][$i]->player->no . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/team/' . $game->home_team->ryaku_name . '/' . $players[$game->home_team->id][$i]->player->no . '.wav');?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/team/' . $game->home_team->ryaku_name . '/' . $players[$game->home_team->id][$i]->player->no . 'd.wav');?>" controls></audio>
<?php endfor;?>
</div>
</body>
</html>
