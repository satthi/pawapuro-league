<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'cards', 'action' => 'index']);?></li>
	</ul>
</div>
<div class="clearfix">
<?php foreach ($playerInfos as $player):?>
<div class="card_set">
<?= $this->element('player_card', ['player' => $player]);?>
<?= $this->element('player_card_back', ['player' => $player]);?>
</div>
<?php endforeach;?>
</div>
<?php if (!empty($userId)):?>
<?= $this->Html->link('もう一度引く', ['action' => 'random', $count, $userId]);?>
<?= $this->Html->link('マイページへ', ['controller' => 'Users', 'action' => 'view', $userId]);?>
<?php endif;?>

<script type="text/javascript">
$(function(){
$('.ura').hide();
$('.block').click(function(){
$(this).parent('.card_set').find('.block').toggle();
});
});
</script>
