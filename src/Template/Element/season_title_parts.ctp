<tr>
    <th><?= $title;?></th>
     <td>
         <?= $kings->all()->first()->{$field};?><?= $tani;?>
     </td>
    <?php foreach ($kings as $king) :?>
        <td class="player_box_td">
            <?= $this->element('player_block', ['player' => $king]);?><br />
         </td>
     <?php endforeach;?>
</tr>
