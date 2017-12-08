<div class="clearfix">
<div class="card_set" style="float: left;width: 500px;">
<?= $this->element('player_card', ['player' => $card->player, 'card' => $card]);?>
<?= $this->element('player_card_back', ['player' => $card->player, 'card' => $card]);?>
<button type="button" id="card_change_save">確定</button>
</div>

<div style="float: left;width: 600px;height:600px;overflow:scroll;">
<table>
<tr>
    <th>形状</th>
    <th>ランク</th>
    <?php if ($card->player->type_p == null):?>
    <th>巧打力</th>
    <th>長打力</th>
    <th>走力</th>
    <th>バント</th>
    <th>守備力</th>
    <?php else:?>
    <th>体力</th>
    <th>球速</th>
    <th>球威</th>
    <th>変化球</th>
    <th>制球力</th>
    <?php endif;?>
    <th>精神力</th>
</tr>
<?php foreach ($cardSkills as $cardSkill):?>
<?php $skill_block_mapping = \Cake\Core\Configure::read('skill_block.' . $cardSkill->shape_type);?>
<tr
    class="skill_tr"
    data-shape="<?= json_encode($skill_block_mapping);?>"
    data-skill_type="<?= $cardSkill->skill_type;?>"
    data-meat="<?= $cardSkill->meat_plus;?>"
    data-power="<?= $cardSkill->power_plus;?>"
    data-speed="<?= $cardSkill->speed_plus;?>"
    data-bant="<?= $cardSkill->bant_plus;?>"
    data-defense="<?= $cardSkill->defense_plus;?>"
    data-mental="<?= $cardSkill->mental_plus;?>"
    data-card_skill_id="<?= $cardSkill->id;?>"
>
<td><?= $this->element('skill_block_short', ['cardSkill' => $cardSkill]);?></td>
<td><?= $cardSkill->rank;?></td>
<td><?= $cardSkill->meat_plus;?></td>
<td><?= $cardSkill->power_plus;?></td>
<td><?= $cardSkill->speed_plus;?></td>
<td><?= $cardSkill->bant_plus;?></td>
<td><?= $cardSkill->defense_plus;?></td>
<td><?= $cardSkill->mental_plus;?></td>
</tr>
<?php endforeach;?>
</table>
</div>
</div>

