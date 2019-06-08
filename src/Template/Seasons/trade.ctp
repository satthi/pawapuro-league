<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $id]);?></li>
	</ul>
</div>
<?= $this->Form->create();?>
	<?= $this->Form->input('before_player_id', ['type' => 'select', 'options' => $players, 'empty' => true]);?>
	<?= $this->Form->input('new_team_id', ['type' => 'select', 'options' => $teams, 'empty' => true]);?>
	<?= $this->Form->input('new_no', ['type' => 'text']);?>
	<?= $this->Form->submit();?>
<?= $this->Form->end();?>
