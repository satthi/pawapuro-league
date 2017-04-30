<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $gameInfo->season_id]);?></li>
		<li><?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $gameInfo->season_id]);?></li>
	</ul>
</div>
<div class="clearfix">
	</div>
	<div style="float:left;width:1000px;">
	<!-- スコアボード-->
	<table>
		<tr>
			<td></td>
			<td>1</td>
			<td>2</td>
			<td>3</td>
			<td>4</td>
			<td>5</td>
			<td>6</td>
			<td>7</td>
			<td>8</td>
			<td>9</td>
			<td>10</td>
			<td>11</td>
			<td>12</td>
			<td>R</td>
			<td>H</td>
		</tr>
		<tr>
			<td><?= $gameInfo->visitor_team->ryaku_name;?></td>
			<?php $sum_point = 0;?>
			<?php $sum_hit = 0;?>
			<?php for ($i = 1; $i <= 12; $i++):?>
			<?php $row = $i * 2 - 1;?>
			<?php if (!empty($inningInfos[$row])):?>
			<?php $sum_point += $inningInfos[$row]['point'];?>
			<?php $sum_hit += $inningInfos[$row]['hit'];?>
			<td><?= $inningInfos[$row]['point'];?></td>
			<?php else :?>
			<td></td>
			<?php endif;?>
			<?php endfor;?>
			<td><?= $sum_point;?></td>
			<td><?= $sum_hit;?></td>
		</tr>
		<tr>
			<td><?= $gameInfo->home_team->ryaku_name;?></td>
			<?php $sum_point = 0;?>
			<?php $sum_hit = 0;?>
			<?php for ($i = 1; $i <= 12; $i++):?>
			<?php $row = $i * 2;?>
			<?php if (!empty($inningInfos[$row])):?>
			<?php $sum_point += $inningInfos[$row]['point'];?>
			<?php $sum_hit += $inningInfos[$row]['hit'];?>
			<td><?= $inningInfos[$row]['point'];?></td>
			<?php else :?>
			<td></td>
			<?php endif;?>
			<?php endfor;?>
			<td><?= $sum_point;?></td>
			<td><?= $sum_hit;?></td>
		</tr>
	</table>
	<p style="margin:0;">
	<?php if (!empty($beforeVisitorTeamGame->id)):?>
	<?= $this->Html->link('<< 前', ['action' => 'play', $beforeVisitorTeamGame->id]);?>
	<?php endif;?>
	<?= $gameInfo->visitor_team->name;?>
	<?php if (!empty($nextVisitorTeamGame->id)):?>
	<?= $this->Html->link('次 >>', ['action' => 'play', $nextVisitorTeamGame->id]);?>
	<?php endif;?>
	<?php if (!empty($beforeHomeTeamGame->id)):?>
	<?= $this->Html->link('<< 前', ['action' => 'play', $beforeHomeTeamGame->id]);?>
	<?php endif;?>
	<?= $gameInfo->home_team->name;?>
	<?php if (!empty($nextHomeTeamGame->id)):?>
	<?= $this->Html->link('次 >>', ['action' => 'play', $nextHomeTeamGame->id]);?>
	<?php endif;?>
	<?php if (!empty($beforeGame->id)):?>
	<?= $this->Html->link('<< 前', ['action' => 'play', $beforeGame->id]);?>
	<?php endif;?>
	全体
	<?php if (!empty($nextGame->id)):?>
	<?= $this->Html->link('次 >>', ['action' => 'play', $nextGame->id]);?>
	<?php endif;?>
	
	</p>
	<h4>結果</h4>
	<button class="tab" data-type="1">概要</button>
	<button class="tab" data-type="2">野手詳細(先攻)</button>
	<button class="tab" data-type="3">野手詳細(後攻)</button>
	<button class="tab" data-type="4">投手詳細(先攻)</button>
	<button class="tab" data-type="5">投手詳細(後攻)</button>
	<div class="tab_body" data-type="1">
		<table style="width:auto;">
		<?php if (!is_null($gameInfo->win_pitcher)):?>
		<tr>
			<th>
				勝ち投手
			</th>
			<td>
				<?= $gameInfo->win_pitcher->name;?> <?= $gameInfo->pitcherPerform($gameInfo->win_pitcher->id);?> <br />
			</td>
		</tr>
		<?php endif;?>
		<?php if (!is_null($gameInfo->lose_pitcher)):?>
		<tr>
			<th>
				負け投手
			</th>
			<td>
				<?= $gameInfo->lose_pitcher->name;?> <?= $gameInfo->pitcherPerform($gameInfo->lose_pitcher->id);?> <br />
			</td>
		</tr>
		<?php endif;?>
		<?php if (!is_null($gameInfo->save_pitcher)):?>
		<tr>
			<th>
				セーブ投手
			</th>
			<td>
				<?= $gameInfo->save_pitcher->name;?> <?= $gameInfo->pitcherPerform($gameInfo->save_pitcher->id);?> <br />
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<th>
				本塁打
			</th>
			<td>
		<?php foreach ($homeruns as $homerun):?>
			<?= $homerun->batter->name;?> <?= $homerun->homerun_count;?>号(<?= $homerun->pitcher->name;?>) 
		<?php endforeach;?>
			</td>
		</tr>
		</table>
	</div>
	<div class="tab_body" data-type="2">
		<table style="width:auto;">
			<tr>
				<th colspan="3"><?= $gameInfo->visitor_team->name;?></th>
				<th colspan="<?= count($visitorGameResultLists[1]);?>"></th>
				<th>通算</th>
				<th>数</th>
				<th>安</th>
				<th>点</th>
			</tr>
			<?php foreach ($visitorMembers as $dajun => $visitorMemberParts) :?>
			<?php // この打順内で調整;?>
			<?php $visitorMemberLists = [];?>
			<?php foreach ($visitorMemberParts as $visitorMember) :?>
				<?php $visitorMemberLists[$visitorMember->player->id]['info'] = $visitorMember->player;?>
			    <?php
			        if($visitorMember->dasu_count == 0) {
			           $ratio = sprintf('%0.3f', round(0, 3));
			        } else {
			           $ratio = sprintf('%0.3f', round($visitorMember->hit_count / ($visitorMember->dasu_count), 3));
			        }
			        $ratio = preg_replace('/^0/', '', $ratio);
			    ?>
				<?php $visitorMemberLists[$visitorMember->player->id]['ratio'] = $ratio . '(' . $visitorMember->hr_count . ')';?>
				<?php if ($visitorMember->stamen_flag == true) {
					$visitorMemberLists[$visitorMember->player->id]['position'][] = '(' . $positionLists[$visitorMember->position] . ')';
				} else {
					$visitorMemberLists[$visitorMember->player->id]['position'][] = $positionLists[$visitorMember->position];
				}
				?>
				<?php ?>
			<?php endforeach;?>
			<?php $first_flag = true;?>
			<?php foreach ($visitorMemberLists as $player_id => $visitorMemberList) :?>
			<tr>
				<td><?php if ($first_flag == true) {
					echo $dajun;
					$first_flag = false;
				}
				?></td>
				<td><?= implode('', $visitorMemberList['position']);?></td>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $visitorMemberList['info']]);?>
                </td>
				<?php
					$daseki = 0;
					$dasu = 0;
					$hit = 0;
					$rbi = 0;
				?>
				<?php for ($i = 0;$i < count($visitorGameResultLists[1]);$i++):?>
					<?php if (
						!empty($visitorGameResultLists[$dajun][$i]) &&
						$visitorGameResultLists[$dajun][$i]->target_player_id == $player_id
					):?>
						<?php 
							$targetInfo = $visitorGameResultLists[$dajun][$i];
							$daseki++;
							$dasu += $targetInfo->result->dasu_flag;
							$hit += $targetInfo->result->hit_flag;
						?>
						<td class="result_td position result_<?= $targetInfo->result->color_type;?>" >
							<?= $targetInfo->result->name;?>
							<?php if ($targetInfo->point > 0):?>
								[<?= $targetInfo->point;?>]
								<?php $rbi += $visitorGameResultLists[$dajun][$i]->point;?>
							<?php endif;?>
						</td>
					<?php else:?>
						<td></td>
					<?php endif;?>
				<?php endfor;?>
				<td>
					<?= $visitorMemberList['ratio'];?>
				</td>
				<td>
					<?= $dasu;?>
				</td>
				<td>
					<?= $hit;?>
				</td>
				<td>
					<?= $rbi;?>
				</td>
			</tr>
			<?php endforeach;?>
			<?php endforeach;?>
		</table>
	</div>
	<div class="tab_body" data-type="3">
		<table style="width:auto;">
			<tr>
				<th colspan="3"><?= $gameInfo->home_team->name;?></th>
				<th colspan="<?= count($homeGameResultLists[1]);?>"></th>
				<th>通算</th>
				<th>数</th>
				<th>安</th>
				<th>点</th>
			</tr>
			<?php foreach ($homeMembers as $dajun => $homeMemberParts) :?>
			<?php // この打順内で調整;?>
			<?php $homeMemberLists = [];?>
			<?php foreach ($homeMemberParts as $homeMember) :?>
				<?php $homeMemberLists[$homeMember->player->id]['info'] = $homeMember->player;?>
			    <?php
			        if($homeMember->dasu_count == 0) {
			           $ratio = sprintf('%0.3f', round(0, 3));
			        } else {
			           $ratio = sprintf('%0.3f', round($homeMember->hit_count / ($homeMember->dasu_count), 3));
			        }
			        $ratio = preg_replace('/^0/', '', $ratio);
			    ?>
				<?php $homeMemberLists[$homeMember->player->id]['ratio'] = $ratio . '(' . $homeMember->hr_count . ')';?>
				<?php if ($homeMember->stamen_flag == true) {
					$homeMemberLists[$homeMember->player->id]['position'][] = '(' . $positionLists[$homeMember->position] . ')';
				} else {
					$homeMemberLists[$homeMember->player->id]['position'][] = $positionLists[$homeMember->position];
				}
				?>
				<?php ?>
			<?php endforeach;?>
			<?php $first_flag = true;?>
			<?php foreach ($homeMemberLists as $player_id => $homeMemberList) :?>
			<tr>
				<td><?php if ($first_flag == true) {
					echo $dajun;
					$first_flag = false;
				}
				?></td>
				<td><?= implode('', $homeMemberList['position']);?></td>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $homeMemberList['info']]);?>
                </td>
				<?php
					$daseki = 0;
					$dasu = 0;
					$hit = 0;
					$rbi = 0;
				?>
				<?php for ($i = 0;$i < count($homeGameResultLists[1]);$i++):?>
					<?php if (
						!empty($homeGameResultLists[$dajun][$i]) &&
						$homeGameResultLists[$dajun][$i]->target_player_id == $player_id
					):?>
						<?php 
							$targetInfo = $homeGameResultLists[$dajun][$i];
							$daseki++;
							$dasu += $targetInfo->result->dasu_flag;
							$hit += $targetInfo->result->hit_flag;
						?>
						<td class="result_td position result_<?= $targetInfo->result->color_type;?>" >
							<?= $targetInfo->result->name;?>
							<?php if ($targetInfo->point > 0):?>
								[<?= $targetInfo->point;?>]
								<?php $rbi += $homeGameResultLists[$dajun][$i]->point;?>
							<?php endif;?>
						</td>
					<?php else:?>
						<td></td>
					<?php endif;?>
				<?php endfor;?>
				<td>
					<?= $homeMemberList['ratio'];?>
				</td>
				<td>
					<?= $dasu;?>
				</td>
				<td>
					<?= $hit;?>
				</td>
				<td>
					<?= $rbi;?>
				</td>
			</tr>
			<?php endforeach;?>
			<?php endforeach;?>
		</table>
	</div>
	<div class="tab_body" data-type="4">
		<table>
			<tr>
				<th></th>
				<th>名前</th>
				<th>投球回</th>
				<th>被安打</th>
				<th>四死球</th>
				<th>奪三振</th>
				<th>被本塁打</th>
				<th>自責点</th>
				<th>防御率</th>
			</tr>
			<?php foreach ($visitorPitcherDatas as $visitorPitcherData):?>
			<tr>
				<td>
					<?php if ($gamePitcherResultLists[$visitorPitcherData->pitcher_id]->win):?>
						○
					<?php elseif ($gamePitcherResultLists[$visitorPitcherData->pitcher_id]->lose):?>
						●
					<?php elseif ($gamePitcherResultLists[$visitorPitcherData->pitcher_id]->save):?>
						S
					<?php elseif ($gamePitcherResultLists[$visitorPitcherData->pitcher_id]->hold):?>
						H
					<?php endif;?>
				</td>
				<td>
						<?= $visitorPitcherData->pitcher->name;?>
				</td>
				<td>
						<?= floor($visitorPitcherData->out_num / 3);?>
			    		<?php if ($visitorPitcherData->out_num % 3 != 0) :?>
			    			<?= $visitorPitcherData->out_num % 3 . '/3'?>
			    		<?php endif;?>
				</td>
				<td><?= (int) $visitorPitcherData->hit_count;?></td>
				<td><?= (int) $visitorPitcherData->yontama_count;?></td>
				<td><?= (int) $visitorPitcherData->sansin_count;?></td>
				<td><?= (int) $visitorPitcherData->hr_count;?></td>
				<td><?= $gamePitcherResultLists[$visitorPitcherData->pitcher_id]->jiseki;?></td>
				<td>
					<?php
					if (!empty($visitorPitcherData->total_inning)) {
				        echo sprintf('%0.2f', $visitorPitcherData->total_jiseki / ($visitorPitcherData->total_inning / 27));
					} else {
				        echo '-';
				    }
				    ?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	<div class="tab_body" data-type="5">
		<table>
			<tr>
				<th></th>
				<th>名前</th>
				<th>投球回</th>
				<th>被安打</th>
				<th>四死球</th>
				<th>奪三振</th>
				<th>被本塁打</th>
				<th>自責点</th>
				<th>防御率</th>
			</tr>
			<?php foreach ($homePitcherDatas as $homePitcherData):?>
			<tr>
				<td>
					<?php if ($gamePitcherResultLists[$homePitcherData->pitcher_id]->win):?>
						○
					<?php elseif ($gamePitcherResultLists[$homePitcherData->pitcher_id]->lose):?>
						●
					<?php elseif ($gamePitcherResultLists[$homePitcherData->pitcher_id]->save):?>
						S
					<?php elseif ($gamePitcherResultLists[$homePitcherData->pitcher_id]->hold):?>
						H
					<?php endif;?>
				</td>
				<td>
						<?= $homePitcherData->pitcher->name;?>
				</td>
				<td>
						<?= floor($homePitcherData->out_num / 3);?>
			    		<?php if ($homePitcherData->out_num % 3 != 0) :?>
			    			<?= $homePitcherData->out_num % 3 . '/3'?>
			    		<?php endif;?>
				</td>
				<td><?= (int) $homePitcherData->hit_count;?></td>
				<td><?= (int) $homePitcherData->yontama_count;?></td>
				<td><?= (int) $homePitcherData->sansin_count;?></td>
				<td><?= (int) $homePitcherData->hr_count;?></td>
				<td><?= $gamePitcherResultLists[$homePitcherData->pitcher_id]->jiseki;?></td>
				<td>
					<?php
					if (!empty($homePitcherData->total_inning)) {
				        echo sprintf('%0.2f', $homePitcherData->total_jiseki / ($homePitcherData->total_inning / 27));
					} else {
				        echo '-';
				    }
				    ?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	</div>
