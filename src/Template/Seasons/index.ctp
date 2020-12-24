<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Season'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link('選手リスト出力', ['action' => 'player_export']) ?></li>
        <li><?= $this->Html->link('通算成績(野手)', ['action' => 'batter_total']) ?></li>
        <li><?= $this->Html->link('通算成績(投手)', ['action' => 'pitcher_total']) ?></li>
        <li><?= $this->Html->link('シーズン成績(野手)', ['action' => 'batter_season_total']) ?></li>
        <li><?= $this->Html->link('シーズン成績(投手)', ['action' => 'pitcher_season_total']) ?></li>
    </ul>
</nav>
<div class="seasons index large-9 medium-8 columns content">
    <h3><?= __('Seasons') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seasons as $season): ?>
            <tr>
                <td><?= h($season->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $season->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
