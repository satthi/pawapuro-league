<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $gameInfo->season_id]);?></li>
		<li><?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $gameInfo->season_id]);?></li>
	</ul>
</div>
<h3>
	<?= $checkTeam->name;?>(<?= $gameInfo->date->format('Y/m/d(D)');?>)
	<?php if ($dh_flag):?>
		DHあり [ <?= $this->Html->link('DHなしにする', ['action' => 'play', $gameInfo->id, false]);?> ]
	<?php else:?>
		DHなし [ <?= $this->Html->link('DHありにする', ['action' => 'play', $gameInfo->id, true]);?> ]
	<?php endif;?>
</h3>
<div class="clearfix">
	<div style="float:left;">
		<h4>
		スタメン
		</h4>
		<table style="width:auto;" id="stamen_table">
			<?php foreach ($stamen as $stamenParts):?>
			<tr>
				<td data-dajun="<?= $stamenParts['dajun'];?>" class="dajun">
					<?php if ($stamenParts['dajun'] != 10):?>
						<?= $stamenParts['dajun'];?>
					<?php endif;?>
				</td>
				<td data-position="<?= $stamenParts['position'];?>" class="position color_<?= $positionColors[$stamenParts['position']];?>"><?= $positionLists[$stamenParts['position']];?></td>
				<td data-player_id="<?= $stamenParts['player']->id;?>" class="player_box_td member">
					<?= $this->element('player_block', ['player' => $stamenParts['player'], 'nolink' => true]);?>
				</td>
				<td class="member_info">
					<?= $stamenParts['player']->player_info;?><br />
					<?= $stamenParts['player']->getRecentBatterPlayerInfo();?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	<div style="float:left;margin-left:30px;">
		<h4>控え(投手)</h4>
		<table style="width:auto;">
			<?php foreach ($hikae as $hiakeParts):?>
			<tr>
				<?php if (empty($hiakeParts['player']->type_p)) continue;?>
				<td data-player_id="<?= $hiakeParts['player']->id;?>" class="player_box_td member">
					<?= $this->element('player_block', ['player' => $hiakeParts['player'], 'nolink' => true]);?>
				</td>
				<td class="member_info"><?= $hiakeParts['player']->player_info;?><br />
					<?= $hiakeParts['player']->getRecentBatterPlayerInfo();?></td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	
	<div style="float:left;margin-left:30px;">
		<h4>控え(野手)</h4>
		<table style="width:auto;">
			<?php foreach ($hikae as $hiakeParts):?>
			<tr>
				<?php if (!empty($hiakeParts['player']->type_p)) continue;?>
				<td data-player_id="<?= $hiakeParts['player']->id;?>" class="player_box_td member">
					<?= $this->element('player_block', ['player' => $hiakeParts['player'], 'nolink' => true]);?>
				</td>
				<td class="member_info"><?= $hiakeParts['player']->player_info;?><br />
					<?= $hiakeParts['player']->getRecentBatterPlayerInfo();?></td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>

	<div style="float:left;margin-left:30px;">
		<h4>けが人</h4>
		<table style="width:auto;">
			<?php foreach ($accidents as $accident):?>
			<tr>
				<td data-player_id="<?= $accident['player']->id;?>" class="player_box_td member">
					<?= $this->element('player_block', ['player' => $accident['player'], 'nolink' => true]);?>
				</td>
				<td class="member_info"><?= $accident->end_date->format('Y/m/d');?>まで</td>
			</tr>
			<?php endforeach;?>
		</table>
		<h4>けが人復帰</h4>
		<table style="width:auto;">
			<?php foreach ($accidentEnds as $accident):?>
			<tr>
				<td data-player_id="<?= $accident['player']->id;?>" class="player_box_td member">
					<?= $this->element('player_block', ['player' => $accident['player'], 'nolink' => true]);?>
				</td>
				<td class="member_info"><?= $accident->end_date->format('Y/m/d');?>まで</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
		
	<div style="float:left;margin-left:30px;">
		<p>最近の先発ピッチャー</p>
		<table style="width:auto;">
			<tr>
			<?php $nowDate = null;?>
			<?php $first = true;?>
			<?php foreach ($pitcherDatas as $pitcherData):?>
				<?php if ($nowDate != $pitcherData->game->date):?>
				<?php if ($first == true):?>
					<?php $first = false;?>
				<?php else:?>
					</tr><tr>
				<?php endif;?>
				<td><?= $pitcherData->game->date->format('Y/m/d(D)');?></td>
				<?php $nowDate = $pitcherData->game->date;?>
				<?php endif;?>
				<td class="player_box_td">
					<?= $this->element('player_block', ['player' => $pitcherData->pitcher, 'nolink' => true]);?>
					
				</td>
				<td>(<?= floor($pitcherData->out_num / 3);?>
					<?php if ($pitcherData->out_num % 3 != 0) :?>
		    			<?= $pitcherData->out_num % 3 . '/3'?>
		    		<?php endif;?>
					)
				</td>
		
			<?php endforeach;?>
			</tr>
		</table>
	</div>
