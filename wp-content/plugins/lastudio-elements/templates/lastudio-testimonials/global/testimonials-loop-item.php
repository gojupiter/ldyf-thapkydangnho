<?php
/**
 * Testimonials item template
 */


$preset = $this->get_settings( 'preset' );

$class_array = array('lastudio-testimonials__item');

if( $this->get_settings('enable_carousel') != 'true' ) {
    $class_array[] = lastudio_elements_tools()->col_classes( array(
        'desk' => $this->get_settings( 'slides_to_show' ),
        'lap' => $this->get_settings( 'slides_to_show_laptop' ),
        'tab' => $this->get_settings( 'slides_to_show_width800' ),
        'tabp'  => $this->get_settings( 'slides_to_show_tablet' ),
        'mob'  => $this->get_settings( 'slides_to_show_mobile' ),
        'mobp'  => $this->get_settings( 'slides_to_show_with640' ),
    ) );
}

?>
<div class="<?php echo esc_attr(join(' ', $class_array)); ?>">
	<div class="lastudio-testimonials__item-inner">
		<div class="lastudio-testimonials__content"><?php

			if($preset == 'type-1'){
				echo $this->__loop_item( array( 'item_icon' ), '<div class="lastudio-testimonials__icon"><div class="lastudio-testimonials__icon-inner"><i class="%s"></i></div></div>' );
				echo $this->__loop_item( array( 'item_comment' ), '<p class="lastudio-testimonials__comment"><span>%s</span></p>' );
				echo '<div class="lastudio-testimonials_info">';
				echo $this->__loop_item( array( 'item_image', 'url' ), '<figure class="lastudio-testimonials__figure"><img class="lastudio-testimonials__tag-img" src="%s" alt=""></figure>' );
				echo $this->__loop_item( array( 'item_name' ), '<div class="lastudio-testimonials__name"><span>%s</span></div>' );
				echo $this->__loop_item( array( 'item_position' ), '<div class="lastudio-testimonials__position"><span>%s</span></div>' );
				echo '</div>';
			}
			else{
				echo $this->__loop_item( array( 'item_image', 'url' ), '<figure class="lastudio-testimonials__figure"><img class="lastudio-testimonials__tag-img" src="%s" alt=""></figure>' );
				echo $this->__loop_item( array( 'item_icon' ), '<div class="lastudio-testimonials__icon"><div class="lastudio-testimonials__icon-inner"><i class="%s"></i></div></div>' );
				echo $this->__loop_item( array( 'item_title' ), '<h5 class="lastudio-testimonials__title">%s</h5>' );
				echo $this->__loop_item( array( 'item_comment' ), '<p class="lastudio-testimonials__comment"><span>%s</span></p>' );
				echo $this->__loop_item( array( 'item_name' ), '<div class="lastudio-testimonials__name"><span>%s</span></div>' );
				echo $this->__loop_item( array( 'item_position' ), '<div class="lastudio-testimonials__position"><span>%s</span></div>' );

				$item_rating = $this->__loop_item( array( 'item_rating' ), '%d' );
				if(absint($item_rating)> 0){
                    $percentage =  (absint($item_rating) * 10) . '%';
                    echo '<div class="lastudio-testimonials__rating"><span class="star-rating"><span style="width: '.$percentage.'"></span></span></div>';
                }
			}
		?></div>
	</div>
</div>