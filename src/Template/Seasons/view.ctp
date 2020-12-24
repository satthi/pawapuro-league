<nav class="large-1 medium-1 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?></h3>
    <?php if (!empty($nextGame)):?>
    <div class="related">
        <h4>次の試合</h4>
        	<?= $nextGame->date->format('Y/m/d(D)');?><br />
        	<?= $nextGame->home_team->ryaku_name;?> VS <?= $nextGame->visitor_team->ryaku_name;?>
    		<?php if ($nextGame->dh_flag == true) :?>
    			(DHあり)
		    <?php else:?>
    			(DHなし)
		    <?php endif;?>
    <?= $this->Html->link('進行', ['controller' => 'games', 'action' => 'play', $nextGame->id, $nextGame->dh_flag]);?>
    </div>
    <?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $season->id, '#' => 'd' . $nextGame->date->format('Ymd')]) ?>
    <?php else:?>
    <?= $this->Html->link('総評', ['controller' => 'seasons', 'action' => 'summary', $season->id]) ?>
    <?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $season->id]) ?>
    <?php endif;?>
    
    <?= $this->cell('MonthInfo', ['seasonId' => $season->id])->render();?>
    <?= $this->Html->link('経過', ['controller' => 'seasons', 'action' => 'view_detail', $season->id]) ?>
    <?= $this->Html->link('野手成績', ['controller' => 'seasons', 'action' => 'batter_detail', $season->id]) ?>
    <?= $this->Html->link('投手成績', ['controller' => 'seasons', 'action' => 'pitcher_detail', $season->id]) ?>
    <?= $this->Html->link('スタメンデモ', ['controller' => 'games', 'action' => 'stamen_demo', $season->id]) ?>
    <?= $this->Html->link('解析', ['controller' => 'seasons', 'action' => 'analyze', $season->id]) ?>
    <?= $this->Html->link('トレード', ['controller' => 'seasons', 'action' => 'trade', $season->id]) ?>
    <div class="related">
        <h4>順位表</h4>
        <table cellpadding="0" cellspacing="0" style="width:auto;">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Game') ?></th>
                <th scope="col"><?= __('Win') ?></th>
                <th scope="col"><?= __('Lose') ?></th>
                <th scope="col"><?= __('Draw') ?></th>
                <th scope="col">残</th>
                <th scope="col">率</th>
                <th scope="col">差</th>
                <th scope="col">打率</th>
                <th scope="col">防御率</th>
                <th scope="col">HR</th>
                <th scope="col">盗塁</th>
                <th scope="col">得点</th>
                <th scope="col">失点</th>
                <th scope="col">優可</th>
                <th scope="col">優確</th>
                <th scope="col">自優可</th>
                <th scope="col">M点</th>
                <th scope="col">M数</th>
            </tr>
            <?php $before_degree = null;?>
            <?php $magic_display_flag = false;?>
            <?php $row = 0;?>
            <?php $before_rank = 0;?>
            <?php $before_ratio = 2;?>
            <?php foreach ($teams as $team): ?>
            <?php $row++;?>
            <?php if ($team->magicCheck == true && $team->championFix == false) $magic_display_flag  = true;?>
            <?php if ($team->win == 0) {
               $ratio = 0;
            } else {
               $ratio = $team->win / ($team->win + $team->lose);
            }
            
            if ($ratio != $before_ratio) {
                $rank = $row;
                $before_rank = $row;
            } else {
               $rank = $before_rank;
            }
            $before_ratio = $ratio;
            
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
                <td><?= $this->Html->link($team->name, ['controller' => 'players', 'action' => 'index', $team->id]) ?></td>
                <td><?= (int) $team->game ?></td>
                <td><?= (int) $team->win ?></td>
                <td><?= (int) $team->lose ?></td>
                <td><?= (int) $team->draw ?></td>
                <td><?= (int) $team->remain ?></td>
                <td><?php  echo sprintf('%0.3f', round($ratio, 3));?></td>
                <td><?php  echo $degree;?></td>
                <td><?php 
			        if($team->dasu == 0) {
			           $ratio = sprintf('%0.3f', round(0, 3));
			        } else {
			           $ratio = sprintf('%0.3f', round($team->hit / ($team->dasu), 3));
			        }
			        echo preg_replace('/^0/', '', $ratio);
                ?></td>
                <td><?php 
					if (!empty($team->inning)) {
				        echo sprintf('%0.2f', $team->jiseki / ($team->inning / 27));
					} else {
				        echo '-';
				    }
                ?></td>
                <td><?php  echo $team->hr;?></td>
                <td><?php  echo $team->steal;?></td>
                <td><?php  echo $team->point;?></td>
                <td><?php  echo $team->loss;?></td>
                <td><?php  echo $team->championPossible;?></td>
                <td><?php  echo $team->championFix;?></td>
                <td><?php  echo $team->championOneself;?></td>
                <td><?php  echo $team->magicCheck;?></td>
                <td><?php  echo $team->magicNo;?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <?php if ($magic_display_flag):?>
        <h4>優勝ライン</h4>
        <table>
	        <tr>
        	<?php foreach ($teams as $team): ?>
	        	<?php if ($team->championPossible):?>
	        	<?php if ($team->magicCheck) {
	        		$max_chokin = ($team->magicNo + $team->win) - ($team->lose + $team->remain - $team->magicNo);
	        		$magic = $team->magicNo;
	    		};?>
	        	<th><?= $team->ryaku_name;?></th>
	        	<?php endif;?>
        	<?php endforeach;?>
	        </tr>
	        <?php $min_chokin = $max_chokin - $magic * 2 - 2;?>
	        <?php //if ($max_chokin - $min_chokin > 10) $min_chokin = $max_chokin - 10;?>
	        <?php for ($i = $max_chokin;$i >= $min_chokin;$i--):?>
	        <tr>
        	<?php foreach ($teams as $team): ?>
	        	<?php if ($team->championPossible):?>
	        	<td>
	        		<?php
	        			$this_win = ($i - ($team->win - $team->lose - $team->remain)) / 2;
	        			if ($this_win >= 0 && $this_win <= $team->remain && is_int($this_win)) {
	        				echo ($this_win) . '-' . ($team->remain - $this_win) . '(' . sprintf('%0.3f', round(($team->win + $this_win) / ($team->win + $team->lose + $team->remain), 3)) . ')';
		        		}
	        		?>
	        	</td>
	        	<?php endif;?>
        	<?php endforeach;?>
	        </tr>
	        <?php endfor;?>
	    </table>
        <?php endif;?>
        
        <h4>対戦成績</h4>
        <?php
        	$row_teams = $teams;
        	$col_teams = $teams;
        ?>
        <table>
	        <tr>
	        	<th></th>
		        <?php foreach ($col_teams as $col_team):?>
		        	<th><?= $col_team['ryaku_name'];?></th>
		        <?php endforeach;?>
	        </tr>
	        <?php foreach ($row_teams as $row_team):?>
	        <tr>
	        	<th><?= $row_team['name'];?></th>
		        <?php foreach ($col_teams as $col_team):?>
		        	<td>
		        		<?php if ($row_team['id'] == $col_team['id']):?>
		        		-
		        		<?php elseif (empty($vsTeam[$row_team['id']][$col_team['id']])):?>
		        		0-0(0)
		        		<?php else:?>
		        		<?= $this->Html->link($vsTeam[$row_team['id']][$col_team['id']]['win'] . '-' . $vsTeam[$row_team['id']][$col_team['id']]['lose'] . '(' . $vsTeam[$row_team['id']][$col_team['id']]['draw'] . ')', ['action' => 'vs_team_detail', $row_team['id'], $col_team['id']]);?>
		        		<?php endif;?>
		        	</td>
		    
		        <?php endforeach;?>
		    </tr>
	        <?php endforeach;?>
        </table>
        <h4>各種ランキング</h4>
		<div class="clearfix">
			<div style="float:left;">
				<p>打率</p>
				<table style="width:auto;">
					<?php foreach ($avgRanking as $avgRank):?>
						<tr>
							<td><?= $avgRank->name;?>[<?= $avgRank->team->ryaku_name;?>]</td>
							<td><?= $avgRank->display_avg;?></td>
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
							<td><?= $hrRank->name;?>[<?= $hrRank->team->ryaku_name;?>]</td>
							<td><?= $hrRank->hr;?>本</td>
							<td><?= $hrRank->display_avg;?> <?= $hrRank->rbi;?>点</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>打点</p>
				<table style="width:auto;">
					<?php foreach ($rbiRanking as $rbiRank):?>
						<tr>
							<td><?= $rbiRank->name;?>[<?= $rbiRank->team->ryaku_name;?>]</td>
							<td><?= $rbiRank->rbi;?>点</td>
							<td><?= $rbiRank->display_avg;?> <?= $rbiRank->hr;?>本</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>盗塁</p>
				<table style="width:auto;">
					<?php foreach ($stealRanking as $stealRank):?>
						<tr>
							<td><?= $stealRank->name;?>[<?= $stealRank->team->ryaku_name;?>]</td>
							<td><?= $stealRank->steal;?>個</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>ヒット</p>
				<table style="width:auto;">
					<?php foreach ($hitRanking as $hitRank):?>
						<tr>
							<td><?= $hitRank->name;?>[<?= $hitRank->team->ryaku_name;?>]</td>
							<td><?= $hitRank->hit;?>本</td>
							<td><?= $hitRank->display_avg;?></td>
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
							<td><?= $eraRank->name;?>[<?= $eraRank->team->ryaku_name;?>]</td>
							<td><?= $eraRank->display_era;?></td>
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
							<td><?= $winRank->name;?>[<?= $winRank->team->ryaku_name;?>]</td>
							<td><?= $winRank->win;?>勝</td>
							<td><?= $winRank->display_era;?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>セーブ</p>
				<table style="width:auto;">
					<?php foreach ($saveRanking as $saveRank):?>
						<tr>
							<td><?= $saveRank->name;?>[<?= $saveRank->team->ryaku_name;?>]</td>
							<td><?= $saveRank->save;?>セーブ</td>
							<td><?= $saveRank->display_era;?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>ホールド</p>
				<table style="width:auto;">
					<?php foreach ($holdRanking as $holdRank):?>
						<tr>
							<td><?= $holdRank->name;?>[<?= $holdRank->team->ryaku_name;?>]</td>
							<td><?= $holdRank->hold;?>HP</td>
							<td><?= $holdRank->display_era;?></td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
			<div style="float:left;">
				<p>奪三振</p>
				<table style="width:auto;">
					<?php foreach ($getSansinRanking as $getSansinRank):?>
						<tr>
							<td><?= $getSansinRank->name;?>[<?= $getSansinRank->team->ryaku_name;?>]</td>
							<td><?= $getSansinRank->get_sansin;?>個</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
    </div>
</div>
