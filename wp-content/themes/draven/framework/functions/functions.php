<?php if ( ! defined( 'ABSPATH' ) ) { die; }

if(!function_exists('draven_render_variable')){
    function draven_render_variable( $html ) {
        return $html;
    }
}

if(!function_exists('draven_deactive_filter')){
    function draven_deactive_filter( $tag, $function_to_remove, $priority = 10) {
        return call_user_func('remove_filter', $tag, $function_to_remove, $priority );
    }
}

if(!function_exists('draven_entry_meta_item_postdate')){
    function draven_entry_meta_item_postdate() {

        global $post;

        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
        }

        $h_time = get_the_date();
        $time = get_post_time( 'G', true, $post, false );
        if ( ( abs( $t_diff = time() - $time ) ) < DAY_IN_SECONDS ) {
            if ( $t_diff < 0 ) {
                $h_time = sprintf( _x( '%s from now', '%s = human-readable time difference', 'draven' ), human_time_diff( $time ) );
            }
            else {
                $h_time = sprintf( _x( '%s ago', '%s = human-readable time difference', 'draven'), human_time_diff( $time ) );
            }
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( $h_time ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );
        printf(
            '<span class="post__date post-meta__item"><a href="%1$s" class="post__date-link"><span>%2$s </span>%3$s</a></span>',
            esc_url( get_permalink() ),
            esc_html_x('Posted on', 'front-view', 'draven'),
            $time_string
        );
    }
}
if(!function_exists('draven_entry_meta_item_author')){
    function draven_entry_meta_item_author( $show_avatar = false ){
        printf(
            '<span class="posted-by post-meta__item">%2$s <a class="posted-by__author" rel="author" href="%1$s">%3$s</a></span>',
            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
            esc_html_x('By', 'front-view', 'draven'),
            esc_html( get_the_author() )
        );
    }
}
if(!function_exists('draven_entry_meta_item_category_list')){
    function draven_entry_meta_item_category_list($before = '', $after = '', $separator = ', ', $parents = '', $post_id = false){
        add_filter('get_the_terms', 'draven_exclude_demo_term_in_category');
        $categories_list = get_the_category_list('{{_}}', $parents, $post_id );
        draven_deactive_filter('get_the_terms', 'draven_exclude_demo_term_in_category');
        if ( $categories_list ) {
            printf(
                '%3$s<span class="screen-reader-text">%1$s</span> <span>%2$s</span>%4$s',
                esc_html_x('Posted in', 'front-view', 'draven'),
                str_replace('{{_}}', $separator, $categories_list),
                $before,
                $after
            );
        }
    }
}

if(!function_exists('draven_exclude_demo_term_in_category')){
    function draven_exclude_demo_term_in_category( $term ){
        return apply_filters('draven/post_category_excluded', $term);
    }
}

if(!function_exists('draven_entry_meta_item_comment_post_link')){
    function draven_entry_meta_item_comment_post_link(){
        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<div class="comments-link">';
            comments_popup_link();
            echo '</div>';
        }
    }
}

if(!function_exists('draven_get_the_term_list')){
    function draven_get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $limit = 0 ) {
        $terms = get_the_terms( $id, $taxonomy );
        if ( is_wp_error( $terms ) )
            return $terms;

        if ( empty( $terms ) )
            return false;

        $links = array();

        $limit = absint($limit);
        $_counter = 1;

        foreach ( $terms as $term ) {
            if($limit > 0 && $_counter > $limit){
                break;
            }
            $_counter++;
            $link = get_term_link( $term, $taxonomy );
            if ( is_wp_error( $link ) ) {
                return $link;
            }
            $links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
        }

        $term_links = apply_filters( "term_links-{$taxonomy}", $links );

        return $before . join( $sep, $term_links ) . $after;
    }
}

if(!function_exists('draven_entry_meta_item_comment_post_link_with_icon')){
    function draven_entry_meta_item_comment_post_link_with_icon(){
        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

            echo '<div class="comments-link">';
            comments_popup_link('<i class="fa fa-comment-o"></i>', '<i class="fa fa-comment-o"></i><span>1</span>', '<i class="fa fa-comment-o"></i><span>%</span>');
            echo '</div>';
        }
    }
}

if(!function_exists('draven_entry_meta_item_post_love')) {
    function draven_entry_meta_item_post_love()
    {
        echo '<span class="post-love-count">';
        $post_love_count = get_post_meta(get_the_ID(), '_la_love_count', true);
        printf(
            '<a data-post-id="%s" href="%s">%s</a>',
            esc_attr(get_the_ID()),
            esc_url( get_permalink() ),
            absint($post_love_count)
        );
        echo '</span>';
    }
}

