<div class="submenu clearfix">
	<ul>
		<li><?= $this->Html->link('TOP', ['controller' => 'seasons', 'action' => 'view', $seasonId]);?></li>
	</ul>
</div>
<div class="clearfix">
	<div id="home_div">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $homeTeamInfo,
			'members' => $homeMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => null,
		]);?>
	</div>
	<div id="main_div">
	<!-- スコアボード-->
	<table>
		<tr>
			<td></td>
			<td>1</td>
			<td>2</td>
			<td>3</td>
			<td>4</td>
			<td>5</td>
			<td>6</td>
			<td>7</td>
			<td>8</td>
			<td>9</td>
			<td>10</td>
			<td>11</td>
			<td>12</td>
			<td>R</td>
			<td>H</td>
		</tr>
		<tr>
			<td><?= $visitorTeamInfo->ryaku_name;?></td>
			<?php for ($i = 1; $i <= 12; $i++):?>
			<td>
			</td>
			<?php endfor;?>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td><?= $homeTeamInfo->ryaku_name;?></td>
			<?php for ($i = 1; $i <= 12; $i++):?>
			<td>
			</td>
			<?php endfor;?>
			<td></td>
			<td></td>
		</tr>
	</table>
	<div class="clearfix" >
		<div style="position:relative;">
			<div style="text-align:center;">
				<div id="screen_img_div">
					<img width="400" height="400" src="<?= $this->Url->build('/img/default.png');?>"/>
				</div>
		
			</div>
			<p class="screen_font" id="screen_no"></p>
			<p class="screen_font" id="screen_name"></p>
			<p class="screen_font" id="screen_record"></p>
		</div>
		<button type="button" id="play">play</button>
	</div>

	</div>
	<div id="visitor_div">
		<?= $this->element('scoreboad_members', [
			'teamInfo' => $visitorTeamInfo,
			'members' => $visitorMembers,
			'positionColors' => $positionColors,
			'positionLists' => $positionLists,
			'batterId' => null,
		]);?>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#home_div td').css('visibility', 'hidden');
		$('#visitor_div td').css('visibility', 'hidden');
		var positionOnsei = {
			1: 'pitcher',
			2: 'catcher',
			3: 'first',
			4: 'second',
			5: 'third',
			6: 'short',
			7: ';left',
			8: 'center',
			9: 'right',
		};
		var playerData = <?= json_encode($playerData);?>;
		
		$('#play').click(function(){
		
			// visitorより
			var time = 0;
			// 
			
			setTimeout(
			function(){
				$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
				$('#screen_name').text('<?= $visitorTeamInfo->name_eng;?>');
				onseiYomiage('せんこう');
			}
			,time);
			setTimeout(
			function(){
				onseiYomiage('<?= $visitorTeamInfo->yomi;?>');
			}
			,time + 1000);
			time += 5000;
			$('#visitor_div .player').each(function(){
				var player_id = $(this).data('player_id');
				var position = $(this).data('position');
				var playerInfo = playerData[player_id];
				setTimeout(
				function(){
					// 音声
					onseiYomiage(playerInfo['dajun'] + 'ばん');
					$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
					$('#screen_name').text('<?= $visitorTeamInfo->name_eng;?>');
					$('#screen_no').text(playerInfo['dajun']);
					$('#screen_record').text('');
					
					//ポジション
					setTimeout(
					function(){
						onseiYomiage(positionOnsei[position]);
					}
					,1000);
				    
					//名前
					setTimeout(
					function(){
						$("[data-player_id='" + player_id + "']").parent('tr').find('td').css('visibility', 'visible');
						$('#screen_img_div img').attr('src', playerInfo['img_path']);
						$('#screen_name').text(playerInfo['name_eng']);
						$('#screen_no').text(playerInfo['no']);
						$('#screen_record').text(playerInfo['info']);
						onseiYomiage(playerInfo['name_read']);
					}
					,3000);
				    
					//ポジション
					setTimeout(
					function(){
						onseiYomiage(positionOnsei[position]);
					}
					,6000);
				    
					//名前
					setTimeout(
					function(){
						onseiYomiage(playerInfo['name_short_read']);
					}
					,7000);
				    
					//背番号
					setTimeout(
					function(){
						onseiYomiage('せばんごう');
					}
					,9000);
					setTimeout(
					function(){
						onseiYomiage(playerInfo['no']);
					}
					,10000);
				}
				,time);
				time += 13000;
			});
			
			setTimeout(
			function(){
				$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
				$('#screen_name').text('<?= $homeTeamInfo->name_eng;?>');
				$('#screen_record').text('');
				$('#screen_no').text('');
				onseiYomiage('こうこう');
			}
			,time);
			setTimeout(
			function(){
				onseiYomiage('<?= $homeTeamInfo->yomi;?>');
			}
			,time + 1000);
			time += 5000;
			// home 
			$('#home_div .player').each(function(){
				var player_id = $(this).data('player_id');
				var position = $(this).data('position');
				var playerInfo = playerData[player_id];
				setTimeout(
				function(){
					// 音声
					onseiYomiage(playerInfo['dajun'] + 'ばん');
					$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
					$('#screen_name').text('<?= $homeTeamInfo->name_eng;?>');
					$('#screen_no').text(playerInfo['dajun']);
					$('#screen_record').text('');
					
					//ポジション
					setTimeout(
					function(){
						onseiYomiage(positionOnsei[position]);
					}
					,1000);
				    
					//名前
					setTimeout(
					function(){
						$("[data-player_id='" + player_id + "']").parent('tr').find('td').css('visibility', 'visible');
						$('#screen_img_div img').attr('src', playerInfo['img_path']);
						$('#screen_name').text(playerInfo['name_eng']);
						$('#screen_no').text(playerInfo['no']);
						$('#screen_record').text(playerInfo['info']);
						onseiYomiage(playerInfo['name_read']);
					}
					,3000);
				    
					//ポジション
					setTimeout(
					function(){
						onseiYomiage(positionOnsei[position]);
					}
					,6000);
				    
					//名前
					setTimeout(
					function(){
						onseiYomiage(playerInfo['name_short_read']);
					}
					,7000);
				    
					//背番号
					setTimeout(
					function(){
						onseiYomiage('せばんごう');
					}
					,9000);
					setTimeout(
					function(){
						onseiYomiage(playerInfo['no']);
					}
					,10000);
				}
				,time);
				time += 13000;
			});
		});
		function onseiYomiage(word) {
		    var synthes = new SpeechSynthesisUtterance(
		        word
		    );
		    synthes.lang = "ja-JP"
		    speechSynthesis.speak( synthes );
		}
		
	});
</script>
