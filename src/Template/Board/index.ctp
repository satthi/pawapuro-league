<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>score board</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('board.css') ?>
    <?= $this->Html->script('jquery') ?>
    <script>var gameId = "<?= $gameId;?>";</script>
    <?= $this->Html->script('board') ?>
</head>
<body>
<div class="clearfix body">
	<div class="fleft member team_<?= $game->home_team->ryaku_name;?>" id="home_team_div">
		<div class="member_logo_div">
			<?= $this->Html->image('logo_mini/' . $game->home_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
		</div>
		<?php for ($i = 1;$i <= 10;$i++):?>
		<?php if (empty( $players[$game->home_team->id][$i])) :?>
			<?php continue;?>
		<?php endif;?>
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
			
			<?php for ($i = 1;$i <= 10;$i++):?>
			<?php if (empty( $players[$game->visitor_team->id][$i])) :?>
				<?php continue;?>
			<?php endif;?>
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
			<?php for ($i = 1;$i <= 10;$i++):?>
			<?php if (empty( $players[$game->home_team->id][$i])) :?>
				<?php continue;?>
			<?php endif;?>
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
	<div class="fleft member team_<?= $game->visitor_team->ryaku_name;?>" id="visitor_team_div">
		<div class="member_logo_div">
			<?= $this->Html->image('logo_mini/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
		</div>
		<?php for ($i = 1;$i <= 10;$i++):?>
			<?php if (empty( $players[$game->visitor_team->id][$i])) :?>
				<?php continue;?>
			<?php endif;?>
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
<?= $game->date;?>
<div style="display:none;">
<?php // 大変お待たせいたしました?>
<audio src="<?= $this->Url->build('/voice/other/taihenomatase.wav');?>" id="voice_taihenomatase" controls></audio>
<?php // ホームチーム名?>
<audio src="<?= $this->Url->build('/voice/other/team_' . $game->home_team->ryaku_name . '.wav');?>" id="voice_home_team_name" controls></audio>
<audio src="<?= $this->Url->build('/voice/other/bgm_' . $game->home_team->ryaku_name . '.mp3');?>" id="voice_home_team_bgm" controls loop></audio>
<?php // 対?>
<audio src="<?= $this->Url->build('/voice/other/vs.wav');?>" id="voice_vs" controls></audio>
<?php // ビジターチーム名?>
<audio src="<?= $this->Url->build('/voice/other/team_' . $game->visitor_team->ryaku_name . '.wav');?>" id="voice_visitor_team_name" controls></audio>
<audio src="<?= $this->Url->build('/voice/other/bgm_' . $game->visitor_team->ryaku_name . '.mp3');?>" id="voice_visitor_team_bgm" controls loop></audio>
<?php // のスターティングラインナップをご紹介?>
<audio src="<?= $this->Url->build('/voice/other/nostamen.wav');?>" id="voice_nostamen" controls></audio>
<?php // 先攻 ?>
<audio src="<?= $this->Url->build('/voice/other/visitor_team.wav');?>" id="voice_visitor_team" controls></audio>
<?php // 後攻 ?>
<audio src="<?= $this->Url->build('/voice/other/home_team.wav');?>" id="voice_home_team" controls></audio>
<?php // それでは試合開始まで ?>
<audio src="<?= $this->Url->build('/voice/other/soredehashiai.wav');?>" id="voice_soredehashiai" controls></audio>
<?php // 背番号?>
<audio src="<?= $this->Url->build('/voice/common/back_number.wav');?>" id="voice_back_number" controls></audio>
<?php // 歓声?>
<audio src="<?= $this->Url->build('/voice/common/kansei.wav');?>" id="voice_kansei" controls></audio>
<?php // 1番～9番?>
<?php for($i = 1;$i <= 10;$i++):?>
<audio src="<?= $this->Url->build('/voice/common/dajun_' . $i . '.wav');?>" id="voice_dajun_<?= $i;?>" controls></audio>
<?php endfor;?>
<?php // 各選手情報?>
<?php // ビジター?>
<?php for($i = 1;$i <= 10;$i++):?>
<?php if (empty( $players[$game->visitor_team->id][$i])) :?>
	<?php continue;?>
<?php endif;?>
<audio src="<?= $this->Url->build('/voice/common/position_' . $players[$game->visitor_team->id][$i]->position . '.wav');?>" id="voice_visitor_player_position_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/common/positiond_' . $players[$game->visitor_team->id][$i]->position . '.wav');?>" id="voice_visitor_player_positiond_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/number/' . $players[$game->visitor_team->id][$i]->player->no . '.wav');?>" id="voice_visitor_no_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/member/' . $players[$game->visitor_team->id][$i]->player->base_player_id . '/base.wav');?>" id="voice_visitor_player_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/member/' . $players[$game->visitor_team->id][$i]->player->base_player_id . '/full.wav');?>" id="voice_visitor_playerd_<?= $i;?>" controls></audio>
<?php endfor;?>
<?php // ホーム?>
<?php for($i = 1;$i <= 10;$i++):?>
<?php if (empty( $players[$game->home_team->id][$i])) :?>
	<?php continue;?>
<?php endif;?>
<audio src="<?= $this->Url->build('/voice/common/position_' . $players[$game->home_team->id][$i]->position . '.wav');?>" id="voice_home_player_position_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/common/positiond_' . $players[$game->home_team->id][$i]->position . '.wav');?>" id="voice_home_player_positiond_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/number/' . $players[$game->home_team->id][$i]->player->no . '.wav');?>" id="voice_home_no_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/member/' . $players[$game->home_team->id][$i]->player->base_player_id . '/base.wav');?>" id="voice_home_player_<?= $i;?>" controls></audio>
<audio src="<?= $this->Url->build('/voice/member/' . $players[$game->home_team->id][$i]->player->base_player_id . '/full.wav');?>" id="voice_home_playerd_<?= $i;?>" controls></audio>
<?php endfor;?>
</div>
</body>
</html>