if(!function_exists('draven_single_post_thumbnail')){
    function draven_single_post_thumbnail( $thumbnail_size = 'full'){
        if ( post_password_required() || is_attachment() ) {
            return;
        }
        $flag_format_content = false;

        switch(get_post_format()){
            case 'link':
                $link = Draven()->settings()->get_post_meta( get_the_ID(), 'format_link' );
                if(!empty($link)){
                    printf(
                        '<div class="entry-thumbnail blog_item--thumbnail format-link" %2$s><div class="format-content">%1$s</div><a class="post-link-overlay" href="%1$s"></a></div>',
                        esc_url($link),
                        has_post_thumbnail() ? 'style="background-image:url('.Draven()->images()->get_post_thumbnail_url(get_the_ID()).')"' : ''
                    );
                    $flag_format_content = true;
                }
                break;
            case 'quote':
                $quote_content = Draven()->settings()->get_post_meta(get_the_ID(), 'format_quote_content');
                $quote_author = Draven()->settings()->get_post_meta(get_the_ID(), 'format_quote_author');
                $quote_background = Draven()->settings()->get_post_meta(get_the_ID(), 'format_quote_background');
                $quote_color = Draven()->settings()->get_post_meta(get_the_ID(), 'format_quote_color');
                if(has_post_thumbnail() && !empty($quote_content)) {
                    echo '<div class="entry-thumbnail single_post_quote_wrap">';
                    Draven()->images()->the_post_thumbnail( get_the_ID(), $thumbnail_size );
                    $quote_content = '<p class="quote-content">' . $quote_content . '</p>';
                    if ( !empty( $quote_author ) ) {
                        $quote_content .= '<span class="quote-author">' . $quote_author . '</span>';
                    }
                    $styles = array();
                    $styles[] = 'background-color:' . $quote_background;
                    $styles[] = 'color:' . $quote_color;
                    echo sprintf( '<div class="quote-wrapper" style="%2$s"><div class="format-content">%1$s</div></div>', $quote_content, esc_attr( implode( ';', $styles ) ) );
                    $flag_format_content = true;
                    echo '</div>';
                }

                break;

            case 'gallery':
                $ids = Draven()->settings()->get_post_meta(get_the_ID(), 'format_gallery');
                $ids = explode(',', $ids);
                $ids = array_map('trim', $ids);
                $ids = array_map('absint', $ids);
                $__tmp = '';
                if(!empty( $ids )){
                    foreach($ids as $image_id){
                        if(wp_attachment_is_image($image_id)){
                            $__tmp .= sprintf('<div>%2$s</div>',
                                get_the_permalink(),
                                Draven()->images()->get_attachment_image( $image_id, $thumbnail_size)
                            );
                        }
                    }
                }
                if(has_post_thumbnail()){
                    $__tmp .= sprintf('<div>%2$s</div>',
                        get_the_permalink(),
                        Draven()->images()->get_post_thumbnail(get_the_ID(), $thumbnail_size )
                    );
                }
                if(!empty($__tmp)){
                    printf(
                        '<div class="entry-thumbnail"><div class="loop__item__thumbnail blog_item--thumbnail format-gallery"><div data-la_component="AutoCarousel" class="js-el la-slick-slider" data-slider_config="%1$s">%2$s</div></div></div>',
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
                        $__tmp
                    );
                    $flag_format_content = true;
                }
                break;

            case 'audio':
            case 'video':
                $embed_source = Draven()->settings()->get_post_meta(get_the_ID(), 'format_embed');
                $embed_aspect_ration = Draven()->settings()->get_post_meta(get_the_ID(), 'format_embed_aspect_ration');
                if(!empty($embed_source)){
                    $flag_format_content = true;
                    printf(
                        '<div class="entry-thumbnail blog_item--thumbnail format-embed"><div class="la-media-wrapper la-media-aspect-%2$s">%1$s</div></div>',
                        $embed_source,
                        esc_attr($embed_aspect_ration ? $embed_aspect_ration : 'origin')
                    );
                }
                break;
        }

        if(!$flag_format_content && has_post_thumbnail()){ ?>
            <div class="entry-thumbnail">
                <a<?php
                if( 'video' == get_post_format() && ( $popup_video_link = Draven()->settings()->get_post_meta(get_the_ID(), 'format_video_url') ) && !empty($popup_video_link) ){
                    printf(' href="%s" class="la-popup"', $popup_video_link );
                }
                else{
                    ?> href="<?php the_permalink();?>"<?php
                }
                ?>>
                    <?php Draven()->images()->the_post_thumbnail(get_the_ID(), $thumbnail_size); ?>
                    <span class="pf-icon pf-icon-<?php echo get_post_format() ? get_post_format() : 'standard' ?>"></span>
                </a>
            </div>
            <?php
        }

    }
}

