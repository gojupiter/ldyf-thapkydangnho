body {
  font-family: <?php echo draven_render_variable( $body_font_family ) ?>;
}

.lastudio-testimonials.preset-type-2 .lastudio-testimonials__comment {
  font-family: <?php echo draven_render_variable( $highlight_font_family ) ?>;
}

h1,
.h1, h2,
.h2, h3,
.h3, h4,
.h4, h5,
.h5, h6,
.h6 {
  font-family: <?php echo draven_render_variable( $heading_font_family ) ?>;
}

.background-color-primary, .slick__nav_style1 .slick-slider .slick-arrow:hover, .item--link-overlay:before, .slick-arrow.circle-border:hover, form.track_order .button, .lahfb-button a, .menu .tip.hot, .lastudio-posts .lastudio-posts__inner-box:hover .lastudio-more, .lastudio-posts .lastudio-more:hover, .comment-form .form-submit input, .wpcf7-submit:hover, .lastudio-slick-dots li.slick-active span, .lastudio-slick-dots li:hover span, .lastudio-team-member__item .loop__item__thumbnail--linkoverlay, .slick__dots__style_2 .lastudio-testimonials .lastudio-slick-dots li.slick-active span, .elementor-button, .outline-btn .elementor-button:hover, .lastudio-progress-bar__status-bar, .lastudio-portfolio__image:after, .lastudio-portfolio .lastudio-portfolio__view-more-button:hover, .product_item--info .elm-countdown .countdown-row, .products-list .product_item .product_item--thumbnail .product_item--action .quickview:hover, .products-grid .product_item_thumbnail_action .button:hover, .la-woo-product-gallery > .woocommerce-product-gallery__trigger, .product--summary .single_add_to_cart_button:hover, .product--summary .add_compare.added, .product--summary .add_compare:hover, .product--summary .add_wishlist.added, .product--summary .add_wishlist:hover, .woocommerce > .return-to-shop .button, form.woocommerce-form-register .button, form.woocommerce-form-login .button, .woocommerce-MyAccount-navigation li:hover a, .woocommerce-MyAccount-navigation li.is-active a, .page-links > span:not(.page-links-title), .page-links > a:hover, .la-custom-badge, .calendar_wrap #today{
  background-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?>;
}

.background-color-secondary, .la-pagination ul .page-numbers.current, .la-pagination ul .page-numbers:hover, .slick-slider .slick-dots button, .wc-toolbar .wc-ordering ul li:hover a, .wc-toolbar .wc-ordering ul li.active a, .widget_layered_nav.widget_layered_nav--borderstyle li:hover a, .widget_layered_nav.widget_layered_nav--borderstyle li.active a, .comment-form .form-submit input:hover, .elementor-button:hover, .product--summary .single_add_to_cart_button {
  background-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?>;
}

.background-color-secondary {
  background-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?>;
}

.background-color-body {
  background-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?>;
}

.background-color-border {
  background-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?>;
}

.la-woo-thumbs .la-thumb.slick-current.slick-active {
  background-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?>;
}

a:hover, .elm-loadmore-ajax a:hover, .search-form .search-button:hover, .slick-slider .slick-dots li:hover span,
.slick-slider .slick-dots .slick-active span, .slick-slider .slick-arrow:hover,
.la-slick-nav .slick-arrow:hover, .vertical-style ul li:hover a, .vertical-style ul li.active a, .widget.widget_product_tag_cloud a.active,
.widget.widget_product_tag_cloud .active a,
.widget.product-sort-by .active a,
.widget.widget_layered_nav .active a,
.widget.la-price-filter-list .active a, .product_list_widget a:hover, .lahfb-wrap .lahfb-nav-wrap .menu > li.current > a,
.lahfb-wrap .lahfb-nav-wrap .menu > li.menu-item > a.active,
.lahfb-wrap .lahfb-nav-wrap .menu li.current ul li a:hover,
.lahfb-wrap .lahfb-nav-wrap .menu ul.sub-menu li.current > a,
.lahfb-wrap .lahfb-nav-wrap .menu ul li.menu-item:hover > a, .la-hamburger-wrap .full-menu li:hover > a, .menu .tip.hot .tip-arrow:before, .error404 .default-404-content h1, .service__style_1 .lastudio-advance-carousel-layout-simple .lastudio-carousel__item-inner:hover .lastudio-carousel__item-title, .lastudio-headline__second, .lastudio-portfolio .lastudio-portfolio__filter-item.active, .lastudio-portfolio.preset-type-4 .lastudio-portfolio__category, .lastudio-portfolio.preset-type-8 .lastudio-portfolio__category, .product_item--thumbnail .elm-countdown .countdown-amount, .product_item .price ins, .product--summary .social--sharing a:hover, .cart-collaterals .woocommerce-shipping-calculator .button:hover,
.cart-collaterals .la-coupon .button:hover, p.lost_password, ul.styled-lists li:before{
  color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?>;
}

