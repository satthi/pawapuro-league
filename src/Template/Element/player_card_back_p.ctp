<?php 
$spheres = [];
// スライダー方向
if (($type =='r' && $player->throw == 1) || ($type =='l' && $player->throw == 2)) {
    $spheres[] = 'status_slider';
    $spheres[] = 'status_hslider';
    $spheres[] = 'status_cut';
// カーブ方向
} elseif (($type =='rd' && $player->throw == 1) || ($type =='ld' && $player->throw == 2)) {
    $spheres[] = 'status_curb';
    $spheres[] = 'status_scurb';
// フォーク方向
} elseif ($type =='d') {
    $spheres[] = 'status_folk';
    $spheres[] = 'status_changeup';
    $spheres[] = 'status_palm';
    $spheres[] = 'status_knuckle';
    $spheres[] = 'status_sff';
    $spheres[] = 'status_vslider';
    
// シンカー方向
} elseif (($type =='ld' && $player->throw == 1) || ($type =='rd' && $player->throw == 2)) {
    $spheres[] = 'status_schange';
    $spheres[] = 'status_sinker';
    $spheres[] = 'status_hsinker';
// シュート方向
} elseif (($type =='l' && $player->throw == 1) || ($type =='r' && $player->throw == 2)) {
    $spheres[] = 'status_shoot';
    $spheres[] = 'status_hshoot';
}
$displaySpheres = [];
foreach ($spheres as $sphere) {
    if (!empty($player->{$sphere})) {
        $displaySpheres[$sphere] = $player->{$sphere};
    }
}
arsort($displaySpheres);
$sphereOptions = [
    'status_slider' => 'スライダー',
    'status_hslider' => '高速スライダー',
    'status_cut' => 'カットボール',
    'status_curb' => 'カーブ',
    'status_scurb' => 'スローカーブ',
    'status_folk' => 'フォーク',
    'status_changeup' => 'チェンジアップ',
    'status_sff' => 'SFF',
    'status_palm' => 'パーム',
    'status_vslider' => 'Vスライダー',
    'status_knuckle' => 'ナックル',
    'status_schange' => 'Cチェンジ',
    'status_sinker' => 'シンカー',
    'status_hsinker' => '高速シンカー',
    'status_shoot' => 'シュート',
    'status_hshoot' => '高速シュート',
];
?>

<div class="sphere_detail sphere_<?= $type;?>">
    <?php foreach ($displaySpheres as $displaySphereKey => $displaySphere) :?>
    <?php if ($displaySphere + $plus >= 90) {
        $grade = 's';
    } elseif ($displaySphere + $plus >= 80) {
        $grade = 'a';
    } elseif ($displaySphere + $plus >= 70) {
        $grade = 'b';
    } elseif ($displaySphere + $plus >= 60) {
        $grade = 'c';
    } elseif ($displaySphere + $plus >= 50) {
        $grade = 'd';
    } elseif ($displaySphere + $plus >= 40) {
        $grade = 'e';
    } elseif ($displaySphere + $plus >= 30) {
        $grade = 'f';
    }
    ?>
    <div class="sphere_detail_parts status_<?= $grade?>"><?= $sphereOptions[$displaySphereKey];?></div>
    <?php endforeach;?>
    <?php for ($i = 1;$i <= 2 - count($displaySpheres);$i++):?>
    <div class="sphere_detail_parts status_none"></div>
    <?php endfor;?>
</div>
<?php 
$file = 'card/card_back_p_' . $type;
if (empty($displaySpheres)) {
   $file.='_g';
}
$file .= '.png';
?>
<?= $this->Html->image($file);?>
