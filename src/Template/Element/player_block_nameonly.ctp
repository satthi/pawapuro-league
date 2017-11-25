<?php 
if (empty($nolink)) {
    $nolink =false;
}
// F‚Ì”»’è—p
$positionInfo = [
    'main' => null,
    'sub' => []
];
$positionLists = [
    'p',
    'c',
    'i',
    'o',
];
foreach ($positionLists as $positionList) {
    if ($player->{'type_' . $positionList} == 2) {
        $positionInfo['main'] = $positionList;
    } elseif ($player->{'type_' . $positionList} == 1) {
        $positionInfo['sub'][] = $positionList;
    }
}
$positionCount = count($positionInfo['sub']) + 1;
?>

<?php //ƒƒCƒ“‚ªˆê”Ô¶?>
<div class="player_background player_background_left<?php if ($positionCount == 1) echo ' player_background_right';?> color_<?= $positionInfo['main'];?>" style="width:<?= 100 / $positionCount;?>%;left:0;"></div>
<?php foreach ($positionInfo['sub'] as $subCount => $subKey) :?>
<div class="player_background<?php if ($positionCount == $subCount + 2) echo ' player_background_right';?> color_<?= $subKey;?>" style="width:<?= 100 / $positionCount;?>%;left:<?= 100 / $positionCount * ($subCount + 1);?>%;"></div>
<?php endforeach;?>
<div class="player_namebox"

>
<?= $this->Html->link($player->name_short, ['controller' => 'cards', 'action' => 'view', $player->team->ryaku_name,$player->no]); ?>
</div>
	