<script type="text/javascript">
$(function(){
$('.ura').hide();
var skill_block_over = false;

$('.block').click(function(){
	if (skill_block_over == true) {
	    return;
	}
	$(this).parent('.card_set').find('.block').toggle();
});

$('.skill_tr').click(function(){
    if ($(this).hasClass('skill_selected')) {
        $(this).removeClass('skill_selected');
    } else {
        $('.skill_selected').removeClass('skill_selected');
        $(this).addClass('skill_selected');
    }
});

var status_check = false;
$('.main-skill-block td').mouseover(function(){
	skill_block_over = true;
    // なんもしない
    if ($('.skill_selected').length == 0) {
        return;
    }
    
    // block mapping
    var shape = $('.skill_selected').data('shape');
    var skill_type = $('.skill_selected').data('skill_type');
    
    var over_no = $(this).data('no');
    var target_class = 'setblock-' + skill_type;
    $('.' + target_class).removeClass(target_class);
    status_check = true;
    $.each(shape, function(i,v){
        // ベースを6で考える
        var check =over_no - 6 + v;
        if (
            // 上下
            check >= 0 && check <= 16 && 
            // 左右
            !(
            // 1番は4列目に来ることはない
            (check % 4 == 0 && v % 4 == 1)
            ||
            // 3番は1列目に来ることはない
            (check % 4 == 1 && v % 4 == 3)
            ||
            // 4番は1,2列に来ることはない
            (check % 4 == 1 && v % 4 == 0)
            ||
            (check % 4 == 2 && v % 4 == 0)
            )
            // アクティブ
        ) {
            $('.block_no_' + check).addClass(target_class);
            // 埋まってたりしているブロック
			if (
            !$('.block_no_' + check).hasClass('active')
            ||
            $('.block_no_' + check).hasClass('detectblock')
            ) {
                status_check = false;
            }

        } else {
            // ng
            status_check = false;
        }
    });
});


$('.main-skill-block td').mouseout(function(){
	skill_block_over = false;
    // なんもしない
    if ($('.skill_selected').length == 0) {
        return;
    }
    var skill_type = $('.skill_selected').data('skill_type');
    var target_class = 'setblock-' + skill_type;
    $('.' + target_class).removeClass(target_class);
    status_check = true;
});

$('.main-skill-block td').click(function(){
    if ($('.skill_selected').length == 0) {
        return;
    }
    if (status_check == false) {
        alert('設定できません');
        return;
    }
    var skill_type = $('.skill_selected').data('skill_type');
    var meat = $('.skill_selected').data('meat');
    var power = $('.skill_selected').data('power');
    var speed = $('.skill_selected').data('speed');
    var bant = $('.skill_selected').data('bant');
    var defense = $('.skill_selected').data('defense');
    var mental = $('.skill_selected').data('mental');
    var target_class = 'setblock-' + skill_type;
    var set_class = 'detectblock-' + skill_type;
    $('.' + target_class).addClass(set_class).addClass('detectblock').removeClass(target_class);
    // 後で登録用に何かしら
    $('.skill_selected').removeClass('skill_selected').hide();
    // 色は一旦消す
    $('.statuspoint').removeClass('pointyellow').removeClass('pointorange').removeClass('pointred');

    var now_meatpoint = parseIntCustom($('.meat .statuspoint').text());
    var now_meatplus = parseIntCustom($('.meat .statusplus').text());
    $('.meat .statuspoint').text(now_meatpoint + meat);
    if (now_meatpoint + meat >= 90) {
        $('.meat .statuspoint').addClass('pointred');
    } else if (now_meatpoint + meat >= 80) {
        $('.meat .statuspoint').addClass('pointorange');
    }else if (now_meatpoint + meat >= 70) {
        $('.meat .statuspoint').addClass('pointyellow');
    }
    var meatplustext = '';
    if (now_meatplus + meat > 0) {
        meatplustext = '+' + (now_meatplus + meat);
    }
    $('.meat .statusbarplus').css('width', (now_meatplus + meat) + '%');
    $('.meat .statusplus').text(meatplustext);
    var now_powerpoint = parseIntCustom($('.power .statuspoint').text());
    var now_powerplus = parseIntCustom($('.power .statusplus').text());
    if (now_powerpoint + power >= 90) {
        $('.power .statuspoint').addClass('pointred');
    } else if (now_powerpoint + power >= 80) {
        $('.power .statuspoint').addClass('pointorange');
    }else if (now_powerpoint + power >= 70) {
        $('.power .statuspoint').addClass('pointyellow');
    }
    $('.power .statuspoint').text(now_powerpoint + power);
    var powerplustext = '';
    if (now_powerplus + power > 0) {
        powerplustext = '+' + (now_powerplus + power);
    }
    $('.power .statusbarplus').css('width', (now_powerplus + power) + '%');
    $('.power .statusplus').text(powerplustext);
    // 投手の時のみ調整
    <?php if ($card->player->type_p != null):?>
    var ball_speed = Math.floor((now_powerpoint + power) / 2 + 115) + 'km';
    $('.speed_block').text(ball_speed);
    <?php endif;?>

    var now_speedpoint = parseIntCustom($('.speed .statuspoint').text());
    var now_speedplus = parseIntCustom($('.speed .statusplus').text());
    $('.speed .statuspoint').text(now_speedpoint + speed);
    if (now_speedpoint + speed >= 90) {
        $('.speed .statuspoint').addClass('pointred');
    } else if (now_speedpoint + speed >= 80) {
        $('.speed .statuspoint').addClass('pointorange');
    }else if (now_speedpoint + speed >= 70) {
        $('.speed .statuspoint').addClass('pointyellow');
    }
    var speedplustext = '';
    if (now_speedplus + speed > 0) {
        speedplustext = '+' + (now_speedplus + speed);
    }
    $('.speed .statusbarplus').css('width', (now_speedplus + speed) + '%');
    $('.speed .statusplus').text(speedplustext);
    var now_bantpoint = parseIntCustom($('.bant .statuspoint').text());
    var now_bantplus = parseIntCustom($('.bant .statusplus').text());
    $('.bant .statuspoint').text(now_bantpoint + bant);
    if (now_bantpoint + bant >= 90) {
        $('.bant .statuspoint').addClass('pointred');
    } else if (now_bantpoint + bant >= 80) {
        $('.bant .statuspoint').addClass('pointorange');
    }else if (now_bantpoint + bant >= 70) {
        $('.bant .statuspoint').addClass('pointyellow');
    }
    var bantplustext = '';
    if (now_bantplus + bant > 0) {
        bantplustext = '+' + (now_bantplus + bant);
    }
    $('.bant .statusbarplus').css('width', (now_bantplus + bant) + '%');
    $('.bant .statusplus').text(bantplustext);
    
    // 投手の時のみ調整
    <?php if ($card->player->type_p != null):?>
    $('.status_s').removeClass('status_s');
    $('.status_a').removeClass('status_a');
    $('.status_b').removeClass('status_b');
    $('.status_c').removeClass('status_c');
    $('.status_d').removeClass('status_d');
    $('.status_e').removeClass('status_e');
    $('.status_f').removeClass('status_f');
    $('.sphere_detail_parts').each(function(){
        var now_point = $(this).data('point');
        var new_point = now_point + bant;
        $(this).attr('data-point', new_point);
        var grade = '';
        if (new_point >= 90) {
	        grade = 's';
	    } else if (new_point >= 80) {
	        grade = 'a';
	    } else if (new_point >= 70) {
	        grade = 'b';
	    } else if (new_point >= 60) {
	        grade = 'c';
	    } else if (new_point >= 50) {
	        grade = 'd';
	    } else if (new_point >= 40) {
	        grade = 'e';
	    } else if (new_point >= 30) {
	        grade = 'f';
	    }
	    $(this).addClass('status_' + grade);
    });
    <?php endif;?>


    var now_defensepoint = parseIntCustom($('.defense .statuspoint').text());
    var now_defenseplus = parseIntCustom($('.defense .statusplus').text());
    $('.defense .statuspoint').text(now_defensepoint + defense);
    if (now_defensepoint + defense >= 90) {
        $('.defense .statuspoint').addClass('pointred');
    } else if (now_defensepoint + defense >= 80) {
        $('.defense .statuspoint').addClass('pointorange');
    }else if (now_defensepoint + defense >= 70) {
        $('.defense .statuspoint').addClass('pointyellow');
    }
    var defenseplustext = '';
    if (now_defenseplus + defense > 0) {
        defenseplustext = '+' + (now_defenseplus + defense);
    }
    $('.defense .statusbarplus').css('width', (now_defenseplus + defense) + '%');
    $('.defense .statusplus').text(defenseplustext);
    
    // 野手の時のみ調整
    <?php if ($card->player->type_p == null):?>
    $('.status_s').removeClass('status_s');
    $('.status_a').removeClass('status_a');
    $('.status_b').removeClass('status_b');
    $('.status_c').removeClass('status_c');
    $('.status_d').removeClass('status_d');
    $('.status_e').removeClass('status_e');
    $('.status_f').removeClass('status_f');
    $('.back_position').each(function(){
        var now_point = $(this).data('point');
        var new_point = now_point + defense;
        $(this).attr('data-point', new_point);
        var grade = '';
        if (new_point >= 90) {
	        grade = 's';
	    } else if (new_point >= 80) {
	        grade = 'a';
	    } else if (new_point >= 70) {
	        grade = 'b';
	    } else if (new_point >= 60) {
	        grade = 'c';
	    } else if (new_point >= 50) {
	        grade = 'd';
	    } else if (new_point >= 40) {
	        grade = 'e';
	    } else if (new_point >= 30) {
	        grade = 'f';
	    }
	    $(this).addClass('status_' + grade);
    });
    <?php endif;?>
    
    var now_mentalpoint = parseIntCustom($('.mental .statuspoint').text());
    var now_mentalplus = parseIntCustom($('.mental .statusplus').text());
    $('.mental .statuspoint').text(now_mentalpoint + mental);
    if (now_mentalpoint + mental >= 90) {
        $('.mental .statuspoint').addClass('pointred');
    } else if (now_mentalpoint + mental >= 80) {
        $('.mental .statuspoint').addClass('pointorange');
    }else if (now_mentalpoint + mental >= 70) {
        $('.mental .statuspoint').addClass('pointyellow');
    }
    var mentalplustext = '';
    if (now_mentalplus + mental > 0) {
        mentalplustext = '+' + (now_mentalplus + mental);
    }
    $('.mental .statusbarplus').css('width', (now_mentalplus + mental) + '%');
    $('.mental .statusplus').text(mentalplustext);
});

function parseIntCustom(value) {
    if (value.trim() == '') {
        return 0;
    }
    return parseInt(value.trim(), 10);
}


$('#card_change_save').click(function(){
	console.log('BBB');
	// ポイント上昇値の保存
	data = {};
	data['meatplus'] = $('.meat .statusplus').text();
	data['powerplus'] = $('.power .statusplus').text();
	data['speedplus'] = $('.speed .statusplus').text();
	data['bantplus'] = $('.bant .statusplus').text();
	data['defenseplus'] = $('.defense .statusplus').text();
	data['mentalplus'] = $('.mental .statusplus').text();
	// ブロックのマッピング
	data['block'] = {};
	$('.detectblock').each(function(){
	    var no = $(this).data('no');
	    var thisclass = $(this).attr('class');
		data['block'][no] = thisclass;
	});
	data['used'] = {};
	// 使用したスキル
	var k = 0;
	$('.skill_tr:hidden').each(function(){
	    data['used'][k] = $(this).data('card_skill_id');
	    k++;
	});
	
	
	$.ajax({
		type: 'POST',
		data: data,
	    url: '<?= $cardSkillSaveUrl;?>',
	    success: function(){
	        location.reload();
	    }
	});
	
});


});
</script>
