<tr>
    <th nowrap><?= $title;?></th>
     <td nowrap>
         <?= $kings->all()->first()->{$field};?><?= $tani;?>
     </td>
    <?php foreach ($kings as $king) :?>
        <?= $this->element('season_title_player_name', ['player' => $king]);?>
     <?php endforeach;?>
</tr>
