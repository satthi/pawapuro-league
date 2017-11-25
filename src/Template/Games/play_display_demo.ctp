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
	<?php if (!empty($gameInfo)):?>
		<h2><?= $gameInfo->date->format('Y/m/d(D)');?> <?= $gameInfo->home_team->ryaku_name;?> VS <?= $gameInfo->visitor_team->ryaku_name;?></h2>
	<?php endif;?>
	<div class="clearfix" >
		<div style="position:relative;" id="photo_frame">
			<div style="text-align:center;">
				<div id="screen_img_div">
					<img width="400" height="400" src="<?= $this->Url->build('/img/default.png');?>"/>
				</div>
		
			</div>
			<p class="screen_font" id="screen_no"></p>
			<p class="screen_font" id="screen_name"></p>
			<p class="screen_font" id="screen_record"></p>
		</div>
		<div class="clearfix" style="margin:0 auto;width:400px;">
		<?php if (array_key_exists('card', $this->request->query) && $seasonId):?>
			<?php $PlayersTable = \Cake\ORM\TableRegistry::get('Players');?>
			<?php foreach ($homeMembers as $homeMember):?>
				<?php $player = $PlayersTable->find('all')
					->where(['Teams.ryaku_name' => $homeTeamInfo->ryaku_name])
					->where(['Players.no' => $homeMember['player']->no])
					->where(['Seasons.regular_flag' => true])
					->where(['Seasons.id <=' => $seasonId])
					->order(['Seasons.id ' => 'DESC'])
					->contain(['Teams' => ['Seasons']])
					->first()
					;
				?>
				<?php if (!empty($player)):?>
					<div class="card_frame" id="card_frame_<?= $homeMember['player']->id;?>">
						<?= $this->element('player_card', ['player' => $player]);?>
					</div>
				<?php endif;?>
			<?php endforeach;?>
			<?php foreach ($visitorMembers as $visitorMember):?>
				<?php $player = $PlayersTable->find('all')
					->where(['Teams.ryaku_name' => $visitorTeamInfo->ryaku_name])
					->where(['Players.no' => $visitorMember['player']->no])
					->where(['Seasons.regular_flag' => true])
					->where(['Seasons.id <=' => $seasonId])
					->order(['Seasons.id ' => 'DESC'])
					->contain(['Teams' => ['Seasons']])
					->first()
					;
				?>
				<?php if (!empty($player)):?>
					<div class="card_frame" id="card_frame_<?= $visitorMember['player']->id;?>">
						<?= $this->element('player_card', ['player' => $player]);?>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
		</div>
		<button type="button" id="play">play</button>
		<?= $this->Html->link('試合へ', ['controller' => 'games', 'action' => 'play', $gameId]);?>

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
			7: 'left',
			8: 'center',
			9: 'right',
		};
		var playerData = <?= json_encode($playerData);?>;
		$('.card_frame').hide();
		$('#play').click(function(){
		
			// visitorより
			var time = 0;
			// 
			
			setTimeout(
			function(){
				// バグ回避？
				onseiYomiage('');
			}
			,time);
			setTimeout(
			function(){
				// バグ回避？
				$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
				$('#screen_name').text('<?= $visitorTeamInfo->name;?>');
				onseiYomiage('せんこう');
			}
			,200);
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
					$('#photo_frame').show();
					$('.card_frame').hide();
					$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
					$('#screen_name').text('<?= $visitorTeamInfo->name;?>');
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
						$('.card_frame').hide();
						if ($('#card_frame_' + player_id).length > 0) {
							$('#photo_frame').hide();
							$('#card_frame_' + player_id).show();
						} else {
							$('.photo_frame').show();
						}
						$("[data-player_id='" + player_id + "']").parent('tr').find('td').css('visibility', 'visible');
						$('#screen_img_div img').attr('src', playerInfo['img_path']);
						$('#screen_name').text(playerInfo['name']);
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
				$('#photo_frame').show();
				$('.card_frame').hide();
				$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
				$('#screen_name').text('<?= $homeTeamInfo->name;?>');
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
					$('#photo_frame').show();
					$('.card_frame').hide();
					$('#screen_img_div img').attr('src', '<?= $this->Url->build('/img/default.png');?>');
					$('#screen_name').text('<?= $homeTeamInfo->name;?>');
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
						$('.card_frame').hide();
						if ($('#card_frame_' + player_id).length > 0) {
							$('#photo_frame').hide();
							$('#card_frame_' + player_id).show();
						} else {
							$('.photo_frame').show();
						}
						$("[data-player_id='" + player_id + "']").parent('tr').find('td').css('visibility', 'visible');
						$('#screen_img_div img').attr('src', playerInfo['img_path']);
						$('#screen_name').text(playerInfo['name']);
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
				location.reload();
			}
			,time);
		});
		function onseiYomiage(word) {
		    var synthes = new SpeechSynthesisUtterance();
		    var voices = speechSynthesis.getVoices();
		    synthes.voice = voices[12];
		    synthes.text = word;
		    synthes.rate = 1.2;
		    before_sp = synthes;
		    synthes.lang = "ja-JP"
		    speechSynthesis.speak( synthes );
		}

		<?php if ($setgameId == 'random'):?>
		$('#play').click();
		<?php endif;?>
		
	});
</script>
