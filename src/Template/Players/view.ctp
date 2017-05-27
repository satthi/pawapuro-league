<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Player'), ['action' => 'edit', $player->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Player'), ['action' => 'delete', $player->id], ['confirm' => __('Are you sure you want to delete # {0}?', $player->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Players'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Player'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Game Members'), ['controller' => 'GameMembers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Game Member'), ['controller' => 'GameMembers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="players view large-9 medium-8 columns content">
    <h3><?= h($player->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th>打率</th>
            <th>HR</th>
            <th>打点</th>
            <th>打席</th>
            <th>打数</th>
            <th>安打</th>
            <th>2塁打</th>
            <th>3塁打</th>
            <th>四球</th>
            <th>死球</th>
            <th>出塁率</th>
            <th>犠打</th>
            <th>犠飛</th>
            <th>三振</th>
            <th>併殺</th>
            <th>盗塁</th>
        </tr>
        <tr>
            <td><?= $player->avg; ?></td>
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
            <td><?= $this->Number->format($player->bant) ?></td>
            <td><?= $this->Number->format($player->sacrifice_fly) ?></td>
            <td><?= $this->Number->format($player->sansin) ?></td>
            <td><?= $this->Number->format($player->heisatsu) ?></td>
            <td><?= $this->Number->format($player->steal) ?></td>
        </tr>
    </table>
    <h4>投手</h4>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
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
            <tr>
                <td>
		        <?php if (!empty($player->inning)):?>
		        <?= sprintf('%0.2f', $player->jiseki / ($player->inning / 27)); ?>
				<?php else:?>
		        -
		        <?php endif;?>
        </td>
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
                <td><?php
                if ($player->inning > 0) {
             echo sprintf('%0.2f', round($player->get_sansin / $player->inning * 27, 2));
        } else {
        echo '-';
        }
            ?></td>
            </tr>
        </tbody>
    </table>
    
    
    <h4>月間打撃成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">年月</th>
                <th scope="col">打率</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($monthBatterInfos as $monthBatterInfo):?>
        <tr>
            <td><?= $monthBatterInfo->year;?>/<?= $monthBatterInfo->month;?></td>
            <td><?php if ($monthBatterInfo->dasu > 0) echo sprintf('%0.3f', round($monthBatterInfo->hit / $monthBatterInfo->dasu, 3));?></td>
            <td><?= $monthBatterInfo->daseki;?></td>
            <td><?= $monthBatterInfo->dasu;?></td>
            <td><?= $monthBatterInfo->hit;?></td>
            <td><?= $monthBatterInfo->hr;?></td>
            <td><?= $monthBatterInfo->rbi;?></td>
        </tr>
        <?php endforeach;?>
     </table>
    
    <h4>対チーム対戦成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">チーム</th>
                <th scope="col">打率</th>
                <th scope="col">試合</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
                <th scope="col">盗塁</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsTeamBatterInfos as $vsTeamBatterInfo):?>
        <tr>
            <td><?= $vsTeamBatterInfo->pitcher->team->ryaku_name;?></td>
            <td><?php if ($vsTeamBatterInfo->dasu > 0) echo sprintf('%0.3f', round($vsTeamBatterInfo->hit / $vsTeamBatterInfo->dasu, 3));?></td>
            <td><?= $vsTeamBatterInfo->game;?></td>
            <td><?= $vsTeamBatterInfo->daseki;?></td>
            <td><?= $vsTeamBatterInfo->dasu;?></td>
            <td><?= $vsTeamBatterInfo->hit;?></td>
            <td><?= $vsTeamBatterInfo->hr;?></td>
            <td><?= $vsTeamBatterInfo->rbi;?></td>
            <td><?= $vsTeamBatterInfo->steal;?></td>
        </tr>
        <?php endforeach;?>
     </table>
    
    <h4>対投手対戦成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">チーム</th>
                <th scope="col">名前</th>
                <th scope="col">打率</th>
                <th scope="col">試合</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsPitcherBatterInfos as $vsPitcherBatterInfo):?>
        <tr>
            <td><?= $vsPitcherBatterInfo->pitcher->team->ryaku_name;?></td>
            <td><?= $vsPitcherBatterInfo->pitcher->name;?></td>
            <td><?php if ($vsPitcherBatterInfo->dasu > 0) echo sprintf('%0.3f', round($vsPitcherBatterInfo->hit / $vsPitcherBatterInfo->dasu, 3));?></td>
            <td><?= $vsPitcherBatterInfo->game;?></td>
            <td><?= $vsPitcherBatterInfo->daseki;?></td>
            <td><?= $vsPitcherBatterInfo->dasu;?></td>
            <td><?= $vsPitcherBatterInfo->hit;?></td>
            <td><?= $vsPitcherBatterInfo->hr;?></td>
            <td><?= $vsPitcherBatterInfo->rbi;?></td>
        </tr>
        <?php endforeach;?>
     </table>
    


    <h4>対左右対戦成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">打率</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsHandBatterInfos as $vsHandBatterInfo):?>
        <tr>
            <td>
                <?php if ($vsHandBatterInfo->pitcher->throw == 1):?>
                    対右
                <?php else:?>
                    対左
               <?php endif;?>
            </td>
            <td><?= sprintf('%0.3f', round($vsHandBatterInfo->hit / $vsHandBatterInfo->dasu, 3));?></td>
            <td><?= $vsHandBatterInfo->daseki;?></td>
            <td><?= $vsHandBatterInfo->dasu;?></td>
            <td><?= $vsHandBatterInfo->hit;?></td>
            <td><?= $vsHandBatterInfo->hr;?></td>
            <td><?= $vsHandBatterInfo->rbi;?></td>
        </tr>
        <?php endforeach;?>
     </table>
    
    <h4>打撃履歴</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">日付</th>
                <th scope="col">対戦相手</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
                <th scope="col">盗塁</th>
                <th scope="col">結果</th>
                <th scope="col">打率</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $totalDasu = 0;
            $totalHit = 0;
            $totalHr = 0;
            $totalRbi = 0;
        ?>
        <?php foreach ($batterResultSets as $gameId => $batterResultSet):?>
        	<?php 
        	$totalDasu += $batterResultSet['dasu'];
        	$totalHit += $batterResultSet['hit'];
        	$totalHr += $batterResultSet['hr'];
        	$totalRbi += $batterResultSet['rbi'];
        ?>
            <tr>
                <td><?= $this->Html->link($batterResultSet['date']->format('m/d'), ['controller' => 'games', 'action' => 'play', $gameId]);?></td>
                <td><?= $batterResultSet['vsTeam'];?></td>
                <td><?= $batterResultSet['daseki'];?></td>
                <td><?= $batterResultSet['dasu'];?></td>
                <td><?= $batterResultSet['hit'];?></td>
                <td><?= $batterResultSet['hr'];?></td>
                <td><?= $batterResultSet['rbi'];?></td>
                <td><?= $batterResultSet['steal'];?></td>
                <td><?= implode(',', $batterResultSet['results']);?></td>
                <td>
                    <?php if ($totalDasu == 0) {
                    echo '0.000';
                    } else {
                        echo sprintf('%0.3f', round($totalHit / $totalDasu, 3));
                    }?>
                </td>
                <td><?= $totalHr;?></td>
                <td><?= $totalRbi;?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
     
    <?php if(!$monthPitcherInfos->isEmpty()):?>
    <h4>月間投手成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">年月</th>
                <th scope="col">試合</th>
                <th scope="col">勝</th>
                <th scope="col">負</th>
                <th scope="col">ホールド</th>
                <th scope="col">セーブ</th>
                <th scope="col">イニング</th>
                <th scope="col">自責点</th>
                <th scope="col">防御率</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($monthPitcherInfos as $monthPitcherInfo):?>
        <tr>
            <td><?= $monthPitcherInfo->year;?>/<?= $monthPitcherInfo->month;?></td>
            <td><?= $monthPitcherInfo->game;?></td>
            <td><?= $monthPitcherInfo->win;?></td>
            <td><?= $monthPitcherInfo->lose;?></td>
            <td><?= $monthPitcherInfo->hold;?></td>
            <td><?= $monthPitcherInfo->save;?></td>
            <td><?= ceil($monthPitcherInfo->inning / 3) . ' ' . $monthPitcherInfo->inning % 3 . '/ 3';?></td>
            <td><?= $monthPitcherInfo->jiseki;?></td>
                <td><?php
                if ($monthPitcherInfo->inning > 0) {
             echo sprintf('%0.2f', round($monthPitcherInfo->jiseki / $monthPitcherInfo->inning * 27, 2));
        } else {
        echo '-';
        }
            ?></td>
        </tr>
        <?php endforeach;?>
     </table>
     <?php endif;?>
     
    <?php if(!$vsTeamPitcherInfos->isEmpty()):?>
    <h4>対チーム投手成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">チーム</th>
                <th scope="col">試合</th>
                <th scope="col">勝</th>
                <th scope="col">負</th>
                <th scope="col">ホールド</th>
                <th scope="col">セーブ</th>
                <th scope="col">イニング</th>
                <th scope="col">自責点</th>
                <th scope="col">防御率</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsTeamPitcherInfos as $vsTeamPitcherInfo):?>
        <tr>
            <td><?= $vsTeamPitcherInfo->team;?></td>
            <td><?= (int) $vsTeamPitcherInfo->game_count;?></td>
            <td><?= (int) $vsTeamPitcherInfo->win_count;?></td>
            <td><?= (int) $vsTeamPitcherInfo->lose_count;?></td>
            <td><?= (int) $vsTeamPitcherInfo->hold_count;?></td>
            <td><?= (int) $vsTeamPitcherInfo->save_count;?></td>
            <td><?= ceil($vsTeamPitcherInfo->inning_count / 3) . ' ' . $vsTeamPitcherInfo->inning_count % 3 . '/ 3';?></td>
            <td><?= $vsTeamPitcherInfo->jiseki_count;?></td>
                <td><?php
                if ($vsTeamPitcherInfo->inning_count > 0) {
             echo sprintf('%0.2f', round($vsTeamPitcherInfo->jiseki_count / $vsTeamPitcherInfo->inning_count * 27, 2));
        } else {
        echo '-';
        }
            ?></td>
        </tr>
        <?php endforeach;?>
     </table>
     <?php endif;?>
     
    <?php if(!$vsHandPitcherInfos->isEmpty()):?>
    <h4>対左右対戦成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">打率</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsHandPitcherInfos as $vsHandPitcherInfo):?>
        <tr>
            <td>
                <?php if ($vsHandPitcherInfo->bat == 1):?>
                    対右
                <?php else:?>
                    対左
               <?php endif;?>
            </td>
            <td><?= sprintf('%0.3f', round($vsHandPitcherInfo->hit / $vsHandPitcherInfo->dasu, 3));?></td>
            <td><?= $vsHandPitcherInfo->daseki;?></td>
            <td><?= $vsHandPitcherInfo->dasu;?></td>
            <td><?= $vsHandPitcherInfo->hit;?></td>
            <td><?= $vsHandPitcherInfo->hr;?></td>
        </tr>
        <?php endforeach;?>
     </table>
     <?php endif;?>

    <h4>対投手対戦成績</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col">チーム</th>
                <th scope="col">名前</th>
                <th scope="col">打率</th>
                <th scope="col">試合</th>
                <th scope="col">打席</th>
                <th scope="col">打数</th>
                <th scope="col">安打</th>
                <th scope="col">HR</th>
                <th scope="col">打点</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vsBatterPitcherInfos as $vsBatterPitcherInfo):?>
        <tr>
            <td><?= $vsBatterPitcherInfo->batter->team->ryaku_name;?></td>
            <td><?= $vsBatterPitcherInfo->batter->name;?></td>
            <td><?php if ($vsBatterPitcherInfo->dasu > 0) echo sprintf('%0.3f', round($vsBatterPitcherInfo->hit / $vsBatterPitcherInfo->dasu, 3));?></td>
            <td><?= $vsBatterPitcherInfo->game;?></td>
            <td><?= $vsBatterPitcherInfo->daseki;?></td>
            <td><?= $vsBatterPitcherInfo->dasu;?></td>
            <td><?= $vsBatterPitcherInfo->hit;?></td>
            <td><?= $vsBatterPitcherInfo->hr;?></td>
            <td><?= $vsBatterPitcherInfo->rbi;?></td>
        </tr>
        <?php endforeach;?>
     </table>

     
    <?php if(!empty($pitcherResultSets)):?>
    <h4>投球履歴</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">日付</th>
                <th scope="col">対戦相手</th>
                <th scope="col">イニング</th>
                <th scope="col">被安打</th>
                <th scope="col">被本塁打</th>
                <th scope="col">自責点</th>
                <th scope="col">奪三振</th>
                <th scope="col">通算防御率</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $totalInning = 0;
            $totalJiseki = 0;
        ?>
        <?php foreach ($pitcherResultSets as $gameId => $pitcherResultSet):?>
        	<?php 
        	$totalInning += $pitcherResultSet['inning'];
        	$totalJiseki += $pitcherResultSet['jiseki'];
        ?>
            <tr>
                <td><?= $pitcherResultSet['result'];?></td>
                <td><?= $this->Html->link($pitcherResultSet['date']->format('m/d'), ['controller' => 'games', 'action' => 'play', $gameId]);?></td>
                <td><?= $pitcherResultSet['vsTeam'];?></td>
                <td><?= floor($pitcherResultSet['inning'] / 3);?>
        		<?php if ($pitcherResultSet['inning'] % 3 != 0) :?>
        			<?= $pitcherResultSet['inning'] % 3 . '/3'?>
        		<?php endif;?>
        		</td>
                <td><?= $pitcherResultSet['hit'];?></td>
                <td><?= $pitcherResultSet['hr'];?></td>
                <td><?= $pitcherResultSet['jiseki'];?></td>
                <td><?= $pitcherResultSet['sansin'];?></td>
                <td>
                    <?php if ($totalInning == 0) {
                    echo '-';
                    } else {
                        echo sprintf('%0.2f', round($totalJiseki / $totalInning * 27, 2));
                    }?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php endif;?>
    
</div>
<style type="text/css">
<!--

.type_1{
	color:red;
}
-->
</style>
