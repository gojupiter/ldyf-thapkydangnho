<?php
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

if ( !class_exists( 'TC_Ticket_Template_Elements' ) ) {

    class TC_Ticket_Template_Elements {

        var $id = '';
        var $template_metas = '';
        var $element_title = '';

        function __construct( $id = '' ) {

            $this->id = $id;

            if ($id !== '') {
                $this->template_metas = tc_get_post_meta_all($id);
            }
            $this->on_creation();
        }

        function on_creation() {}

        function admin_content() {

            echo $this->get_font_sizes();
            echo $this->get_font_style();
            echo $this->get_font_colors();

            echo $this->get_cell_alignment();
            echo $this->get_element_margins();
            do_action('tc_ticket_admin_content');
        }

        function advanced_admin_element_settings() {}

        function advanced_admin_element_content() {}

        function ticket_content() {}

        function save() {}

        function get_all_set_elements() {

            $set_elements = array();

            for ( $i = 1; $i <= apply_filters( 'tc_ticket_template_row_number', 10 ); $i++ ) {
                $rows_elements = get_post_meta( $this->id, 'rows_' . $i, true );
                if ( isset( $rows_elements ) && $rows_elements !== '' ) {
                    $element_class_names = explode( ',', $rows_elements );

                    foreach ( $element_class_names as $element_class_name ) {
                        $set_elements[] = $element_class_name;
                    }
                }
            }

            return $set_elements;
        }

        function get_dpi( $class = '' ) { ?>
            <label>
                <?php _e('Resolution (DPI)', 'tc'); ?>
            </label>
            <div class="<?php echo esc_attr($class); ?>">
                <select name="dpi_post_meta">
                    <option value="72"  <?php selected(isset($this->template_metas['dpi']) ? $this->template_metas['dpi'] : '72', '72', true); ?>><?php _e('72 (default)', 'tc'); ?></option>
                    <option value="150" <?php selected(isset($this->template_metas['dpi']) ? $this->template_metas['dpi'] : '72', '150', true); ?>><?php _e('150', 'tc'); ?></option>
                    <option value="300" <?php selected(isset($this->template_metas['dpi']) ? $this->template_metas['dpi'] : '72', '300', true); ?>><?php _e('300', 'tc'); ?></option>
                    <?php do_action('tc_additional_ticket_dpi', $this->template_metas['dpi']); ?>
                </select>
            </div>
            <?php
        }

        function get_document_sizes( $class = '' ) { ?>
            <label><?php _e( 'Ticket Size', 'tc' ); ?></label>
            <div class="<?php echo esc_attr($class); ?>">
                <select name="document_ticket_size_post_meta">
                    <?php $document_ticket_size = isset( $this->template_metas['document_ticket_size'] ) ? $this->template_metas['document_ticket_size'] : 'A4'; ?>
                    <option value="A4" <?php selected( $document_ticket_size, 'A4', true ); ?>><?php echo _e( 'A4 (210 × 297 mm)', 'tc' ); ?></option>
                    <option value="A5" <?php selected( $document_ticket_size, 'A5', true ); ?>><?php echo _e( 'A5 (148 × 210 mm)', 'tc' ); ?></option>
                    <option value="A6" <?php selected( $document_ticket_size, 'A6', true ); ?>><?php echo _e( 'A6 (105 × 148 mm)', 'tc' ); ?></option>
                    <option value="A7" <?php selected( $document_ticket_size, 'A7', true ); ?>><?php echo _e( 'A7 (74 × 105 mm)', 'tc' ); ?></option>
                    <option value="A8" <?php selected( $document_ticket_size, 'A8', true ); ?>><?php echo _e( 'A8 (52 × 74 mm)', 'tc' ); ?></option>
                    <option value="ANSI_A" <?php selected( $document_ticket_size, 'ANSI_A', true ); ?>><?php echo _e( 'Letter (216x279 mm)', 'tc' ); ?></option>
                    <?php do_action( 'tc_additional_ticket_document_size', $document_ticket_size ); ?>
                </select>
            </div>
            <?php
        }

        function get_document_orientation( $class = '' ) {
            ?>
            <label><?php _e( 'Orientation', 'tc' ); ?></label>
            <div class="<?php echo esc_attr( $class ); ?>">
                <select name="document_ticket_orientation_post_meta" <?php echo esc_attr( $class ); ?>>
                    <?php $document_ticket_orientation = isset( $this->template_metas['document_ticket_orientation'] ) ? $this->template_metas['document_ticket_orientation'] : 'P'; ?>
                    <option value="P" <?php selected( $document_ticket_orientation, 'P', true ); ?>><?php echo _e( 'Portrait', 'tc' ); ?></option>
                    <option value="L" <?php selected( $document_ticket_orientation, 'L', true ); ?>><?php echo _e( 'Landscape', 'tc' ); ?></option>
                </select>
            </div>
            <?php
        }

        function get_document_margins() { ?>
            <label><?php _e( 'Document Margins', 'tc' ); ?></label>
            <?php _e( 'Top', 'tc' ); ?> <input class="ticket_margin" type="text" name="document_ticket_top_margin_post_meta" value="<?php echo esc_attr( isset( $this->template_metas['document_ticket_top_margin'] ) ? $this->template_metas['document_ticket_top_margin'] : '' ); ?>" />
            <?php _e( 'Right', 'tc' ); ?> <input class="ticket_margin" type="text" name="document_ticket_right_margin_post_meta" value="<?php echo esc_attr( isset( $this->template_metas['document_ticket_right_margin'] ) ? $this->template_metas['document_ticket_right_margin'] : '' ); ?>" />
            <?php _e( 'Left', 'tc' ); ?> <input class="ticket_margin" type="text" name="document_ticket_left_margin_post_meta" value="<?php echo esc_attr( isset( $this->template_metas['document_ticket_left_margin'] ) ? $this->template_metas['document_ticket_left_margin'] : '' ); ?>" />
            </p>
            <?php
        }

        function get_full_background_image() { ?>
            <label><?php _e( 'Ticket Background Image', 'tc' ); ?>
                <input class="file_url" type="text" size="36" name="document_ticket_background_image_post_meta" value="<?php echo esc_attr( ( isset( $this->template_metas['document_ticket_background_image'] ) && $this->template_metas['document_ticket_background_image'] !== '' ) ? $this->template_metas['document_ticket_background_image'] : '' ); ?>" />
                <input class="file_url_button button-secondary" type="button" value="<?php _e('Browse', 'tc'); ?>" />
            </label>
            <?php
        }

        function get_text_alignment() { ?>
            <label><?php _e( 'Text Alignment', 'tc' ); ?></label>
            <select name="<?php echo $this->element_name; ?>_text_alignment_post_meta" class="tc_att_text_alignment">
                <option value="left" <?php selected( isset( $this->template_metas[$this->element_name . '_text_alignment'] ) ? $this->template_metas[$this->element_name . '_text_alignment'] : 'left', 'left', true ); ?>><?php echo _e( 'Left', 'tc' ); ?></option>
                <option value="right" <?php selected( isset( $this->template_metas[$this->element_name . '_text_alignment'] ) ? $this->template_metas[$this->element_name . '_text_alignment'] : 'left', 'right', true ); ?>><?php echo _e( 'Right', 'tc' ); ?></option>
                <option value="center" <?php selected( isset( $this->template_metas[$this->element_name . '_text_alignment'] ) ? $this->template_metas[$this->element_name . '_text_alignment'] : 'left', 'center', true ); ?>><?php echo _e( 'Center', 'tc' ); ?></option>
            </select>
            <?php
        }

        function get_cell_alignment() { ?>
            <label><?php _e( 'Cell Alignment', 'tc' ); ?></label>
            <select name="<?php echo $this->element_name; ?>_cell_alignment_post_meta">
                <option value="left" <?php selected( isset( $this->template_metas[$this->element_name . '_cell_alignment'] ) ? $this->template_metas[$this->element_name . '_cell_alignment'] : 'left', 'left', true ); ?>><?php echo _e( 'Left', 'tc' ); ?></option>
                <option value="right" <?php selected( isset( $this->template_metas[$this->element_name . '_cell_alignment'] ) ? $this->template_metas[$this->element_name . '_cell_alignment'] : 'left', 'right', true ); ?>><?php echo _e( 'Right', 'tc' ); ?></option>
                <option value="center" <?php selected( isset( $this->template_metas[$this->element_name . '_cell_alignment'] ) ? $this->template_metas[$this->element_name . '_cell_alignment'] : 'left', 'center', true ); ?>><?php echo _e( 'Center', 'tc' ); ?></option>
            </select>
            <?php
        }

        function get_element_margins() { ?>
            <label><?php _e( 'Element Break Lines', 'tc' ); ?></label>
            <?php _e( 'Top', 'tc' ); ?> <input class="ticket_element_padding" type="text" name="<?php echo esc_attr( $this->element_name ); ?>_top_padding_post_meta" value="<?php echo esc_attr( isset( $this->template_metas[$this->element_name . '_top_padding'] ) ? $this->template_metas[$this->element_name . '_top_padding'] : 1 ); ?>" />
            <?php _e( 'Bottom', 'tc' ); ?> <input class="ticket_element_padding" type="text" name="<?php echo esc_attr( $this->element_name ); ?>_bottom_padding_post_meta" value="<?php echo esc_attr( isset($this->template_metas[$this->element_name . '_bottom_padding'] ) ? $this->template_metas[$this->element_name . '_bottom_padding'] : 1 ); ?>" />
            </p>
            <?php
        }

        function get_font_style() { ?>
            <label><?php _e( 'Font Style', 'tc' ); ?></label>
            <select name="<?php echo esc_attr( $this->element_name ); ?>_font_style_post_meta" class="tc_att_font_style">
                <?php $element_font_style = isset( $this->template_metas[$this->element_name . '_font_style'] ) ? $this->template_metas[$this->element_name . '_font_style'] : ''; ?>
                <option value="" <?php selected( $element_font_style, '', true ); ?>><?php echo _e( 'Regular', 'tc' ); ?></option>
                <option value="B" <?php selected( $element_font_style, 'B', true ); ?>><?php echo _e( 'Bold', 'tc' ); ?></option>
                <option value="BI" <?php selected( $element_font_style, 'BI', true ); ?>><?php echo _e( 'Bold + Italic', 'tc' ); ?></option>
                <option value="BU" <?php selected( $element_font_style, 'BU', true ); ?>><?php echo _e( 'Bold + Underline', 'tc' ); ?></option>
                <option value="BIU" <?php selected( $element_font_style, 'BIU', true ); ?>><?php echo _e( 'Bold + Underline + Italic', 'tc' ); ?></option>
                <option value="I" <?php selected( $element_font_style, 'I', true ); ?>><?php echo _e( 'Italic', 'tc' ); ?></option>
                <option value="IU" <?php selected( $element_font_style, 'IU', true ); ?>><?php echo _e( 'Italic + Underline', 'tc' ); ?></option>
                <option value="U" <?php selected( $element_font_style, 'U', true ); ?>><?php echo _e( 'Underline', 'tc' ); ?></option>
            </select>
            <?php
        }

        function get_colors( $label = 'Color', $field_name = 'color', $default_color = '#000000' ) { ?>
            <label><?php echo $label; ?></label>
            <input type="text" class="tc-color-picker" name="<?php echo esc_attr( $this->element_name ); ?>_<?php echo $field_name; ?>_post_meta" value="<?php echo isset( $this->template_metas[$this->element_name . '_' . $field_name] ) ? esc_attr( $this->template_metas[$this->element_name . '_' . $field_name] ) : $default_color; ?>" />
            <?php
        }

        function get_font_colors( $label = 'Font Color', $field_name = 'font_color', $default_color = '#000000' ) { ?>
            <label><?php echo $label; ?></label>
            <input type="text" class="tc-color-picker" name="<?php echo esc_attr( $this->element_name ); ?>_<?php echo $field_name; ?>_post_meta" value="<?php echo isset( $this->template_metas[$this->element_name . '_' . $field_name] ) ? esc_attr( $this->template_metas[$this->element_name . '_' . $field_name] ) : $default_color; ?>" />
            <?php
        }

        function get_att_fonts() { ?>
            <select name="<?php echo esc_attr( $this->element_name ); ?>_att_font_family_post_meta" class="tc_att_font_family">
                <?php $element_att_font_family = isset( $this->template_metas[$this->element_name . '_att_font_family'] ) ? $this->template_metas[$this->element_name . '_att_font_family'] : ''; ?>
                <option value="Montserrat" <?php selected( $element_att_font_family, '', true ); ?>><?php echo _e( 'Montserrat', 'tc' ); ?></option>
                <option value="Oswald" <?php selected( $element_att_font_family, 'Oswald', true ); ?>><?php echo _e( 'Oswald', 'tc' ); ?></option>
                <option value="Indie Flower" <?php selected( $element_att_font_family, 'Indie Flower', true ); ?>><?php echo _e( 'Indie Flower', 'tc' ); ?></option>
                <option value="Faster One" <?php selected( $element_att_font_family, 'Faster One', true ); ?>><?php echo _e( 'Faster One', 'tc' ); ?></option>
            </select>
            <?php
        }

        function tcpdf_get_fonts( $prefix = 'document', $default_font = 'helvetica' ) { ?>
            <label><?php _e('Font', 'tc'); ?></label>
            <select name="document_font_post_meta">
                <?php $template_prefix_font = isset( $this->template_metas[$prefix . '_font'] ) ? $this->template_metas[$prefix . '_font'] : $default_font; ?>
                <option value='aealarabiya' <?php selected( $template_prefix_font, 'aealarabiya', true ); ?>><?php _e( 'Al Arabiya', 'tc' ); ?></option>
                <option value='aefurat' <?php selected( $template_prefix_font, 'aefurat', true ); ?>><?php _e( 'Furat', 'tc' ); ?></option>
                <option value='cid0cs' <?php selected( $template_prefix_font, 'cid0cs', true ); ?>><?php _e( 'Arial Unicode MS (Simplified Chinese)', 'tc' ); ?></option>
                <option value='cid0jp' <?php selected( $template_prefix_font, 'cid0jp', true ); ?>><?php _e( 'Arial Unicode MS (Japanese)', 'tc' ); ?></option>
                <option value='cid0kr' <?php selected( $template_prefix_font, 'cid0kr', true ); ?>><?php _e( 'Arial Unicode MS (Korean)', 'tc' ); ?></option>
                <option value='courier <?php selected( $template_prefix_font, 'courier', true ); ?>'><?php _e( 'Courier', 'tc' ); ?></option>
                <option value='dejavusans' <?php selected( $template_prefix_font, 'dejavusans', true ); ?>><?php _e( 'DejaVu Sans', 'tc' ); ?></option>
                <option value='dejavusanscondensed' <?php selected( $template_prefix_font, 'dejavusanscondensed', true ); ?>><?php _e( 'DejaVu Sans Condensed', 'tc' ); ?></option>
                <option value='dejavusansextralight' <?php selected( $template_prefix_font, 'dejavusansextralight', true ); ?>><?php _e( 'DejaVu Sans ExtraLight', 'tc' ); ?></option>
                <option value='dejavusansmono' <?php selected( $template_prefix_font, 'dejavusansmono', true ); ?>><?php _e( 'DejaVu Sans Mono', 'tc' ); ?></option>
                <option value='dejavuserif' <?php selected( $template_prefix_font, 'dejavuserif', true ); ?>><?php _e( 'DejaVu Serif', 'tc' ); ?></option>
                <option value='dejavuserifcondensed' <?php selected( $template_prefix_font, 'dejavuserifcondensed', true ); ?>><?php _e( 'DejaVu Serif Condensed', 'tc' ); ?></option>
                <option value='freemono' <?php selected( $template_prefix_font, 'freemono', true ); ?>><?php _e( 'FreeMono', 'tc' ); ?></option>
                <option value='freesans' <?php selected( $template_prefix_font, 'freesans', true ); ?>><?php _e( 'FreeSans', 'tc' ); ?></option>
                <option value='freeserif' <?php selected( $template_prefix_font, 'freeserif', true ); ?>><?php _e( 'FreeSerif', 'tc' ); ?></option>
                <option value='helvetica' <?php selected( $template_prefix_font, 'helvetica', true ); ?>><?php _e( 'Helvetica', 'tc' ); ?></option>
                <option value='hysmyeongjostdmedium' <?php selected( $template_prefix_font, 'hysmyeongjostdmedium', true ); ?>><?php _e( 'MyungJo Medium (Korean)', 'tc' ); ?></option>
                <option value='kozgopromedium' <?php selected( $template_prefix_font, 'kozgopromedium', true ); ?>><?php _e( 'Kozuka Gothic Pro (Japanese Sans-Serif)', 'tc' ); ?></option>
                <option value='kozminproregular' <?php selected( $template_prefix_font, 'kozminproregular', true ); ?>><?php _e( 'Kozuka Mincho Pro (Japanese Serif)', 'tc' ); ?></option>
                <option value='msungstdlight' <?php selected( $template_prefix_font, 'msungstdlight', true ); ?>><?php _e( 'MSung Light (Traditional Chinese)', 'tc' ); ?></option>
                <option value='pdfacourier' <?php selected( $template_prefix_font, 'pdfacourier', true ); ?>><?php _e( 'PDFA Courier', 'tc' ); ?></option>
                <option value='pdfahelvetica' <?php selected( $template_prefix_font, 'pdfahelvetica', true ); ?>><?php _e( 'PDFA Helvetica', 'tc' ); ?></option>
                <option value='pdfatimes' <?php selected( $template_prefix_font, 'pdfatimes', true ); ?>><?php _e( 'PDFA Times', 'tc' ); ?></option>
                <option value='stsongstdlight' <?php selected( $template_prefix_font, 'stsongstdlight', true ); ?>><?php _e( 'STSong Light (Simplified Chinese)', 'tc' ); ?></option>
                <option value='symbol' <?php selected( $template_prefix_font, 'symbol', true ); ?>><?php _e( 'Symbol', 'tc' ); ?></option>
                <option value='times' <?php selected( $template_prefix_font, 'times', true ); ?>><?php _e( 'Times-Roman', 'tc' ); ?></option>
                <option value='thsarabun' <?php selected( $template_prefix_font, 'thsarabun', true ); ?>><?php _e( 'Sarabun (Thai)', 'tc' ); ?></option>
                <?php do_action( 'tc_ticket_font', isset( $this->template_metas[$prefix . '_font'] ) ? $this->template_metas[$prefix . '_font'] : '', $default_font ); ?>
            </select>
            <?php
        }

        function get_font_sizes( $box_title = false, $default_font_size = false ) { ?>
            <label><?php
                if ( $box_title ) {
                    echo $box_title;

                } else {
                    _e( 'Font Size', 'tc' );
                }
                ?></label>
            <select name="<?php echo esc_attr( $this->element_name ); ?>_font_size_post_meta" class="tc_att_font_size">
                <?php
                for ( $i = 8; $i <= 100; $i++ ) { ?>
                    <option value='<?php echo $i; ?>' <?php selected( isset( $this->template_metas[$this->element_name . '_font_size'] ) ? $this->template_metas[$this->element_name . '_font_size'] : ( $default_font_size ? $default_font_size : 14 ), $i, true ); ?>><?php echo $i; ?> pt</option>
                    <?php
                }
                ?>
            </select>
            <?php
        }

        function get_default_text_value( $text ) { ?>
            <div class="tc_att_default_text_value default_text_value"><?php echo $text; ?></div>
            <?php
        }

    }

}

function tc_register_template_element( $class_name, $element_title ) {
    global $tc_template_elements;

    if ( !is_array( $tc_template_elements ) ) {
        $tc_template_elements = array();
    }

    if ( class_exists( $class_name ) ) {
        $tc_template_elements[] = array( $class_name, $element_title );

    } else {
        return false;
    }
}
?>