$(function(){
    // 初期遷移時の番号
    var number = 0;
    var nextNumber = null;
    getInfo(number);

    $(window).keyup(function(e){
        // →
        if (e.keyCode == 39 || e.keyCode == 13) {
            getInfo(nextNumber);
        }
    });

    $('.inning_score').on('click', function(){
        var startNumber = $(this).data('start_number');
        if (startNumber != null) {
            getInfo(startNumber);
        }
    });


    $('.member_block > div > span').css('visibility', 'visible');
    function getInfo(number) {
        var url = ajaxUrl + '/' + number;
        $.ajax({
            'url' : url,
            'type' : 'post',
            'dataType' : 'json'
        }).done(function(res){
            $('.active').removeClass('active');
            // member
            memberSet(res, 'home');
            memberSet(res, 'visitor');
            resultSet(res);
            scoreboardSet(res);
            nextNumber = res['nextNumber'];
        });
    }

    function memberSet(res, type) {
        $.each(res['member'][type], function(i, v){
            var targetDom = $('#' + type + '_' + v['dajun'] + '_side');
            targetDom.find('.position > span').text(v['position']);
            targetDom.find('.name > span').text(v['player']);
            var nameDiv = targetDom.find('.name');
            var div_width = nameDiv.width();
            var span_width = nameDiv.find('span').width();

            var now_font_size;
            if (nameDiv.css('font-size') == undefined) {
                now_font_size = '30px'
            } else {
                now_font_size = nameDiv.css('font-size').replace(/px$/, '');
            }

            var resize_font_size = now_font_size * div_width / span_width;
            if (resize_font_size > 30) {
                resize_font_size = 30;
            }

            nameDiv.css('font-size',resize_font_size + 'px');
            if (v['dajun'] != 10) {
                targetDom.find('.avg > span').text(v['avg']);
                targetDom.find('.hr > span').text(v['hr']);
                targetDom.find('.rbi > span').text(v['rbi']);
            } else {
                targetDom.find('.era > span').text(v['win'] + '-' + v['lose'] + ' ' + v['era']);
            }

            if (Object.keys(res['member'][type]).length == 9 && v['position'] == 1) {
                var pitcherDom = $('#' + type + '_10_side');
                pitcherDom.find('.position > span').text('P');
                pitcherDom.find('.name > span').text(v['player']);
                pitcherDom.find('.era > span').text(v['win'] + '-' + v['lose'] + ' ' + v['era']);


                var pitcherNameDiv = pitcherDom.find('.name');
                var picther_div_width = pitcherNameDiv.width();
                var pitcher_span_width = pitcherNameDiv.find('span').width();
                var pitcher_now_font_size = pitcherNameDiv.css('font-size').replace(/px$/, '');
                var pitcher_resize_font_size = pitcher_now_font_size * picther_div_width / pitcher_span_width;
                if (pitcher_resize_font_size > 30) {
                    pitcher_resize_font_size = 30;
                }

                pitcherNameDiv.css('font-size', pitcher_resize_font_size + 'px');


            }

            // 光らせる
            $.each(res['activeMembers'], function(i2, v2){
                if (v2 == v['player_id']) {
                    if (v2 == v['player_id']) {
                        targetDom.find('div').addClass('active');
                    }
                }
            });
            $.each(res['activePositions'], function(i2, v2){
                if (v2 == v['player_id']) {
                    targetDom.find('.position').addClass('active');
                }
            });
        });

    }

    function resultSet(res) {
        $('#result_text1').text('');
        $('#result_text2').text('');
        $('.stadium').hide();
        if (res['resultSet'] != null && res['resultSet']['result_hit']) {
            $('#result_text').css('color', 'red');
        } else {
            $('#result_text').css('color', 'black');
        }
        if (res['resultSet'] == null) {
            $('#result_text1').text('試合開始前');
            $('#stadium_result_none').show();
        } else if(res['resultSet']['type'] == 1) {
            $('#result_text1').text('選手交代');
            $('#stadium_result_none').show();
        } else if(res['resultSet']['type'] == 2) {
            $('#result_text1').text(res['resultSet']['result']);
            var text2 = '';
            if (res['resultSet']['outNum'] != 0) {
                text2 += '+' + res['resultSet']['outNum'] + 'アウト(' + res['nowOut'] + 'アウト)';
            }
            if (res['resultSet']['point'] != 0 && res['resultSet']['point'] != null) {
                if (text2 != '') {
                    text2 += ' ';
                }
                text2 += res['resultSet']['point'] + '点';
            }
            $('#result_text2').text(text2);

            // エフェクト付き
            $('#stadium_result_' + res['resultSet']['result_code']).show();
        } else if (res['resultSet']['type'] == 3) {
            if (res['resultSet']['outNum'] == 0) {
                $('#result_text1').text('盗塁成功');
            } else {
                $('#result_text1').text('盗塁失敗');
                var text2 = '';
                if (res['resultSet']['outNum'] != 0) {
                    text2 += '+' + res['resultSet']['outNum'] + 'アウト(' + res['nowOut'] + 'アウト)';
                }
                if (res['resultSet']['point'] != 0 && res['resultSet']['point'] != null)  {
                    if (text2 != '') {
                        text2 += ' ';
                    }
                    text2 += res['resultSet']['point'] + '点';
                }
                $('#result_text2').text(text2);
            }
            $('#stadium_result_none').show();
        } else {
            $('#result_text1').text('得点のみ');
            $('#stadium_result_none').show();
        }
    }

    function scoreboardSet(res) {
        $.each(res['scoreBoards'], function(teamType, scores) {
            $.each(scores, function(inning, point){
                $('#' + teamType + '_' + inning).text(point);
            })
        });
        // activeinning
        if (res['resultSet'] != null) {
            if (res['resultSet']['activeInning'] % 2 == 1) {
                $('#visitor_' + Math.ceil(res['resultSet']['activeInning'] /2)).addClass('active');
            } else {
                $('#home_' + res['resultSet']['activeInning'] /2).addClass('active');
            }
        }
    }


});