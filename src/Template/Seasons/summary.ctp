<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
	</ul>
</div>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?></h3>
    <?php foreach ($seasons as $seasonPart): ?>
        <?= $this->Html->link($seasonPart->short_name, ['action' => 'summary', $seasonPart->id]);?>&nbsp;
    <?php endforeach;?>
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
            <?= $this->element('season_title_player_name', ['player' => $season->mvp]);?>
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
        <div style="position:relative;">
            <?= $this->Html->image('ground.png', ['width' => 600]);?>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9p]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:440px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9c]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:420px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b91b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:180px;left:350px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b92b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:60px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b93b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:180px;left:130px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9ss]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:120px;left:90px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9of1]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:60px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9of2]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:120px;left:390px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->b9of3]);?>
                </tr>
            </table>
        </div>
    </div>


    <div class="tab_body" data-type="3">
        <div style="position:relative;">
            <?= $this->Html->image('ground.png', ['width' => 600]);?>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggp]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:440px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggc]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:420px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->gg1b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:180px;left:350px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->gg2b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:270px;left:60px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->gg3b]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:180px;left:130px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggss]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:120px;left:90px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggof1]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:60px;left:240px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggof2]);?>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:auto;height:35px;position:absolute;top:120px;left:390px;">
                <tr>
                    <?= $this->element('season_title_player_name', ['player' => $season->ggof3]);?>
                </tr>
            </table>
        </div>
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
