<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
	</ul>
</div>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?></h3>

    <h4>総評</h4>
    <div><?= nl2br($season->summary);?></div>

    <h4>各種タイトル</h4>
    <?= $this->Html->link('タイトル登録', ['controller' => 'seasons', 'action' => 'summary_edit', $season->id]);?><br />
    <button class="tab" data-type="1">主要タイトル</button>
    <button class="tab" data-type="2">ベストナイン</button>
    <button class="tab" data-type="3">ゴールデングラブ</button><br />
    <?php $a = 3;?>
    <?php foreach ($teams as $team) :?>
    <?php $a++;?>
    <button class="tab" data-type="<?= $a;?>"><?= $team->ryaku_name;?></button>
    <?php endforeach;?>

    <div class="tab_body" data-type="1">
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <tr>
            <th>MVP</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->mvp]);?>
            </td>
        </tr>
        <?= $this->element('season_title_parts', ['kings' => $avgKings, 'field' => 'display_avg', 'tani' => '', 'title' => '首位打者']);?>
        <?= $this->element('season_title_parts', ['kings' => $hrKings, 'field' => 'hr', 'tani' => '本', 'title' => 'HR王']);?>
        <?= $this->element('season_title_parts', ['kings' => $rbiKings, 'field' => 'rbi', 'tani' => '打点', 'title' => '打点王']);?>
        <?= $this->element('season_title_parts', ['kings' => $hitKings, 'field' => 'hit', 'tani' => '本', 'title' => '最多安打']);?>
        <?= $this->element('season_title_parts', ['kings' => $stealKings, 'field' => 'steal', 'tani' => '個', 'title' => '盗塁王']);?>
        <?= $this->element('season_title_parts', ['kings' => $eraKings, 'field' => 'display_era', 'tani' => '', 'title' => '最優秀防御率']);?>
        <?= $this->element('season_title_parts', ['kings' => $winKings, 'field' => 'win', 'tani' => '勝', 'title' => '最多勝']);?>
        <?= $this->element('season_title_parts', ['kings' => $winRatioKings, 'field' => 'display_win_ratio', 'tani' => '', 'title' => '最高勝率']);?>
        <?= $this->element('season_title_parts', ['kings' => $getSansinKings, 'field' => 'get_sansin', 'tani' => '個', 'title' => '最多奪三振']);?>
        <?= $this->element('season_title_parts', ['kings' => $holdKings, 'field' => 'hold', 'tani' => 'ホールド', 'title' => '最優秀中継ぎ投手']);?>
        <?= $this->element('season_title_parts', ['kings' => $saveKings, 'field' => 'save', 'tani' => 'セーブ', 'title' => '最優秀救援投手']);?>
    </table>
    </div>


    <div class="tab_body" data-type="2">
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <tr>
            <th>B9投</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9p]);?>
            </td>
            <th>B9捕</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9c]);?>
            </td>
            <th>B9一</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b91b]);?>
            </td>
        </tr>
        <tr>
            <th>B9二</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b92b]);?>
            </td>
            <th>B9三</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b93b]);?>
            </td>
            <th>B9遊</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9ss]);?>
            </td>
        </tr>
        <tr>
            <th>B9外1</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9of1]);?>
            </td>
            <th>B9外2</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9of2]);?>
            </td>
            <th>B9外3</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->b9of3]);?>
            </td>
        </tr>
    </table>
    </div>


    <div class="tab_body" data-type="3">
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <tr>
            <th>GG投</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggp]);?>
            </td>
            <th>GG捕</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggc]);?>
            </td>
            <th>GG一</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->gg1b]);?>
            </td>
        </tr>
        <tr>
            <th>GG二</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->gg2b]);?>
            </td>
            <th>GG三</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->gg3b]);?>
            </td>
            <th>GG遊</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggss]);?>
            </td>
        </tr>
        <tr>
            <th>GG外1</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggof1]);?>
            </td>
            <th>GG外2</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggof2]);?>
            </td>
            <th>GG外3</th>
            <td>-</td>
            <td class="player_box_td">
                <?= $this->element('player_block', ['player' => $season->ggof3]);?>
            </td>
        </tr>
    </table>
    </div>

    <?php $a = 3;?>
    <?php foreach ($teams as $team) :?>
    <?php $a++;?>
    <div class="tab_body" data-type="<?= $a;?>">
        <?php $dajunPlayers = $team->getBestMember();?>
        <div style="float:left;margin-right:100px;">
        <table cellpadding="0" cellspacing="0" style="width:auto;">
            <?php foreach ($dajunPlayers as $dajun => $dajunPlayerInfo) :?>
            <tr>
                <th><?= $dajun;?></th>
                <td><?= $dajunPlayerInfo['position'];?></td>
                <td class="player_box_td"><?= $this->element('player_block', ['player' => $dajunPlayerInfo['player']]);?></td>
                <td><?= $dajunPlayerInfo['player']->display_avg;?> <?= $dajunPlayerInfo['player']->hr;?>本 <?= $dajunPlayerInfo['player']->rbi;?>点 <?= $dajunPlayerInfo['player']->steal;?>盗塁</td>
            </tr>
            <?php endforeach;?>
            <tr>
                <th>9</th>
                <td>投</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        </div>
        <?php $picherInfo = $team->getMainPitchers();?>
        <div style="float:left;">
        <h5>先発</h5>
        <table cellpadding="0" cellspacing="0" style="width:auto;">
            <?php foreach ($picherInfo['starter'] as  $starterPicher) :?>
            <tr>
                <td class="player_box_td"><?= $this->element('player_block', ['player' => $starterPicher]);?></td>
                <td><?= $starterPicher->game;?>試 <?= $starterPicher->display_era;?> <?= $starterPicher->win;?>勝 <?= $starterPicher->lose;?>敗</td>
            </tr>
            <?php endforeach;?>
        </table>
        <h5>中継ぎ</h5>
        <table cellpadding="0" cellspacing="0" style="width:auto;">
            <?php foreach ($picherInfo['nakatsugi'] as  $nakatsugiPicher) :?>
            <tr>
                <td class="player_box_td"><?= $this->element('player_block', ['player' => $nakatsugiPicher]);?></td>
                <td><?= $nakatsugiPicher->game;?>試 <?= $nakatsugiPicher->display_era;?> <?= $nakatsugiPicher->win;?>勝 <?= $nakatsugiPicher->lose;?>敗 <?= $nakatsugiPicher->hold;?>H <?= $nakatsugiPicher->save;?>S</td>
            </tr>
            <?php endforeach;?>
        </table>
        </div>
    </div>
    <?php endforeach;?>



</div>



<script type="text/javascript">
$(function(){
	$('.tab_body[data-type!=1]').hide();
	$('.tab').click(function(){
		$('.tab_body').hide();
		$('.tab_body[data-type=' + $(this).data('type') + ']').show();
	});
});
</script>
