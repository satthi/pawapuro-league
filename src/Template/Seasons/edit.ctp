<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $season->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $season->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="seasons form large-9 medium-8 columns content">
    <?= $this->Form->create($season) ?>
    <fieldset>
        <legend><?= __('Edit Season') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('deleted');
            echo $this->Form->input('deleted_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
