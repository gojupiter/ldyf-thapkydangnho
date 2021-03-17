<div class="lahfb-predefined-modal-inner-content">
    <?php
    $assetsPrebuilds = 'assets/dist/images/prebuilds/';

    $lahfb_preheaders = LAHFB_Helper::get_prebuild_headers();

    $current_url = admin_url( 'admin.php?page=lastudio_header_builder_setting' );

    if(LAHFB_Helper::is_frontend_builder()){
        $current_url = admin_url( 'admin.php?page=lastudio_header_builder' );
    }

    if(!empty($lahfb_preheaders)){
        foreach ($lahfb_preheaders as $k => $v){
            if(!empty($v['image']) && wp_attachment_is_image($v['image'])){
                $tmp_name = '<img src="'.wp_get_attachment_image_url($v['image'],'full').'" alt="'.esc_attr($v['name']).'"/>';
            }
            elseif( !empty($v['image_url']) ){
                $tmp_name = '<img src="'.$v['image_url'].'" alt="'.esc_attr($v['name']).'"/>';
            }
            else{
                $tmp_name = '<span>' . $v['name'] . '</span>';
            }
            echo sprintf(
                '<a class="lahfb-prebuild-item" data-saved-name="%s" href="%s">%s<i class="fa fa-remove"></i></a>',
                esc_attr($k),
                add_query_arg(array('prebuild_header' => esc_attr($k)), $current_url ),
                $tmp_name
            );
        }
    }
    ?>
    <?php /*
<!-- corporate -->
<a class="lahfb-prebuild-item" data-file-name="{{json_file_name}} href="#">
    <img src="<?php echo LAHFB_Helper::get_file_uri( $assetsPrebuilds . '{{preview}}' ) ?>">
</a>
 */
    ?>
</div>