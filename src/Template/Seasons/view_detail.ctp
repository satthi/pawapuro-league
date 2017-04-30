<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $season->id]);?></li>
	</ul>
</div>
<nav class="large-1 medium-1 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="seasons view large-11 medium-11 columns content">
    <h3><?= h($season->name) ?></h3>
    <div class="related">
    
<div id="div998" style="height:600px;"></div>

<script type="text/javascript">
$(document).ready(function(){

    // 表示データ
    data = [
    	<?php foreach ($gameGraphDatas as $team => $gameGraphData):?>
        [
            <?php foreach ($gameGraphData as $date => $point):?>
            ['<?php echo $date;?>',<?php echo $point;?>],
            <?php endforeach;?>
        ],
        <?php endforeach;?>
    ];

    // オプション
    options = {
        // グラフのタイトル
        title: { text: '貯金 推移'},
        axes:{                                            // 軸
            // 横軸(x軸)
            xaxis:{
                renderer: $.jqplot.DateAxisRenderer,      // プラグイン
                min: '<?php echo $firstDate;?>',                        // 軸開始の値
                max: '<?php echo $lastDate;?>',                        // 軸終了の値
                tickInterval: '10 days',                 // 目盛りの間隔
                tickOptions: { formatString: '%Y/%m/%d'},    // 表示フォーマット
                label: 'month',                           // ラベル
            },
            // 縦軸(y軸)
            yaxis:{
                label: '貯金',                // ラベル
            }
        },
        series: [
                {
                    color: 'blue'
                },
                {
                    color: 'black'
                },
                {
                    color: 'green'
                },
                {
                    color: 'orange'
                },
                {
                    color: 'yellow'
                },
                {
                    color: 'red'
                },
            ],
         
        labels: [
        	<?php foreach ($gameGraphDatas as $team => $gameGraphData):?>
        	'<?php echo $team;?>',
        	<?php endforeach;?>
        ],
    }

    // 作成実行 (グラフ表示する#div, 表示データ, オプションの順に指定)
    $.jqplot( 'div998', data, options);
});
</script>    
    </div>
</div>
