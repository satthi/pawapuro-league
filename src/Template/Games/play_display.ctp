<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $gameInfo->season_id]);?></li>
		<li><?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $gameInfo->season_id]);?></li>
	</ul>
</div>
<div class="clearfix">
	<div style="float:left;width:280px;border:1px solid #333;">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $gameInfo->home_team,
			'members' => $homeMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => $batterId,
		]);?>
		<?php if ($attack_team_id == $gameInfo->home_team->id):?>
		<div>
			<?= $this->cell('BatterInfo', ['id' => $batterId, 'game_id' => $gameInfo->id, 'dajun' => $batter_dajun])->render();?>
		</div>
		<?php elseif ($attack_team_id == $gameInfo->visitor_team->id):?>
		<div>
			<?= $this->cell('PitcherInfo', ['id' => $pitcherId, 'game_id' => $gameInfo->id])->render();?>
		</div>
		<?php else:?>
			<div style="padding: 10px;">
				<div>
				<button type="button" style="padding:2px;font-size:13px;" class="colorbox_button" href="<?= $this->Url->build(['action' => 'positionChange', 'game_id' => $gameInfo->id, 'team_id' => $gameInfo->home_team->id]);?>">守備</button>
				</div>
			</div>
		<?php endif;?>
	</div>
	<div style="float:left;width:700px;">
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
			<td<?php if ($gameInfo->status == $row) echo ' id="current_inning"';?>>
			<?php if (!empty($inningInfos[$row])):?>
			<?php $sum_point += $inningInfos[$row]['point'];?>
			<?php $sum_hit += $inningInfos[$row]['hit'];?>
			<?= $inningInfos[$row]['point'];?>
			<?php endif;?>
			</td>
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
			<td<?php if ($gameInfo->status == $row) echo ' id="current_inning"';?>>
			<?php if (!empty($inningInfos[$row])):?>
			<?php $sum_point += $inningInfos[$row]['point'];?>
			<?php $sum_hit += $inningInfos[$row]['hit'];?>
			<?= $inningInfos[$row]['point'];?>
			<?php endif ;?>
			</td>
			<?php endfor;?>
			<td><?= $sum_point;?></td>
			<td><?= $sum_hit;?></td>
		</tr>
	</table>
	<h4>結果</h4>
	<?php if ($gameInfo->status != 0 && $gameInfo->out_num != 3):?>
	<div class="clearfix">
		<div style="float:left;">
			<div id="out_count">
			<?php
				$out_options = [];
				for ($i = 0; $i <= 3 - $gameInfo->out_num;$i++) {
					$out_options[$i] = $i;
				}
			?>
			<?= $this->Form->input('out', ['type' => 'radio', 'options' => $out_options, 'class' => 'out_count']);?>
			</div>
		</div>
		<div style="float:left;margin-left:40px;">
			<div id="point_count">
			<?= $this->Form->input('point', ['type' => 'radio', 'options' => [0 => 0,1 => 1,2 => 2,3 => 3,4 => 4,], 'value' => 0]);?>
			</div>
		</div>
		<div style="float:right;margin-right:30px;">
			O 
			<span style="color:red;">
			<?php if ($gameInfo->out_num < 1) :?>
				○
			<?php else :?>
				●
			<?php endif;?>
			<?php if ($gameInfo->out_num < 2) :?>
				○
			<?php else :?>
				●
			<?php endif;?>
			</span>
		</div>
	</div>
	<table id="result_table">
		<?php $row = 0;?>
		<?php while(true):?>
		<?php $whlileEnd = true;?>
		<tr>
			<?php for ($p = 0;$p <= 9; $p++):?>
				<?php if (!empty($resultSet[$p][$row])):?>
				<?php $target = $resultSet[$p][$row];?>
					<td class="result_td position result_<?= $target->color_type;?>" data-out="<?= $target->out;?>" data-point="<?= (int) $target->point_flag;?>" data-result="<?= $target->id;?>">
						<?= $target->name;?>
					</td>
					<?php $whlileEnd = false;?>
				<?php else:?>
					<td></td>
				<?php endif;?>
			<?php endfor;?>
		</tr>
		<?php if ($whlileEnd == true) :?>
			<?php break;?>
		<?php endif;?>
		<?php $row++;?>
		<?php endwhile;?>
	</table>
	<div>
		<button type="button" id="submit">登録</button>
	</div>
	<?php else:?>
	<div>
		<button type="button" id="next_inning">次のイニングへ</button>
	</div>
	<?php endif;?>
	<div style="text-align:right">
		<button type="button" id="back" style="padding:2px;font-size:13px;">戻る</button>
		<button type="button" id="point_only" style="padding:2px;font-size:13px;">点数のみ</button>
	</div>

	</div>
	<div style="float:left;width:280px;border:1px solid #333;">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $gameInfo->visitor_team,
			'members' => $visitorMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => $batterId,
		]);?>
		
		<?= $this->element('scoreboad_nowplayer_info', [
			'attack_team_id' => $attack_team_id,
			'teamInfo' => $gameInfo->visitor_team,
			'vsTeamInfo' => $gameInfo->home_team,
			'batterId' => $batterId,
			'gameId' => $gameInfo->id,
			'batter_dajun' => $batter_dajun,
			'pitcherId' => $pitcherId,
		]);?>
	</div>
