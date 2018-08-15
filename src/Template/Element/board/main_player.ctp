<div class="main_player team_<?= $team_ryaku;?>" id="<?= $type;?>_<?= $dajun;?>_main">
	<div class="main_player_inner">
		<div class="player_image_wrap">
			<img src="<?= $img_path;?>" class="player_image" />
		</div>
		<div class="player_team_logo_wrap">
			<?= $this->Html->image('logo_big/' . $team_ryaku . '.png', ['class' => 'player_team_logo']);?>
		</div>
		<div class="player_number">
			<?= $number;?>
		</div>
		<div class="player_name">
			<?= $name;?>
		</div>
		<?php if (empty($era)):?>
			<div class="avg_label">
				AVG
			</div>
			<div class="avg">
				<?= $avg;?><br />(<?= $dasu;?>-<?= $hit;?>)
			</div>
			<div class="hr_label">
				H R
			</div>
			<div class="hr">
				<?= $hr;?>
			</div>
			<div class="rbi_label">
				RBI
			</div>
			<div class="rbi">
				<?= $rbi;?>
			</div>
			<div class="sb_label">
				SB
			</div>
			<div class="sb">
				<?= $steal;?>
			</div>
		<?php else:?>
		
			<div class="wl_label">
				W-L
			</div>
			<div class="wl">
				<?= $win_sum;?>-<?= $lose_sum;?>
			</div>
			<div class="hs_label">
				H-S
			</div>
			<div class="hs">
				<?= $hold_sum;?>-<?= $save_sum;?>
			</div>
			<div class="era_label">
				ERA
			</div>
			<div class="era">
				<?= $era;?>
			</div>
			<div class="game_label">
				GAME
			</div>
			<div class="game">
				<?= $game_sum;?>
			</div>
			<div class="inning_label">
				INN
			</div>
			<div class="inning">
				<?= floor($inning_sum / 3);?>
				<?php if ($inning_sum % 3 > 1) :?>
					<?= $inning_sum % 3;?>/3
				<?php endif;?>
			</div>
			<div class="so_label">
				SO
			</div>
			<div class="so">
				<?= $sansin_sum;?>
			</div>
		<?php endif;?>
		
	</div>
</div>
