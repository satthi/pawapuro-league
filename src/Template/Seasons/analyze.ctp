<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $id]);?></li>
	</ul>
</div>

<div class="games index columns">
    <h3>スタメンメンバー解析</h3>
    <table>
        <tr>
            <th></th>
            <?php foreach ($teams as $team):?>
            <th><?= $team->ryaku_name;?></th>
            <?php endforeach;?>
        </tr>
        <?php for ($i = 1;$i <= 9;$i++):?>
        <tr>
            <td><?= $i;?>番</td>
            <?php foreach ($teams as $team):?>
            <td>
                <table style="width:auto;">
                    <?php foreach ($stamenMemberAnalyzeLists[$team->id][$i] as $stamenMemberAnalyzeList):?>
                    <tr>
                        <td<?=$stamenMemberAnalyzeList->player->player_color;?>><?= $stamenMemberAnalyzeList->player->name_short;?></td>
                        <td><?= round(($stamenMemberAnalyzeList->count / $team->game) * 100);?>%</td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </td>
            <?php endforeach;?>
        </tr>
        <?php endfor;?>
    </table>
</div>
<div class="games index columns">
    <h3>スタメンポジション解析</h3>
    <table>
        <tr>
            <th></th>
            <?php foreach ($teams as $team):?>
            <th><?= $team->ryaku_name;?></th>
            <?php endforeach;?>
    <th>total</th>
        </tr>
        <?php for ($i = 1;$i <= 9;$i++):?>
        <tr>
            <td><?= $i;?>番</td>
            <?php $totalGameCount = 0;?>
            <?php foreach ($teams as $team):?>
            <td>
                <table style="width:auto;">
                    <?php foreach ($stamenPositionAnalyzeLists[$team->id][$i] as $stamenPositionAnalyzeList):?>
                    <tr>
                        <td><?= $positionLists[$stamenPositionAnalyzeList->position];?>
                        <td><?= round(($stamenPositionAnalyzeList->count / $team->game) * 100,1);?>%</td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </td>
            <?php $totalGameCount += $team->game;?>
            <?php endforeach;?>
            <td>
                <table style="width:auto;">
                    <?php foreach ($stamenPositionTotalAnalyzeLists[$i] as $stamenPositionTotalAnalyzeList):?>
                    <tr>
                        <td><?= $positionLists[$stamenPositionTotalAnalyzeList->position];?>
                        <td><?= round(($stamenPositionTotalAnalyzeList->count / $totalGameCount) * 100, 1);?>%</td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </td>
        </tr>
        <?php endfor;?>
    </table>
</div>
	