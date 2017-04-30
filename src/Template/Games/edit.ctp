<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $game->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $game->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Games'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Home Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Home Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Game Innings'), ['controller' => 'GameInnings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Game Inning'), ['controller' => 'GameInnings', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Game Members'), ['controller' => 'GameMembers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Game Member'), ['controller' => 'GameMembers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Game Results'), ['controller' => 'GameResults', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Game Result'), ['controller' => 'GameResults', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="games form large-9 medium-8 columns content">
    <?= $this->Form->create($game) ?>
    <fieldset>
        <legend><?= __('Edit Game') ?></legend>
        <?php
            echo $this->Form->input('season_id', ['options' => $seasons, 'empty' => true]);
            echo $this->Form->input('date', ['empty' => true]);
            echo $this->Form->input('home_team_id', ['options' => $homeTeams, 'empty' => true]);
            echo $this->Form->input('visitor_team_id', ['options' => $visitorTeams, 'empty' => true]);
            echo $this->Form->input('home_point');
            echo $this->Form->input('visitor_point');
            echo $this->Form->input('deleted');
            echo $this->Form->input('deleted_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
