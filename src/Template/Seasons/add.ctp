<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="seasons form large-9 medium-8 columns content">
    <?= $this->Form->create($season, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Season') ?></legend>
        <?php
            echo $this->Form->input('name', ['type' => 'text']);
            echo $this->Form->input('regular_flag', ['type' => 'checkbox']);
        ?>
        チーム
        <table>
            <tr>
                <th>チーム名</th>
                <th>略称</th>
                <th>よみ</th>
                <th>英語</th>
            </tr>
            <?php for ($i = 1;$i <= 6;$i++):?>
            <tr>
                <td><?php echo $this->Form->input('Teams.' . $i . '.name', ['type' => 'text']);?></td>
                <td><?php echo $this->Form->input('Teams.' . $i . '.ryaku_name', ['type' => 'text']);?></td>
                <td><?php echo $this->Form->input('Teams.' . $i . '.yomi', ['type' => 'text']);?></td>
                <td><?php echo $this->Form->input('Teams.' . $i . '.name_eng', ['type' => 'text']);?></td>
            </tr>
            <?php endfor;?>
        </table>
        <?php
            echo $this->Form->input('player_excel', ['type' => 'file']);
        ?>
        <?php
            echo $this->Form->input('game_excel', ['type' => 'file']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
