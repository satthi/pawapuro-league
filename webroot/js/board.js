$(function(){
	$('.member_block .name').each(function(){
		var div_width = $(this).width();
		var span_width = $(this).find('span').width();
		if (div_width < span_width) {
			var now_font_size = $(this).css('font-size').replace(/px$/, '');
			$(this).css('font-size', (now_font_size * div_width / span_width) + 'px');
		}
		
		$(window).keyup(function(e){
			if (e.which == 13) {
				stamen_start();
			}
		});
	});
	var stamen_started = false;
	var settime = 0;
	function stamen_start() {
		if (stamen_started == true) {
			return;
		}
		stamen_started = true;
		// set timeoutの仕込み時間
		
		var first_time = 1000;
		
		// 先攻
		settime += first_time;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_visitor').show();
		}, settime);
		
		var visitor_first_time = 1000;
		settime += visitor_first_time;
		setTimeout(function(){
			for ($i = 1;$i <= 9;$i++) {
				member_set('visitor', $i);
			}
		}, settime);
		// この辺の設定値全部globalにすっかな
		settime += 2000 * 9;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_visitor').show();
			$('.member_block .active').removeClass('active');
		}, settime);
		
		// 後攻
		settime += first_time;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_home').show();
		}, settime);
		
		var home_first_time = 1000;
		settime += home_first_time;
		setTimeout(function(){
			for ($i = 1;$i <= 9;$i++) {
				member_set('home', $i);
			}
		}, settime);
		// この辺の設定値全部globalにすっかな
		settime += 2000 * 9;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.mark_home').show();
			$('.member_block .active').removeClass('active');
		}, settime);
		
		var last_time = 1000;
		settime += last_time;
		setTimeout(function(){
			$('.screen > div').hide();
			$('.vs_screen').show();
		}, settime);
	}
	
	function member_set(type, i) {
		var all_time = 2000;
		var name_display = 1000;
		setTimeout(function(){
			$('.member_block .active').removeClass('active');
			$('.screen > div').hide();
			$('.mark_' + type).show();
		}, all_time * (i - 1));
		
		setTimeout(function(){
			$('#' + type + '_' + i + '_side').find('span').css('visibility', 'visible');
			$('#' + type + '_' + i + '_side div.position').addClass('active');
			$('#' + type + '_' + i + '_side div.name').addClass('active');
			
			$('.screen > div').hide();
			$('#' + type + '_' + i + '_main').show();
		}, all_time * (i - 1) + name_display);
	}
});