</div>
<div>
	<button type="button" class="submit" id="submit">登録</button>
	<?php if ($checkTeam->id == $gameInfo->home_team_id):?>
		<button type="button" class="submit" id="stamen">スタメン発表</button>
	<?php endif;?>
</div>

<script type="text/javascript">
	$(function(){
		$(document).on('click', '.position', function() {
			if ($('#position_selected').size() == 0) {
				$(this).attr('id', 'position_selected');
			} else {
				if ($(this).attr('id') == 'position_selected') {
					$(this).removeAttr('id');
				} else {
					var selected_dom = $('#position_selected');
					var self = $(this);
					selected_dom.before(self.clone());
					self.before(selected_dom.clone());
					self.remove();
					selected_dom.remove();
					$('#position_selected').removeAttr('id');
				}
			}
		});
		$(document).on('click', '.member', function() {
			if ($('#member_selected').size() == 0) {
				$(this).attr('id', 'member_selected');
			} else {
				if ($(this).attr('id') == 'member_selected') {
					$(this).removeAttr('id');
				} else {
					var selected_dom = $('#member_selected');
					var self = $(this);
					// 成績も一緒に移動
					var selected_info_dom = selected_dom.next('.member_info');
					var this_info_dom = self.next('.member_info');
					// スタメン内の打順の入れ替え時にはポジションを合わせて移動
					if (
						selected_dom.parents('table').attr('id') == 'stamen_table' &&
						self.parents('table').attr('id') == 'stamen_table'
					) {
						selected_dom.parents('tr').find('td.position').click();
						self.parents('tr').find('td.position').click();
					}
					selected_dom.before(self.clone());
					self.before(selected_dom.clone());
					self.remove();
					selected_dom.remove();
					
					// 成績の移動
					selected_info_dom.before(this_info_dom.clone());
					this_info_dom.before(selected_info_dom.clone());
					this_info_dom.remove();
					selected_info_dom.remove();

					$('#member_selected').removeAttr('id');

				}
			}
		});
		
		$('.submit').click(function(){
			//データの登録
			var data = {};
			$('#stamen_table tr').each(function(){
				var game_id = <?= $gameInfo->id;?>;
				var team_id = <?= $checkTeam->id;?>;
				var dajun = $(this).find('td.dajun').data('dajun');
				var position = $(this).find('td.position').data('position');
				var player_id = $(this).find('td.member').data('player_id');
				var stamen_flag = true;
				data[dajun] = {
					'game_id' : game_id,
					'team_id' : team_id,
					'dajun' : dajun,
					'position' : position,
					'player_id' : player_id,
					'stamen_flag' : stamen_flag,
					'inning' : 0,
				}
			});
			var submit_id = $(this).prop('id');
			$.ajax({
				data: data,
				type: "POST",
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'member_change']);?>',
				success: function() {
					if (submit_id == 'stamen') {
						location.href='<?= $this->Url->build(['controller' => 'games', 'action' => 'stamenDemoSet', $gameInfo->season_id, $gameInfo->visitor_team_id, $gameInfo->home_team_id, $gameInfo->id]);?>';
					} else {
						location.reload();
					}
				}
			});
			
		});
	});
</script>