</div>
<div id="fukidashi_display" style="position:absolute;"></div>

<script type="text/javascript">
	$(function(){
		var synthesSet = [];
		// 交代周り
		$(".colorbox").colorbox({width: '700px',height: '700px',iframe:true});
		$(".colorbox_button").colorbox({width: '1200px',height: '800px',iframe:true});
		
		$(document).on('click', '#result_table td.result_td', function() {
			$('#result_selected').removeAttr('id');
			$(this).attr('id', 'result_selected');
			$('#out_count input:radio[value="' + $(this).data('out') + '"]').prop('checked', true);
		});
		
		$('#out_count input:radio' ).click(function(){
			alert('out change click');
		});
		
		$(document).on('keyup', function(e){
			if (e.keyCode == 13) {
				$('#submit').click();
			}
		});

		$('#next_inning').click(function(){
			var data = {};
			var game_id = <?= $gameInfo->id;?>;
			var inning = <?= $gameInfo->status;?>;
			data = {
				'game_id' : game_id,
				'inning' : <?= $gameInfo->status;?>
			}
			$.ajax({
				data: data,
				type: "POST",
				async:false,
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'next_inning']);?>',
				success: function() {
					location.href='<?= $this->Url->build();?>';
				}
			});
		});
		
		<?php if ($gameInfo->status != 0 && $gameInfo->out_num != 3):?>
		$('#submit').click(function(){
			//データの登録
			var data = {};
			var game_id = <?= $gameInfo->id;?>;
			var dajun = <?= $batter_dajun;?>;
			var player_id = <?= $batterId;?>;
			var pitcher_id = <?= $pitcherId;?>;
			var team_id = <?= $attack_team_id;?>;
			var inning = <?= $gameInfo->status;?>;
			var out_num = $('#out_count input:radio:checked').val();
			var result = $('#result_selected').data('result');
			var point_flag = $('#result_selected').data('point');
			var point = $('#point_count input:radio:checked').val();
			if (out_num === undefined ) {
				alert('out 指定なし');
				return false;
			}
			if (point === undefined ) {
				alert('point 指定なし');
				return false;
			}
			if (point_flag == 1 && point == 0){
				alert('HR/犠飛 ポイントなし');
				return false;
			}
			data = {
				'game_id' : game_id,
				'team_id' : team_id,
				'pitcher_id' : pitcher_id,
				'dajun' : dajun,
				'player_id' : player_id,
				'inning' : <?= $gameInfo->status;?>,
				'result' : result,
				'out_num' : out_num,
				'point' : point,
			}
			$.ajax({
				data: data,
				type: "POST",
				async:false,
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'result_save']);?>',
				success: function() {
					location.href='<?= $this->Url->build();?>';
				}
			});
			
		});
		
		// 併殺時など打点がつかない点数を入れる場合
		$('#point_only').click(function(){
			//データの登録
			var data = {};
			var game_id = <?= $gameInfo->id;?>;
			var team_id = <?= $attack_team_id;?>;
			var inning = <?= $gameInfo->status;?>;
			var point = $('#point_count input:radio:checked').val();
			if (point === undefined ) {
				alert('point 指定なし');
				return false;
			}
			if (point == 0){
				alert('ポイントなし');
				return false;
			}
			data = {
				'game_id' : game_id,
				'team_id' : team_id,
				'inning' : <?= $gameInfo->status;?>,
				'point' : point,
			}
			$.ajax({
				data: data,
				type: "POST",
				async:false,
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'point_only_save']);?>',
				success: function() {
					location.href='<?= $this->Url->build();?>';
				}
			});
			
		});
		<?php endif;?>
		$('#back').click(function(){
			//データの登録
			var data = {};
			var game_id = <?= $gameInfo->id;?>;
			data = {
				'game_id' : game_id
			}
			$.ajax({
				data: data,
				type: "POST",
				async:false,
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'back']);?>',
				success: function() {
					$.each(synthesSet, function(i,v) {
						//v.cancel();
					});
					location.href='<?= $this->Url->build();?>';
				}
			});
			
		});
		
		// 音声回り(ぁ)
		var positionOnsei = {
			1: 'pitcher',
			2: 'catcher',
			3: 'first',
			4: 'second',
			5: 'third',
			6: 'short',
			7: ';left',
			8: 'center',
			9: 'right',
			10: 'ピンチヒッター',
			11: 'runner',
		};
		
			setTimeout(
			function(){
				// バグ回避？
				onseiYomiage('');
			}
			,0);

			setTimeout(
			function(){
				// バグ回避？

		<?php //試合開始?>
		<?php if ($gameInfo->status == 0):?>
			onseiYomiage('<?= $gameInfo->home_team->yomi;?>');
			onseiYomiage('対');
			onseiYomiage('<?= $gameInfo->visitor_team->yomi;?>');
			onseiYomiage('まもなく試合開始です');
		<?php endif;?>
		
		<?php //イニング当初?>
		<?php if ($gameInfo->status > 0 && $inningLastType == null):?>
			<?php 
				$inningText = ceil($gameInfo->status /2) . '回の';
				if ($gameInfo->status % 2 == 1) {
					$inningText .= '表';
					$teamText = $gameInfo->visitor_team->yomi;
				} else {
					$inningText .= '裏';
					$teamText = $gameInfo->home_team->yomi;
				}
			?>
			onseiYomiage('<?= $inningText;?>');
			onseiYomiage('<?= $teamText ;?>の攻撃は');
		<?php endif;?>
		
		<?php //プレイ先頭?>
		<?php if ($inningLastType == null || ($inningLastType == 2 && $gameInfo->out_num != 3)):?>
			<?php 
				if ($gameInfo->status % 2 == 1) {
					$batterPosition = $visitorMembers[$batter_dajun]->position;
					$batterName= $visitorMembers[$batter_dajun]->player->name_read;
					$batterNameShort= $visitorMembers[$batter_dajun]->player->name_short_read;
					$batterNo= $visitorMembers[$batter_dajun]->player->no;
				} else {
					$batterPosition = $homeMembers[$batter_dajun]->position;
					$batterName= $homeMembers[$batter_dajun]->player->name_read;
					$batterNameShort= $homeMembers[$batter_dajun]->player->name_short_read;
					$batterNo = $homeMembers[$batter_dajun]->player->no;
				}
			?>
			onseiYomiage('<?= $batter_dajun;?>ばん');
			onseiYomiage(positionOnsei[<?= $batterPosition;?>]);
			onseiYomiage('<?= $batterName;?>');
			onseiYomiage(positionOnsei[<?= $batterPosition;?>]);
			onseiYomiage('<?= $batterNameShort;?>');
			onseiYomiage('せばんごう');
			onseiYomiage('<?= $batterNo;?>');
		<?php endif;?>
		<?php if ($gameInfo->status > 0 && $inningLastType == 1):?>
			<?php if ($targetChangeTeam->id == $attack_team_id):?>
				<?php 
					foreach ($changeMembers as $changeMember):
				?>
				<?php if ($changeMember->position == 10):?>
				onseiYomiage('ばったー');
				<?php elseif ($changeMember->position == 11):?>
				onseiYomiage('らんなー');
				<?php endif;?>
				onseiYomiage('<?= $changeMember->batter->name_read;?>');
				<?php if ($changeMember->position == 10):?>
				onseiYomiage('ばったー');
				<?php elseif ($changeMember->position == 11):?>
				onseiYomiage('らんなー');
				<?php endif;?>
				onseiYomiage('<?= $changeMember->batter->name_short_read;?>');
				onseiYomiage('せばんごう');
				onseiYomiage('<?= $changeMember->batter->no;?>');
				<?php endforeach;?>
			<?php else:?>
				onseiYomiage('<?= $targetChangeTeam->yomi;?>');
				onseiYomiage('選手の交代をお知らせします');
				<?php 
					foreach ($changeMembers as $changeMember):
				?>
				onseiYomiage('<?= $changeMember->dajun;?>ばん');
				onseiYomiage(positionOnsei[<?= $changeMember->position;?>]);
				onseiYomiage('<?= $changeMember->batter->name_read;?>');
				<?php endforeach;?>
				onseiYomiage('以上です');
			<?php endif;?>
		<?php endif;?>
			}
			,100);
		
		function onseiYomiage(word) {
		    var synthes = new SpeechSynthesisUtterance();
		    var voices = speechSynthesis.getVoices();
		    synthes.voice = voices[12];
		    synthes.text = word;
		    synthes.rate = 1.2;
		    before_sp = synthes;
		    synthes.lang = "ja-JP"
		    speechSynthesis.speak( synthes );
		    synthesSet.push(synthes) ;
		}
		
		// 詳細のポップアップ
		$('.player_box_td').mouseover(function(){
			//console.log($(this).data('fukidashi'));
			var offset = $(this).offset();
			$('#fukidashi_display').html($(this).find('.player_namebox').data('fukidashi'));
			var left;
			$('#fukidashi_display').show();
			if (offset.left > 500) {
				left = offset.left - $('#fukidashi_display').width() - 60;
			} else {
				left = offset.left + 150;
			}
			$('#fukidashi_display').css({
				top: (offset.top - 20) + 'px',
				left: left + 'px',
			});
		});
		$('.player_box_td').mouseout(function(){
			$('#fukidashi_display').hide();
		});
	});
</script>
