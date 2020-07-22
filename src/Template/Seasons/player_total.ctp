<div class="players index columns content">
    <h3><?= __('Players') ?></h3>
    <h4>打者</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:150px;">name</th>
                <th scope="col">no</th>
	            <th><?= $this->Html->link('試合', ['sort' => 'yashu_game']) ?></th>
	            <th><?= $this->Html->link('打率', ['sort' => 'avg']) ?></th>
	            <th><?= $this->Html->link('HR', ['sort' => 'hr']) ?></th>
	            <th><?= $this->Html->link('打点', ['sort' => 'rbi']) ?></th>
	            <th><?= $this->Html->link('打席', ['sort' => 'daseki']) ?></th>
	            <th><?= $this->Html->link('打数', ['sort' => 'dasu']) ?></th>
	            <th><?= $this->Html->link('安打', ['sort' => 'hit']) ?></th>
	            <th><?= $this->Html->link('2塁打', ['sort' => 'base2']) ?></th>
	            <th><?= $this->Html->link('3塁打', ['sort' => 'base3']) ?></th>
	            <th><?= $this->Html->link('四球', ['sort' => 'walk']) ?></th>
	            <th><?= $this->Html->link('死球', ['sort' => 'deadball']) ?></th>
	            <th><?= $this->Html->link('出塁率', ['sort' => 'obp']) ?></th>
	            <th><?= $this->Html->link('長打率', ['sort' => 'slg']) ?></th>
	            <th><?= $this->Html->link('ops', ['sort' => 'ops']) ?></th>
	            <th><?= $this->Html->link('犠打', ['sort' => 'bant']) ?></th>
	            <th><?= $this->Html->link('犠飛', ['sort' => 'sacrifice_fly']) ?></th>
	            <th><?= $this->Html->link('三振', ['sort' => 'sansin']) ?></th>
	            <th><?= $this->Html->link('併殺', ['sort' => 'heisatsu']) ?></th>
	            <th><?= $this->Html->link('盗塁', ['sort' => 'steal']) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
            <tr>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $player]);?>
                </td>
                <td><?= h($player->no) ?></td>
	            <td><?= $player->yashu_game; ?></td>
	            <td><?= $player->display_avg; ?></td>
	            <td><?= $this->Number->format($player->hr) ?></td>
	            <td><?= $this->Number->format($player->rbi) ?></td>
	            <td><?= $this->Number->format($player->daseki) ?></td>
	            <td><?= $this->Number->format($player->dasu) ?></td>
	            <td><?= $this->Number->format($player->hit) ?></td>
	            <td><?= $this->Number->format($player->base2) ?></td>
	            <td><?= $this->Number->format($player->base3) ?></td>
	            <td><?= $this->Number->format($player->walk) ?></td>
	            <td><?= $this->Number->format($player->deadball) ?></td>
	            <td><?= $player->obp; ?></td>
	            <td><?= $player->slg; ?></td>
	            <td><?= $player->ops; ?></td>
	            <td><?= $this->Number->format($player->bant) ?></td>
	            <td><?= $this->Number->format($player->sacrifice_fly) ?></td>
	            <td><?= $this->Number->format($player->sansin) ?></td>
	            <td><?= $this->Number->format($player->heisatsu) ?></td>
	            <td><?= $this->Number->format($player->steal) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
