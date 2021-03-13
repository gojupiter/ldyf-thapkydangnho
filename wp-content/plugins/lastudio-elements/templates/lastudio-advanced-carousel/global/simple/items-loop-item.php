<?php
/**
 * Loop item template
 */
?>
<div class="lastudio-carousel__item<?php echo $this->__loop_item( array('item_css_class'), ' %s' )?>">
	<div class="lastudio-carousel__item-inner"><?php
		$target = $this->__loop_item( array( 'item_link_target' ), ' target="%s"' );

		echo $this->__loop_item( array( 'item_link' ), '<a href="%s" class="lastudio-carousel__item-link"' . $target . '><span>' );
		echo $this->get_advanced_carousel_img( 'lastudio-carousel__item-img' );
		echo $this->__loop_item( array( 'item_link' ), '</span></a>' );

		$title  = $this->__loop_item( array( 'item_title' ), '<h5 class="lastudio-carousel__item-title">%s</h5>' );
		$text   = $this->__loop_item( array( 'item_text' ), '<div class="lastudio-carousel__item-text">%s</div>' );
		$button =  $this->__loop_button_item( array( 'item_link', 'item_button_text' ), '<a class="elementor-button elementor-size-md lastudio-carousel__item-button" href="%1$s"' . $target . '>%2$s</a>' );

		if ( $title || $text ) {

			echo '<div class="lastudio-carousel__content">';
				echo $title;
				echo $text;
				echo $button;
			echo '</div>';
		}
?></div>
</div>
