<?= $this->Html->link('コスト順', ['action' => 'cardlist', $userId, 'cost']);?>
<?= $this->Html->link('ポジション', ['action' => 'cardlist', $userId, 'position']);?>
<div class="clearfix">
<?php foreach ($cardLists as $cardList) :?>
<?= $this->element('card_short', ['card' => $cardList]);?>
<?php endforeach;?>
</div>
