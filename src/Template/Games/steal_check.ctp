<h2>代走</h2>
<?= $this->Form->create();?>
<div class="clearfix">
	<div style="float:left;width:33%;">
		<h3>今の打順</h3>
		<table class="change_dajun">
			<?php foreach ($nowMembers as $nowMember):?>
			<tr>
                <td class="player_box_td" data-id="<?= $nowMember->player->id;?>">
                    <?= $this->element('player_block', ['player' => $nowMember->player, 'nolink' => true]);?>
                </td>
				<td>
					<?= $nowMember->player->real_batter_player_info;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	<div style="float:left;width:33%;">
		<h3>結果</h3>
		<?= $this->Form->input('result' ,['type' => 'select', 'options' => [0 => '成功', 1 => '失敗']]);?>
	</div>
	<div style="float:left;width:34%;">
	</div>
</div>

<?= $this->Form->input('target_player_id', ['type' => 'hidden', 'id' => 'target_player']);?>
<button type="submit">登録</button>
<?= $this->Form->end();?>


<script type="text/javascript">
	$(function(){
		$('.change_dajun td').click(function(){
			$('#dajun_selected').removeAttr('id');
			$(this).attr('id', 'dajun_selected');
			$('#target_player').val($(this).data('id'));
		});
	});
</script>
