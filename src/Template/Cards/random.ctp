<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'cards', 'action' => 'index']);?></li>
	</ul>
</div>
<?= $this->Html->link('次のカード', '#', ['id' => 'next']);?>
<?php if (!empty($userId)):?>
<?= $this->Html->link('もう一度引く', ['action' => 'random', $count, $userId, $high], ['id' => 'onemore']);?>
<?= $this->Html->link('マイページへ', ['controller' => 'Users', 'action' => 'view', $userId], ['id' => 'mypage_back']);?>
<?php endif;?>
<div id="display_card" class="clearfix" style="position:relative;width:420px;height:620px;scroll:none;background-color:#000;">
</div>


<div id="base_card" class="clearfix" style="display:none;">
<?php foreach ($playerInfos as $player):?>
<div class="card_set">
<?= $this->element('player_card', ['player' => $player]);?>
</div>
<?php endforeach;?>
</div>

<script type="text/javascript">
$(function(){



function card_display() {
	first_dom = $('#base_card .card_set:first');
    $('#display_card').html(first_dom.clone())
    $('#display_card .card_set').css({'top': '420px', 'position': 'absolute'}).animate({'top': 0}, 500);
    
    first_dom.remove();
    
    if ($('#base_card .card_set').length > 0) {
        $('#next').show();
        $('#onemore').hide();
        
    } else {
        $('#next').hide();
        $('#onemore').show();
    }
}
card_display();
$('#next').click(function(){
	card_display();
	return false;
});

});
</script>
