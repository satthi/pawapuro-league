<div class="card_set">
<?= $this->element('player_card', ['player' => $card->player, 'card' => $card]);?>
<?= $this->element('player_card_back', ['player' => $card->player, 'card' => $card]);?>
</div>

<script type="text/javascript">
$(function(){
$('.ura').hide();
$('.block').click(function(){
$(this).parent('.card_set').find('.block').toggle();
});
});
</script>
