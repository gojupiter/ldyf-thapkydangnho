<?php
/**
 * Posts loop start template
 */

$preset = $this->get_attr('preset');

?>
<div class="lastudio-posts__item <?php echo lastudio_elements_tools()->col_classes( array(
    'desk' => $this->get_attr( 'columns' ),
    'lap' => $this->get_attr( 'columns_laptop' ),
    'tab' => $this->get_attr( 'columns_tablet' ),
    'tabp'  => $this->get_attr( 'columns_width800' ),
    'mob'  => $this->get_attr( 'columns_mobile' ),
    'mobp'  => $this->get_attr( 'columns_mobile' ),
) ); ?>">
	<div class="lastudio-posts__inner-box"<?php $this->add_box_bg(); ?>><?php

        if($preset != 'type-1'){
            $this->_load_template( $this->get_template( 'item-thumb' ) );
        }

		echo '<div class="lastudio-posts__inner-content">';

            if($preset == 'type-1'){
                $this->_load_template( $this->get_template( 'item-meta' ) );
            }

            $this->_load_template( $this->get_template( 'item-title' ) );
            if($preset != 'type-1'){
                $this->_load_template( $this->get_template( 'item-meta' ) );
            }
            $this->_load_template( $this->get_template( 'item-content' ) );
            $this->_load_template( $this->get_template( 'item-more' ) );

		echo '</div>';

	?></div>
</div>