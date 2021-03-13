<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Shortcodes_Param{

    public static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){

        if(!function_exists('vc_add_shortcode_param')){
            return;
        }

        vc_add_shortcode_param('la_column', array($this, 'columnResponsiveCallBack') );
        vc_add_shortcode_param('la_number' , array($this, 'laNumberCallBack' ));
        vc_add_shortcode_param('la_heading' , array($this, 'headingCallBack' ));
        vc_add_shortcode_param('datetimepicker' , array($this, 'datetimePickerCallBack' ) );
        vc_add_shortcode_param('gradient' , array($this, 'gradient_picker' ) );
        vc_add_shortcode_param('la_slides_navigation' , array($this, 'slides_navigation' ) );
        vc_add_shortcode_param('la_hotspot_image_preview' , array($this, 'hotspot_image_preview' ) );

    }

    public function columnResponsiveCallBack($settings, $value){
        $unit = $settings['unit'];
        $medias = $settings['media'];

        if(is_numeric($value)){
            $value = "lg:".$value . $unit.';';
        }

        $uid = 'lastudio-responsive-'. rand(1000, 9999);

        $require = sprintf(
            '<div class="simplify"><span class="la-vc-icon"><div class="la-vc-tooltip simplify-options">%s</div><i class="simplify-icon dashicons dashicons-arrow-right-alt2"></i></span></div>',
            __('Responsive Options', 'lastudio')
        );
        $html  = '<div class="lastudio-responsive-wrapper" id="'.$uid.'"><div class="lastudio-responsive-items">';

        foreach($medias as $key => $default_value ) {

            switch ($key) {
                case 'xlg':
                    $html .= $this->getParamMedia(
                        'optional',
                        '<i class="fa fa-desktop"></i>',
                        __('Large Desktop', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );
                    break;

                case 'lg':
                    $html .= $this->getParamMedia(
                        'required',
                        '<i class="dashicons dashicons-desktop"></i>',
                        __('Desktop', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );
                    $html .= $require;
                    break;

                case 'md':
                    $html .= $this->getParamMedia(
                        'optional',
                        '<i class="dashicons dashicons-tablet" style="transform: rotate(90deg);"></i>',
                        __('Tablet', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );

                    break;

                case 'sm':
                    $html .= $this->getParamMedia(
                        'optional',
                        '<i class="dashicons dashicons-tablet"></i>',
                        __('Tablet Portrait', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );
                    break;

                case 'xs':
                    $html .= $this->getParamMedia(
                        'optional',
                        '<i class="dashicons dashicons-smartphone" style="transform: rotate(90deg);"></i>',
                        __('Mobile Landscape', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );
                    break;
                case 'mb':
                    $html .= $this->getParamMedia(
                        'optional',
                        '<i class="dashicons dashicons-smartphone"></i>',
                        __('Mobile', 'lastudio'),
                        $default_value,
                        $unit,
                        $key
                    );
                    break;
            }
        }
        $html .= '</div>';
        $html .= '<div class="lastudio-unit-section"><label>'.$unit.'</label></div>';
        $html .= '<input type="hidden" data-unit="'.$unit.'"  name="'.$settings['param_name'].'" class="wpb_vc_param_value lastudio-responsive-value '.$settings['param_name'].' '.$settings['type'].'_field" value="'.$value.'" />';
        $html .= '</div>';
        $html .= '<script type="text/javascript">jQuery("#'.$uid.'").trigger("vc_param.la_columns")</script>';
        return $html;
    }

    public function laNumberCallBack($settings, $value){
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $min = isset($settings['min']) ? $settings['min'] : '';
        $max = isset($settings['max']) ? $settings['max'] : '';
        $step = isset($settings['step']) ? $settings['step'] : '';
        $suffix = isset($settings['suffix']) ? $settings['suffix'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $output = '<input type="number" min="'.$min.'" max="'.$max.'" step="'.$step.'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'" style="max-width:100px; margin-right: 10px;" />'.$suffix;
        return $output;
    }

    public function headingCallBack($settings, $value){
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $text = isset($settings['text']) ? $settings['text'] : '';
        $output = '<h4 class="wpb_vc_param_value '.$class.'">'.$text.'</h4>';
        $output .= '<input type="hidden" name="'.$param_name.'" class="wpb_vc_param_value lastudio-param-heading '.$param_name.' '.$settings['type'].'_field" value="'.$value.'" />';
        return $output;
    }

    public function datetimePickerCallBack($settings, $value){
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $uni = uniqid('datetimepicker-'.rand());
        $output = '<div id="date-time-'.$uni.'" class="elm-datetime"><input data-format="yyyy/MM/dd hh:mm:ss" readonly class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" style="width:258px;" value="'.$value.'"/><span class="add-on">
                        <span class="dashicons-before dashicons-calendar"></span>
                    </span></div>';
        $output .= '<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("#date-time-'.$uni.'").bsdatetimepicker();
				})
				</script>';
        return $output;
    }

    private function getParamMedia($class, $icon, $tip, $default_value, $unit, $data_id){
        return sprintf(
            '<div class="la-responsive-item %1$s %2$s"><span class="la-vc-icon"><div class="la-vc-tooltip %1$s %2$s">%3$s</div>%4$s</span><input type="text" class="la-responsive-input" data-default="%5$s" data-unit="%6$s" data-id="%2$s"/></div>',
            esc_attr($class),
            esc_attr($data_id),
            esc_html($tip),
            $icon,
            esc_attr($default_value),
            esc_attr($unit)
        );
    }

    public function gradient_picker($settings, $value) {
        $dependency = '';
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $color1 = isset($settings['color1']) ? $settings['color1'] : ' ';
        $color2 = isset($settings['color2']) ? $settings['color2'] : ' ';
        $class = isset($settings['class']) ? $settings['class'] : '';

        $dependency_element = $settings['dependency']['element'];
        $dependency_value = $settings['dependency']['value'];
        $dependency_value_json =  wp_json_encode($dependency_value);

        $uni = uniqid();
        $output = '<div class="vc_ug_control" data-uniqid="'.$uni.'" data-color1="'.$color1.'" data-color2="'.$color2.'">';
        $output .= '<select id="grad_type'.$uni.'" class="grad_type" data-uniqid="'.$uni.'">
				<option value="vertical">'.__('Vertical', 'lastudio').'</option>
				<option value="horizontal">'.__('Horizontal', 'lastudio').'</option>
				<option value="custom">'.__('Custom', 'lastudio').'</option>
			</select>
			<div id="grad_type_custom_wrapper'.$uni.'" class="grad_type_custom_wrapper" style="display:none;"><input type="number" id="grad_type_custom'.$uni.'" placeholder="45" data-uniqid="'.$uni.'" class="grad_custom" style="width: 200px; margin-bottom: 10px;"/> deg</div>';
        $output .= '<div class="wpb_element_label" style="margin-top: 10px;">'.__('Choose Colors', 'lastudio').'</div>';
        $output .= '<div class="grad_hold" id="grad_hold'.$uni.'"></div>';
        $output .= '<div class="grad_trgt" id="grad_target'.$uni.'"></div>';

        $output .= '<input id="grad_val'.$uni.'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . ' vc_ug_gradient" name="' . $param_name . '"  style="display:none"  value="'.$value.'" '.$dependency.'/></div>';

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var dependency_element = '<?php echo $dependency_element ?>';
                var dependency_values = jQuery.parseJSON('<?php echo $dependency_value_json ?>');
                var dependency_values_array = jQuery.map(dependency_values, function(el) { return el; });

                var get_depend_value = jQuery('.'+dependency_element).val();

                jQuery('.grad_type').change(function(){
                    var uni = jQuery(this).data('uniqid');
                    var hid = "#grad_hold"+uni;
                    var did = "#grad_target"+uni;
                    var cid = "#grad_type_custom"+uni;
                    var tid = "#grad_val"+uni;
                    var cid_wrapper = "#grad_type_custom_wrapper"+uni;
                    var orientation = jQuery(this).children('option:selected').val();

                    if(orientation == 'custom')
                    {
                        jQuery(cid_wrapper).show();
                    }
                    else
                    {
                        jQuery(cid_wrapper).hide();
                        if(orientation == 'vertical')
                            var ori = 'top';
                        else
                            var ori = 'left';

                        jQuery(hid).data('ClassyGradient').setOrientation(ori);
                        var newCSS = jQuery(hid).data('ClassyGradient').getCSS();

                        jQuery(tid).val(newCSS);
                    }

                });

                jQuery('.grad_custom').on('keyup',function() {
                    var uni = jQuery(this).data('uniqid');
                    var hid = "#grad_hold"+uni;
                    var gid = "#grad_type"+uni;
                    var tid = "#grad_val"+uni;
                    var orientation = jQuery(this).val()+'deg';
                    jQuery(hid).data('ClassyGradient').setOrientation(orientation);
                    var newCSS = jQuery(hid).data('ClassyGradient').getCSS();
                    jQuery(tid).val(newCSS);
                });

                function gradient_pre_defined(dependency_element, dependency_values_array){
                    jQuery('.vc_ug_control').each(function(){
                        var uni = jQuery(this).data('uniqid');
                        var hid = "#grad_hold"+uni;
                        var did = "#grad_target"+uni;
                        var tid = "#grad_val"+uni;
                        var oid = "#grad_type"+uni;
                        var cid = "#grad_type_custom"+uni;
                        var cid_wrapper = "#grad_type_custom_wrapper"+uni;
                        var orientation = jQuery(oid).children('option:selected').val();
                        var prev_col = jQuery(tid).val();

                        var is_custom = 'false';

                        if(prev_col!='')
                        {
                            if(prev_col.indexOf('-webkit-linear-gradient(top,') != -1)
                            {
                                var p_l = prev_col.indexOf('-webkit-linear-gradient(top,');
                                prev_col = prev_col.substring(p_l+28);
                                p_l = prev_col.indexOf(');');
                                prev_col = prev_col.substring(0,p_l);
                                orientation = 'vertical';
                            }
                            else if(prev_col.indexOf('-webkit-linear-gradient(left,') != -1)
                            {
                                var p_l = prev_col.indexOf('-webkit-linear-gradient(left,');
                                prev_col = prev_col.substring(p_l+29);
                                p_l = prev_col.indexOf(');');
                                prev_col = prev_col.substring(0,p_l);
                                orientation = 'horizontal';
                            }
                            else
                            {
                                var p_l = prev_col.indexOf('-webkit-linear-gradient(');

                                var subStr = prev_col.match("-webkit-linear-gradient((.*));background: -o");

                                var prev_col = subStr[1].replace(/\(|\)/g, '');

                                var temp_col = prev_col;

                                var t_l = temp_col.indexOf('deg');
                                var deg = temp_col.substring(0,t_l);

                                prev_col = prev_col.substring(t_l+4, prev_col.length);

                                jQuery(cid).val(deg);
                                jQuery(cid_wrapper).show();
                                orientation = 'custom';
                                is_custom = 'true';
                            }
                        }
                        else
                        {
                            prev_col ="#e3e3e3 0%";
                        }

                        jQuery(oid).children('option').each(function(i,opt){
                            if(opt.value == orientation)
                                jQuery(this).attr('selected',true);

                        });

                        if(is_custom == 'true')
                            orientation = deg+'deg';
                        else
                        {
                            if(orientation == 'vertical')
                                orientation = 'top';
                            else
                                orientation = 'left';
                        }

                        jQuery(hid).ClassyGradient({
                            width:350,
                            height:25,
                            orientation : orientation,
                            target:did,
                            gradient: prev_col,
                            onChange: function(stringGradient,cssGradient) {

                                var depend = uvc_gradient_verfiy_depedant(dependency_element, dependency_values_array);

                                cssGradient = cssGradient.replace('url(data:image/svg+xml;base64,','');
                                var e_pos = cssGradient.indexOf(';');
                                cssGradient = cssGradient.substring(e_pos+1);
                                if(jQuery(tid).parents('.wpb_el_type_gradient').css('display')=='none'){
                                    //jQuery(tid).val('');
                                    cssGradient='';
                                }
                                if(depend)
                                    jQuery(tid).val(cssGradient);
                                else
                                    jQuery(tid).val('');
                            },
                            onInit: function(cssGradient){
                                //console.log(jQuery(tid).val())
                                //check_for_orientation();

                            }
                        });
                        jQuery('.colorpicker').css('z-index','999999');
                    })
                }

                gradient_pre_defined(dependency_element, dependency_values_array);

                jQuery('.'+dependency_element).on('change',function(){
                    var depend = uvc_gradient_verfiy_depedant(dependency_element, dependency_values_array);
                    jQuery('.vc_ug_control').each(function(){
                        var uni = jQuery(this).data('uniqid');
                        var tid = "#grad_val"+uni;
                        if(depend === false)
                            jQuery(tid).val('');
                        else
                            gradient_pre_defined(dependency_element, dependency_values_array);
                    });

                });

                function uvc_gradient_verfiy_depedant(dependency_element, dependency_values_array) {
                    var get_depend_value = jQuery('.'+dependency_element).val();
                    if(jQuery.inArray( get_depend_value, dependency_values_array ) !== -1)
                        return true;
                    else
                        return false;
                }

            })
        </script>
        <?php
        return $output;
    }

    public function slides_navigation( $settings, $value ){
        $uid = uniqid();
        $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
        $type = isset($settings['type']) ? $settings['type'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        if($param_name == "next_icon"){
            $icons = array('fa fa-caret-square-o-right', 'fa fa-toggle-right', 'fa fa-angle-double-right', 'fa fa-angle-right', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-right', 'fa fa-arrow-right', 'fa fa-caret-right', 'fa fa-chevron-circle-right', 'fa fa-chevron-right', 'fa fa-long-arrow-right', 'dl-icon-right');
        }
        if($param_name == "prev_icon"){
            $icons = array('fa fa-caret-square-o-left', 'fa fa-toggle-left', 'fa fa-angle-double-left', 'fa fa-angle-left', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-left', 'fa fa-arrow-left', 'fa fa-caret-left', 'fa fa-chevron-circle-left', 'fa fa-chevron-left', 'fa fa-long-arrow-left', 'dl-icon-left');
        }
        if($param_name == "dots_icon"){
            $icons = array('fa fa-circle','fa fa-circle-o','fa fa-circle-thin','fa fa-dot-circle-o','fa fa-stop-circle','fa fa-stop-circle-o', 'fa fa-square', 'fa fa-square-o');
        }
        $output = '<input type="hidden" name="'.esc_attr( $param_name ).'" class="wpb_vc_param_value '.esc_attr( $param_name ).' '.esc_attr( $type ).' '.esc_attr( $class ).'" value="'.esc_attr( $value ).'" id="trace-'.esc_attr( $uid ).'"/>';
        $output .='<div data-trace="#trace-'. esc_attr( $uid ) .'" id="icon-dropdown-'.esc_attr( $uid ).'" >';
        $output .= '<ul class="icon-list">';
        foreach( $icons as $icon ) {
            $output .= sprintf(
                '<li %2$s data-ac-icon="%1$s"><span><i class="%1$s"></i></span><label>%1$s</label></li>',
                esc_attr( $icon ),
                ($icon == $value) ? 'class="selected"' : ''
            );
        }
        $output .='</ul>';
        $output .='</div>';
        return $output;
    }

    public function hotspot_image_preview( $settings, $value ){
        $image_output = null;

        if(!empty($value)) $image_output = '<img src="'. esc_attr($value) . '" alt="preview" />';

        return '<div id="la_image_with_hotspots_preview"><input name="' . esc_attr( $settings['param_name'] ) . '" type="hidden" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . '" value="'.esc_attr($value).'" /> '.$image_output. '</div>';
    }

}