<div class="submenu clearfix">
    <ul>
        <li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $id]);?></li>
    </ul>
</div>
<div class="players index columns content">
    <h3><?= __('Players') ?></h3>
    <h4>投手</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:150px;">name</th>
                <th scope="col">T</th>
                <th scope="col">no</th>
                <th><?= $this->Html->link('試合', [$id, 'sort' => 'game']) ?></th>
                <th><?= $this->Html->link('era', [$id, 'sort' => 'era']) ?></th>
                <th><?= $this->Html->link('inning', [$id, 'sort' => 'inning']) ?></th>
                <th><?= $this->Html->link('jiseki', [$id, 'sort' => 'jiseki']) ?></th>
                <th><?= $this->Html->link('win', [$id, 'sort' => 'win']) ?></th>
                <th><?= $this->Html->link('lose', [$id, 'sort' => 'lose']) ?></th>
                <th><?= $this->Html->link('勝率', [$id, 'sort' => 'display_win_ratio']) ?></th>
                <th><?= $this->Html->link('hold', [$id, 'sort' => 'hold']) ?></th>
                <th><?= $this->Html->link('save', [$id, 'sort' => 'save']) ?></th>
                <th><?= $this->Html->link('完投', [$id, 'sort' => 'kanto']) ?></th>
                <th><?= $this->Html->link('完封', [$id, 'sort' => 'kanpu']) ?></th>
                <th><?= $this->Html->link('被安打', [$id, 'sort' => 'p_hit']) ?></th>
                <th><?= $this->Html->link('四球', [$id, 'sort' => 'p_walk']) ?></th>
                <th><?= $this->Html->link('被打率', [$id, 'sort' => 'p_avg']) ?></th>
                <th><?= $this->Html->link('被本塁打', [$id, 'sort' => 'p_hr']) ?></th>
                <th><?= $this->Html->link('奪三振', [$id, 'sort' => 'get_sansin']) ?></th>
                <th><?= $this->Html->link('奪三振率', [$id, 'sort' => 'sansin_ritsu']) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
            <tr>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $player]);?>
                </td>
                <td><?= h($player->team->ryaku_name) ?></td>
                <td><?= h($player->no) ?></td>
                <td><?= h($player->game) ?></td>
                <td><?= h($player->display_era) ?></td>
                <td><?= floor($player->inning / 3);?>
                <?php if ($player->inning % 3 != 0) :?>
                    <?= $player->inning % 3 . '/3'?>
                <?php endif;?>
                </td>
                <td><?= $this->Number->format($player->jiseki) ?></td>
                <td><?= $this->Number->format($player->win) ?></td>
                <td><?= $this->Number->format($player->lose) ?></td>
                <td><?= $player->display_win_ratio ?></td>
                <td><?= $this->Number->format($player->hold) ?></td>
                <td><?= $this->Number->format($player->save) ?></td>
                <td><?= $this->Number->format($player->kanto) ?></td>
                <td><?= $this->Number->format($player->kanpu) ?></td>
                <td><?= $this->Number->format($player->p_hit) ?></td>
                <td><?= $this->Number->format($player->p_walk) ?></td>
                <td><?= $player->p_avg ?></td>
                <td><?= $this->Number->format($player->p_hr) ?></td>
                <td><?= $this->Number->format($player->get_sansin) ?></td>
                <td><?= $player->sansin_ritsu ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
