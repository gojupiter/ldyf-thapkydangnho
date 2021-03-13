<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_index         = absint(draven_get_theme_loop_prop('loop_index', 1));

$is_main_loop       = draven_get_theme_loop_prop('is_main_loop', false);
$loop_name          = draven_get_theme_loop_prop('loop_name', false);
$show_thumbnail     = draven_get_theme_loop_prop('show_thumbnail', true);
$layout             = draven_get_theme_loop_prop('loop_layout', 'grid');
$style              = draven_get_theme_loop_prop('loop_style', 1);
$thumbnail_size     = draven_get_theme_loop_prop('image_size', 'thumbnail');
$title_tag          = draven_get_theme_loop_prop('title_tag', 'h3');
$excerpt_length     = draven_get_theme_loop_prop('excerpt_length', 0);
$show_excerpt       = absint($excerpt_length) > 0 ? true : false;
$responsive_column  = draven_get_theme_loop_prop('responsive_column', array());

$height_mode        = draven_get_theme_loop_prop('height_mode', 'original');
$thumb_custom_height= draven_get_theme_loop_prop('height', '');

if($is_main_loop){
    $height_mode = Draven()->settings()->get('blog_thumbnail_height_mode', $height_mode);
    $thumb_custom_height = Draven()->settings()->get('blog_thumbnail_height_custom', $thumb_custom_height);
}

$post_class = array('blog__item', 'grid-item', 'lastudio-posts__item');

if($layout != 'list' || ( $layout == 'list' && $style != 1 ) ){
    $post_class[] = 'loop__item';
}

$post_class[] = ($show_excerpt ? 'show' : 'hide') . '-excerpt';

$thumb_css_style = '';

if ( 'original' !== $height_mode ) {
    $thumb_css_class = ' gitem-zone-height-mode-' . $height_mode;
}
else{
    $thumb_css_class = ' gitem-zone-height-mode-original2';
}

$thumb_src = '';
$thumb_width = $thumb_height = 0;
if (!has_post_thumbnail() || ($is_main_loop && !$show_thumbnail)) {
    $post_class[] = 'no-featured-image';
}
else{
    if($thumbnail_obj = Draven()->images()->get_attachment_image_src( get_post_thumbnail_id(), $thumbnail_size )){
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

if ( 'custom' === $height_mode ) {
    if ( strlen( $thumb_custom_height ) > 0 ) {
        if ( preg_match( '/^\d+$/', $thumb_custom_height ) ) {
            $thumb_custom_height .= 'px';
        }
        $thumb_css_style = 'padding-bottom: ' . $thumb_custom_height . ';';
        $thumb_css_class .= ' gitem-hide-img';
    }
}
elseif ( 'original' !== $height_mode ) {
	$thumb_css_style = '';
    $thumb_css_class .= ' gitem-hide-img gitem-zone-height-mode-auto' . ( strlen( $height_mode ) > 0 ? ' gitem-zone-height-mode-auto-' . $height_mode : '' );
}

$allow_featured_image = true;
if($is_main_loop && !$show_thumbnail){
    $allow_featured_image = false;
    $post_class[] = 'no-featured-image';
}

if($style == 'echeck' && !has_post_thumbnail()){
    $allow_featured_image = false;
    $post_class[] = 'no-featured-image';
}

$use_lazy_load = Draven_Helper::is_enable_image_lazy();
if($use_lazy_load){
    $thumb_css_class .= ' la-lazyload-image';
}

?>
<div <?php post_class($post_class); ?>>
    <div class="loop__item__inner lastudio-posts__inner-box">
        <div class="loop__item__inner2">
            <?php if( $allow_featured_image ) : ?>
            <div class="post-thumbnail loop__item__thumbnail">
                <?php
                if($is_main_loop && 'gallery' == get_post_format() && $style != 5){
                    $galleries = draven_get_image_for_post_type_gallery(get_the_ID(), $thumbnail_size);
                    $gallery_html = '';

                    $_thumb_css_style = $thumb_css_style;

                    foreach($galleries as $gallery){
                        if(!$use_lazy_load){
                            $_thumb_css_style = $thumb_css_style . sprintf('background-image: url(%s);', $gallery);
                        }
                        $gallery_html .= sprintf(
                            '<div class="g-item"><div class="loop__item__thumbnail--bkg %1$s" data-background-image="%2$s" style="%3$s"></div></div>',
                            esc_attr($thumb_css_class),
                            $gallery,
                            esc_attr($_thumb_css_style)
                        );
                    }
                    echo sprintf(
                        '<div data-la_component="AutoCarousel" class="js-el la-slick-slider" data-slider_config="%1$s">%2$s</div>',
                        esc_attr(json_encode(array(
                            'slidesToShow' => 1,
                            'slidesToScroll' => 1,
                            'dots' => false,
                            'arrows' => true,
                            'speed' => 300,
                            'autoplay' => false,
                            'infinite' => false,
                            'prevArrow'=> '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                            'nextArrow'=> '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>'
                        ))),
                        $gallery_html
                    );
                }
                ?>
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>"
                     data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>"
                     style="<?php
                        if(!$use_lazy_load && !empty($thumb_src)){
                            $thumb_css_style .= sprintf('background-image: url(%s);', $thumb_src);
                        }
                        echo esc_attr($thumb_css_style);?>">
                    <?php
                    if('gallery' != get_post_format() && has_post_thumbnail()) {
                        if ( has_post_thumbnail() ) {
                            echo Draven()->images()->render_image( $thumb_src, array(
                                'width' => $thumb_width,
                                'height' => $thumb_height,
                                'alt' => get_the_title()
                            ) );
                            echo sprintf( '<a href="%s" title="%s" class="loop__item__thumbnail--linkoverlay" rel="nofollow"><span class="pf-icon pf-icon-link"></span><span class="item--overlay"></span></a>', esc_url( get_the_permalink() ), the_title_attribute( array( 'echo' => false ) ) );
                        }
                    }
                ?></div>
                <?php

                if($is_main_loop && 'quote' == get_post_format()){
                    draven_get_image_for_post_type_quote(get_the_ID());
                }
                ?>
            </div>
            <?php endif; ?>
            <div class="lastudio-posts__inner-content">
                <div class="post-meta post-meta-top"><?php
                    draven_entry_meta_item_postdate();
                    draven_entry_meta_item_author();
                    draven_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>', '');
                ?></div>
                <?php
                    echo sprintf(
                        '<%1$s class="entry-title"><a href="%2$s">%3$s</a></%1$s>',
                        esc_attr($title_tag),
                        esc_url(get_the_permalink()),
                        esc_html(get_the_title())
                    );
                ?>

                <div class="post-meta post-meta-bottom"><?php
                    draven_entry_meta_item_postdate();
                    draven_entry_meta_item_author();
                ?></div>
                <div class="entry-excerpt"><?php echo draven_get_the_excerpt( $excerpt_length ); ?></div>
                <div class="lastudio-more-wrap"><a href="<?php the_permalink(); ?>" class="btn elementor-button lastudio-more"><span class="btn__text"><?php echo esc_html_x('Read more', 'front-view', 'draven'); ?></span></a></div>
            </div>
        </div>
    </div>
</div>