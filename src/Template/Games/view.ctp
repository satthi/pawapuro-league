<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Game'), ['action' => 'edit', $game->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Game'), ['action' => 'delete', $game->id], ['confirm' => __('Are you sure you want to delete # {0}?', $game->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Games'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Game'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Home Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Home Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Game Innings'), ['controller' => 'GameInnings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Game Inning'), ['controller' => 'GameInnings', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Game Members'), ['controller' => 'GameMembers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Game Member'), ['controller' => 'GameMembers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Game Results'), ['controller' => 'GameResults', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Game Result'), ['controller' => 'GameResults', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="games view large-9 medium-8 columns content">
    <h3><?= h($game->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Season') ?></th>
            <td><?= $game->has('season') ? $this->Html->link($game->season->name, ['controller' => 'Seasons', 'action' => 'view', $game->season->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Home Team') ?></th>
            <td><?= $game->has('home_team') ? $this->Html->link($game->home_team->name, ['controller' => 'Teams', 'action' => 'view', $game->home_team->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Visitor Team') ?></th>
            <td><?= $game->has('visitor_team') ? $this->Html->link($game->visitor_team->name, ['controller' => 'Teams', 'action' => 'view', $game->visitor_team->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($game->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Home Point') ?></th>
            <td><?= $this->Number->format($game->home_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Visitor Point') ?></th>
            <td><?= $this->Number->format($game->visitor_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($game->date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted Date') ?></th>
            <td><?= h($game->deleted_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($game->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($game->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $game->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Game Innings') ?></h4>
        <?php if (!empty($game->game_innings)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Game Id') ?></th>
                <th scope="col"><?= __('Inning') ?></th>
                <th scope="col"><?= __('Omote Ura') ?></th>
                <th scope="col"><?= __('Hit') ?></th>
                <th scope="col"><?= __('Point') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col"><?= __('Deleted Date') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($game->game_innings as $gameInnings): ?>
            <tr>
                <td><?= h($gameInnings->id) ?></td>
                <td><?= h($gameInnings->game_id) ?></td>
                <td><?= h($gameInnings->inning) ?></td>
                <td><?= h($gameInnings->omote_ura) ?></td>
                <td><?= h($gameInnings->hit) ?></td>
                <td><?= h($gameInnings->point) ?></td>
                <td><?= h($gameInnings->deleted) ?></td>
                <td><?= h($gameInnings->deleted_date) ?></td>
                <td><?= h($gameInnings->created) ?></td>
                <td><?= h($gameInnings->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'GameInnings', 'action' => 'view', $gameInnings->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'GameInnings', 'action' => 'edit', $gameInnings->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'GameInnings', 'action' => 'delete', $gameInnings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gameInnings->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Game Members') ?></h4>
        <?php if (!empty($game->game_members)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Game Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Dajun') ?></th>
                <th scope="col"><?= __('Position') ?></th>
                <th scope="col"><?= __('Player Id') ?></th>
                <th scope="col"><?= __('Stamen Flag') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col"><?= __('Deleted Date') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($game->game_members as $gameMembers): ?>
            <tr>
                <td><?= h($gameMembers->id) ?></td>
                <td><?= h($gameMembers->game_id) ?></td>
                <td><?= h($gameMembers->type) ?></td>
                <td><?= h($gameMembers->dajun) ?></td>
                <td><?= h($gameMembers->position) ?></td>
                <td><?= h($gameMembers->player_id) ?></td>
                <td><?= h($gameMembers->stamen_flag) ?></td>
                <td><?= h($gameMembers->deleted) ?></td>
                <td><?= h($gameMembers->deleted_date) ?></td>
                <td><?= h($gameMembers->created) ?></td>
                <td><?= h($gameMembers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'GameMembers', 'action' => 'view', $gameMembers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'GameMembers', 'action' => 'edit', $gameMembers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'GameMembers', 'action' => 'delete', $gameMembers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gameMembers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Game Results') ?></h4>
        <?php if (!empty($game->game_results)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Game Id') ?></th>
                <th scope="col"><?= __('Batter Id') ?></th>
                <th scope="col"><?= __('Pitcher Id') ?></th>
                <th scope="col"><?= __('Inning') ?></th>
                <th scope="col"><?= __('Result') ?></th>
                <th scope="col"><?= __('Out Num') ?></th>
                <th scope="col"><?= __('Display') ?></th>
                <th scope="col"><?= __('Stamen Flag') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col"><?= __('Deleted Date') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($game->game_results as $gameResults): ?>
            <tr>
                <td><?= h($gameResults->id) ?></td>
                <td><?= h($gameResults->game_id) ?></td>
                <td><?= h($gameResults->batter_id) ?></td>
                <td><?= h($gameResults->pitcher_id) ?></td>
                <td><?= h($gameResults->inning) ?></td>
                <td><?= h($gameResults->result) ?></td>
                <td><?= h($gameResults->out_num) ?></td>
                <td><?= h($gameResults->display) ?></td>
                <td><?= h($gameResults->stamen_flag) ?></td>
                <td><?= h($gameResults->deleted) ?></td>
                <td><?= h($gameResults->deleted_date) ?></td>
                <td><?= h($gameResults->created) ?></td>
                <td><?= h($gameResults->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'GameResults', 'action' => 'view', $gameResults->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'GameResults', 'action' => 'edit', $gameResults->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'GameResults', 'action' => 'delete', $gameResults->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gameResults->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
