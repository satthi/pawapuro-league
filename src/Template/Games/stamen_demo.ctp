<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $seasonId]);?></li>
	</ul>
</div>
<?= $this->Form->create('');?>
<?= $this->Form->input('home_team_id', ['type' => 'select', 'options' => $teams]);?>
<?= $this->Form->input('visitor_team_id', ['type' => 'select', 'options' => $teams]);?>
<?= $this->Form->submit();?>
<?= $this->Form->end();?>
