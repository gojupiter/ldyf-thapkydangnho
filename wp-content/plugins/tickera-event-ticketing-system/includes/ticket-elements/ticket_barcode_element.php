<?php
class tc_ticket_barcode_element_core extends TC_Ticket_Template_Elements {

    var $element_name	 = 'tc_ticket_barcode_element_core';
    var $element_title	 = 'Barcode';
    var $font_awesome_icon = '<i class="fa fa-barcode"></i>';

    function on_creation() {
        $this->element_title = apply_filters( 'tc_ticket_barcode_element_title', __( 'Barcode', 'bcr' ) );
    }

    function admin_content() {
        echo $this->get_1d_barcode_types();
        echo $this->get_1d_barcode_text_visibility();
        echo $this->get_1d_barcode_size();
        echo parent::get_font_sizes( 'Barcode Text Font Size (if visible)', 8 );
        echo parent::get_cell_alignment();
        echo parent::get_element_margins();
    }

    function get_1d_barcode_size() {
        ?>
        <label><?php _e( 'Barcode Size', 'bcr' ); ?>
            <input class="ticket_element_padding" type="text" name="<?php echo $this->element_name; ?>_1d_barcode_size_post_meta" value="<?php echo esc_attr( isset( $this->template_metas[ $this->element_name . '_1d_barcode_size' ] ) ? $this->template_metas[ $this->element_name . '_1d_barcode_size' ] : '50'  ); ?>" />
        </label>
        <?php
    }

    function get_1d_barcode_text_visibility() {
        $text_visibility = (isset( $this->template_metas[ $this->element_name . '_barcode_text_visibility' ] ) ? $this->template_metas[ $this->element_name . '_barcode_text_visibility' ] : 'visible');
        ?>
        <label><?php _e( 'Barcode Text Visibility', 'bcr' ); ?></label>
        <select name="<?php echo $this->element_name; ?>_barcode_text_visibility_post_meta">
            <option value="visible" <?php selected( $text_visibility, 'visible', true ); ?>><?php echo _e( 'Visible', 'bcr' ); ?></option>
            <option value="invisible" <?php selected( $text_visibility, 'invisible', true ); ?>><?php echo _e( 'Invisible', 'bcr' ); ?></option>
        </select>
        <?php
    }

    function get_1d_barcode_types() {
        ?>
        <label><?php _e( 'Barcode Type', 'bcr' ); ?></label>
        <?php $barcode_type = isset( $this->template_metas[ $this->element_name . '_barcode_type' ] ) ? $this->template_metas[ $this->element_name . '_barcode_type' ] : 'C39'; ?>
        <select name="<?php echo $this->element_name; ?>_barcode_type_post_meta">
            <option value="C39" <?php selected( $barcode_type, 'C39', true ); ?>><?php echo _e( 'C39', 'bcr' ); ?></option>
            <option value="C39E" <?php selected( $barcode_type, 'C39E', true ); ?>><?php echo _e( 'C39E', 'bcr' ); ?></option>
            <option value="C93" <?php selected( $barcode_type, 'C93', true ); ?>><?php echo _e( 'C93', 'bcr' ); ?></option>
            <option value="C128" <?php selected( $barcode_type, 'C128', true ); ?>><?php echo _e( 'C128', 'bcr' ); ?></option>
            <option value="C128A" <?php selected( $barcode_type, 'C128A', true ); ?>><?php echo _e( 'C128A', 'bcr' ); ?></option>
            <option value="C128B" <?php selected( $barcode_type, 'C128B', true ); ?>><?php echo _e( 'C128B', 'bcr' ); ?></option>
            <option value="EAN2" <?php selected( $barcode_type, 'EAN2', true ); ?>><?php echo _e( 'EAN2', 'bcr' ); ?></option>
            <option value="EAN5" <?php selected( $barcode_type, 'EAN5', true ); ?>><?php echo _e( 'EAN5', 'bcr' ); ?></option>
            <option value="EAN13" <?php selected( $barcode_type, 'EAN13', true ); ?>><?php echo _e( 'EAN-13', 'bcr' ); ?></option>
            <option value="UPCA" <?php selected( $barcode_type, 'UPCA', true ); ?>><?php echo _e( 'UPCA', 'bcr' ); ?></option>
            <option value="UPCE" <?php selected( $barcode_type, 'UPCE', true ); ?>><?php echo _e( 'UPCE', 'bcr' ); ?></option>
            <option value="MSI" <?php selected( $barcode_type, 'MSI', true ); ?>><?php echo _e( 'MSI', 'bcr' ); ?></option>
            <option value="MSI+" <?php selected( $barcode_type, 'MSI+', true ); ?>><?php echo _e( 'MSI+', 'bcr' ); ?></option>
            <option value="RMS4CC" <?php selected( $barcode_type, 'RMS4CC', true ); ?>><?php echo _e( 'RMS4CC', 'bcr' ); ?></option>
            <option value="IMB" <?php selected( $barcode_type, 'IMB', true ); ?>><?php echo _e( 'IMB', 'bcr' ); ?></option>
            <?php do_action( 'tc_ticket_barcode_element_after_types_options', $barcode_type ); ?>
        </select>
        <span class="description"><?php _e( 'Following Barcode types are supported by the iOS check-in app: EAN-13, UPCA, C93, C128 </br><hr><strong>IMPORTANT:</strong> EAN-13 barcode type supports numeric characters only!</br>If you intend on using this barcode type, you must utilize <strong><a href="https://tickera.com/addons/serial-ticket-codes/">Serial Ticket Codes</a></strong> add-on and set ticket codes with maximum of 12 characters, without prefix and suffix.</br>For more information, please read <strong><a href="https://tickera.com/tickera-documentation/barcode-reader/">documentation</a></strong> on Barcode Reader add-on and always test ticket scanning prior going live with ticket sales.<hr>', 'bcr' ); ?></span>
        <?php
    }

