<div class="players index columns content">
	<?php foreach ($monthSets as $monthSet):?>
    <?= $this->Html->link($monthSet->month . '月月間', ['controller' => 'teams', 'action' => 'month', $teamID,$monthSet->year,$monthSet->month]) ?>
    <?php endforeach;?>
    <h3><?= __('Players') ?></h3>
    <h4>打者</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:150px;"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('no') ?></th>
	            <th>打率</th>
	            <th>HR</th>
	            <th>打点</th>
	            <th>試合</th>
	            <th>打席</th>
	            <th>打数</th>
	            <th>安打</th>
	            <th>2塁打</th>
	            <th>3塁打</th>
	            <th>四球</th>
	            <th>死球</th>
	            <th>出塁率</th>
	            <th>長打率</th>
	            <th>ops</th>
	            <th>犠打</th>
	            <th>犠飛</th>
	            <th>三振</th>
	            <th>併殺</th>
	            <th>盗塁</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
            <tr>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $player]);?>
                </td>
                <td><?= h($player->no) ?></td>
	            <td><?= $player->display_avg; ?></td>
	            <td><?= $this->Number->format($player->hr) ?></td>
	            <td><?= $this->Number->format($player->rbi) ?></td>
	            <td><?= $this->Number->format($player->yashu_game) ?></td>
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
    
    <h4>投手</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:150px;"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bougyo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inning') ?></th>
                <th scope="col"><?= $this->Paginator->sort('jiseki') ?></th>
                <th scope="col"><?= $this->Paginator->sort('game') ?></th>
                <th scope="col"><?= $this->Paginator->sort('win') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lose') ?></th>
                <th scope="col"><?= $this->Paginator->sort('hold') ?></th>
                <th scope="col"><?= $this->Paginator->sort('save') ?></th>
                <th scope="col">被安打</th>
                <th scope="col">被打率</th>
                <th scope="col">被本塁打</th>
                <th scope="col">奪三振</th>
                <th scope="col">奪三振率</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
            <?php if ($player->type_p === null) continue;?>
            <tr>
                <td class="player_box_td">
                    <?= $this->element('player_block', ['player' => $player]);?>
                </td>
                <td><?= h($player->no) ?></td>
                <td><?= h($player->display_era) ?></td>
                <td><?= floor($player->inning / 3);?>
        		<?php if ($player->inning % 3 != 0) :?>
        			<?= $player->inning % 3 . '/3'?>
        		<?php endif;?>
        		</td>
                <td><?= $this->Number->format($player->jiseki) ?></td>
                <td><?= $this->Number->format($player->game) ?></td>
                <td><?= $this->Number->format($player->win) ?></td>
                <td><?= $this->Number->format($player->lose) ?></td>
                <td><?= $this->Number->format($player->hold) ?></td>
                <td><?= $this->Number->format($player->save) ?></td>
                <td><?= $this->Number->format($player->p_hit) ?></td>
                <td><?= $player->p_avg ?></td>
                <td><?= $this->Number->format($player->p_hr) ?></td>
                <td><?= $this->Number->format($player->get_sansin) ?></td>
                <td><?php if (!empty($player->inning)) echo sprintf('%0.2f', round($player->get_sansin / $player->inning * 27, 2)) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
