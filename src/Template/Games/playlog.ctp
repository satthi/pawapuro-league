<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>score board</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('board.css') ?>
    <?= $this->Html->script('jquery') ?>
    <script>
        var gameId = "<?= $game->id;?>";
        var ajaxUrl = "<?= $this->Url->build(['action' => 'playlogInfo', $game->id]);?>";
    </script>
    <?= $this->Html->script('playlog') ?>
</head>
<body>
<div class="clearfix body">
    <div class="fleft member team_<?= $game->home_team->ryaku_name;?>">
        <div class="member_logo_div">
            <?= $this->Html->image('logo_mini/' . $game->home_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
        </div>
        <?php for ($i = 1;$i <= 10;$i++):?>
        <?php
            $options = [
                'type' => 'home',
                'dajun' => $i,
            ];
        ?>
        <?= $this->element('playlog/player', $options);?>
        <?php endfor;?>
        <?= $game->date;?>
    </div>
    <div class="fleft main">
        <div class="score clearfix">
            <div class="fleft score_main">
                <table>
                    <tr>
                        <th></th>
                        <?php for($i = 1;$i <= 12; $i++):?>
                        <th class="score_num"><?= $i;?></th>
                        <?php endfor;?>
                        <th class="score_num">R</th>
                        <th class="score_num">H</th>
                    </tr>
                    <tr class="num_tr">
                        <td><?= $this->Html->image('logo_mini/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'score_logo']);?></td>
                        <?php for($i = 1;$i <= 12; $i++):?>
                        <td id="visitor_<?= $i;?>" class="inning_score" style="text-align:center;" data-start_number="<?= array_key_exists($i * 2 - 1, $inningStart) ? $inningStart[$i * 2 - 1] : '';?> "></td>
                        <?php endfor;?>
                        <td id="visitor_R" style="text-align:center;"></td>
                        <td id="visitor_H" style="text-align:center;"></td>
                    </tr>
                    <tr class="num_tr">
                        <td><?= $this->Html->image('logo_mini/' . $game->home_team->ryaku_name . '.png', ['class' => 'score_logo']);?></td>
                        <?php for($i = 1;$i <= 12; $i++):?>
                        <td id="home_<?= $i;?>" class="inning_score" style="text-align:center;" data-start_number="<?= array_key_exists($i * 2, $inningStart) ? $inningStart[$i * 2] : '';?> "></td>
                        <?php endfor;?>
                        <td id="home_R" style="text-align:center;"></td>
                        <td id="home_H" style="text-align:center;"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="screen">
            <!-- main -->
            <div id="result_text" style="width:300px; height: 60px;background-color: white;margin: 10px auto;color:black;text-align: center;">
                <div id="result_text1" style="font-size: 20px;"></div>
                <div id="result_text2" style="font-size: 12px;"></div>
            </div>
            <div class="vs_screen" style="text-align: center;">
                <div style="position: absolute;width: 994px;">
                    <?= $this->Html->image('playlog/stadium.png', ['width' => '600px', 'class' => 'stadium' , 'id' => 'stadium_result_none']);?>
                    <?php for ($i = 1;$i <= 138;$i++):?>
                        <?= $this->Html->image('playlog/' . $i . '.png', ['width' => '600px', 'style' => 'display:none;', 'class' => 'stadium' , 'id' => 'stadium_result_' .  $i]);?>
                    <?php endfor;?>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="fleft member team_<?= $game->visitor_team->ryaku_name;?>">
        <div class="member_logo_div">
            <?= $this->Html->image('logo_mini/' . $game->visitor_team->ryaku_name . '.png', ['class' => 'member_logo']);?>
        </div>
        <?php for ($i = 1;$i <= 10;$i++):?>
        <?php
            $options = [
                'type' => 'visitor',
                'dajun' => $i,
            ];
        ?>
        <?= $this->element('playlog/player', $options);?>
        <?php endfor;?>
    </div>
</div>
<?= $game->date;?>

</body>
</html>