    function ticket_content( $ticket_instance_id = false ) {

        global $tc, $pdf;

        $cell_alignment = isset( $this->template_metas[ $this->element_name . '_cell_alignment' ] ) ? $this->template_metas[ $this->element_name . '_cell_alignment' ] : 'N';

        switch ( $cell_alignment ) {

            case 'right':
                $cell_alignment = 'R';
                break;

            case 'left':
                $cell_alignment = 'L';
                break;

            case 'center':
                $cell_alignment = 'N';
                break;
        }

        $text_visibility = ( isset( $this->template_metas[ $this->element_name . '_barcode_text_visibility' ] ) ? $this->template_metas[ $this->element_name . '_barcode_text_visibility' ] : 'visible' );
        $text_visibility = ( 'visible' == $text_visibility ) ? true : false;
        $ticket_instance = $ticket_instance_id ? new TC_Ticket_Instance( $ticket_instance_id ) : [];

        $barcode_params = $pdf->serializeTCPDFtagParameters( array (
            ( $ticket_instance ) ? $ticket_instance->details->ticket_code : $tc->create_unique_id(), // Code value
            ( isset( $this->template_metas[ $this->element_name . '_barcode_type' ] ) ? $this->template_metas[ $this->element_name . '_barcode_type' ] : 'C128' ), // Type
            apply_filters( 'tc_barcode_element_x', '' ), // X
            apply_filters( 'tc_barcode_element_y', '' ), // Y
            isset( $this->template_metas[ $this->element_name . '_1d_barcode_size' ] ) ? $this->template_metas[ $this->element_name . '_1d_barcode_size' ] : 50, // W
            apply_filters( 'tc_barcode_element_h', 0 ), // H
            apply_filters( 'tc_barcode_element_xres', 0.4 ), // Xres
            array (
                'position'		 => apply_filters( 'tc_barcode_element_cell_alignment', $cell_alignment ),
                'border'		 => apply_filters( 'tc_show_barcode_border', true ),
                'padding'		 => apply_filters( 'tc_barcode_padding', 2 ),
                'fgcolor'		 => tc_hex2rgb( '#000000' ), // Black (don't change it or won't be readable by the barcode reader)
                'bgcolor'		 => tc_hex2rgb( '#ffffff' ), // White (don't change it or won't be readable by the barcode reader)
                'text'			 => $text_visibility,
                'font'			 => apply_filters( 'tc_1d_barcode_font', 'helvetica' ),
                'fontsize'		 => isset( $this->template_metas[ $this->element_name . '_font_size' ] ) ? $this->template_metas[ $this->element_name . '_font_size' ] : 8,
                'cellfitalign'	 => apply_filters( 'tc_barcode_element_cellfitalign', true ),
                'stretchtext'	 => apply_filters( 'tc_barcode_element_stretchtext', 0 ) ),
            'N' ) );

        return '<tcpdf method="write1DBarcode" params="' . apply_filters( 'tc_barcode_element_params', $barcode_params) . '" />';
    }
}

tc_register_template_element( 'tc_ticket_barcode_element_core', __( 'Barcode', 'bcr' ) );