.text-color-primary {
  color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.slick-arrow.circle-border:hover, .swatch-wrapper:hover, .swatch-wrapper.selected, .entry-content blockquote, .lahfb-nav-wrap.preset-vertical-menu-02 li.mm-lv-0:hover > a:before, .lahfb-nav-wrap.preset-vertical-menu-02 li.mm-lv-0.current > a:before {
  border-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?>;
}

.border-color-primary {
  border-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.border-top-color-primary {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.border-bottom-color-primary {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.border-left-color-primary {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.border-right-color-primary {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?> !important;
}

.woocommerce-message,
.woocommerce-error,
.woocommerce-info, .form-row label, .wc-toolbar .woocommerce-result-count,
.wc-toolbar .wc-view-toggle .active, .wc-toolbar .wc-view-count li.active, div.quantity, .widget_recent_entries .pr-item .pr-item--right a, .widget_recent_comments li.recentcomments a, .product_list_widget a, .product_list_widget .amount, .widget.widget_product_tag_cloud .tagcloud, .sidebar-inner .dokan-category-menu #cat-drop-stack > ul li.parent-cat-wrap, .author-info__name,
.author-info__link, .post-navigation .blog_pn_nav-title, .woocommerce-Reviews .woocommerce-review__author, .woocommerce-Reviews .comment-reply-title, .product_item .price > .amount, .products-list .product_item .price,
.products-list-mini .product_item .price, .products-list .product_item .product_item--thumbnail .product_item--action .quickview,
.products-grid .product_item_thumbnail_action .button, .la-woo-thumbs .slick-arrow, .product--summary .entry-summary > .stock.in-stock, .product--summary .product-nextprev, .product--summary .single-price-wrapper .price ins .amount,
.product--summary .single-price-wrapper .price > .amount, .product--summary .product_meta, .product--summary .product_meta_sku_wrapper, .product--summary .product-share-box, .product--summary .group_table td, .product--summary .variations td, .product--summary .add_compare,
.product--summary .add_wishlist, .wc-tabs li:hover > a,
.wc-tabs li.active > a, .wc-tab .wc-tab-title, #tab-description .tab-content, .shop_table td.product-price,
.shop_table td.product-subtotal, .cart-collaterals .shop_table, .cart-collaterals .woocommerce-shipping-calculator .button,
.cart-collaterals .la-coupon .button, .woocommerce > p.cart-empty, table.woocommerce-checkout-review-order-table, .wc_payment_methods .wc_payment_method label, .woocommerce-order ul strong, .blog-main-loop__btn-loadmore, ul.styled-lists li, .lahfb-wrap .lahfb-area {
  color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?>;
}

.text-color-secondary {
  color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

input:focus, select:focus, textarea:focus {
  border-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?>;
}

.border-color-secondary {
  border-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-top-color-secondary {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-bottom-color-secondary {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-left-color-secondary {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-right-color-secondary {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

h1,
.h1, h2,
.h2, h3,
.h3, h4,
.h4, h5,
.h5, h6,
.h6, table th, .slick-arrow.circle-border i, .sidebar-inner .dokan-category-menu .widget-title, .product--summary .social--sharing a, .wc_tabs_at_bottom .wc-tabs li.active > a, .extradiv-after-frm-cart {
  color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?>;
}

.text-color-heading {
  color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-color-heading {
  border-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-top-color-heading {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-bottom-color-heading {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-left-color-heading {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.border-right-color-heading {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("secondary_color","#343538") ) ?> !important;
}

.wc_tabs_at_bottom .wc-tabs li a {
  color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?>;
}

.text-color-three {
  color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

.border-color-three {
  border-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

.border-top-color-three {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

.border-bottom-color-three {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

.border-left-color-three {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

.border-right-color-three {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("three_color","#b5b7c4") ) ?> !important;
}

body, table.woocommerce-checkout-review-order-table .variation,
table.woocommerce-checkout-review-order-table .product-quantity {
  color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?>;
}

.text-color-body {
  color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

.border-color-body {
  border-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

.border-top-color-body {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

.border-bottom-color-body {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

.border-left-color-body {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

.border-right-color-body {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("text_color","#9d9d9d") ) ?> !important;
}

input, select, textarea, table,
table th,
table td, .share-links a, .select2-container .select2-selection--single, .swatch-wrapper, .widget_shopping_cart_content .total, .calendar_wrap caption, .shop_table.woocommerce-cart-form__contents td {
  border-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?>;
}

.border-color {
  border-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?> !important;
}

.border-top-color {
  border-top-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?> !important;
}

.border-bottom-color {
  border-bottom-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?> !important;
}

.border-left-color {
  border-left-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?> !important;
}

.border-right-color {
  border-right-color: <?php echo esc_attr( Draven()->settings()->get("border_color","rgba(150,150,150,0.30)") ) ?> !important;
}

.lahfb-modal-login #user-logged .author-avatar img{
    border-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?>;
}
.lastudio-testimonials.preset-type-5 .lastudio-testimonials__name{
    background-color: <?php echo esc_attr( Draven()->settings()->get("primary_color","#35d56a") ) ?>;;
}