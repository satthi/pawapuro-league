<?php
$positions = [
    'status_slider' => 'c',
    'status_hslider' => '1b',
    'status_cut' => '2b',
    'status_curb' => '3b',
    'status_scurb' => 'ss',
    'status_folk' => 'of'
];

?>

<?php foreach ($positions as $positionKey => $position) :?>
	<?php if (!empty($player->$positionKey)) :?>
    <?php if ($player->$positionKey + $plus >= 90) {
        $grade = 's';
    } elseif ($player->$positionKey + $plus >= 80) {
        $grade = 'a';
    } elseif ($player->$positionKey + $plus >= 70) {
        $grade = 'b';
    } elseif ($player->$positionKey + $plus >= 60) {
        $grade = 'c';
    } elseif ($player->$positionKey + $plus >= 50) {
        $grade = 'd';
    } elseif ($player->$positionKey + $plus >= 40) {
        $grade = 'e';
    } elseif ($player->$positionKey + $plus >= 30) {
        $grade = 'f';
    }
    ?>
<div class="back_position position_<?= $position;?> status_<?= $grade;?>"></div>
	<?php endif;?>
<?php endforeach;?>

