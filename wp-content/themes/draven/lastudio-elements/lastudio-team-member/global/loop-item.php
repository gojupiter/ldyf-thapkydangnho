<?php
/**
 * team-member loop start template
 */

$role           = Draven()->settings()->get_post_meta(get_the_ID(), 'role');
$thumbnail_size = $this->get_attr( 'thumb_size' );
$thumb_src = '';
$thumb_width = $thumb_height = 0;
$thumb_css_class = '';
$thumb_css_style = '';
if(has_post_thumbnail()){
    if($thumbnail_obj = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumbnail_size )){
        list( $thumb_src, $thumb_width, $thumb_height ) = $thumbnail_obj;
        if( $thumb_width > 0 && $thumb_height > 0 ) {
            $thumb_css_style .= 'padding-bottom:' . round( ($thumb_height/$thumb_width) * 100, 2 ) . '%;';
            if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
                $photon_args = array(
                    'resize' => $thumb_width . ',' . $thumb_height
                );
                $thumb_src = wp_get_attachment_url( get_post_thumbnail_id() );
                $thumb_src = jetpack_photon_url( $thumb_src, $photon_args );
            }
        }
    }
}

$preset = $this->get_attr( 'preset' );

if($preset != 'type-4'){
    $thumb_css_class .= ' img-perspective';
}

$use_lazy_load = Draven_Helper::is_enable_image_lazy();
if($use_lazy_load){
    $thumb_css_class .= ' la-lazyload-image';
}
else{
    $thumb_css_style .= sprintf("background-image: url('%s');", esc_url($thumb_src));
}

$post_link = get_the_permalink();

?>
<div class="lastudio-team-member__item <?php echo lastudio_elements_tools()->col_classes( array(
    'desk' => $this->get_attr( 'columns' ),
    'lap' => $this->get_attr( 'columns_laptop' ),
    'tab' => $this->get_attr( 'columns_tablet' ),
    'tabp'  => $this->get_attr( 'columns_width800' ),
    'mob'  => $this->get_attr( 'columns_mobile' ),
    'mobp'  => $this->get_attr( 'columns_mobile' )
) ); ?>">
    <div class="lastudio-team-member__inner-box">
        <div class="lastudio-team-member__inner">
            <div class="lastudio-team-member__image">
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>" data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>" style="<?php echo esc_attr($thumb_css_style); ?>">
                    <a href="<?php echo esc_url($post_link); ?>" title="<?php the_title_attribute(); ?>" class="loop__item__thumbnail--linkoverlay"><span class="hidden"><?php the_title(); ?></span></a>
                    <?php the_post_thumbnail(); ?>
                </div>
                <div class="lastudio-team-member__cover">
                    <?php if($preset != 'type-1' && $preset != 'type-4'): ?>
                        <div class="lastudio-team-member__content">
                            <h3 class="lastudio-team-member__name"><a href="<?php echo esc_url($post_link); ?>"><?php the_title();?></a></h3>
                            <?php if(!empty($role)){
                                echo sprintf('<div class="lastudio-team-member__position"><span>%s</span></div>', esc_html($role));
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if($preset != 'type-4'): ?>
                        <div class="lastudio-team-member__socials">
                            <?php Draven()->layout()->render_member_social_tpl(get_the_ID()); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if($preset == 'type-1' || $preset == 'type-4'): ?>
                <div class="lastudio-team-member__content">
                    <h3 class="lastudio-team-member__name"><a href="<?php echo esc_url($post_link); ?>"><?php the_title();?></a></h3>
                    <?php if(!empty($role)){
                        echo sprintf('<div class="lastudio-team-member__position"><span>%s</span></div>', esc_html($role));
                    }
                    ?>
                    <?php if($preset == 'type-4'): ?>
                        <div class="lastudio-team-member__socials">
                            <?php Draven()->layout()->render_member_social_tpl(get_the_ID()); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>