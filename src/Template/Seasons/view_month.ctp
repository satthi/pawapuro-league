<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
		<li><?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $season->id]);?></li>
	</ul>
</div>
<nav class="large-1 medium-1 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?><?= $month;?>月 月間成績</h3>
    <?= $this->cell('MonthInfo', ['seasonId' => $season->id])->render();?>
    <div class="related">
        <h4>順位表</h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Game') ?></th>
                <th scope="col"><?= __('Win') ?></th>
                <th scope="col"><?= __('Lose') ?></th>
                <th scope="col"><?= __('Draw') ?></th>
                <th scope="col">率</th>
                <th scope="col">差</th>
                <th scope="col">チーム打率</th>
                <th scope="col">防御率</th>
                <th scope="col">HR</th>
                <th scope="col">得点</th>
                <th scope="col">失点</th>
            </tr>
            <?php $before_degree = null;?>
            <?php $row = 0;?>
            <?php $before_rank = 0;?>
            <?php $before_ratio = 2;?>
            <?php foreach ($teams as $team): ?>
            <?php $row++;?>
            <?php 
            
            if ($team->win_ratio != $before_ratio) {
                $rank = $row;
                $before_rank = $row;
            } else {
               $rank = $before_rank;
            }
            $before_ratio = $team->win_ratio;
            
            $this_degree = $team->win - $team->lose;
            if (is_null($before_degree)) {
                $degree = '-';
            } else {
                $degree = sprintf('%0.1f', ($before_degree - $this_degree) / 2);
            }
            $before_degree = $this_degree;
            ?>
            <tr>
                <td><?= $rank ?></td>
                <td><?= $team->team_name ?></td>
                <td><?= (int) $team->game ?></td>
                <td><?= (int) $team->win ?></td>
                <td><?= (int) $team->lose ?></td>
                <td><?= (int) $team->draw ?></td>
                <td><?= sprintf('%0.3f',round($team->win_ratio, 3)) ?></td>
                <td><?php  echo $degree;?></td>
                <td><?= sprintf('%0.3f',round($team->avg, 3)) ?></td>
                <td><?= sprintf('%0.2f',round($team->era, 2)) ?></td>
                <td><?php  echo $team->hr;?></td>
                <td><?php  echo $team->point;?></td>
                <td><?php  echo $team->loss;?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h4>各種ランキング</h4>
		<div class="clearfix">
			<div style="float:left;">
				<p>打率</p>
				<table style="width:auto;">
					<?php foreach ($avgRanking as $avgRank):?>
						<tr>
							<td><?= $avgRank->player_name;?>[<?= $avgRank->team_ryaku_name;?>]</td>
							<td><?= sprintf('%0.3f', round($avgRank->hit / $avgRank->dasu, 3));?></td>
							<td><?= $avgRank->hr;?>本<?= $avgRank->rbi;?>点</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>HR</p>
				<table style="width:auto;">
					<?php foreach ($hrRanking as $hrRank):?>
						<tr>
							<td><?= $hrRank->player_name;?>[<?= $hrRank->team_ryaku_name;?>]</td>
							<td><?= $hrRank->hr;?>本</td>
							<td><?= sprintf('%0.3f', round($hrRank->hit / $hrRank->dasu, 3));?> <?= $hrRank->rbi;?>点</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
		<div class="clearfix">
			<div style="float:left;">
				<p>打点</p>
				<table style="width:auto;">
					<?php foreach ($rbiRanking as $rbiRank):?>
						<tr>
							<td><?= $rbiRank->player_name;?>[<?= $rbiRank->team_ryaku_name;?>]</td>
							<td><?= $rbiRank->rbi;?>点</td>
							<td><?= sprintf('%0.3f', round($rbiRank->hit / $rbiRank->dasu, 3));?> <?= $rbiRank->hr;?>本</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>ヒット</p>
				<table style="width:auto;">
					<?php foreach ($hitRanking as $hitRank):?>
						<tr>
							<td><?= $hitRank->player_name;?>[<?= $hitRank->team_ryaku_name;?>]</td>
							<td><?= $hitRank->hit;?>本</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
		<div class="clearfix">
			<div style="float:left;">
				<p>防御率</p>
				<table style="width:auto;">
					<?php foreach ($eraRanking as $eraRank):?>
						<tr>
							<td><?= $eraRank->player_name;?>[<?= $eraRank->team_ryaku_name;?>]</td>
							<td><?= sprintf('%0.2f', round($eraRank->jiseki / $eraRank->inning * 27, 2));?></td>
							<td><?= $eraRank->game;?>試<?= $eraRank->win;?> - <?= $eraRank->lose;?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>勝ち</p>
				<table style="width:auto;">
					<?php foreach ($winRanking as $winRank):?>
						<tr>
							<td><?= $winRank->player_name;?>[<?= $winRank->team_ryaku_name;?>]</td>
							<td><?= $winRank->win;?>勝</td>
							<td><?= sprintf('%0.2f', round($winRank->jiseki / $winRank->inning * 27, 2));?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
		<div class="clearfix">
			<div style="float:left;">
				<p>セーブ</p>
				<table style="width:auto;">
					<?php foreach ($saveRanking as $saveRank):?>
						<tr>
							<td><?= $saveRank->player_name;?>[<?= $saveRank->team_ryaku_name;?>]</td>
							<td><?= $saveRank->save;?>セーブ</td>
							<td><?= sprintf('%0.2f', round($saveRank->jiseki / $saveRank->inning * 27, 2));?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>ホールド</p>
				<table style="width:auto;">
					<?php foreach ($holdRanking as $holdRank):?>
						<tr>
							<td><?= $holdRank->player_name;?>[<?= $holdRank->team_ryaku_name;?>]</td>
							<td><?= $holdRank->hold;?>HP</td>
							<td><?= sprintf('%0.2f', round($holdRank->jiseki / $holdRank->inning * 27, 2));?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
    </div>
</div>
