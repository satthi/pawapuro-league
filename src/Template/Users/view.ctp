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

    <h4>現在のスタメン</h4>
    <h5>野手</h5>
    <div class="clearfix">
        <?php foreach ($members['dajun'] as $dajun => $cardInfo):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardInfo['card_id'], 'position' => $cardInfo['position'], 'dajun' => $dajun . '番'])->render();?>
        <?php endforeach;?>
    </div>
    <div class="clearfix">
    	<?php //debug($members);?>
        <?php foreach ($members['hikaeBatters'] as $dajun => $cardId):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '代' . ($dajun + 1)])->render();?>
        <?php endforeach;?>
    </div>
    <h5>投手</h5>
    <div class="clearfix">
    	<?php //debug($members);?>
        <?php foreach ($members['startPitcher'] as $dajun => $cardId):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '先' . ($dajun + 1)])->render();?>
        <?php endforeach;?>
    </div>
    <div class="clearfix">
    	<?php //debug($members);?>
        <?php foreach ($members['nakatsugiPitchers'] as $dajun => $cardId):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '中' . ($dajun + 1)])->render();?>
        <?php endforeach;?>
        <?php foreach ($members['setupperPitchers'] as $dajun => $cardId):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => 'ｾｯﾄｱｯﾊﾟｰ'])->render();?>
        <?php endforeach;?>
        <?php foreach ($members['osaePitchers'] as $dajun => $cardId):?>
        <?= $this->cell('CardInfo', ['card_id' => $cardId, 'position' => null, 'dajun' => '抑え'])->render();?>
        <?php endforeach;?>
    </div>
</div>
