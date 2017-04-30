<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season_id]);?></li>
	</ul>
</div>
<div class="games index columns">
    <h3><?= __('Games') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('game1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('game2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('game3') ?></th>
            </tr>
        </thead>
        <tbody>
    <?php $nowDate = $minDate;?>
            <?php while(true): ?>
            <tr id="d<?= $nowDate->format('Ymd');?>">
                <td><?= $nowDate->format('Y/m/d(D)') ?></td>
                <td><?= $this->element('game_display', ['gameLists' => $gameLists, 'nowDate' => $nowDate, 'position' => 0]);?></td>
                <td><?= $this->element('game_display', ['gameLists' => $gameLists, 'nowDate' => $nowDate, 'position' => 1]);?></td>
                <td><?= $this->element('game_display', ['gameLists' => $gameLists, 'nowDate' => $nowDate, 'position' => 2]);?></td>
            </tr>
    <?php $nowDate = $nowDate->addDay();?>
    <?php if ($nowDate > $maxDate) break;?>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
