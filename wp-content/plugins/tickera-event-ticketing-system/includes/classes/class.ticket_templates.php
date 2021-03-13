<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('TC_Ticket_Templates')) {

    class TC_Ticket_Templates {

        var $form_title = '';
        var $valid_admin_fields_type = array('text', 'textarea', 'checkbox', 'function');

        function __construct() {

            /**
             * If true allows to call TCPDF methods using HTML syntax
             * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
             */
            if ( !defined( 'K_TCPDF_CALLS_IN_HTML' ) )
                define('K_TCPDF_CALLS_IN_HTML', true);

            $this->valid_admin_fields_type = apply_filters('tc_valid_admin_fields_type', $this->valid_admin_fields_type);
        }

        /**
         * Generate the pdf file
         *
         * @param bool $ticket_instance_id
         * @param bool $force_download
         * @param bool $template_id
         * @param bool $ticket_type_id
         * @param bool $string_attachment
         * @return string
         */
        function generate_preview( $ticket_instance_id = false, $force_download = false, $template_id = false, $ticket_type_id = false, $string_attachment = false ) {
            global $tc, $pdf;

            // Trying to set a memory limit to a high value since some template might need more memory (when a huge background is set, etc)
            @ini_set( 'memory_limit', '1024M' );

            // Display all errors if TC_DEBUG is true
            if ( defined('TC_DEBUG' ) || isset( $_GET['TC_DEBUG'] ) ) {
                error_reporting(E_ALL);
                @ini_set('display_errors', 'On');
            } else {
                error_reporting(0);
            }

            // Initialize TCPDF Libraries
            if( !class_exists('TCPDF') )
                require_once( $tc->plugin_dir . 'includes/tcpdf/examples/tcpdf_include.php' );

            ob_start();
            $output_buffering = ini_get('output_buffering');
            if ( isset( $output_buffering ) && $output_buffering > 0 ) {
                if ( !ob_get_level() ) {
                    ob_end_clean();
                    ob_start();
                }
            }

            $post_id = $template_id;

            // Use $template_id only if you preview the ticket
            if ( $ticket_instance_id ) {

                $ticket_instance_status = get_post_status( $ticket_instance_id );
                if ( 'publish' == $ticket_instance_status ) {
                    $ticket_instance = new TC_Ticket( $ticket_instance_id );
                    $pdf_filename = apply_filters('tc_pdf_ticket_name', $ticket_instance->details->ticket_code, $ticket_instance) . '.pdf';

                    $ticket_template = get_post_meta( $ticket_instance->details->ticket_type_id, 'ticket_template', true );
                    $post_id = ( !$ticket_template )
                        ? get_post_meta( apply_filters( 'tc_ticket_type_id', $ticket_instance->details->ticket_type_id ), apply_filters( 'tc_ticket_template_field_name', '_ticket_template' ), true )
                        : $ticket_template;

                } else {
                    _e( 'Something went wrong. Ticket does not exists.', 'tc' );
                    exit;
                }
            } else {
                $pdf_filename = __('preview', 'tc');
            }

            // Retrieve template's post metas
            $metas = ( $post_id ) ? tc_get_post_meta_all( $post_id ) : array();
            $tc_document_orientation = ( $metas ) ? $metas['document_ticket_orientation'] : 'P';
            $tc_document_paper_size = ( $metas ) ? apply_filters( 'tc_document_paper_size', $metas['document_ticket_size'] )  : 'A4';

            // Create new PDF document
            $pdf = new TCPDF( $tc_document_orientation, PDF_UNIT, apply_filters('tc_additional_ticket_document_size_output', $tc_document_paper_size), true, apply_filters('tc_ticket_document_encoding', 'UTF-8'), false);

            // Set TCPDF Defaults
            $pdf->SetCompression(true);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set Font & Margins
            if ( $metas ) {
                $tc_font = ( $metas['document_font'] ) ? $metas['document_font'] : 'helvetica';
                $margin_left = ( $metas['document_ticket_left_margin'] ) ? $metas['document_ticket_left_margin'] : 1;
                $margin_top = ( $metas['document_ticket_top_margin'] ) ? $metas['document_ticket_top_margin'] : 0;
                $margin_right = ( $metas['document_ticket_right_margin'] ) ? $metas['document_ticket_right_margin'] : 0;

                $pdf->SetFont( $tc_font, '', 14 );
                $pdf->SetMargins( $margin_left, $margin_top, $margin_right );
            }

            $tc_general_settings = get_option('tc_general_setting', false);
            $ticket_template_auto_pagebreak = ( isset( $tc_general_settings['ticket_template_auto_pagebreak'] )
                && 'yes' == $tc_general_settings['ticket_template_auto_pagebreak'] ) ? true : false ;

            $pdf->SetAutoPageBreak(false, 0);
            $pdf->setJPEGQuality(100);
            $pdf->AddPage();

            // Set Background image
            if ( isset( $metas['document_ticket_background_image'] ) && $metas['document_ticket_background_image'] ) {
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                $tc_ticket_background = tc_ticket_template_image_url( $metas['document_ticket_background_image'] );
                $tc_ticket_background_values = array(
                    'P' => array(
                        'A4' => array(0, 0, 210, 297),
                        'A5' => array(0, 0, 148, 210),
                        'A6' => array(0, 0, 105, 148),
                        'A7' => array(0, 0, 74, 105),
                        'A8' => array(0, 0, 52, 74),
                        'ANSI_A' => array(0, 0, 216, 279)
                    ),
                    'L' => array(
                        'A4' => array(0, 0, 297, 210),
                        'A5' => array(0, 0, 210, 148),
                        'A6' => array(0, 0, 148, 105),
                        'A7' => array(0, 0, 105, 74),
                        'A8' => array(0, 0, 74, 52),
                        'ANSI_A' => array(0, 0, 279, 216)
                    )
                );

                $tc_bg_size = $tc_ticket_background_values[$tc_document_orientation][$tc_document_paper_size];
                $pdf->Image( $tc_ticket_background, $tc_bg_size[0], $tc_bg_size[1], $tc_bg_size[2], $tc_bg_size[3], '', '', '', true, 300, '', false, false, 0, false );
            }

            $pdf->SetAutoPageBreak($ticket_template_auto_pagebreak, PDF_MARGIN_BOTTOM);

            $col_1 = 'width: 100%;';
            $col_1_width = '100%';
            $col_2 = 'width: 49.2%; margin-right: 1%;';
            $col_2_width = '49.2%';
            $col_3 = 'width: 32.5%; margin-right: 1%;';
            $col_3_width = '32.5%';
            $col_4 = 'width: 24%; margin-right: 1%;';
            $col_5 = 'width: 19%; margin-right: 1%;';
            $col_6 = 'width: 15.66%; margin-right: 1%;';
            $col_7 = 'width: 13.25%; margin-right: 1%;';
            $col_8 = 'width: 11.43%; margin-right: 1%;';
            $col_9 = 'width: 10%; margin-right: 1%;';
            $col_10 = 'width: 8.94%; margin-right: 1%;';

            $rows = '<table>';

            for ($i = 1; $i <= apply_filters('tc_ticket_template_row_number', 10); $i++) {

                $rows .= '<tr>';
                $rows_elements = get_post_meta($post_id, 'rows_' . $i, true);

                if (isset($rows_elements) && $rows_elements !== '') {

                    $element_class_names = explode(',', $rows_elements);
                    $rows_count = count($element_class_names);

                    foreach ($element_class_names as $element_class_name) {

                        if (class_exists($element_class_name)) {

                            if (isset($post_id)) {

                                $font_style_values = array (
                                    'B'     => 'font-weight: bold;', // Bold
                                    'BI'    => 'font-weight: bold; font-style: italic;', // Bold and Italic
                                    'BU'    => 'font-weight: bold; text-decoration: underline;', // Bold and Underline
                                    'BIU'   => 'font-weight: bold; bold; font-style: italic; text-decoration: underline;', // Bold, Italic and Underline
                                    'I'     => 'font-style: italic;', // Italic
                                    'IU'    => 'font-style: italic; text-decoration: underline;', // Italic, Underline
                                    'U'     => 'text-decoration: underline;', // Underline
                                );

                                $font_style_orig = isset($metas[$element_class_name . '_font_style']) ? $metas[$element_class_name . '_font_style'] : '';
                                $font_style = isset( $font_style_values[$font_style_orig] ) ? $font_style_values[$font_style_orig] : '';

                                $rows .= '<td ' . (isset($metas[$element_class_name . '_cell_alignment']) ? 'align="' . $metas[$element_class_name . '_cell_alignment'] . '"' : 'align="left"') . ' style="' . ${"col_" . $rows_count} . (isset($metas[$element_class_name . '_cell_alignment']) ? 'text-align:' . $metas[$element_class_name . '_cell_alignment'] . ';' : '') . (isset($metas[$element_class_name . '_font_size']) ? 'font-size:' . $metas[$element_class_name . '_font_size'] . ';' : '') . (isset($metas[$element_class_name . '_font_color']) ? 'color:' . $metas[$element_class_name . '_font_color'] . ';' : '') . (isset($font_style) ? $font_style : '') . '">';

                                if ( ! isset( $metas[$element_class_name . '_top_padding'] ) || ! $metas[$element_class_name . '_top_padding'] ) {
                                    $metas[$element_class_name . '_top_padding'] = "1";
                                }

                                for ( $s = 1; $s <= $metas[$element_class_name . '_top_padding']; $s++ ) {
                                    $rows .= '<br />';
                                }

                                $element = new $element_class_name($post_id);
                                $rows .= $element->ticket_content($ticket_instance_id, $ticket_type_id);

                                if ( ! isset( $metas[$element_class_name . '_bottom_padding'] ) || ! $metas[$element_class_name . '_bottom_padding'] ) {
                                    $metas[$element_class_name . '_bottom_padding'] = "1";
                                }

                                for ( $s = 1; $s <= $metas[$element_class_name . '_bottom_padding']; $s++ ) {
                                    $rows .= '<br />';
                                }

                                $rows .= '</td>';

                            }
                        }
                    }
                }
                $rows .= '</tr>';
            }
            $rows .= '</table>';

            $page1 = preg_replace("/\s\s+/", '', $rows); // Strip excess whitespace
            do_action('tc_before_pdf_write', $ticket_instance_id, $force_download, $template_id, $ticket_type_id, is_admin());

            $pdf->writeHTML($page1, true, 0, true, 0); // Write page 1

            do_action('tc_pdf_template', $pdf, $metas, $page1, $rows, $tc_document_paper_size, @$ticket_instance, $template_id, $force_download);

            if ( $string_attachment ) {
                return $pdf->Output( $pdf_filename, 'S' );

            } else {
                $pdf->Output( $pdf_filename, ( $force_download ? 'D' : apply_filters( 'tc_change_tcpdf_save_option', 'I' ) ) ) ;
                exit;
            }
        }

        function TC_Cart_Form() {
            $this->__construct();
        }

        function add_new_template() {
            global $wpdb;

            if (isset($_POST['template_title'])) {

                $post = array(
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_title' => sanitize_text_field($_POST['template_title']),
                    'post_type' => 'tc_templates',
                );

                $post = apply_filters('tc_template_post', $post);

                if (isset($_POST['template_id'])) {
                    $post['ID'] = (int) $_POST['template_id']; //If ID is set, wp_insert_post will do the UPDATE instead of insert
                }

                $post_id = wp_insert_post($post);

                //Update post meta
                if ($post_id != 0) {
                    foreach ($_POST as $key => $value) {
                        if (preg_match("/_post_meta/i", $key)) {//every field name with sufix "_post_meta" will be saved as post meta automatically
                            update_post_meta($post_id, sanitize_key(str_replace('_post_meta', '', $key)), sanitize_text_field($value));
                            do_action('tc_template_post_metas');
                        }
                    }
                }

                TC_Template::delete_cache($post_id);

                return $post_id;
            }
        }

        function get_template_col_fields() {

            $default_fields = array(
                array(
                    'field_name' => 'post_title',
                    'field_title' => __('Template Name', 'tc'),
                    'field_type' => 'text',
                    'field_description' => '',
                    'post_field_type' => 'post_title',
                    'table_visibility' => true,
                ),
                array(
                    'field_name' => 'post_date',
                    'field_title' => __('Date', 'tc'),
                    'field_type' => 'text',
                    'field_description' => '',
                    'post_field_type' => 'post_date',
                    'table_visibility' => true,
                ),
            );

            return apply_filters('tc_template_col_fields', $default_fields);
        }

        function get_columns() {
            $fields = $this->get_template_col_fields();
            $results = search_array($fields, 'table_visibility', true);

            $columns = array();

            $columns['ID'] = __('ID', 'tc');

            foreach ($results as $result) {
                $columns[$result['field_name']] = $result['field_title'];
            }

            $columns['edit'] = __('Edit', 'tc');
            $columns['delete'] = __('Delete', 'tc');
            //add duplicate field
            if (tc_iw_is_pr() && !tets_fs()->is_free_plan()) {
                $columns['tc_duplicate'] = __('Action', 'tc');
            }
            return $columns;
        }

        function check_field_property($field_name, $property) {
            $fields = $this->get_template_col_fields();
            $result = search_array($fields, 'field_name', $field_name);
            return $result[0]['post_field_type'];
        }

        function is_valid_template_col_field_type($field_type) {
            if (in_array($field_type, $this->valid_admin_fields_type)) {
                return true;
            } else {
                return false;
            }
        }

    }

}

?>
