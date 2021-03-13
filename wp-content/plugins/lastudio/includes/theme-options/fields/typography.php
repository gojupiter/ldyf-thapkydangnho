<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class LaStudio_Theme_Options_Field_Typography extends LaStudio_Theme_Options_Field_Base {

  public function output() {

    echo $this->element_before();

    $defaults_value = array(
      'family'  => 'Arial',
      'variant' => 'regular',
      'font'    => 'websafe',
    );

    $default_variants = apply_filters( 'la_websafe_fonts_variants', array(
      'regular',
      'italic',
      '700',
      '700italic',
      'inherit'
    ));

    $websafe_fonts = apply_filters( 'la_websafe_fonts', array(
      'Arial',
      'Arial Black',
      'Comic Sans MS',
      'Impact',
      'Lucida Sans Unicode',
      'Tahoma',
      'Trebuchet MS',
      'Verdana',
      'Courier New',
      'Lucida Console',
      'Georgia, serif',
      'Palatino Linotype',
      'Times New Roman'
    ));

    $value         = wp_parse_args( $this->element_value(), $defaults_value );
    $family_value  = $value['family'];
    $variant_value = $value['variant'];
    $is_variant    = ( isset( $this->field['variant'] ) && $this->field['variant'] === false ) ? false : true;

    $variant_multi = ($is_variant == true && $this->field['variant'] === 'multi') ? true : false;

    $is_chosen     = ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
    $google_json   = la_get_google_fonts();
    $chosen_rtl    = ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

    $has_fz_res   = ( isset( $this->field['fz_res'] ) && $this->field['fz_res'] === true ) ? true : false;
    $has_lh_res   = ( isset( $this->field['lh_res'] ) && $this->field['lh_res'] === true ) ? true : false;

    if( is_object( $google_json ) ) {

      $googlefonts = array();

      foreach ( $google_json->items as $key => $font ) {
        $googlefonts[$font->family] = $font->variants;
      }

      $is_google = ( array_key_exists( $family_value, $googlefonts ) ) ? true : false;

      $la_attr = '';

      if(!empty( $this->field['attributes']['data-customize-setting-link'] )){
        $la_attr .= ' data-la-customize-setting-link="' . $this->field['attributes']['data-customize-setting-link'] . '"';
        $la_attr .= ' data-la-customize-setting-key="'. (( isset( $this->field['id'] ) ) ? $this->field['id'] : '') .'"';
        unset($this->field['attributes']['data-customize-setting-link']);
      }

      echo '<div class="la-parent-fields" '. $la_attr .'>';

      echo '<label class="la-typography-family">';
      echo '<span>'.esc_html__('Font Family', 'lastudio').'</span>';
      echo '<select name="'. $this->element_name( '[family]' ) .'" class="'. $is_chosen . $chosen_rtl .'la-typo-family la-child-field" data-atts="family"'. $this->element_attributes() .'>';

      do_action( 'lastudio/action/framework/field/typography_family', $family_value, $this );

      echo '<optgroup label="'. __( 'Web Safe Fonts', 'lastudio' ) .'">';
      foreach ( $websafe_fonts as $websafe_value ) {
        echo '<option value="'. $websafe_value .'" data-variants="'. implode( '|', $default_variants ) .'" data-type="websafe"'. selected( $websafe_value, $family_value, true ) .'>'. $websafe_value .'</option>';
      }
      echo '</optgroup>';

      echo '<optgroup label="'. __( 'Google Fonts', 'lastudio' ) .'">';
      foreach ( $googlefonts as $google_key => $google_value ) {
        echo '<option value="'. $google_key .'" data-variants="'. implode( '|', $google_value ) .'" data-type="google"'. selected( $google_key, $family_value, true ) .'>'. $google_key .'</option>';
      }
      echo '</optgroup>';

      echo '</select>';
      echo '</label>';

      if( ! empty( $is_variant ) ) {

        $variants = ( $is_google ) ? $googlefonts[$family_value] : $default_variants;
        $variants = ( $value['font'] === 'google' || $value['font'] === 'websafe' ) ? $variants : array( 'regular' );

        $_variant_name = ($variant_multi == true) ? '[variant][]' : '[variant]';
        $_variant_att = ($variant_multi == true) ? ' multiple="multiple" style="width: 230px"' : '';

        echo '<label class="la-typography-variant">';
        echo '<span>'.esc_html__('Font Weight & Style', 'lastudio').'</span>';
        echo '<select name="'. $this->element_name( $_variant_name ) .'" class="'. $is_chosen . $chosen_rtl .'la-typo-variant la-child-field" data-atts="variant" '.$_variant_att.'>';
        foreach ( $variants as $variant ) {
          echo '<option value="'. $variant .'"'. $this->checked( $variant_value, $variant, 'selected' ) .'>'. $variant .'</option>';
        }
        echo '</select>';
        echo '</label>';

      }

      echo '<input type="text" name="'. $this->element_name( '[font]' ) .'" class="la-typo-font hidden la-child-field" data-atts="font" value="'. $value['font'] .'" />';

      if($has_fz_res){
        $val_fz_res_tmp  = (isset($value['fz_res']) && !empty($value['fz_res'])) ? (array) $value['fz_res'] : array();
        $value_fz_res = array_merge(array(
          'lg' => '',
          'md' => '',
          'sm' => '',
          'xs' => '',
          'mb' => ''
        ), $val_fz_res_tmp);
        echo '<div class="fz-res-elem">';
          echo '<label>' . esc_html__('Font Size', 'lastudio') . '</label>';
          echo '<div class="fz-res-elem-wrap">';
            echo la_fw_add_element( array(
                'pseudo'          => true,
                'id'              => $this->field['id'].'_fz_res_lg',
                'type'            => 'number',
                'name'            => $this->element_name( '[fz_res][lg]' ),
                'value'           => $value_fz_res['lg'],
                'class'         => 'la-child-field',
                'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Desktop', 'lastudio') .'"><i class="fa fa-desktop"></i></label>'
            ) );
            echo la_fw_add_element( array(
                'pseudo'          => true,
                'id'              => $this->field['id'].'_fz_res_md',
                'type'            => 'number',
                'name'            => $this->element_name( '[fz_res][md]' ),
                'value'           => $value_fz_res['md'],
                'class'         => 'la-child-field',
                'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet', 'lastudio') .'"><i class="dashicons dashicons-tablet" style="transform: rotate(90deg);"></i></label>'
            ) );
            echo la_fw_add_element( array(
                'pseudo'          => true,
                'id'              => $this->field['id'].'_fz_res_sm',
                'type'            => 'number',
                'name'            => $this->element_name( '[fz_res][sm]' ),
                'value'           => $value_fz_res['sm'],
                'class'         => 'la-child-field',
                'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet Portrait', 'lastudio') .'"><i class="dashicons dashicons-tablet"></i></label>'
            ) );
            echo la_fw_add_element( array(
                'pseudo'          => true,
                'id'              => $this->field['id'].'_fz_res_xs',
                'type'            => 'number',
                'name'            => $this->element_name( '[fz_res][xs]' ),
                'value'           => $value_fz_res['xs'],
                'class'         => 'la-child-field',
                'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile Landscape', 'lastudio') .'"><i class="dashicons dashicons-smartphone" style="transform: rotate(90deg);"></i></label>'
            ) );
            echo la_fw_add_element( array(
                'pseudo'          => true,
                'id'              => $this->field['id'].'_fz_res_mb',
                'type'            => 'number',
                'name'            => $this->element_name( '[fz_res][mb]' ),
                'value'           => $value_fz_res['mb'],
                'class'         => 'la-child-field',
                'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile', 'lastudio') .'"><i class="dashicons dashicons-smartphone"></i></label>'
            ) );
          echo '</div>';
        echo '</div>';
      }

      if($has_lh_res){
        $val_lh_res_tmp  = (isset($value['lh_res']) && !empty($value['lh_res'])) ? (array) $value['lh_res'] : array();
        $value_lh_res = array_merge(array(
            'lg' => '',
            'md' => '',
            'sm' => '',
            'xs' => '',
            'mb' => ''
        ), $val_lh_res_tmp);
        echo '<div class="fz-res-elem">';
        echo '<label>' . esc_html__('Line Height', 'lastudio') . '</label>';
        echo '<div class="fz-res-elem-wrap">';
          echo la_fw_add_element( array(
              'pseudo'          => true,
              'id'              => $this->field['id'].'_lh_res_lg',
              'type'            => 'number',
              'name'            => $this->element_name( '[lh_res][lg]' ),
              'value'           => $value_lh_res['lg'],
              'class'         => 'la-child-field',
              'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Desktop', 'lastudio') .'"><i class="fa fa-desktop"></i></label>'
          ) );
          echo la_fw_add_element( array(
              'pseudo'          => true,
              'id'              => $this->field['id'].'_lh_res_md',
              'type'            => 'number',
              'name'            => $this->element_name( '[lh_res][md]' ),
              'value'           => $value_lh_res['md'],
              'class'         => 'la-child-field',
              'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet', 'lastudio') .'"><i class="dashicons dashicons-tablet" style="transform: rotate(90deg);"></i></label>'
          ) );
          echo la_fw_add_element( array(
              'pseudo'          => true,
              'id'              => $this->field['id'].'_lh_res_sm',
              'type'            => 'number',
              'name'            => $this->element_name( '[lh_res][sm]' ),
              'value'           => $value_lh_res['sm'],
              'class'         => 'la-child-field',
              'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Tablet Portrait', 'lastudio') .'"><i class="dashicons dashicons-tablet"></i></label>'
          ) );
          echo la_fw_add_element( array(
              'pseudo'          => true,
              'id'              => $this->field['id'].'_lh_res_xs',
              'type'            => 'number',
              'name'            => $this->element_name( '[lh_res][xs]' ),
              'value'           => $value_lh_res['xs'],
              'class'         => 'la-child-field',
              'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile Landscape', 'lastudio') .'"><i class="dashicons dashicons-smartphone" style="transform: rotate(90deg);"></i></label>'
          ) );
          echo la_fw_add_element( array(
              'pseudo'          => true,
              'id'              => $this->field['id'].'_lh_res_mb',
              'type'            => 'number',
              'name'            => $this->element_name( '[lh_res][mb]' ),
              'value'           => $value_lh_res['mb'],
              'class'         => 'la-child-field',
              'before'          => '<label class="la-tip" data-position="top" data-title="'. esc_attr__('Mobile', 'lastudio') .'"><i class="dashicons dashicons-smartphone"></i></label>'
          ) );
          echo '</div>';
        echo '</div>';
      }

      echo '</div>';
    } else {

      echo __( 'Error! Can not load json file.', 'lastudio' );

    }

    echo $this->element_after();

  }

}
