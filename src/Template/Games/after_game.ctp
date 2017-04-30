<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $gameInfo->season_id]);?></li>
		<li><?= $this->Html->link('日程一覧', ['controller' => 'games', 'action' => 'index', $gameInfo->season_id]);?></li>
	</ul>
</div>
<div class="clearfix">
	<div id="home_div">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $gameInfo->home_team,
			'members' => $homeMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => null,
		]);?>
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
	<h4>結果</h4>
	
	<p><?= $gameInfo->home_team->name;?></p>
	<table>
		<tr>
			<th>
				
			</th>
			<th>
				勝
			</th>
			<th>
				負
			</th>
			<th>
				S
			</th>
			<th>
				HP
			</th>
			<th>
				自責点
			</th>
		</tr>
		<?php foreach ($homePitchers as $homePitcher) :?>
		<tr class="pitcher_lists" data-team_id="<?= $gameInfo->home_team->id;?>" data-player_id="<?= $homePitcher->id;?>">
			<td>
				<?= $homePitcher->name;?>
			</td>
			<td>
				<?= $this->Form->input('win', ['type' => 'radio', 'options' => [$homePitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('lose', ['type' => 'radio', 'options' => [$homePitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('save', ['type' => 'radio', 'options' => [$homePitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('hp', ['type' => 'checkbox', 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('jiseki', ['type' => 'text', 'label' => false,'size' => 1]);?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
					
	<p><?= $gameInfo->visitor_team->name;?></p>
	<table>
		<tr>
			<th>
				
			</th>
			<th>
				勝
			</th>
			<th>
				負
			</th>
			<th>
				S
			</th>
			<th>
				HP
			</th>
			<th>
				自責点
			</th>
		</tr>
		<?php foreach ($visitorPitchers as $visitorPitcher) :?>
		<tr class="pitcher_lists" data-team_id="<?= $gameInfo->visitor_team->id;?>" data-player_id="<?= $visitorPitcher->id;?>">
			<td>
				<?= $visitorPitcher->name;?>
			</td>
			<td>
				<?= $this->Form->input('win', ['type' => 'radio', 'options' => [$visitorPitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('lose', ['type' => 'radio', 'options' => [$visitorPitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('save', ['type' => 'radio', 'options' => [$visitorPitcher->id => ''], 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('hp', ['type' => 'checkbox', 'label' => false]);?>
			</td>
			<td>
				<?= $this->Form->input('jiseki', ['type' => 'text', 'label' => false,'size' => 1]);?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
	<div>
		<button type="button" id="submit">登録</button>
		<button type="button" id="back">戻る</button>
	</div>

	</div>
	<div id="visitor_div">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $gameInfo->visitor_team,
			'members' => $visitorMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => null,
		]);?>
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
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'back']);?>',
				success: function() {
					location.href='<?= $this->Url->build();?>';
				}
			});
			
		});
		
		$('#submit').click(function(){
			//データの登録
			var data = {};
			var win_flag =false;
			var lose_flag =false;
			var error =false;
			$('.pitcher_lists').each(function(){
				var win = $(this).find('input:radio[name="win"]:checked').size() != 0;
				var lose = $(this).find('input:radio[name="lose"]:checked').size() != 0;
				var save = $(this).find('input:radio[name="save"]:checked').size() != 0;
				var hp = $(this).find('input:checkbox[name="hp"]:checked').size() != 0;
				var jiseki = $(this).find('input:text[name="jiseki"]').val();
				// 自責点は必ず記入
				if (jiseki == '') {
					alert('自責点記入');
					error = true;
					return;
				}
				if (win + lose + save + hp > 1) {
					alert('二つ以上指定あり');
					error = true;
					return;
				}
				var player_id = $(this).data('player_id');
				var team_id = $(this).data('team_id');
				data[player_id] = {
					win: win,
					lose: lose,
					save: save,
					hp: hp,
					jiseki: jiseki,
					team_id: team_id,
					player_id: player_id,
				};
			});
			if (error == true) {
				return;
			}
			if (win_flag != lose_flag) {
				alert('勝ちと負け一方のみ指定あり');
				return;
			}
			$.ajax({
				data: data,
				type: "POST",
				url: '<?= $this->Url->build(['controller' => 'games', 'action' => 'after_game_save',$gameInfo->id]);?>',
				success: function() {
					location.reload();
				}
			});
			
		});
	});
</script>