</div>


<style type="text/css">
<!--
#result_selected {
	border: 2px solid #000;
}
.position{
	background-size:100% 100%;
}
.position_p{
	background-image: url('<?= $this->Url->build('/img/p_color/p.png');?>');
}
.position_c{
	background-image: url('<?= $this->Url->build('/img/p_color/c.png');?>');
}
.position_i{
	background-image: url('<?= $this->Url->build('/img/p_color/i.png');?>');
}
.position_o{
	background-image: url('<?= $this->Url->build('/img/p_color/o.png');?>');
}
.position_g{
	background-image: url('<?= $this->Url->build('/img/p_color/g.png');?>');
}
.result_1{
	background-image: url('<?= $this->Url->build('/img/p_color/c.png');?>');
}
.result_2{
	background-image: url('<?= $this->Url->build('/img/p_color/p.png');?>');
}
.result_3{
	background-image: url('<?= $this->Url->build('/img/p_color/i.png');?>');
}
.result_4{
	background-image: url('<?= $this->Url->build('/img/p_color/g.png');?>');
}

#batter{
	background-color: #336699;
	color: #FFFFFF;
}
label {
	display: inline;
	margin: 5px;
}
-->
</style>

<script type="text/javascript">
$(function(){
	$('.tab_body[data-type!=1]').hide();
	$('.tab').click(function(){
		$('.tab_body').hide();
		$('.tab_body[data-type=' + $(this).data('type') + ']').show();
	});
});
</script>
