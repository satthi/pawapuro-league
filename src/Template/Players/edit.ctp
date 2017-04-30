<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $player->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $player->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Players'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Game Members'), ['controller' => 'GameMembers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Game Member'), ['controller' => 'GameMembers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="players form large-9 medium-8 columns content">
    <?= $this->Form->create($player) ?>
    <fieldset>
        <legend><?= __('Edit Player') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('type');
            echo $this->Form->input('daseki');
            echo $this->Form->input('dasu');
            echo $this->Form->input('hit');
            echo $this->Form->input('hr');
            echo $this->Form->input('rbi');
            echo $this->Form->input('inning');
            echo $this->Form->input('jiseki');
            echo $this->Form->input('win');
            echo $this->Form->input('lose');
            echo $this->Form->input('hold');
            echo $this->Form->input('save');
            echo $this->Form->input('deleted');
            echo $this->Form->input('deleted_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
