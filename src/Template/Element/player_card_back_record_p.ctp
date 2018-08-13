<table>
    <tr>
    <th>試合数</th>
    <th>防御率</th>
    <th>勝利</th>
    <th>敗戦</th>
    <th>セーブ</th>
    <th>投球回</th>
    <th>自責点</th>
    </tr>
    <tr>
    <td><?= $player->game;?></td>
    <td><?= sprintf('%.2f', $player->era);?></td>
    <td><?= $player->win;?></td>
    <td><?= $player->lose;?></td>
    <td><?= $player->save;?></td>
    <td><?= floor($player->inning / 3) . '.' . $player->inning % 3;?></td>
    <td><?= $player->jiseki;?></td>
    </tr>
</table>