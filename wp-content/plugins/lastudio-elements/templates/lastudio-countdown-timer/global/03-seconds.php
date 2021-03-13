<?php echo $this->blocks_separator(); ?>
<div class="lastudio-countdown-timer__item item-seconds">
	<div class="lastudio-countdown-timer__item-value" data-value="seconds"><?php echo $this->date_placeholder(); ?></div>
	<?php $this->__html( 'label_sec', '<div class="lastudio-countdown-timer__item-label">%s</div>' ); ?>
</div>
