<div class="member_block clearfix" id="<?= $type;?>_<?= $dajun;?>_side">
	<div class="position fleft">
		<span>
			<?php if ($dajun == 10) :?>
				P
			<?php elseif ($position != 99) :?>
				<?= $position;?>
			<?php else :?>
				D
			<?php endif;?>
		</span>
	</div>
	<div class="name fleft">
		<span>
			<?= $name;?>
		</span>
	</div>
	<?php if (empty($era)):?>
	<div class="avg fleft">
		<span>
			<?= $avg;?>
		</span>
	</div>
	<div class="hr fleft">
		<span>
			<?= $hr;?>
		</span>
	</div>
	<div class="rbi fleft">
		<span>
			<?= $rbi;?>
		</span>
	</div>
	<?php else:?>
	<div class="era fleft">
		<span>
			防：<?= $era;?>
		</span>
	</div>
	<?php endif;?>
</div>
