<div class="players index columns content">
	<?php foreach ($monthSets as $monthSet):?>
    <?= $this->Html->link($monthSet->month . '月月間', ['controller' => 'teams', 'action' => 'month', $teamId,$monthSet->year,$monthSet->month]) ?>
    <?php endforeach;?>
    <h3><?= $team->name; ?></h3>
    <h4><?= $year ;?>年<?= $month;?>月</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
                <th>日</th>
            </tr>
        </thead>
        <tbody>
            <?php $day = 0;?>
            <?php $endFlag = false;?>
            <?php while(true):?>
             <tr>
                 <?php for ($i = 1;$i <= 7;$i++):?>
                 <td>
                     <?php if ($day == 0){
                         if (date('N', strtotime($year. '/' . $month . '/1')) == $i) {
                             $day = 1;
                         }
                     } else {
                         $day++;
                     }
                     if (date('t', strtotime($year .'/' . $month . '/1')) < $day) {
                         $endFlag = true;
                     }
                     ?>
                     <?php if ($day > 0 && $endFlag ==false) :?>
                         <?= $day;?>
                         <?php if (!empty($games[date('Ymd', strtotime($year .'/' . $month . '/' . $day))])):?>
                             <?php $game = $games[date('Ymd', strtotime($year .'/' . $month . '/' . $day))];?>
                         	<?php
							 $now = '';
							if ($game->status == 0) {
							 $now = '試合前';
							} elseif ($game->status == 99) {
							 $now = '終了';
							} else {
								$now = ceil($game->status / 2);
								if ($game->status % 2 == 1) {
									$now .= '表';
								} else {
									$now .= '裏';
								}
							}
							?>
							<table>
								<tr>
									<td colspan="2">
										vs 
										<?php if ($teamId == $game->home_team->id):?>
											<?= $game->visitor_team->ryaku_name;?>
										<?php else:?>
											<?= $game->home_team->ryaku_name;?>
										<?php endif;?>
										<?php if ($game->status == 99):?>
											<span style="font-size:2em;">
											<?php if (
												(
													$teamId == $game->home_team->id &&
													$game->home_point > $game->visitor_point 
												) ||
												(
													$teamId == $game->visitor_team->id &&
													$game->home_point < $game->visitor_point 
												)
												
											):?>
											○
											<?php elseif (
												(
													$teamId == $game->home_team->id &&
													$game->home_point < $game->visitor_point 
												) ||
												(
													$teamId == $game->visitor_team->id &&
													$game->home_point > $game->visitor_point 
												)
												
											):?>
											●
											<?php else:?>
											△
											<?php endif;?>
											</span>
										<?php endif;?>
									</td>
									<td><?= $this->Html->link($now, ['controller' => 'games', 'action' => 'play', $game->id]);?></td>
								</tr>
								<tr>
									<td>
										<?php if ($teamId == $game->home_team->id):?>
											<?= $game->home_point;?>
										<?php else:?>
											<?= $game->visitor_point;?>
										<?php endif;?>
									</td>
									<td>-</td>
									<td>
										<?php if ($teamId == $game->home_team->id):?>
											<?= $game->visitor_point;?>
										<?php else:?>
											<?= $game->home_point;?>
										<?php endif;?>
									</td>
								</tr>
								<?php if ($game->status == 99):?>
								<tr>
									<td colspan="3">
										<?php if (!is_null($game->win_pitcher)):?>
											勝:<?= $game->win_pitcher->name_short;?><br />
										<?php endif;?>
										<?php if (!is_null($game->lose_pitcher)):?>
											負:<?= $game->lose_pitcher->name_short;?><br />
										<?php endif;?>
										<?php if (!is_null($game->save_pitcher)):?>
											Ｓ:<?= $game->save_pitcher->name_short;?><br />
										<?php endif;?>
									</td>
								</tr>
								<?php endif;?>
							</table>
                         
                         
                         <?php endif;?>
                     <?php endif;?>
                 </td>
                 <?php endfor;?>
             </tr>
             <?php if ($endFlag == true) break;?>
            <?php endwhile;?>
        </tbody>
    </table>
    

</div>
