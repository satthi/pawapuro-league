$(function(){
	$('.member_block .name').each(function(){
		var div_width = $(this).width();
		var span_width = $(this).find('span').width();
		if (div_width < span_width) {
			var now_font_size = $(this).css('font-size').replace(/px$/, '');
			$(this).css('font-size', (now_font_size * div_width / span_width) + 'px');
		}
		
	});
	var stamen_started = false;
	var settime = 0;
	// 各設定値
	var first_time = 15000;
	var visitor_first_time = 5000;
	var home_first_time = 5000;
	var last_time = 5000;
	var member_all_time = 15000;
	var name_display = 4000;
	var kansei_timing = 1000;

	$("#voice_kansei").get(0).volume = 0.2;
	$("#voice_visitor_team_bgm").get(0).volume = 0.1;
	$("#voice_home_team_bgm").get(0).volume = 0.1;


	$(window).keyup(function(e){
		if (e.which == 13) {
			stamen_start();
		}
	});
	
	if (gameId == 'random') {
		stamen_start();
	}
	
	function stamen_start() {
		console.log('hoge');
		if (stamen_started == true) {
			return;
		}
		stamen_started = true;
		// set timeoutの仕込み時間
		// 初期音声
		$("#voice_taihenomatase").get(0).play();
		$("#voice_taihenomatase").on("ended", function(){
			$("#voice_home_team_name").get(0).play();
			$("#voice_home_team_name").on("ended", function(){
				$("#voice_vs").get(0).play();
				$("#voice_vs").on("ended", function(){
					$("#voice_visitor_team_name").get(0).play();
					$("#voice_visitor_team_name").on("ended", function(){
						$("#voice_nostamen").get(0).play();
					});
				});
			});
		});
		
		// 先攻
		settime += first_time;
		setTimeout(function(){
			// ここまで残っていると支障が出るのでイベントを消しておく
			$("#voice_taihenomatase").off("ended");
			$("#voice_home_team_name").off("ended");
			$("#voice_vs").off("ended");
			$("#voice_visitor_team_name").off("ended");
			$('.screen > div').hide();
			$('.mark_visitor').show();
			$("#voice_visitor_team").get(0).play();
			$("#voice_visitor_team").on('ended', function(){
				$("#voice_visitor_team_name").get(0).play();
			});
			$("#voice_visitor_team_bgm").get(0).play();
			
		}, settime);
		
		settime += visitor_first_time;
		setTimeout(function(){
			for ($i = 1;$i <= $('#visitor_team_div .member_block').length;$i++) {
				member_set('visitor', $i);
			}
		}, settime);
		// この辺の設定値全部globalにすっかな
		settime += member_all_time * 9;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_visitor').show();
			$('.member_block .active').removeClass('active');
		}, settime);
		
		// 後攻
		settime += first_time;
		setTimeout(function(){
			$("#voice_visitor_team_bgm").get(0).pause();
			$('.screen > div').hide();
			$('.mark_home').show();
			$("#voice_home_team").get(0).play();
			$("#voice_home_team").on('ended', function(){
				$("#voice_home_team_name").get(0).play();
			});
			$("#voice_home_team_bgm").get(0).play();
		}, settime);
		
		settime += home_first_time;
		setTimeout(function(){
			for ($i = 1;$i <= $('#home_team_div .member_block').length;$i++) {
				member_set('home', $i);
			}
		}, settime);
		// この辺の設定値全部globalにすっかな
		settime += member_all_time * 9;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_home').show();
			$('.member_block .active').removeClass('active');
		}, settime);
		
		settime += last_time;
		setTimeout(function(){
			$("#voice_home_team_bgm").get(0).pause();
			$('.screen > div').hide();
			$('.vs_screen').show();
			$("#voice_soredehashiai").get(0).play();
		}, settime);
		
		if (gameId == 'random') {
			setTimeout(function(){
				location.reload();
			}, settime + 10000);
		}

	}
	
	function member_set(type, i) {
		setTimeout(function(){
			$('.member_block .active').removeClass('active');
			$('.screen > div').hide();
			$('.mark_' + type).show();
			
			$("#voice_dajun_" + i).get(0).play();
			$("#voice_dajun_" + i).on('ended', function(){
				if (i != 10) {
					$("#voice_" + type + "_player_positiond_" + i).get(0).play();
				}
			});
		}, member_all_time * (i - 1));
		
		setTimeout(function(){
			// 不要なイベントは消せるときに消す
			$("#voice_dajun_" + i).off("ended");
			
			$('#' + type + '_' + i + '_side').find('span').css('visibility', 'visible');
			$('#' + type + '_' + i + '_side div.position').addClass('active');
			$('#' + type + '_' + i + '_side div.name').addClass('active');
			
			$('.screen > div').hide();
			$('#' + type + '_' + i + '_main').show();
			
			$("#voice_" + type + "_playerd_" + i).get(0).play();
			$("#voice_" + type + "_playerd_" + i).on('ended', function(){
				$("#voice_" + type + "_player_position_" + i).get(0).play();
				$("#voice_" + type + "_player_position_" + i).on('ended', function(){
					$("#voice_" + type + "_player_" + i).get(0).play();
					$("#voice_" + type + "_player_" + i).on('ended', function(){
						$("#voice_back_number").get(0).play();
						$("#voice_back_number").on('ended', function(){
							$("#voice_" + type + "_no_" + i).get(0).play();
						});
					});
				});
			});
		}, member_all_time * (i - 1) + name_display);
		
		setTimeout(function(){
			$("#voice_kansei").get(0).play();
		}, member_all_time * (i - 1) + name_display + kansei_timing);
		
		setTimeout(function(){
			// 終わったイベントを解除
			$("#voice_" + type + "_playerd_" + i).off('ended');
			$("#voice_" + type + "_player_position_" + i).off('ended');
			$("#voice_" + type + "_player_" + i).off('ended');
			$("#voice_back_number").off('ended');
		}, member_all_time * i);

	}
});