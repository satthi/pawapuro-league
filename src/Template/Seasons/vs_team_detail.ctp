<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
	</ul>
</div>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($rowTeam->name) ?>(vs <?= h($colTeam->name);?>)</h3>
    <?= $this->Html->link('逆', [$colTeam->id, $rowTeam->id]);?>
    <?php foreach ($otherTeams as $otherTeam):?>
        <?= $this->Html->link('vs ' . $otherTeam->name, [$rowTeam->id, $otherTeam->id]);?>
    <?php endforeach;?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>対戦成績</th>
            <th>得点</th>
            <th>失点</th>
        </tr>
        <tr>
            <td><?=$vsTeamDetail['shukei']['game'];?>試合<?=$vsTeamDetail['shukei']['win'];?>勝<?=$vsTeamDetail['shukei']['lose'];?>敗<?=$vsTeamDetail['shukei']['draw'];?>分け</td>
            <td><?=$vsTeamDetail['shukei']['tokuten'];?></td>
            <td><?=$vsTeamDetail['shukei']['shitten'];?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>打率</th>
            <th>HR</th>
            <th>三振</th>
            <th>盗塁</th>
        </tr>
        <tr>
            <td><?= round($vsTeamDetail['shukei2']['hit_count'] / $vsTeamDetail['shukei2']['dasu_count'], 3);?></td>
            <td><?=$vsTeamDetail['shukei2']['hr_count'];?></td>
            <td><?=$vsTeamDetail['shukei2']['sansin_count'];?></td>
            <td><?=$vsTeamDetail['shukei2']['steal_count'];?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>防御率</th>
            <th>被打率</th>
            <th>被HR</th>
            <th>奪三振</th>
            <th>被盗塁</th>
        </tr>
        <tr>
            <td><?= round($vsTeamDetail['shukei4']['jiseki_count'] / $vsTeamDetail['shukei3']['inning_count'] * 27, 2);?></td>
            <td><?= round($vsTeamDetail['shukei3']['hit_count'] / $vsTeamDetail['shukei3']['dasu_count'], 3);?></td>
            <td><?=$vsTeamDetail['shukei3']['hr_count'];?></td>
            <td><?=$vsTeamDetail['shukei3']['sansin_count'];?></td>
            <td><?=$vsTeamDetail['shukei3']['steal_count'];?></td>
        </tr>
    </table;?>


    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>日付</th>
                <th>H/V</th>
                <th>結果</th>
                <th>勝ち投手</th>
                <th>負け投手</th>
                <th>セーブ投手</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vsTeamDetail['gameResults'] as $gameResult) :?>
            <tr>
                <td><?= $this->Html->link($gameResult->date->format('Y-m-d'), ['controller' => 'games', 'action' => 'play', $gameResult->id]);?></td>
                <td><?= $gameResult->home_team_id === $rowTeam->id ? 'ホーム' : 'ビジター';?></td>
                <td>
			<?php if ($gameResult->status == 99):?>
				<span style="font-size:2em;">
				<?php if (
					(
						$rowTeam->id == $gameResult->home_team_id &&
						$gameResult->home_point > $gameResult->visitor_point 
					) ||
					(
						$rowTeam->id == $gameResult->visitor_team_id &&
						$gameResult->home_point < $gameResult->visitor_point 
					)
					
				):?>
				○
				<?php elseif (
					(
						$rowTeam->id == $gameResult->home_team_id &&
						$gameResult->home_point < $gameResult->visitor_point 
					) ||
					(
						$rowTeam->id == $gameResult->visitor_team_id &&
						$gameResult->home_point > $gameResult->visitor_point 
					)
					
				):?>
				●
				<?php else:?>
				△
				<?php endif;?>
				</span>
				<?php if ($rowTeam->id == $gameResult->home_team_id):?>
					<?= $gameResult->home_point;?>
				<?php else:?>
					<?= $gameResult->visitor_point;?>
				<?php endif;?>
				-
				<?php if ($rowTeam->id == $gameResult->home_team_id):?>
					<?= $gameResult->visitor_point;?>
				<?php else:?>
					<?= $gameResult->home_point;?>
				<?php endif;?>
			<?php endif;?>
                </td>
		<td>
			<?php if (!is_null($gameResult->win_pitcher)):?>
				<?= $gameResult->win_pitcher->name_short;?><br />
			<?php endif;?>
                </td>
		<td>
			<?php if (!is_null($gameResult->lose_pitcher)):?>
				<?= $gameResult->lose_pitcher->name_short;?><br />
			<?php endif;?>
                </td>
		<td>
			<?php if (!is_null($gameResult->save_pitcher)):?>
				<?= $gameResult->save_pitcher->name_short;?><br />
			<?php endif;?>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    
</div>