if(!function_exists('draven_get_image_for_post_type_gallery')){
    function draven_get_image_for_post_type_gallery($post_id, $thumbnail_size = 'full'){
        $ids = Draven()->settings()->get_post_meta( $post_id, 'format_gallery' );
        $ids = explode(',', $ids);
        $ids = array_map('trim', $ids);
        $ids = array_map('absint', $ids);
        $return_ids = array();
        if(has_post_thumbnail($post_id)){
            $return_ids[] = get_the_post_thumbnail_url($post_id, $thumbnail_size);
        }
        if(!empty( $ids )){
            foreach($ids as $image_id){
                if(wp_attachment_is_image($image_id)){
                    $return_ids[] = wp_get_attachment_image_url( $image_id, $thumbnail_size );
                }
            }
        }
        return $return_ids;
    }
}

if(!function_exists('draven_get_image_for_post_type_quote')){
    function draven_get_image_for_post_type_quote($post_id){
        $quote_content = Draven()->settings()->get_post_meta($post_id, 'format_quote_content');
        $quote_author = Draven()->settings()->get_post_meta($post_id, 'format_quote_author');
        $quote_background = Draven()->settings()->get_post_meta($post_id, 'format_quote_background');
        $quote_color = Draven()->settings()->get_post_meta($post_id, 'format_quote_color');
        if(!empty($quote_content)) {
            echo '<div class="single_post_quote_wrap">';
            $quote_content = '<p class="quote-content">' . $quote_content . '</p>';
            if ( !empty( $quote_author ) ) {
                $quote_content .= '<span class="quote-author">' . $quote_author . '</span>';
            }
            $styles = array();
            $styles[] = 'background-color:' . $quote_background;
            $styles[] = 'color:' . $quote_color;
            echo sprintf( '<div class="quote-wrapper" style="%2$s"><div class="format-content">%1$s</div></div>', $quote_content, esc_attr( implode( ';', $styles ) ) );
            echo '</div>';
        }
    }
}

