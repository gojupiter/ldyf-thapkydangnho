<?php
/**
 * Header Builder - Helper methods.
 *
 * @author LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHFB_Helper' ) ) :

    class LAHFB_Helper {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		LAHFB_Helper
		 */
		private static $instance;

        /**
         * Hold elements.
         *
         * @since	1.0.0
         * @var		array
         */
        private static $elements = array();

        /**
         * Hold dynamic styles.
         *
         * @since	1.0.0
         * @var		string
         */
        private static $dynamic_styles = '';

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since	1.0.0
		 * @return	object
		 */
		public static function get_instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
            }

			return self::$instance;

		}
		
        /**
         * Add element.
         *
         * @since	1.0.0
         */
        public static function add_element( $id, $func_name ) {

            if ( empty( $id ) ) {
                return;
            }

            self::$elements[ $id ] = $func_name;

        }

        /**
         * Get elements.
         *
         * @since	1.0.0
         */
        public static function get_elements() {
            return self::$elements;
        }

        /**
         * Set dynamic styles.
         *
         * @since	1.0.0
         */
        public static function set_dynamic_styles( $styles, $preset_name = '', $force_save = false ) {
            self::$dynamic_styles .= $styles;
        }

        public static function get_styles(){
            return self::$dynamic_styles;
        }

        /**
         * Get dynamic styles.
         *
         * @since	1.0.0
         */
        public static function get_dynamic_styles($preset_name = '') {

            $session_key = 'lahfb_dynamic_style';
            if(!empty($preset_name)){
                $session_key = 'lahfb_dynamic_style_' . $preset_name;
            }

            $tmp = isset($_SESSION[$session_key]) ? $_SESSION[$session_key]: '';

            $dynamic_styles = str_replace(
                array( "\r\n", "\r", "\n", "\t", '    ' ),
                '',
                preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $tmp )
            );

            return apply_filters('lahfb_get_dynamic_style', $dynamic_styles);
        }

		/**
         * Sanatize CSS value.
         *
         * @since	1.0.0
         */
        public static function css_sanatize( $css_value ) {

            if ( is_numeric( $css_value ) ) :
                return $css_value . 'px';
            endif;

            return $css_value;

        }

        /**
		 * Get file.
		 *
		 * @since	1.0.0
		 */
		public static function get_file( $path ) {

            return LAHFB::get_path() . $path;

        }

		/**
         * Get file (uri).
		 *
         * @since	1.0.0
		 */
        public static function get_file_uri( $path ) {

            return LAHFB::get_url() . $path;

        }

        /**
         * Used to select the proper template.
         *
         * @since	1.0.0
         */
        public static function get_template( $file, $return = false ) {

            if ( empty( $file ) ) {
                return;
            }

			$path = self::get_file( 'includes/templates/' . $file );


            if($return){
                ob_start();
            }

			require_once $path;

            if($return){
                return ob_get_clean();
            }
        }

        public static function is_backend_builder(){
            return is_admin() && isset( $_GET['page'] ) &&  ($_GET['page'] == 'lastudio_header_builder' || $_GET['page'] == 'lastudio_header_builder_setting') && current_user_can( 'manage_options' ) ? true : false;
        }

        /**
         * Used to check if current page is LaStudio Frontend Header Builder.
         *
         * @since	1.0.0
         */
        public static function is_frontend_builder() {
            return is_admin() && isset( $_GET['page'] ) &&  $_GET['page'] == 'lastudio_header_builder' && current_user_can( 'manage_options' ) ? true : false;
		}

        /**
         * Combine user attributes with known attributes and fill in defaults when needed.
         *
         * The pairs should be considered to be all of the attributes which are
         * supported by the caller and given as a list. The returned attributes will
         * only contain the attributes in the $pairs list.
         *
         * If the $atts list has unsupported attributes, then they will be ignored and
         * removed from the final returned list.
         *
         * @since	1.0.0
         *
         * @param array  $pairs     Entire list of supported attributes and their defaults.
         * @param array  $atts      User defined attributes in component tag.
         * @return array Combined and filtered attribute list.
         */
        public static function component_atts( $pairs, $atts ) {

            $pairs = array_merge($pairs, $atts);
            $atts = (array)$atts;
            $out = array();

            foreach ($pairs as $name => $default) {
                if ( array_key_exists($name, $atts) )
                    $out[$name] = $atts[$name];
                else
                    $out[$name] = $default;
            }

            return $out;

        }

        /**
         * Set header default data.
         *
         * @since	1.0.0
         */
        public static function setHeaderDefaultData() {

            $has_init = get_option('lastudio_has_init_header_builder', false);

            if ( !$has_init ) {
                $sample_data = self::getHeaderDefaultData();
                if($sample_data){
                    update_option('lahfb_preheaders', $sample_data);
                }
                $editor_components = self::get_default_components();
                update_option( 'lahfb_data_frontend_components', $editor_components );
                update_option( 'lastudio_has_init_header_builder', true );
                update_option( 'lastudio_header_layout', 'builder' );
            }

        }

        public static function getHeaderDefaultData(){
            $sample_file = self::get_file('includes/prebuilds/default.json' );
            $sample_data = @file_get_contents($sample_file);
            if(!is_wp_error($sample_data) && !empty($sample_data)){
                return json_decode($sample_data, true);
            }
            else{
                return false;
            }
        }

        /**
         * Clear header data.
         *
         * @since	1.0.0
         */
        public static function clearHeaderData() {
            delete_option( 'lahfb_data_frontend_components' );
        }

        /**
         * Convert old settings
         * @return array
         *  'components' => data,
         *  '{$screen}-view => data
         */
        public static function convertOldHeaderData( $data_to_convert = array() ){
            $editor_components = array();
            $components = array();
            $fake_vertical_setting = array();
            if(!empty($data_to_convert)){
                if(isset($data_to_convert['components'])){
                    return $data_to_convert;
                }
                foreach ( $data_to_convert as $platform_key => $platform_data ) {
                    $new_data = array();
                    if(!empty($platform_data)){
                        foreach ( $platform_data as $panel_key => $panel_data ) {
                            $new_panel_data = array();
                            if(!empty($panel_data)){
                                foreach ( $panel_data as $row_key => $row_data ){
                                    $new_row_data = array();
                                    $new_row_data2 = array();
                                    if($row_key == 'settings' || $row_key == 'left_settings' || $row_key == 'center_settings' || $row_key == 'right_settings'){
                                        $tmp2 = array();
                                        if(isset($row_data['element'])){
                                            $tmp2['element'] = $row_data['element'];
                                        }
                                        if(isset($row_data['hidden_element'])){
                                            $tmp2['hidden_element'] = $row_data['hidden_element'];
                                        }
                                        if(isset($row_data['uniqueId'])){
                                            $tmp2['uniqueId'] = $row_data['uniqueId'];
                                        }
                                        if(isset($row_data['header_type'])){
                                            $tmp2['header_type'] = $row_data['header_type'];
                                        }
                                        $new_row_data = $tmp2;

                                        $new_row_data2 = $row_data;

                                        if(isset($new_row_data2['editor_icon'])){
                                            unset($new_row_data2['editor_icon']);
                                        }
                                        if(isset($new_row_data2['hidden_element'])){
                                            unset($new_row_data2['hidden_element']);
                                        }
                                        if(isset($new_row_data2['uniqueId']) && isset($new_row_data2['element'])){
                                            unset($new_row_data2['uniqueId']);
                                            unset($new_row_data2['element']);
                                            if(!empty($new_row_data2)){
                                                $new_row_data2['component_name'] = $row_data['element'];
                                                if($platform_key == 'desktop-view' && $panel_key == 'row1'){
                                                    $fake_vertical_setting = $new_row_data2;
                                                    $fake_vertical_setting['uniqueId'] = $row_data['uniqueId'];
                                                }
                                                $components[$row_data['uniqueId']] = $new_row_data2;
                                            }
                                        }
                                    }
                                    else{
                                        if(!empty($row_data) && is_array($row_data)){
                                            foreach ($row_data as $column_key => $column_data){
                                                $new_column_data = array();
                                                if(isset($column_data['editor_icon'])){
                                                    $new_column_data['editor_icon'] = $column_data['editor_icon'];
                                                }
                                                if(isset($column_data['hidden_element'])){
                                                    $new_column_data['hidden_element'] = $column_data['hidden_element'];
                                                }
                                                if(isset($column_data['name'])){
                                                    $new_column_data['name'] = $column_data['name'];
                                                }
                                                if(isset($column_data['uniqueId'])){
                                                    $new_column_data['uniqueId'] = $column_data['uniqueId'];
                                                }
                                                $new_row_data[$column_key] = $new_column_data;

                                                $new_column_data2 = $column_data;
                                                if(isset($new_column_data2['editor_icon'])){
                                                    unset($new_column_data2['editor_icon']);
                                                }
                                                if(isset($new_column_data2['hidden_element'])){
                                                    unset($new_column_data2['hidden_element']);
                                                }
                                                if(isset($new_column_data2['uniqueId']) && isset($new_column_data2['name'])){
                                                    unset($new_column_data2['uniqueId']);
                                                    unset($new_column_data2['name']);
                                                    if(!empty($new_column_data2)){
                                                        $new_column_data2['component_name'] = $column_data['name'];
                                                        $components[$column_data['uniqueId']] = $new_column_data2;
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    $new_panel_data[$row_key] = $new_row_data;
                                }
                            }

                            $new_data[$panel_key] =  $new_panel_data;
                        }
                    }
                    $editor_components[$platform_key] = $new_data;
                }
            }
            $return_data = $editor_components;

            if(!empty($fake_vertical_setting)){
                $tmp_id = $fake_vertical_setting['uniqueId'];
                unset($fake_vertical_setting['uniqueId']);
                $components[$tmp_id] = $fake_vertical_setting;
            }

            $return_data['components'] = $components;
            return $return_data;
        }

        public static function convertOldHeaderPreset() {
            $old_presets = get_option('lahfb_preheaders');
            if(!empty($old_presets)){
                $new_presets = array();
                foreach ($old_presets as $preset_name => $preset_data ){
                    $_tmp_data = json_decode($preset_data['data'], true);
                    $_data_frontend_components = self::convertOldHeaderData($_tmp_data['lahfb_data_frontend_components']);
                    $_preset_data = $preset_data;
                    $_preset_data['data'] = json_encode(array('lahfb_data_frontend_components' => $_data_frontend_components ));
                    $new_presets[$preset_name] = $_preset_data;
                }
                update_option('lahfb_preheaders', $new_presets);
            }
        }

        /**
         * Get cell components.
         *
         * @since	1.0.0
         */
        public static function getCellComponents( $editor_components, $panel, $row, $cell ) {
            if (empty($editor_components[$panel][$row][$cell])) {
                return;
            }
            $out = '';
            foreach ($editor_components[$panel][$row][$cell] as $cell_key => $el) {

                $el['hidden_element'] = $el['hidden_element'] ? 'true' : 'false';

                $out .= '
                <div class="lahfb-elements-item" data-element="' . esc_attr( $el['name'] ) . '" data-unique-id="' . esc_attr( $el['uniqueId'] ) . '" data-hidden_element="' . esc_attr( $el['hidden_element'] ) . '" data-editor_icon="' . esc_attr( $el['editor_icon'] ) . '">
                    <span class="lahfb-controls">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Copy to Clipboard">
                            <i class="lahfb-control lahfb-copy-btn fa fa-clipboard"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-pencil-square-o"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Remove">
                            <i class="lahfb-control lahfb-delete-btn fa fa-times"></i>
                        </span>
                    </span>
                    <a href="#">
                        <i class="' . esc_attr( $el['editor_icon'] ) . '"></i>
                        <span class="lahfb-element-name">' . esc_html( ucfirst( $el['name'] ) ) . '</span>
                    </a>
                </div>
                ';
            }
            return $out;
        }

        /**
         * Getting data for builder
         */

        public static function get_data_frontend_components( $header_preset = '' ){

            if(empty($header_preset)){
                $header_preset = !empty($_GET['prebuild_header']) ? esc_attr($_GET['prebuild_header']) : '';
            }

            $option = self::get_data_frontend_component_with_preset( $header_preset, get_option('lahfb_data_frontend_components', array()));

            if(isset($option['sticky-view'])){
                unset($option['sticky-view']);
            }
            return $option;

        }

        /**
         * @deprecated
         * @since 1.0.2
         */
        public static function get_backend_editor_components_from_settings( $components = array() ){
            $editor_components = array();
            if(!empty($components)){
                foreach ( $components as $platform_key => $platform_data ) {
                    $new_data = array();
                    if(!empty($platform_data)){
                        foreach ( $platform_data as $panel_key => $panel_data ) {
                            $new_panel_data = array();
                            if(!empty($panel_data)){
                                foreach ( $panel_data as $row_key => $row_data ){
                                    $new_row_data = array();
                                    if($row_key == 'settings' || $row_key == 'left_settings' || $row_key == 'center_settings' || $row_key == 'right_settings'){
                                        $new_row_data = $row_data;
                                    }
                                    else{
                                        if(!empty($row_data)){
                                            foreach ($row_data as $column_key => $column_data){
                                                $new_column_data = array();
                                                if(isset($column_data['editor_icon'])){
                                                    $new_column_data['editor_icon'] = $column_data['editor_icon'];
                                                }
                                                if(isset($column_data['hidden_element'])){
                                                    $new_column_data['hidden_element'] = $column_data['hidden_element'];
                                                }
                                                if(isset($column_data['name'])){
                                                    $new_column_data['name'] = $column_data['name'];
                                                }
                                                if(isset($column_data['uniqueId'])){
                                                    $new_column_data['uniqueId'] = $column_data['uniqueId'];
                                                }
                                                $new_row_data[$column_key] = $new_column_data;
                                            }
                                        }
                                    }

                                    $new_panel_data[$row_key] = $new_row_data;
                                }
                            }

                            $new_data[$panel_key] =  $new_panel_data;
                        }
                    }
                    $editor_components[$platform_key] = $new_data;
                }
            }
            return $editor_components;
        }

        /**
         * @deprecated
         * @since 1.0.2
         */
        public static function get_components_from_settings_deprecated( $components = array() ){
            $editor_components = array();
            if(!empty($components)){
                foreach ( $components as $platform_key => $platform_data ) {
                    if(!empty($platform_data)){
                        foreach ( $platform_data as $panel_key => $panel_data ) {
                            if(!empty($panel_data)){
                                foreach ( $panel_data as $row_key => $row_data ){
                                    if( $row_key == 'settings' || $row_key == 'left_settings' || $row_key == 'center_settings' || $row_key == 'right_settings') {
                                        $new_row_data = $row_data;
                                        if(isset($new_row_data['editor_icon'])){
                                            unset($new_row_data['editor_icon']);
                                        }
                                        if(isset($new_row_data['hidden_element'])){
                                            unset($new_row_data['hidden_element']);
                                        }
                                        if(isset($new_row_data['uniqueId']) && isset($new_row_data['element'])){
                                            unset($new_row_data['uniqueId']);
                                            unset($new_row_data['element']);
                                            if(!empty($new_row_data)){
                                                $editor_components[$row_data['element'] . '_' .$row_data['uniqueId']] = $new_row_data;
                                            }
                                        }
                                    }
                                    else{
                                        if(!empty($row_data)){
                                            foreach ($row_data as $column_key => $column_data){
                                                $new_column_data = $column_data;
                                                if(isset($new_column_data['editor_icon'])){
                                                    unset($new_column_data['editor_icon']);
                                                }
                                                if(isset($new_column_data['hidden_element'])){
                                                    unset($new_column_data['hidden_element']);
                                                }
                                                if(isset($new_column_data['uniqueId']) && isset($new_column_data['name'])){
                                                    unset($new_column_data['uniqueId']);
                                                    unset($new_column_data['name']);
                                                    if(!empty($new_column_data)){
                                                        $editor_components[$column_data['name'] . '_' .$column_data['uniqueId']] = $new_column_data;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $editor_components;
        }

        /**
         * Helper functional to get components and position setting in each devices
         * @param array $components
         * @return array
         * get only components
         */

        public static function get_only_components_from_settings ( $settings = array() ) {
            $components = array();

            if(!empty($settings['components'])){
                $components = $settings['components'];
            }

            return $components;
        }

        public static function get_only_panels_from_settings( $settings = array() ){
            if( isset($settings['components']) ) {
                unset( $settings['components'] );
            }
            $panels = $settings;
            return $panels;
        }

        /**
         * @deprecated
         * @since 1.0.2
         */
        public static function get_components_from_settings( $components = array() ){
            $editor_components = array();
            if(!empty($components)){
                foreach ( $components as $platform_key => $platform_data ) {
                    if(!empty($platform_data)){
                        foreach ( $platform_data as $panel_key => $panel_data ) {
                            if(!empty($panel_data)){
                                foreach ( $panel_data as $row_key => $row_data ){
                                    if( $row_key == 'settings' || $row_key == 'left_settings' || $row_key == 'center_settings' || $row_key == 'right_settings') {
                                        $new_row_data = $row_data;
                                        if(isset($new_row_data['editor_icon'])){
                                            unset($new_row_data['editor_icon']);
                                        }
                                        if(isset($new_row_data['hidden_element'])){
                                            unset($new_row_data['hidden_element']);
                                        }
                                        if(isset($new_row_data['uniqueId']) && isset($new_row_data['element'])){
                                            unset($new_row_data['uniqueId']);
                                            unset($new_row_data['element']);
                                            if(!empty($new_row_data)){
                                                $editor_components[$row_data['element'] . '_' .$row_data['uniqueId']] = $new_row_data;
                                            }
                                        }
                                    }
                                    else{
                                        if(!empty($row_data)){
                                            foreach ($row_data as $column_key => $column_data){
                                                $new_column_data = $column_data;
                                                if(isset($new_column_data['editor_icon'])){
                                                    unset($new_column_data['editor_icon']);
                                                }
                                                if(isset($new_column_data['hidden_element'])){
                                                    unset($new_column_data['hidden_element']);
                                                }
                                                if(isset($new_column_data['uniqueId']) && isset($new_column_data['name'])){
                                                    unset($new_column_data['uniqueId']);
                                                    unset($new_column_data['name']);
                                                    if(!empty($new_column_data)){
                                                        $editor_components[$column_data['name'] . '_' .$column_data['uniqueId']] = $new_column_data;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $editor_components;
        }

        public static function get_prebuild_headers(){
            return apply_filters('LAHFB/preheaders', get_option('lahfb_preheaders', array()));
        }

        public static function is_prebuild_header_exists( $header_key = ''){
            $presets = self::get_prebuild_headers();
            $exist = false;
            if(!empty($header_key) && !empty($presets) && !empty($presets[$header_key])){
                $exist = true;
            }
            return $exist;
        }

        public static function get_data_frontend_component_with_preset( $header_key = '', $fallback = array() ) {
            $presets = self::get_prebuild_headers();
            if(!empty($header_key) && !empty($presets) && !empty($presets[$header_key])){
                $tmp = json_decode($presets[$header_key]['data'], true);
                $fallback = $tmp['lahfb_data_frontend_components'];
            }
            return $fallback;
        }

        public static function get_all_prebuild_header_for_dropdown(){
            $presets = self::get_prebuild_headers();
            $options = array();
            foreach ($presets as $k => $v){
                $options[$k] = $v['name'];
            }
            return $options;
        }

        public static function remove_js_autop($content, $autop = false){
            if ( $autop ) {
                $content = preg_replace( '/<\/?p\>/', "\n", $content );
                $content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', "", $content );
                $content = wpautop( $content . "\n" );
            }
            return do_shortcode( shortcode_unautop( $content ) );
        }

        public static function get_default_components( $is_empty = false ){
            $platforms = array('desktop-view', 'tablets-view', 'mobiles-view');
            $editor_components = array();

            if($is_empty !== false){
                $uniqueId = $is_empty;
                foreach ($platforms as $platform) {
                    $platform_view = array(
                        'topbar'    => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'tapbar'
                            )
                        ),
                        'row1'  => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => false,
                                'uniqueId'          => $uniqueId . 'row1',
                                'header_type'       => 'horizontal'
                            )
                        ),
                        'row2'  => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'row2'
                            )
                        ),
                        'row3'  => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'row3'
                            )
                        )
                    );
                    $editor_components[$platform] = $platform_view;
                }
            }
            else{
                $uniqueId = uniqid();
                foreach ($platforms as $platform) {
                    $platform_view = array(
                        'topbar'    => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'tapbar'
                            )
                        ),
                        'row1'  => array(
                            'left'      => array(
                                array(
                                    "editor_icon" => "fa fa-picture-o",
                                    "hidden_element" => false,
                                    "name" => "logo",
                                    "type" => "image",
                                    "logo_text" => get_bloginfo( 'name' ),
                                    "uniqueId" => $uniqueId . 'logo'
                                )
                            ),
                            'center'    => array(),
                            'right'     => array(
                                array(
                                    "editor_icon" => "fa fa-align-justify",
                                    "hidden_element" => false,
                                    "name" => "menu",
                                    "menu"  => "default_menu",
                                    "uniqueId" => $uniqueId . 'menu'
                                )
                            ),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => false,
                                'uniqueId'          => $uniqueId . 'row1',
                                'header_type'       => 'horizontal'
                            )
                        ),
                        'row2'  => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'row2'
                            )
                        ),
                        'row3'  => array(
                            'left'      => array(),
                            'center'    => array(),
                            'right'     => array(),
                            'settings'  => array(
                                'element'           => 'header-area',
                                'hidden_element'    => true,
                                'uniqueId'          => $uniqueId . 'row3'
                            )
                        ),

                    );
                    $editor_components[$platform] = $platform_view;
                }
                $editor_components['components'] = array(
                    $uniqueId.'logo' => array(
                        'component_name' => 'logo',
                        'type' => 'image',
                        'logo_text' => get_bloginfo( 'name' )
                    ),
                    $uniqueId.'menu' => array(
                        'component_name' => 'menu',
                        'menu'  => 'default_menu'
                    ),
                    $uniqueId.'row1' => array(
                        'component_name' => 'header-area',
                        'header_type'  => 'horizontal'
                    )
                );
            }

            return $editor_components;
        }

        public static function render_background_atts($options, $echo = true){
            $return = '';
            if(!empty($options) && is_array($options)){
                foreach ($options as $k => $val){
                    if(!empty($val)){
                        if($echo){
                            $return .= sprintf('background-%s: %s;'
                                , esc_attr($k)
                                , ($k == 'image' ? 'url('.esc_url( str_replace(array('https://', 'http://'), '//', $val) ).')' : esc_attr($val))
                            );
                        }
                        else{
                            $return .= sprintf('background-%s: %s;'
                                , esc_attr($k)
                                , ($k == 'image' ? 'url('.esc_url( str_replace(array('https://', 'http://'), '//', $val) ).')' : esc_attr($val))
                            );
                        }
                    }
                }
            }
            if($echo){
                echo $return;
            }
            else{
                return $return;
            }
        }

        public static function render_string($string = '', $type = 'attr'){
            if(!empty($string)){
                return _x( $string, 'dynamic string', 'lastudio-header-footer-builder');
            }
            else{
                return $string;
            }
        }
    }

endif;
