現在：<?= $targetInfo->player->name;?>(<?= $positionLists[$targetInfo->position];?>)
変更：
<?= $this->Form->create();?>
<?= $this->Form->input('position', ['type' => 'select', 'options' => $positionLists, 'value' => $targetInfo->position]);?>
<?= $this->Form->input('player_id', ['type' => 'select', 'options' => $playerLists, 'value' => $targetInfo->player->id]);?>
<?= $this->Form->submit('変更');?>
<?= $this->Form->end();?>
<br /><br />
<?= $this->Html->link('盗塁成功', ['controller' => 'games','action' =>'torui' ,'result' => 'true', 'game_id' => $game_id, 'team_id' => $team_id,'pitcher_id' => $pitcher_id,'player_id' => $targetInfo->player->id]);?>
<br /><br />
<?= $this->Html->link('盗塁失敗', ['controller' => 'games','action' =>'torui' ,'result' => 'false', 'game_id' => $game_id, 'team_id' => $team_id,'pitcher_id' => $pitcher_id,'player_id' => $targetInfo->player->id]);?>
