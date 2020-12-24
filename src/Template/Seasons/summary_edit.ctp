<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
	</ul>
</div>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?></h3>
    <?= $this->Form->create($season);?>
    <h4>総評</h4>
    <div><?= $this->Form->input('summary', ['type' =>'textarea', 'label' => false]);?></div>

    <h4>各種タイトル</h4>
    <table cellpadding="0" cellspacing="0" style="width:auto;">
        <tr>
            <th>MVP</th>
            <td><?= $this->Form->input('mvp_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 投手</th>
            <td><?= $this->Form->input('b9_p_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 捕手</th>
            <td><?= $this->Form->input('b9_c_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 一塁手</th>
            <td><?= $this->Form->input('b9_1b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 二塁手</th>
            <td><?= $this->Form->input('b9_2b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 三塁手</th>
            <td><?= $this->Form->input('b9_3b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 遊撃手</th>
            <td><?= $this->Form->input('b9_ss_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 外野手1</th>
            <td><?= $this->Form->input('b9_of1_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 外野手2</th>
            <td><?= $this->Form->input('b9_of2_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>B9 外野手3</th>
            <td><?= $this->Form->input('b9_of3_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 投手</th>
            <td><?= $this->Form->input('gg_p_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 捕手</th>
            <td><?= $this->Form->input('gg_c_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 一塁手</th>
            <td><?= $this->Form->input('gg_1b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 二塁手</th>
            <td><?= $this->Form->input('gg_2b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 三塁手</th>
            <td><?= $this->Form->input('gg_3b_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 遊撃手</th>
            <td><?= $this->Form->input('gg_ss_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 外野手1</th>
            <td><?= $this->Form->input('gg_of1_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 外野手2</th>
            <td><?= $this->Form->input('gg_of2_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
        <tr>
            <th>GG 外野手3</th>
            <td><?= $this->Form->input('gg_of3_player_id', ['type' =>'select', 'options' => $players, 'label' => false]);?></td>
        </tr>
    </table>
    <?= $this->Form->submit('submit');?>
    <?= $this->Form->end();?>
</div>