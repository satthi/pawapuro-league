<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $id]);?></li>
	</ul>
</div>
<div class="players index columns content">
    <h3><?= __('Players') ?></h3>
    <h4>打者</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:150px;">name</th>
                <th scope="col">T</th>
                <th scope="col">no</th>
	            <th><?= $this->Html->link('試合', [$id, 'sort' => 'yashu_game']) ?></th>
	            <th><?= $this->Html->link('打率', [$id, 'sort' => 'avg']) ?></th>
	            <th><?= $this->Html->link('HR', [$id, 'sort' => 'hr']) ?></th>
	            <th><?= $this->Html->link('打点', [$id, 'sort' => 'rbi']) ?></th>
	            <th><?= $this->Html->link('打席', [$id, 'sort' => 'daseki']) ?></th>
	            <th><?= $this->Html->link('打数', [$id, 'sort' => 'dasu']) ?></th>
	            <th><?= $this->Html->link('安打', [$id, 'sort' => 'hit']) ?></th>
	            <th><?= $this->Html->link('2塁打', [$id, 'sort' => 'base2']) ?></th>
	            <th><?= $this->Html->link('3塁打', [$id, 'sort' => 'base3']) ?></th>
	            <th><?= $this->Html->link('四球', [$id, 'sort' => 'walk']) ?></th>
	            <th><?= $this->Html->link('死球', [$id, 'sort' => 'deadball']) ?></th>
	            <th>出塁率</th>
	            <th>長打率</th>
	            <th>ops</th>
	            <th><?= $this->Html->link('犠打', [$id, 'sort' => 'bant']) ?></th>
	            <th><?= $this->Html->link('犠飛', [$id, 'sort' => 'sacrifice_fly']) ?></th>
	            <th><?= $this->Html->link('三振', [$id, 'sort' => 'sansin']) ?></th>
	            <th><?= $this->Html->link('併殺', [$id, 'sort' => 'heisatsu']) ?></th>
	            <th><?= $this->Html->link('盗塁', [$id, 'sort' => 'steal']) ?></th>
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
