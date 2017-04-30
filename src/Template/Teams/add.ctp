<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="teams form large-9 medium-8 columns content">
    <?= $this->Form->create($team) ?>
    <fieldset>
        <legend><?= __('Add Team') ?></legend>
        <?php
            echo $this->Form->input('season_id', ['options' => $seasons, 'empty' => true]);
            echo $this->Form->input('name');
            echo $this->Form->input('ryaku_name');
            echo $this->Form->input('game');
            echo $this->Form->input('win');
            echo $this->Form->input('lose');
            echo $this->Form->input('draw');
            echo $this->Form->input('deleted');
            echo $this->Form->input('deleted_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
