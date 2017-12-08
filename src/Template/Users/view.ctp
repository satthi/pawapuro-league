<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= $this->Number->format($user->point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Point') ?></th>
            <td><?= $user->name ?></td>
        </tr>
    </table>
    <?= $this->Html->link('カードを1枚引く', ['controller' => 'cards', 'action' => 'random', 1, $user->id]);?>
    <?= $this->Html->link('カードを10枚引く', ['controller' => 'cards','action' => 'random', 10, $user->id]);?>
    <?= $this->Html->link('カードを200枚引く', ['controller' => 'cards','action' => 'random', 200, $user->id]);?>
    <?= $this->Html->link('高コストカードを10枚引く', ['controller' => 'cards','action' => 'random', 10, $user->id, true]);?>
    <?= $this->Html->link('所持カード一覧', ['controller' => 'users','action' => 'cardlist', $user->id]);?>
    <?= $this->Html->link('オートスタメン', ['controller' => 'users','action' => 'auto_stamen', $user->id]);?>
    <?= $this->Html->link('スキルを1枚引く', ['controller' => 'users', 'action' => 'skilladd', 1, $user->id]);?>
    <?= $this->Html->link('スキルを10枚引く', ['controller' => 'users','action' => 'skilladd', 10, $user->id]);?>
    <?= $this->Html->link('スキルを50枚引く', ['controller' => 'users','action' => 'skilladd', 50, $user->id]);?>
    <?= $this->Html->link('高コストスキルを10枚引く', ['controller' => 'users','action' => 'skilladd', 10, $user->id,1]);?>

    <h4>現在のスタメン(総コスト<span id="sum_cost"></span>)</h4>
    <div id="stamen_block">
	    <h5>野手</h5>
	    <div class="clearfix">
	        <?php foreach ($members['dajun'] as $dajun => $cardInfo):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardInfo['card_id'], 'position' => $cardInfo['position'], 'dajun' => $dajun . '番', 'dataType' => 'dajun.' . $dajun])->render();?>
	        <?php endforeach;?>
	    </div>
	    <div class="clearfix">
	    	<?php //debug($members);?>
	        <?php foreach ($members['hikaeBatters'] as $dajun => $cardId):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '代' . ($dajun + 1), 'dataType' => 'hikaeBatters.' . $dajun])->render();?>
	        <?php endforeach;?>
	    </div>
	    <h5>投手</h5>
	    <div class="clearfix">
	    	<?php //debug($members);?>
	        <?php foreach ($members['startPitcher'] as $dajun => $cardId):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '先' . ($dajun + 1), 'dataType' => 'startPitcher.' . $dajun])->render();?>
	        <?php endforeach;?>
	    </div>
	    <div class="clearfix">
	    	<?php //debug($members);?>
	        <?php foreach ($members['nakatsugiPitchers'] as $dajun => $cardId):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '中' . ($dajun + 1), 'dataType' => 'nakatsugiPitchers.' . $dajun])->render();?>
	        <?php endforeach;?>
	        <?php foreach ($members['setupperPitchers'] as $dajun => $cardId):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => 'ｾｯﾄｱｯﾊﾟｰ', 'dataType' => 'setupperPitchers.' . $dajun])->render();?>
	        <?php endforeach;?>
	        <?php foreach ($members['osaePitchers'] as $dajun => $cardId):?>
	        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '抑え', 'dataType' => 'osaePitchers.' . $dajun])->render();?>
	        <?php endforeach;?>
	    </div>
    </div>
    <button type="button" id="change_save">変更保存</button>
    <h4>控え</h4>
    <div id="sub_members" class="clearfix" style="height:300px;overflow:scroll;">
		<?php foreach ($cardLists as $cardList) :?>
		<?= $this->element('card_short', ['card' => $cardList]);?>
		<?php endforeach;?>
    </div>
</div>

<script type="text/javascript">
$(function(){
function cost_calc(){
var sum = 0;
$('#stamen_block .cost_number').each(function(){
sum += parseInt($(this).text(), 10);
});
$('#sum_cost').text(sum);
}
cost_calc();

$(document).on('click', '.player_block', function() {
    if ($('.player_block_selected').length == 0) {
        $(this).addClass('player_block_selected');
    } else {
        var selected_dom = $('.player_block_selected');
        selected_dom.removeClass('player_block_selected');
        selected_html = selected_dom.parent('.player_block_wrap').html();
        this_html = $(this).parent('.player_block_wrap').html();
        cost_calc();
        // スタメン同士の入れ替えの時のみ
        var position_dom_a = selected_dom.parents('.block_short').find('.short_position');
        var position_dom_b = $(this).parents('.block_short').find('.short_position');
        console.log(position_dom_a.length )
        console.log(position_dom_b.length )
        if (position_dom_a.length > 0 && position_dom_b.length > 0) {
	        selected_position_html = position_dom_a.html();
	        this_position_html = position_dom_b.html();
	        selected_class = position_dom_a.attr('class');
	        this_class = position_dom_b.attr('class');
	        position_dom_a.html(this_position_html);
	        position_dom_b.html(selected_position_html);
	        position_dom_a.attr('class',this_class);
	        position_dom_b.attr('class',selected_class);
        }
        selected_dom.parent('.player_block_wrap').html(this_html);
        $(this).parent('.player_block_wrap').html(selected_html);
    }
});


$(document).on('click', '.short_position', function() {
    if ($('.short_position_selected').length == 0) {
        $(this).addClass('short_position_selected');
    } else {
        var selected_dom = $('.short_position_selected');
        selected_dom.removeClass('short_position_selected');
        selected_html = selected_dom.html();
        this_html = $(this).html();
        selected_class = selected_dom.attr('class');
        this_class = $(this).attr('class');
        selected_dom.html(this_html);
        $(this).html(selected_html);
        selected_dom.attr('class',this_class);
        $(this).attr('class',selected_class);
    }
});

$('#change_save').click(function(){
    var resultData = {};
    $('#stamen_block .block_short').each(function(){
        var dataType = $(this).data('type');
        var cardId = $(this).find('.short_image').data('card_id');
        var dataTypeSplit = dataType.split('.');
        if (!(dataTypeSplit[0] in resultData)) {
            resultData[dataTypeSplit[0]] = {};
        }
        if (dataTypeSplit[0] == 'dajun') {
            var position = $(this).find('.position_data').data('position');
            resultData[dataTypeSplit[0]][dataTypeSplit[1]] = {};
            resultData[dataTypeSplit[0]][dataTypeSplit[1]]['position'] = position;
            resultData[dataTypeSplit[0]][dataTypeSplit[1]]['card_id'] = cardId;
        } else {
            resultData[dataTypeSplit[0]][dataTypeSplit[1]] = cardId;
        }
    });
    $.ajax({
        type: 'POST',
        data: resultData,
        url: '<?= $saveUrl;?>',
        success: function(){
            alert('change save');
        }
    });
    return false;
});



});
</script>
