    <td nowrap><?= $player->team->ryaku_name;?></td>
    <td style="padding:0;" nowrap>
<div style="width:35px;"></div>
        <?php if (file_exists(ROOT . '/webroot/img/base_player/' . $player->base_player_id . '/file')):?>
            <?= $this->Html->image('base_player/' . $player->base_player_id . '/file', ['style' => 'height:35px;width:35px;display:inline;', 'height' => 35, 'width' => 35]);?>
        <?php endif;?>
    </td>
    <td class="player_box_td" nowrap>
        <?= $this->element('player_block', ['player' => $player]);?>
    </td>
