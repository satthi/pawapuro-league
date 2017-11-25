<div class="players index columns content">
    <h3><?= __('Cards') ?></h3>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <thead>
            <tr>
                <th style="width:150px;"><?= $this->Paginator->sort('name') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
            <tr>
                <td class="player_box_td">
                    <?= $this->element('player_block_nameonly', ['player' => $player]);?>
                </td>
                <td><?= h($player->team->ryaku_name) ?></td>
                <td><?= h($player->no) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
