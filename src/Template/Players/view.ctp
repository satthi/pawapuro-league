<div class="players view columns content">
    <div class="clearfix">
        <h3 style="float:left"><?= h($player->name) ?></h3>
        <?php if (file_exists(ROOT . '/webroot/img/base_player/' . $player->base_player_id . '/file')):?>
            <?= $this->Html->image('base_player/' . $player->base_player_id . '/file', ['style' => 'float:right;']);?>
        <?php endif;?>
    </div>
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
                <th scope="col">四球</th>
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
                <td><?= $this->Number->format($player->p_walk) ?></td>
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
                <th scope="col">出場</th>
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
                <td><?= $batterResultSet['start_result'];?></td>
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
    
    <h3>歴代成績</h3>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <tr>
            <th nowrap>シーズン</th>
            <th nowrap>チーム</th>
            <th nowrap>背番号</th>
            <th nowrap>試合</th>
            <th nowrap>打率</th>
            <th nowrap>HR</th>
            <th nowrap>打点</th>
            <th nowrap>打席</th>
            <th nowrap>打数</th>
            <th nowrap>安打</th>
            <th nowrap>2塁打</th>
            <th nowrap>3塁打</th>
            <th nowrap>四球</th>
            <th nowrap>死球</th>
            <th nowrap>出塁率</th>
            <th nowrap>犠打</th>
            <th nowrap>犠飛</th>
            <th nowrap>三振</th>
            <th nowrap>併殺</th>
            <th nowrap>盗塁</th>
            <th></th>
        </tr>
        <?php $total = [
            'hr' => 0,
            'rbi' => 0,
            'daseki' => 0,
            'dasu' => 0,
            'hit' => 0,
            'base2' => 0,
            'base3' => 0,
            'walk' => 0,
            'deadball' => 0,
            'bant' => 0,
            'sacrifice_fly' => 0,
            'sansin' => 0,
            'heisatsu' => 0,
            'steal' => 0,
        ];?>
        <?php foreach ($histories as $history):?>
        <tr>
            <td><?= $this->Html->link($history->team->season->name, ['action' => 'view', $history->id]); ?></td>
            <td><?= $history->team->ryaku_name; ?></td>
            <td nowrap><?= $history->no; ?></td>
            <td nowrap><?= $history->yashu_game; ?></td>
            <td nowrap <?= $history->avg_top_check;?>>
                <?= preg_replace('/^0/', '', sprintf('%0.3f', $history->avg)); ?>
                <?= $this->Player->displayRank($history->avg_rank, $history->daseki >= $history->team->game * 3.1);?>
            </td>
            <td nowrap <?= $history->hr_top_check;?>>
                <?= $this->Number->format($history->hr) ?>
                <?= $this->Player->displayRank($history->hr_rank);?>
            </td>
            <td nowrap <?= $history->rbi_top_check;?>>
                <?= $this->Number->format($history->rbi) ?>
                <?= $this->Player->displayRank($history->rbi_rank);?>
            </td>
            <td nowrap <?= $history->daseki_top_check;?>><?= $this->Number->format($history->daseki) ?></td>
            <td nowrap <?= $history->dasu_top_check;?>><?= $this->Number->format($history->dasu) ?></td>
            <td nowrap <?= $history->hit_top_check;?>>
                <?= $this->Number->format($history->hit) ?>
                <?= $this->Player->displayRank($history->hit_rank);?>
            </td>
            <td nowrap <?= $history->base2_top_check;?>>
                <?= $this->Number->format($history->base2) ?>
                <?= $this->Player->displayRank($history->base2_rank);?>
            </td>
            <td nowrap <?= $history->base3_top_check;?>>
                <?= $this->Number->format($history->base3) ?>
                <?= $this->Player->displayRank($history->base3_rank);?>
            </td>
            <td nowrap <?= $history->walk_top_check;?>><?= $this->Number->format($history->walk) ?></td>
            <td nowrap <?= $history->deadball_top_check;?>><?= $this->Number->format($history->deadball) ?></td>
            <td nowrap><?= $history->obp; ?></td>
            <td nowrap <?= $history->bant_top_check;?>><?= $this->Number->format($history->bant) ?></td>
            <td nowrap <?= $history->sacrifice_fly_top_check;?>><?= $this->Number->format($history->sacrifice_fly) ?></td>
            <td nowrap <?= $history->sansin_top_check;?>><?= $this->Number->format($history->sansin) ?></td>
            <td nowrap <?= $history->heisatsu_top_check;?>><?= $this->Number->format($history->heisatsu) ?></td>
            <td nowrap <?= $history->steal_top_check;?>>
                <?= $this->Number->format($history->steal) ?>
                <?= $this->Player->displayRank($history->steal_rank);?>
            </td>
            <td nowrap>
                <?php if ($history->team->season->mvp_player_id == $history->id) :?>
                    MVP
                <?php endif;?>
                <?php if (
                    $history->team->season->b9_p_player_id == $history->id ||
                    $history->team->season->b9_c_player_id == $history->id ||
                    $history->team->season->b9_1b_player_id == $history->id ||
                    $history->team->season->b9_2b_player_id == $history->id ||
                    $history->team->season->b9_3b_player_id == $history->id ||
                    $history->team->season->b9_ss_player_id == $history->id ||
                    $history->team->season->b9_of1_player_id == $history->id ||
                    $history->team->season->b9_of2_player_id == $history->id ||
                    $history->team->season->b9_of3_player_id == $history->id
                 ) :?>
                    B9
                <?php endif;?>
                <?php if (
                    $history->team->season->gg_p_player_id == $history->id ||
                    $history->team->season->gg_c_player_id == $history->id ||
                    $history->team->season->gg_1b_player_id == $history->id ||
                    $history->team->season->gg_2b_player_id == $history->id ||
                    $history->team->season->gg_3b_player_id == $history->id ||
                    $history->team->season->gg_ss_player_id == $history->id ||
                    $history->team->season->gg_of1_player_id == $history->id ||
                    $history->team->season->gg_of2_player_id == $history->id ||
                    $history->team->season->gg_of3_player_id == $history->id
                 ) :?>
                    GG
                <?php endif;?>
            </td>
        </tr>
            <?php if ($history->team->season->regular_flag):?>
        <?php $total = [
            'hr' => $total['hr'] + $history->hr,
            'rbi' => $total['rbi'] + $history->rbi,
            'daseki' => $total['daseki'] + $history->daseki,
            'dasu' => $total['dasu'] + $history->dasu,
            'hit' => $total['hit'] + $history->hit,
            'base2' => $total['base2'] + $history->base2,
            'base3' => $total['base3'] + $history->base3,
            'walk' => $total['walk'] + $history->walk,
            'deadball' => $total['deadball'] + $history->deadball,
            'bant' => $total['bant'] + $history->bant,
            'sacrifice_fly' => $total['sacrifice_fly'] + $history->sacrifice_fly,
            'sansin' => $total['sansin'] + $history->sansin,
            'heisatsu' => $total['heisatsu'] + $history->heisatsu,
            'steal' => $total['steal'] + $history->steal,
        ];?>
        <?php endif;?>
        <?php endforeach;?>
        <tr>
            <td colspan="4">シーズン合計</td>
                <td nowrap>
		        <?php if (!empty($total['dasu'])):?>
		        <?= preg_replace('/^0/', '', sprintf('%0.3f', $total['hit'] / $total['dasu'])); ?>
				<?php else:?>
		        -
		        <?php endif;?>
        </td>
            <td nowrap><?= $this->Number->format($total['hr']) ?></td>
            <td nowrap><?= $this->Number->format($total['rbi']) ?></td>
            <td nowrap><?= $this->Number->format($total['daseki']) ?></td>
            <td nowrap><?= $this->Number->format($total['dasu']) ?></td>
            <td nowrap><?= $this->Number->format($total['hit']) ?></td>
            <td nowrap><?= $this->Number->format($total['base2']) ?></td>
            <td nowrap><?= $this->Number->format($total['base3']) ?></td>
            <td nowrap><?= $this->Number->format($total['walk']) ?></td>
            <td nowrap><?= $this->Number->format($total['deadball']) ?></td>
                <td>
		        <?php if (!empty($total['dasu'] + $total['walk'] + $total['deadball'] + $total['sacrifice_fly'])):?>
		        <?= preg_replace('/^0/', '', sprintf('%0.3f', ($total['hit'] + $total['walk'] + $total['deadball']) / ($total['dasu'] + $total['walk'] + $total['deadball'] + $total['sacrifice_fly']))); ?>
				<?php else:?>
		        -
		        <?php endif;?>
        </td>
            <td nowrap><?= $this->Number->format($total['bant']) ?></td>
            <td nowrap><?= $this->Number->format($total['sacrifice_fly']) ?></td>
            <td nowrap><?= $this->Number->format($total['sansin']) ?></td>
            <td nowrap><?= $this->Number->format($total['heisatsu']) ?></td>
            <td nowrap><?= $this->Number->format($total['steal']) ?></td>
            <td></td>
        </tr>
    </table>
    <h4>投手</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th nowrap>シーズン</th>
                <th nowrap>チーム</th>
                <th nowrap>背番号</th>
                <th nowrap scope="col"><?= $this->Paginator->sort('bougyo') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('inning') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('jiseki') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('game') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('win') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('lose') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('win_rate') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('hold') ?></th>
                <th nowrap scope="col"><?= $this->Paginator->sort('save') ?></th>
                <th nowrap scope="col">完投</th>
                <th nowrap scope="col">完封</th>
                <th nowrap scope="col">被安打</th>
                <th nowrap scope="col">被打率</th>
                <th nowrap scope="col">被四球</th>
                <th nowrap scope="col">被本塁打</th>
                <th nowrap scope="col">奪三振</th>
                <th nowrap scope="col">奪三振率</th>
            </tr>
        </thead>
        <tbody>
        <?php $total = [
            'inning' => 0,
            'jiseki' => 0,
            'game' => 0,
            'win' => 0,
            'lose' => 0,
            'hold' => 0,
            'save' => 0,
            'kanto' => 0,
            'kanpu' => 0,
            'p_hit' => 0,
            'p_dasu' => 0,
            'p_hr' => 0,
            'p_walk' => 0,
            'get_sansin' => 0,
        ];?>
        <?php foreach ($histories as $history):?>
            <tr>
                <td><?= $this->Html->link($history->team->season->name, ['action' => 'view', $history->id]); ?></td>
                <td nowrap><?= $history->team->ryaku_name; ?></td>
                <td nowrap><?= $history->no; ?></td>
                <td nowrap <?= $history->era_top_check;?>>
		        <?php if (!empty($history->inning)):?>
		        <?= sprintf('%0.2f', $history->jiseki / ($history->inning / 27)); ?>
				<?php else:?>
		        -
		        <?php endif;?>
                    <?= $this->Player->displayRank($history->era_rank, $history->inning >= $history->team->game * 3);?>

        </td>
                <td nowrap <?= $history->inning_top_check;?>><?= floor($history->inning / 3);?>
        		<?php if ($history->inning % 3 != 0) :?>
        			<?= $history->inning % 3 . '/3'?>
        		<?php endif;?>
        		</td>
                <td nowrap <?= $history->jiseki_top_check;?>><?= $this->Number->format($history->jiseki) ?></td>
                <td nowrap <?= $history->game_top_check;?>><?= $this->Number->format($history->game) ?></td>
                <td nowrap <?= $history->win_top_check;?>>
                    <?= $this->Number->format($history->win) ?>
                    <?= $this->Player->displayRank($history->win_rank);?>
                </td>
                <td nowrap <?= $history->lose_top_check;?>><?= $this->Number->format($history->lose) ?></td>
                <td nowrap <?= $history->win_ratio_top_check;?>>
                    <?= $this->Number->format($history->win_ratio) ?>
                    <?= $this->Player->displayRank($history->win_ratio_rank, $history->win >= 13);?>
                </td>
                <td nowrap <?= $history->hold_top_check;?>>
                    <?= $this->Number->format($history->hold) ?>
                    <?= $this->Player->displayRank($history->hold_rank);?>
                </td>
                <td nowrap <?= $history->save_top_check;?>>
                    <?= $this->Number->format($history->save) ?>
                    <?= $this->Player->displayRank($history->save_rank);?>
                </td>
                <td nowrap <?= $history->kanto_top_check;?>><?= $this->Number->format($history->kanto) ?></td>
                <td nowrap <?= $history->kanpu_top_check;?>><?= $this->Number->format($history->kanpu) ?></td>
                <td nowrap <?= $history->p_hit_top_check;?>><?= $this->Number->format($history->p_hit) ?></td>
                <td nowrap><?= $history->p_avg ?></td>
                <td nowrap <?= $history->p_walk_top_check;?>><?= $this->Number->format($history->p_walk) ?></td>
                <td nowrap <?= $history->p_hr_top_check;?>><?= $this->Number->format($history->p_hr) ?></td>
                <td nowrap <?= $history->get_sansin_top_check;?>>
                    <?= $this->Number->format($history->get_sansin) ?>
                    <?= $this->Player->displayRank($history->get_sansin_rank);?>
                </td>
                <td nowrap><?php
                if ($history->inning > 0) {
             echo sprintf('%0.2f', round($history->get_sansin / $history->inning * 27, 2));
        } else {
        echo '-';
        }
            ?></td>
            </tr>
            <?php if ($history->team->season->regular_flag):?>
        <?php $total = [
            'inning' => $total['inning'] + $history->inning,
            'jiseki' => $total['jiseki'] + $history->jiseki,
            'game' => $total['game'] + $history->game,
            'win' => $total['win'] + $history->win,
            'lose' => $total['lose'] + $history->lose,
            'hold' => $total['hold'] + $history->hold,
            'save' => $total['save'] + $history->save,
            'p_hit' => $total['p_hit'] + $history->p_hit,
            'p_dasu' => $total['p_dasu'] + $history->p_dasu,
            'p_walk' => $total['p_walk'] + $history->p_walk,
            'p_hr' => $total['p_hr'] + $history->p_hr,
            'kanto' => $total['kanto'] + $history->kanto,
            'kanpu' => $total['kanpu'] + $history->kanpu,
            'get_sansin' => $total['get_sansin'] + $history->get_sansin,
        ];?>
        <?php endif;?>
        <?php endforeach;?>
            <tr>
                <td colspan="3">シーズン合計</td>
                <td nowrap>
		        <?php if (!empty($total['inning'])):?>
		        <?= sprintf('%0.2f', $total['jiseki'] / ($total['inning'] / 27)); ?>
				<?php else:?>
		        -
		        <?php endif;?>
        </td>
                <td nowrap><?= floor($total['inning'] / 3);?>
        		<?php if ($total['inning'] % 3 != 0) :?>
        			<?= $total['inning'] % 3 . '/3'?>
        		<?php endif;?>
        		</td>
                <td nowrap><?= $this->Number->format($total['jiseki']) ?></td>
                <td nowrap><?= $this->Number->format($total['game']) ?></td>
                <td nowrap><?= $this->Number->format($total['win']) ?></td>
                <td nowrap><?= $this->Number->format($total['lose']) ?></td>
                <td nowrap><?= $total['win'] == 0 ? '.000' : preg_replace('/^0/', '', sprintf('%0.3f', round($total['win'] / ($total['win'] + $total['lose']), 3)));?></td>
                <td nowrap><?= $this->Number->format($total['hold']) ?></td>
                <td nowrap><?= $this->Number->format($total['save']) ?></td>
                <td nowrap><?= $this->Number->format($total['kanto']) ?></td>
                <td nowrap><?= $this->Number->format($total['kanpu']) ?></td>
                <td nowrap><?= $this->Number->format($total['p_hit']) ?></td>
                <td nowrap>
		        <?php if (!empty($total['p_dasu'])):?>
		        <?= preg_replace('/^0/', '', sprintf('%0.3f', $total['p_hit'] / $total['p_dasu'])); ?>
				<?php else:?>
		        -
		        <?php endif;?>
        </td>
                <td nowrap><?= $this->Number->format($total['p_walk']) ?></td>
                <td nowrap><?= $this->Number->format($total['p_hr']) ?></td>
                <td nowrap><?= $this->Number->format($total['get_sansin']) ?></td>
                <td nowrap><?php
                if ($total['inning'] > 0) {
             echo sprintf('%0.2f', round($total['get_sansin'] / $total['inning'] * 27, 2));
        } else {
        echo '-';
        }
            ?></td>
            </tr>
        </tbody>
    </table>

    
</div>
<style type="text/css">
<!--

.type_1{
	color:red;
}
-->
</style>
