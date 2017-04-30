<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Team'), ['action' => 'edit', $team->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Team'), ['action' => 'delete', $team->id], ['confirm' => __('Are you sure you want to delete # {0}?', $team->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="teams view large-9 medium-8 columns content">
    <h3><?= h($team->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Season') ?></th>
            <td><?= $team->has('season') ? $this->Html->link($team->season->name, ['controller' => 'Seasons', 'action' => 'view', $team->season->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($team->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Game') ?></th>
            <td><?= $this->Number->format($team->game) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Win') ?></th>
            <td><?= $this->Number->format($team->win) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lose') ?></th>
            <td><?= $this->Number->format($team->lose) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Draw') ?></th>
            <td><?= $this->Number->format($team->draw) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted Date') ?></th>
            <td><?= h($team->deleted_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($team->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($team->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $team->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Name') ?></h4>
        <?= $this->Text->autoParagraph(h($team->name)); ?>
    </div>
    <div class="row">
        <h4><?= __('Ryaku Name') ?></h4>
        <?= $this->Text->autoParagraph(h($team->ryaku_name)); ?>
    </div>
</div>
