<h2>代打</h2>
<div class="clearfix">
	<div style="float:left;width:33%;">
		<h3>今の打者</h3>
		<table>
			<tr>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $nowBatter->player, 'nolink' => true]);?>
                </td>
				<td>
					<?= $nowBatter->player->real_batter_player_info;?>
				</td>
			</tr>
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
                <td class="player_box_td"data-id="<?= $pinchHitterList->id;?>">
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
                <td class="player_box_td"data-id="<?= $pinchHitterList->id;?>">
                    <?= $this->element('player_block', ['player' => $pinchHitterList, 'nolink' => true]);?>
                </td>
				<td>
					<?= $pinchHitterList->real_batter_player_info;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>

<?= $this->Form->create();?>
<?= $this->Form->input('change_player_id', ['type' => 'hidden', 'id' => 'change_player']);?>
<button type="submit">交代</button>
<?= $this->Form->end();?>

<script type="text/javascript">
	$(function(){
		$('.change_list td').click(function(){
			$('#member_selected').removeAttr('id');
			$(this).attr('id', 'member_selected');
			$('#change_player').val($(this).data('id'));
		});
	});
</script>