if(!function_exists('draven_social_sharing')){
    function draven_social_sharing( $post_link = '', $post_title = '', $image = '', $post_excerpt = '', $echo = true){
        if(empty($post_link) || empty($post_title)){
            return;
        }
        if(!$echo){
            ob_start();
        }
        echo '<span class="social--sharing">';
        if(draven_string_to_bool(Draven()->settings()->get('sharing_facebook'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="facebook" title="%2$s"><i class="fa fa-facebook"></i></a>',
                esc_url( 'https://www.facebook.com/sharer.php?u=' . $post_link ),
                esc_attr_x('Share this post on Facebook', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_twitter'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="twitter" title="%2$s"><i class="fa fa-twitter"></i></a>',
                esc_url( 'https://twitter.com/intent/tweet?text=' . $post_title . '&url=' . $post_link ),
                esc_attr_x('Share this post on Twitter', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_reddit'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="reddit" title="%2$s"><i class="fa fa-reddit"></i></a>',
                esc_url( 'https://www.reddit.com/submit?url=' . $post_link . '&title=' . $post_title ),
                esc_attr_x('Share this post on Reddit', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_linkedin'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="linkedin" title="%2$s"><i class="fa fa-linkedin"></i></a>',
                esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_link . '&title=' . $post_title ),
                esc_attr_x('Share this post on Linked In', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_tumblr'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="tumblr" title="%2$s"><i class="fa fa-tumblr"></i></a>',
                esc_url( 'https://www.tumblr.com/share/link?url=' . $post_link ) ,
                esc_attr_x('Share this post on Tumblr', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_pinterest'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="pinterest" title="%2$s"><i class="fa fa-pinterest-p"></i></a>',
                esc_url( 'https://pinterest.com/pin/create/button/?url=' . $post_link . '&media=' . $image . '&description=' . $post_title) ,
                esc_attr_x('Share this post on Pinterest', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_google_plus'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="google-plus" title="%2$s"><i class="fa fa-google-plus"></i></a>',
                esc_url( 'https://plus.google.com/share?url=' . $post_link ),
                esc_attr_x('Share this post on Google Plus', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_line'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="network-line" title="%2$s"><span><svg class="dlicon-networkline" width="14px" height="14px" viewBox="0 0 448 512"><path fill="currentColor" d="M272.1 204.2v71.1c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.1 0-2.1-.6-2.6-1.3l-32.6-44v42.2c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.8 0-3.2-1.4-3.2-3.2v-71.1c0-1.8 1.4-3.2 3.2-3.2H219c1 0 2.1.5 2.6 1.4l32.6 44v-42.2c0-1.8 1.4-3.2 3.2-3.2h11.4c1.8-.1 3.3 1.4 3.3 3.1zm-82-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 1.8 1.4 3.2 3.2 3.2h11.4c1.8 0 3.2-1.4 3.2-3.2v-71.1c0-1.7-1.4-3.2-3.2-3.2zm-27.5 59.6h-31.1v-56.4c0-1.8-1.4-3.2-3.2-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 .9.3 1.6.9 2.2.6.5 1.3.9 2.2.9h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.7-1.4-3.2-3.1-3.2zM332.1 201h-45.7c-1.7 0-3.2 1.4-3.2 3.2v71.1c0 1.7 1.4 3.2 3.2 3.2h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2V234c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2v-11.4c-.1-1.7-1.5-3.2-3.2-3.2zM448 113.7V399c-.1 44.8-36.8 81.1-81.7 81H81c-44.8-.1-81.1-36.9-81-81.7V113c.1-44.8 36.9-81.1 81.7-81H367c44.8.1 81.1 36.8 81 81.7zm-61.6 122.6c0-73-73.2-132.4-163.1-132.4-89.9 0-163.1 59.4-163.1 132.4 0 65.4 58 120.2 136.4 130.6 19.1 4.1 16.9 11.1 12.6 36.8-.7 4.1-3.3 16.1 14.1 8.8 17.4-7.3 93.9-55.3 128.2-94.7 23.6-26 34.9-52.3 34.9-81.5z"></path></svg></span></a>',
                esc_url( 'https://social-plugins.line.me/lineit/share?url=' . $post_link ),
                esc_attr_x('LINE it!', 'front-view', 'draven')
            );

        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_vk'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="vk" title="%2$s"><i class="fa fa-vk"></i></a>',
                esc_url( 'http://vkontakte.ru/share.php?url=' . $post_link . '&title=' . $post_title ) ,
                esc_attr_x('Share this post on VK', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_whatapps'))){
            printf('<a href="%1$s" rel="nofollow" class="whatsapp" data-action="share/whatsapp/share" title="%2$s"><i class="fa fa-whatsapp"></i></a>',
                'whatsapp://send?text=' . esc_attr( $post_title . ' ' . $post_link ),
                esc_attr_x('Share via Whatsapp', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_telegram'))){
            printf('<a href="%1$s" rel="nofollow" class="telegram" title="%2$s"><i class="fa fa-telegram"></i></a>',
                esc_attr( add_query_arg(array( 'url' => $post_link, 'text' => $post_title ), 'https://telegram.me/share/url') ),
                esc_attr_x('Share via Telegram', 'front-view', 'draven')
            );
        }
        if(draven_string_to_bool(Draven()->settings()->get('sharing_email'))){
            printf('<a target="_blank" href="%1$s" rel="nofollow" class="email" title="%2$s"><i class="fa fa-envelope"></i></a>',
                esc_url( 'mailto:?subject=' . $post_title . '&body=' . $post_link ),
                esc_attr_x('Share this post via Email', 'front-view', 'draven')
            );
        }
        echo '</span>';
        if(!$echo){
            return ob_get_clean();
        }
    }
}

if(!function_exists('draven_the_pagination')){
    function draven_the_pagination($args = array(), $query = null) {
        if(null === $query) {
            $query = $GLOBALS['wp_query'];
        }
        if($query->max_num_pages < 2) {
            return;
        }
        $paged        = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $wp_rewrite  = $GLOBALS['wp_rewrite'];
        $query_args   = array();
        $url_parts    = explode('?', $pagenum_link);
        if(isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format  = $wp_rewrite->using_index_permalinks() && ! strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

        printf('<div class="la-pagination">%s</div>',
            paginate_links(array_merge(array(
                'base'     => $pagenum_link,
                'format'   => $format,
                'total'    => $query->max_num_pages,
                'current'  => $paged,
                'mid_size' => 1,
                'add_args' => array_map('urlencode', $query_args),
                'prev_text'    => '<i class="fa fa-angle-double-left"></i>',
                'next_text'    => '<i class="fa fa-angle-double-right"></i>',
                'type'         => 'list'
            ), $args))
        );
    }
}

if(!function_exists('draven_get_social_media')){
    function draven_get_social_media( $style = 'default', $el_class = ''){
        $css_class = implode(' ', array(
                'social-media-link',
                'style-' . $style
            )) ;
        $css_class .= ' ' . $el_class;

        $social_links = Draven()->settings()->get('social_links', array());
        if(!empty($social_links)){
            echo '<div class="'.esc_attr($css_class).'">';
            foreach($social_links as $item){
                if(!empty($item['link']) && !empty($item['icon'])){
                    $title = isset($item['title']) ? $item['title'] : '';
                    printf(
                        '<a href="%1$s" class="%2$s" title="%3$s" target="_blank" rel="nofollow"><i class="%4$s"></i></a>',
                        esc_url($item['link']),
                        esc_attr(sanitize_title($title)),
                        esc_attr($title),
                        esc_attr($item['icon'])
                    );
                }
            }
            echo '</div>';
        }
    }
}
if(!function_exists('draven_get_portfolio_social_media')){
    function draven_get_portfolio_social_media($post_id = 0, $el_class = ''){

        $css_class = 'social--sharing ' . $el_class;

        $social_links = Draven()->settings()->get_post_meta($post_id,'social_links');

        if(!empty($social_links) && is_array($social_links)){
            echo '<div class="'.esc_attr($css_class).'">';
            foreach($social_links as $item){
                if(!empty($item['link']) && !empty($item['icon'])){
                    $title = isset($item['title']) ? $item['title'] : '';
                    $custom_style = array();
                    if(!empty($item['text_color'])){
                        $custom_style[] = "color:" .$item['text_color'];
                    }
                    if(!empty($item['bg_color'])){
                        $custom_style[] = "background-color:" .$item['bg_color'];
                    }
                    printf(
                        '<a href="%1$s" class="%2$s" title="%3$s" style="%5$s" target="_blank" rel="nofollow"><i class="%4$s"></i></a>',
                        esc_url($item['link']),
                        esc_attr(sanitize_title($title)),
                        esc_attr($title),
                        esc_attr($item['icon']),
                        esc_attr(implode(';', $custom_style))
                    );
                }
            }
            echo '</div>';
        }
    }
}

if(!function_exists('draven_comment_form_callback')) {
    function draven_comment_form_callback($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li id="pingback-comment-<?php comment_ID(); ?>">
                <p class="cmt-pingback"><?php echo esc_html_x('Pingback:', 'front-view', 'draven'); ?><?php comment_author_link(); ?><?php edit_comment_link(esc_html_x('Edit', 'front-view', 'draven'), '<span class="ping-meta"><span class="edit-link">', '</span></span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                ?>
            <li id="li-comment-<?php echo esc_attr(get_comment_ID()); ?>" <?php comment_class('clearfix'); ?>>
                <div id="comment-<?php echo esc_attr(get_comment_ID()); ?>" class="comment_container clearfix">
                    <?php echo get_avatar($comment, $args['avatar_size']); ?>
                    <div class="comment-text">
                        <div class="description"><?php comment_text(); ?></div>
                        <div class="comment-meta">
                            <div class="comment-author"><?php comment_author_link(); ?></div><?php
                            printf('<time datetime="%1$s">%2$s</time>',
                                get_comment_time('c'),
                                sprintf(esc_html_x('%1$s', '1: date', 'draven'), get_comment_date())
                            );
                            edit_comment_link(esc_html_x('Edit', 'front-view', 'draven'), ' <span class="edit-link">', '</span>'); ?>
                            <?php if ('0' == $comment->comment_approved) : ?>
                                <em class="comment-awaiting-moderation"><?php echo esc_html_x('Your comment is awaiting moderation.', 'front-view', 'draven'); ?></em>
                            <?php endif; ?>
                            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                        </div>
                    </div>
                </div>
                <?php
                break;
        endswitch;
    }
}


if(!function_exists('draven_get_favorite_link')){
    function draven_get_favorite_link( $post_id = 0 ){
        if(empty($post_id)){
            $post_id = get_the_ID();
        }
        $lists = Draven()->favorite()->load_favorite_lists();
        $count = Draven()->favorite()->get_total_favorites_for_post( $post_id );
        $class = '';
        if(in_array($post_id, $lists)){
            $class = 'added';
        }
        printf(
            '<div class="la-favorite-link"><a class="%1$s" href="javascript:;" rel="nofollow" data-favorite_id="%2$s"><i class="fa fa-heart-o"></i><span class="favorite_count">%3$s</span></a></div>',
            esc_attr($class),
            esc_attr($post_id),
            ($count ? esc_html($count) : '')
        );
    }
}


if(!function_exists('draven_get_wishlist_url')){
    function draven_get_wishlist_url(){
        $wishlist_page_id = Draven()->settings()->get('wishlist_page', 0);
        return (!empty($wishlist_page_id) ? get_the_permalink($wishlist_page_id) : home_url('/'));
    }
}

if(!function_exists('draven_get_compare_url')){
    function draven_get_compare_url(){
        $compare_page_id = Draven()->settings()->get('compare_page', 0);
        return (!empty($compare_page_id) ? get_the_permalink($compare_page_id) : home_url('/'));
    }
}

if(!function_exists('draven_get_wc_attribute_for_compare')){
    function draven_get_wc_attribute_for_compare(){
        return array(
            'image'         => __( 'Image', 'draven' ),
            'title'         => __( 'Title', 'draven' ),
            'add-to-cart'   => __( 'Add to cart', 'draven' ),
            'price'         => __( 'Price', 'draven' ),
            'sku'           => __( 'Sku', 'draven' ),
            'description'   => __( 'Description', 'draven' ),
            'stock'         => __( 'Availability', 'draven' ),
            'weight'        => __( 'Weight', 'draven' ),
            'dimensions'    => __( 'Dimensions', 'draven' )
        );
    }
}

if(!function_exists('draven_get_wc_attribute_taxonomies')){
    function draven_get_wc_attribute_taxonomies( ){

        $attributes = array();

        if( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if(!empty($attribute_taxonomies)){
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    $attributes[$tax] = ucfirst( $attribute->attribute_name );
                }
            }
        }

        return $attributes;
    }
}

if(!function_exists('draven_protected_token_key')){
    function draven_protected_token_key( $key = '', $decode = false ) {
        $newkey = '';
        if(!empty($key)){
            $tmp = explode('.', $key);
            $tmp2 = array();
            foreach($tmp as $str){
                $_lg = strlen($str);
                if($_lg > 5){
                    $f_str = substr($str, 0, 3);
                    $e_str = substr($str, $_lg - 3);
                    $m_str = substr($str, 3, $_lg - 6);
                    if(!empty($m_str)){
                        $m_str = strrev($m_str);
                    }
                    if($decode){
                        $tmp2[] = strrev($f_str) . $m_str . strrev($e_str);
                    }
                    else{
                        $tmp2[] = strrev($e_str) . $m_str . strrev($f_str);
                    }
                }
                else{
                    $tmp2[] = $_lg > 0 ? strrev($str) : $str;
                }
            }
            $newkey = implode('.', $tmp2);
        }
        return $newkey;
    }
}

if(!function_exists('draven_string_to_bool')){
    function draven_string_to_bool( $string ){
        return is_bool( $string ) ? $string : ( 'yes' === $string || 'on' === $string || 1 === $string || 'true' === $string || '1' === $string );
    }
}

add_filter('comment_form_fields', 'draven_override_comment_form');
if(!function_exists('draven_override_comment_form')){
    function draven_override_comment_form( $fields ) {
        if(isset($fields['comment'])){
            $tmp = $fields['comment'];
            unset($fields['comment']);
            $fields['comment'] = $tmp;
        }
        if(isset($fields['cookies'])){
            $tmp = $fields['cookies'];
            unset($fields['cookies']);
            $fields['cookies'] = $tmp;
        }
        return $fields;
    }
}

if(!function_exists('draven_get_list_image_sizes')){
    function draven_get_list_image_sizes() {

        global $_wp_additional_image_sizes;

        $sizes  = get_intermediate_image_sizes();
        $result = array();

        foreach ( $sizes as $size ) {
            if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                $result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
            } else {
                $result[ $size ] = sprintf(
                    '%1$s (%2$sx%3$s)',
                    ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
                    $_wp_additional_image_sizes[ $size ]['width'],
                    $_wp_additional_image_sizes[ $size ]['height']
                );
            }
        }

        return array_merge( array( 'full' => esc_html__( 'Full', 'draven' ), ), $result );
    }
}

add_filter('LAHFB/load_assets_frontend', '__return_false');