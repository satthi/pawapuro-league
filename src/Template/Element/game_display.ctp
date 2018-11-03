<?php if (!empty($gameLists[$nowDate->format('Y-m-d')][$position])):?>
	<?php
	$stamenDemo = true;
	 $now = '';
	if ($gameLists[$nowDate->format('Y-m-d')][$position]->status == 0) {
	 $now = '試合前';
	 $stamenDemo = false;
	} elseif ($gameLists[$nowDate->format('Y-m-d')][$position]->status == 99) {
	 $now = '終了';
	} else {
		$now = ceil($gameLists[$nowDate->format('Y-m-d')][$position]->status / 2);
		if ($gameLists[$nowDate->format('Y-m-d')][$position]->status % 2 == 1) {
			$now .= '表';
		} else {
			$now .= '裏';
		}
	}
	?>
	<table>
		<tr>
			<td><?= $gameLists[$nowDate->format('Y-m-d')][$position]->home_team->ryaku_name;?></td>
			<td>
				<?= $this->Html->link($now, ['controller' => 'games', 'action' => 'play', $gameLists[$nowDate->format('Y-m-d')][$position]->id, $gameLists[$nowDate->format('Y-m-d')][$position]->dh_flag]);?>
				<?php if ($stamenDemo == true):?>
					<br />
					<?= $this->Html->link('デモ', ['controller' => 'board', 'action' => 'index', $gameLists[$nowDate->format('Y-m-d')][$position]->id]);?>
				<?php endif;?>
			</td>
			<td><?= $gameLists[$nowDate->format('Y-m-d')][$position]->visitor_team->ryaku_name;?></td>
		</tr>
		<tr>
			<td><?= $gameLists[$nowDate->format('Y-m-d')][$position]->home_point;?></td>
			<td>-</td>
			<td><?= $gameLists[$nowDate->format('Y-m-d')][$position]->visitor_point;?></td>
		</tr>
	</table>
<?php else:?>
	-
<?php endif;?>