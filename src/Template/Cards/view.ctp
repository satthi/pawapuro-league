<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'cards', 'action' => 'index']);?></li>
	</ul>
</div>
<div class="clearfix">
<?php foreach ($playerInfos as $player):?>
<?= $this->element('player_card', ['player' => $player]);?>
<?php endforeach;?>
</div>
<div class="clearfix">
<?php foreach ($playerInfos as $player):?>
<?= $this->element('player_card_back', ['player' => $player]);?>
<?php endforeach;?>
</div>

<div class="clearfix">
<?php foreach ($playerInfos as $player):?>
	<div class="block" style="height:100%;">
		<?= $player->player_info;?>
		<?= $player->steal;?>盗塁
		<?= $this->Form->create($player, ['novalidate' => true]);?>
		<?= $this->Form->input('id');?>
	<?php if ($player->type_p === null):?>
		<?= $this->Form->input('status_meat', ['label' => '巧打力']);?>
		<?= $this->Form->input('status_power', ['label' => '長打力']);?>
		<?= $this->Form->input('status_speed', ['label' => '走力']);?>
		<?= $this->Form->input('status_bant', ['label' => 'バント']);?>
		<?= $this->Form->input('status_defense', ['label' => '守備']);?>
		<?= $this->Form->input('status_mental', ['label' => '精神力']);?>
		<?= $this->Form->input('status_position', ['type' => 'select', 'options' => $statusPositionLists]);?>
		<?= $this->Form->input('status_slider', ['label' => '捕手']);?>
		<?= $this->Form->input('status_hslider', ['label' => '一塁']);?>
		<?= $this->Form->input('status_cut', ['label' => '二塁']);?>
		<?= $this->Form->input('status_curb', ['label' => '三塁']);?>
		<?= $this->Form->input('status_scurb', ['label' => '遊撃']);?>
		<?= $this->Form->input('status_folk', ['label' => '外野']);?>
		<?= $this->Form->input('status_cost');?>
	<?php else:?>
		<?= $this->Form->input('status_meat', ['label' => '体力']);?>
		<?= $this->Form->input('status_power', ['label' => '球速']);?>
		<?= $this->Form->input('status_speed', ['label' => '球威']);?>
		<?= $this->Form->input('status_bant', ['label' => '変化球']);?>
		<?= $this->Form->input('status_defense', ['label' => '制球力']);?>
		<?= $this->Form->input('status_mental', ['label' => '精神力']);?>
		<?= $this->Form->input('status_position', ['type' => 'select', 'options' => $statusPositionLists]);?>
		<?= $this->Form->input('status_slider');?>
		<?= $this->Form->input('status_hslider');?>
		<?= $this->Form->input('status_cut');?>
		<?= $this->Form->input('status_curb');?>
		<?= $this->Form->input('status_scurb');?>
		<?= $this->Form->input('status_folk');?>
		<?= $this->Form->input('status_sff');?>
		<?= $this->Form->input('status_changeup');?>
		<?= $this->Form->input('status_palm');?>
		<?= $this->Form->input('status_vslider');?>
		<?= $this->Form->input('status_knuckle');?>
		<?= $this->Form->input('status_schange');?>
		<?= $this->Form->input('status_sinker');?>
		<?= $this->Form->input('status_hsinker');?>
		<?= $this->Form->input('status_shoot');?>
		<?= $this->Form->input('status_hshoot');?>
		<?= $this->Form->input('status_cost');?>
	<?php endif;?>
		<?= $this->Form->submit('update');?>
		<?= $this->Form->end();?>
	</div>
<?php endforeach;?>
<div>
