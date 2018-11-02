<h2>守備変更</h2>
<div class="clearfix">
	<div style="float:left;width:33%;">
		<h3>今の打順</h3>
		<table id="stamen_table" class="change_dajun" style="width:auto">
			<?php foreach ($nowMembers as $nowMember):?>
			<tr>
				<td class="dajun" data-dajun="<?= $nowMember['member_info']->dajun;?>">
				<?php if ($nowMember['member_info']->dajun != 10):?>
					<?= $nowMember['member_info']->dajun;?>
				<?php endif;?>
				</td>
				
				<td data-position="<?= $nowMember['position'];?>" class="position color_<?= $positionColors[$nowMember['position']];?>"><?= $positionLists[$nowMember['position']];?></td>
                <td class="member player_box_td" data-player_id="<?= $nowMember['member_info']->player->id;?>">
                    <?= $this->element('player_block', ['player' => $nowMember['member_info']->player, 'nolink' => true]);?>
                </td>
				<td>
					<?= $nowMember['member_info']->player->real_batter_player_info;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	<div style="float:left;width:33%;">
		<h3>交代(野手)</h3>
		<table class="change_list">
			<?php foreach ($pinchHitterLists as $pinchHitterList):?>
			<?php if (!is_null($pinchHitterList->type_p)) {
				continue;
			}?>
			<tr>
                <td class="member member_info member player_box_td" data-player_id="<?= $pinchHitterList->id;?>">
                    <?= $this->element('player_block', ['player' => $pinchHitterList, 'nolink' => true]);?>
                </td>
				<td>
					<?= $pinchHitterList->real_batter_player_info;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	<div style="float:left;width:34%;">
		<h3>交代(投手)</h3>
		<table class="change_list">
			<?php foreach ($pinchHitterLists as $pinchHitterList):?>
			<?php if (is_null($pinchHitterList->type_p)) {
				continue;
			}?>
			<tr>
                <td class="member member_info member player_box_td" data-player_id="<?= $pinchHitterList->id;?>">
                    <?= $this->element('player_block', ['player' => $pinchHitterList, 'nolink' => true]);?>
                </td>
				<td>
					<?= $pinchHitterList->pitcher_player_info;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>

<?= $this->Form->create(null, ['id' => 'change_form']);?>
<?php for ($i = 1;$i <= 10; $i++):?>
<?= $this->Form->input('Players.' . $i . '.player_id',['id' => 'player_id_' . $i, 'type' => 'hidden']);?>
<?= $this->Form->input('Players.' . $i . '.position',['id' => 'position_' . $i, 'type' => 'hidden']);?>
<?php endfor;?>
<button type="submit">交代</button>
<?= $this->Form->end();?>


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
					var selected_dom_table_id = selected_dom.parents('table').attr('id');
					var self_table_id = self.parents('table').attr('id');
					// 成績も一緒に移動
					var selected_info_dom = selected_dom.next('.member_info');
					var this_info_dom = self.next('.member_info');
					// スタメン内の打順の入れ替えはさせない
					if (
						selected_dom_table_id == 'stamen_table' &&
						self_table_id == 'stamen_table'
					) {
						return false;
					}
					
					if (selected_dom_table_id == 'stamen_table') {
						selected_dom.before(self.clone());
					}
					if (self_table_id == 'stamen_table') {
						self.before(selected_dom.clone());
					}
					
					// 成績の移動
					selected_info_dom.before(this_info_dom.clone());
					this_info_dom.before(selected_info_dom.clone());
					this_info_dom.remove();
					selected_info_dom.remove();
					
					if (selected_dom_table_id == 'stamen_table') {
						self.parents('tr').remove();
					} else {
						self.remove();
					}
					if (self_table_id == 'stamen_table') {
						selected_dom.parents('tr').remove();
					} else {
						selected_dom.remove();
					}
					

					$('#member_selected').removeAttr('id');

				}
			}
		});
		
		$('#change_form').submit(function(){
			$('#stamen_table tr').each(function(){
				var dajun = $(this).find('.dajun').data('dajun');
				var position = $(this).find('.position').data('position');
				var player_id = $(this).find('.member').data('player_id');
				$('#player_id_' + dajun).val(player_id);
				$('#position_' + dajun).val(position);
			});

			if ($('#player_id_10').val() == '') {
				$('#player_id_10').remove();
			}
			if ($('#position_10').val() == '') {
				$('#position_10').remove();
			}
		});
	});
</script>
