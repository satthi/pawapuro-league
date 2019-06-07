<table>
    <tr>
    <th>試合数</th>
    <th>打率</th>
    <th>打数</th>
    <th>安打</th>
    <th>本塁打</th>
    <th>打点</th>
    <th>盗塁</th>
    </tr>
    <tr>
    <td><?= $player->yashu_game;?></td>
    <td><?= preg_replace('/^0/', '' , sprintf('%.3f', $player->avg));?></td>
    <td><?= $player->dasu;?></td>
    <td><?= $player->hit;?></td>
    <td><?= $player->hr;?></td>
    <td><?= $player->rbi;?></td>
    <td><?= $player->steal;?></td>
    </tr>
</table>