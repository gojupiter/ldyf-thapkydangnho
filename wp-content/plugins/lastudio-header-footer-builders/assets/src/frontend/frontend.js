(function ($) {
    "use strict";

    var $document = $(document);

    // Initialize global variable
    var LAHFB = {
        core        : {}
    };
    window.LAHFB = LAHFB;

    LAHFB.core.init = function(){

        // Navigation Current Menu
        $('#nav li.current-menu-item, #nav li.current_page_item, #side-nav li.current_page_item, #nav li.current-menu-ancestor, #nav li ul li ul li.current-menu-item , #hamburger-nav li.current-menu-item, #hamburger-nav li.current_page_item, #hamburger-nav li.current-menu-ancestor, #hamburger-nav li ul li ul li.current-menu-item, .full-menu li.current-menu-item, .full-menu li.current_page_item, .full-menu li.current-menu-ancestor, .full-menu li ul li ul li.current-menu-item ').addClass('current');
        $('#nav li ul li:has(ul)').addClass('submenux');

        $('.lahfb-header-toggle').contentToggle({
            defaultState: 'close',
            globalClose: true,
            triggerSelector: ".lahfb-trigger-element",
            toggleProperties: ['height', 'opacity'],
            toggleOptions: {
                duration: 300
            }
        });

        // Share modal
        var header_share = $('.header-share-modal-wrap').html();
        $('.header-share-modal-wrap').remove();
        $(".main-slide-toggle").append(header_share);

        // Social modal
        var header_social = $('.header-social-modal-wrap').html();
        $('.header-social-modal-wrap').remove();
        $(".main-slide-toggle").append(header_social);

        // Search modal Type 2
        var header_search_type2 = $('.header-search-modal-wrap').html();
        $('.header-search-modal-wrap').remove();
        $(".main-slide-toggle").append(header_search_type2);

        // Share Toggles
        $('#la-share-modal-icon').click(function () {
            var $current_element = $(this).closest('#lastudio-header-builder');
            if ($current_element.find('.la-header-share').hasClass('opened')) {
                $current_element.find('.main-slide-toggle').slideUp('opened');
                $current_element.find('.la-header-share').removeClass('opened');
            } else {
                $current_element.find('.main-slide-toggle').slideDown(240);
                $current_element.find('#header-search-modal').slideUp(240);
                $current_element.find('#header-social-modal').slideUp(240);
                $current_element.find('#header-share-modal').slideDown(240);
                $current_element.find('.la-header-share').addClass('opened');
                $current_element.find('.la-header-search').removeClass('opened');
                $current_element.find('.la-header-social').removeClass('opened');
            }

        });

        $document.on('click', function (e) {
            if (e.target.id == 'la-share-modal-icon')
                return;

            var $this = $('#la-share-modal-icon');
            if ($this.closest('#lastudio-header-builder').find('.la-header-share').hasClass('opened')) {
                $this.closest('#lastudio-header-builder').find('.main-slide-toggle').slideUp('opened');
                $this.closest('#lastudio-header-builder').find('.la-header-share').removeClass('opened');
            }
        });

        // Social dropdown
        $('.lahfb-social').each(function (index, el) {
            $(this).find('#la-social-dropdown-icon').on('click', function (event) {
                $(this).siblings('#header-social-dropdown-wrap').fadeToggle('fast', function () {
                    if ($("#header-social-dropdown-wrap").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-social').length)
                                return;
                            $("#header-social-dropdown-wrap").css({
                                display: 'none',
                            });
                        });
                    }
                });
            });
        });

        // social full
        $('.lahfb-social').find('#la-social-slide-icon').each(function (index, el) {
            $(this).magnificPopup({
                type: 'inline',
                removalDelay: 100,
                mainClass: 'mfp-zoom-in full-social',
                midClick: true,
                showCloseBtn: true,
                closeBtnInside: false,
                closeOnBgClick: false,
            });
        });


        // Social Toggles
        $('#la-social-modal-icon').click(function () {
            var $current_element = $(this).closest('#lastudio-header-builder');
            if ($current_element.find('.la-header-social').hasClass('opened')) {
                $current_element.find('.main-slide-toggle').slideUp('opened');
                $current_element.find('.la-header-social').removeClass('opened');
            } else {
                $current_element.find('.main-slide-toggle').slideDown(240);
                $current_element.find('#header-search-modal').slideUp(240);
                $current_element.find('#header-share-modal').slideUp(240);
                $current_element.find('#header-social-modal').slideDown(240);
                $current_element.find('.la-header-social').addClass('opened');
                $current_element.find('.la-header-search').removeClass('opened');
                $current_element.find('.la-header-share').removeClass('opened');
            }

        });

        $(document).on('click', function (e) {
            if (e.target.id == 'la-social-modal-icon')
                return;

            var $this = $('#la-social-modal-icon');
            if ($this.closest('#lastudio-header-builder').find('.la-header-social').hasClass('opened')) {
                $this.closest('#lastudio-header-builder').find('.main-slide-toggle').slideUp('opened');
                $this.closest('#lastudio-header-builder').find('.la-header-social').removeClass('opened');
            }
        });

        // Search full

        $(document).on('click', '.lahfb-cart > a', function (e) {
            e.preventDefault();
            if(!$(this).closest('.lahfb-cart').hasClass('force-display-on-mobile')){
                if($(window).width() > 767){
                    e.preventDefault();
                    $('body').toggleClass('open-cart-aside');
                }
            }
            else{
                e.preventDefault();
                $('body').toggleClass('open-cart-aside');
            }
        });

        $(document).on('click', '.lahfb-search.lahfb-header-full > a', function (e) {
            e.preventDefault()
            $('body').addClass('open-search-form');
            setTimeout(function(){
                $('.searchform-fly .search-field').focus();
            }, 600);
        });

        $('.lahfb-header-full').find('.lahfb-trigger-element').find('a').each(function (index, el) {
            $(this).magnificPopup({
                type: 'inline',
                removalDelay: 100,
                mainClass: 'mfp-zoom-in full-search',
                midClick: true,
                showCloseBtn: true,
                closeBtnInside: false,
                closeOnBgClick: false,
            });
        });


        // Search Toggles
        $('.lahfb-header-slide').find('.lahfb-trigger-element').on('click', function (e) {
            var $current_element = $(this).closest('#lastudio-header-builder');
            if ($current_element.find('.la-header-search').hasClass('opened')) {
                $current_element.find('.main-slide-toggle').slideUp('opened');
                $current_element.find('.la-header-search').removeClass('opened');
            } else {
                $current_element.find('.main-slide-toggle').slideDown(240);
                $current_element.find('#header-social-modal').slideUp(240);
                $current_element.find('#header-share-modal').slideUp(240);
                $current_element.find('#header-search-modal').slideDown(240);
                $current_element.find('.la-header-search').addClass('opened');
                $current_element.find('.la-header-social').removeClass('opened');
                $current_element.find('.la-header-share').removeClass('opened');
                $current_element.find('.header-search-modal-text-box').focus();
            }

        });

        $(document).on('click', function (e) {
            //  return;
            var target = $(e.target);
            if (e.target.id == 'la-search-modal-icon' || e.target.id == 'search-icon-trigger' || e.target.id == 'lahfb-trigger-element' || target.parents('.main-slide-toggle').length)
                return;

            var $this = $('.lahfb-header-slide').find('.lahfb-trigger-element');
            if ($this.closest('#lastudio-header-builder').find('.la-header-search').hasClass('opened')) {
                $this.closest('#lastudio-header-builder').find('.main-slide-toggle').slideUp('opened');
                $this.closest('#lastudio-header-builder').find('.la-header-search').removeClass('opened');
            }
        });

        $(document).on('click', '#header-search-modal,#header-social-modal,#header-share-modal', function (e) {
            e.stopPropagation();
        });


        // Woocommerce Mini Cart
        $(document).on('click', '.mini_cart_item .remove', function (event) {
            var $this = $(this),
                $preloader = $('<div class="la-circle-side-wrap"><div data-loader="la-circle-side"></div></div>'),
                cartproductid = $this.data('product_id');
            event.preventDefault();
            $preloader.appendTo($(this).closest('.la-header-woo-cart'));
            $.ajax({
                url: woocommerce_params.ajax_url,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'la_woo_ajax_update_cart',
                    cart_product_id: cartproductid,
                },
                success: function (data) {
                    $('.la-header-woo-cart-wrap').html(data);
                    $preloader.remove();
                    $this.closest('.la-header-woo-cart').slideDown();
                    setTimeout(function () {
                        $this.find('.la-ajax-error').remove();
                    }, 400);
                    var cart_items = $('.la-count-cart-product').attr('data-items');
                    $('span.header-cart-count-icon').text(cart_items);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });

        });

        // Woocommerce Add To Cart
        $(document).on('click', '.la-woo-add-to-cart-btn .add_to_cart_button, .type-list-readmore .add_to_cart_button', function (event) {
            event.preventDefault();
            var $this = $(this),
                $preloader = $('<div class="la-circle-side-wrap"><div data-loader="la-circle-side"></div></div>'),
                $cart = $('#lastudio-header-builder').find('.lahfb-screen-view:not(.lahfb-sticky-view)').find('.la-header-woo-cart'),
                cartproductid = $this.data('product_id');

            $("html, body").animate({scrollTop: 0}, 700);
            $cart.append($preloader);
            if (!$cart.hasClass('open-cart')) {
                $cart.addClass('is-open').slideDown();
            }
            if (!$('#la-cart-modal-icon').hasClass('open-cart')) {
                $('#la-cart-modal-icon').addClass('open-cart');
            }

            $.ajax({
                url: woocommerce_params.ajax_url,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'la_woo_ajax_add_to_cart',
                    cart_product_id: cartproductid,
                },
                success: function (data) {
                    $('.la-header-woo-cart-wrap').html(data);
                    $cart.find('.la-circle-side-wrap').remove();
                    var cart_items = $('.la-count-cart-product').attr('data-items');
                    $('.lahfb-cart').find('.la-cart-modal-icon').find('.header-cart-count-icon').text(cart_items).attr('data-cart_count', cart_items);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });

        });


        // Woocommerce Icon in header
        $('#lastudio-header-builder').find('.lahfb-cart').on('click', '#la-cart-modal-icon', function (event) {
            event.preventDefault();
            if ($(this).hasClass('open-cart')) {
                $(this).removeClass('open-cart');
                $(this).closest('.lahfb-cart').removeClass('is-open');
                $(this).closest('.lahfb-cart').find('.la-header-woo-cart').addClass('is-open').slideUp(300);
            } else {
                $(this).addClass('open-cart');
                $(this).closest('.lahfb-cart').addClass('is-open');
                $(this).closest('.lahfb-cart').find('.la-header-woo-cart').removeClass('is-open').slideDown(300);
            }
        });

        $(document).click(function (e) {
            var target = $(e.target);
            if (e.target.id == 'la-cart-modal-icon' || e.target.id == 'header-cart-icon' || e.target.id == 'header-cart-count-icon' || target.hasClass('add_to_cart_button') || target.parent().hasClass('add_to_cart_button')) {
                return;
            }

            var target_element = $('#lastudio-header-builder').find('.lahfb-cart').find('#la-cart-modal-icon');
            if (target_element.hasClass('open-cart')) {
                target_element.removeClass('open-cart');
                target_element.closest('.lahfb-cart').removeClass('is-open');
                target_element.closest('.lahfb-cart').find('.la-header-woo-cart').slideUp(300);
            }
        });

        $(document).on('click', '#la-header-woo-cart', function (e) {
            e.stopPropagation();
        });

        if ($.fn.magnificPopup) {

            // Popup map or any iframe
            $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,

                fixedContentPos: false
            });

            // Inline popups
            $('.lahfb-modal-element').each(function (index, el) {
                $(this).magnificPopup({
                    type: 'inline',
                    removalDelay: 500,
                    callbacks: {
                        beforeOpen: function () {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });

        }


        if ($.fn.niceSelect) {
            $('.la-polylang-switcher-dropdown select').niceSelect();
        }


        if ($.fn.superfish) {
            $('.lahfb-area:not(.lahfb-vertical) .lahfb-nav-wrap:not(.has-megamenu) ul#nav').superfish({
                delay: 300,
                hoverClass: 'la-menu-hover',
                animation: {
                    opacity: "show",
                    height: 'show'
                },
                animationOut: {
                    opacity: "hide",
                    height: 'hide'
                },
                easing: 'easeOutQuint',
                speed: 100,
                speedOut: 0,
                pathLevels: 2,
            });
        }

        $('.lahfb-nav-wrap #nav li a').addClass('hcolorf');

        // Hamburger Menu
        var $hamurgerMenuWrapClone = $('.toggle').find('#hamburger-menu-wrap').clone().remove();
        if ($hamurgerMenuWrapClone.length > 0) {
            $('body').find('.la-hamuburger-bg').remove();
            $hamurgerMenuWrapClone.appendTo('body');
        }

        if ($('#hamburger-menu-wrap').hasClass('toggle-right')) {
            $('body').addClass('la-body lahmb-right');
        } else if ($('#hamburger-menu-wrap').hasClass('toggle-left')) {
            $('body').addClass('la-body lahmb-left');
        }

        //Hamburger Nicescroll
        $('.hamburger-menu-main').niceScroll({
            scrollbarid: 'lahfb-hamburger-scroll',
            cursorwidth: "5px",
            autohidemode: true
        });

        $('.la-body').find('.lahfb-hamburger-menu.toggle').on('click', 'a.lahfb-icon-element', function (event) {
            event.preventDefault();
            if ($(this).closest('.lahfb-hamburger-menu.toggle').hasClass('is-open')) {
                $(this).closest('.lahfb-hamburger-menu.toggle').removeClass('is-open');
                $(this).closest('.la-body').find('#hamburger-menu-wrap').removeClass('hm-open');
                $(this).closest('.la-body').removeClass('is-open');
                $('.hamburger-menu-main').getNiceScroll().resize();
            } else {
                $(this).closest('.lahfb-hamburger-menu.toggle').addClass('is-open');
                $(this).closest('.la-body').find('#hamburger-menu-wrap').addClass('hm-open');
                $(this).closest('.la-body').addClass('is-open');
                $('.hamburger-menu-main').getNiceScroll().resize();
            }
        });

        $('#hamburger-nav.toggle-menu').find('li').each(function () {
            var $list_item = $(this);

            if ($list_item.children('ul').length) {
                $list_item.children('a').append('<i class="fa fa-angle-down hamburger-nav-icon"></i>');
            }

            $('> a > .hamburger-nav-icon', $list_item).on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if( $that.hasClass('active') ){
                    $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                    $('>ul', $list_item).stop().slideUp(350);
                }
                else{
                    $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                    $('>ul', $list_item).stop().slideDown(350);
                }
            })
        });

        //Full hamburger Menu

        $(document)
            .on('click', '.lahfb-hamburger-menu.full .lahfb-icon-element.close-button', function (e) {
                $(this).siblings('.la-hamburger-wrap').addClass('open-menu');
                $(this).removeClass('close-button').addClass('open-button').find('.hamburger-icon').addClass('open');
            })
            .on('click', '.lahfb-hamburger-menu.full .lahfb-icon-element.open-button', function (e) {
                $(this).siblings('.la-hamburger-wrap').removeClass('open-menu');
                $(this).removeClass('open-button').addClass('close-button').find('.hamburger-icon').removeClass('open');
            });


        $('.full-menu').find('li').each(function () {
            var $list_item = $(this);

            if ($list_item.children('ul').length) {
                $list_item.children('a').append('<i class="fa fa-angle-down hamburger-nav-icon"></i>');
            }

            $('> a > .hamburger-nav-icon', $list_item).on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if( $that.hasClass('active') ){
                    $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                    $('>ul', $list_item).stop().slideUp(350);
                }
                else{
                    $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                    $('>ul', $list_item).stop().slideDown(350);
                }
            })

        });

        $(document).on('click', function (e) {
            var target = $(e.target);
            if (e.target.id == 'la-hamburger-icon' || target.closest('#la-hamburger-icon').length)
                return;
            var $this = $('body');
            if ($this.hasClass('is-open')) {
                $this.find('.lahfb-hamburger-menu.toggle').removeClass('is-open');
                $this.removeClass('is-open');
            }
        });

        $(document).on('click', '#hamburger-menu-wrap', function (e) {
            e.stopPropagation();
        });


        // Topbar search form
        $('.top-search-form-icon').on('click', function () {
            $('.top-search-form-box').addClass('show-sbox');
            $('#top-search-box').focus();
        });
        $(document).on('click', function (ev) {
            var myID = ev.target.id;
            if ((myID != 'top-searchbox-icon') && myID != 'top-search-box') {
                $('.top-search-form-box').removeClass('show-sbox');
            }
        });

        // Responsive Menu
        $('.lahfb-responsive-menu-icon-wrap').on('click', function () {
            var $toggleMenuIcon = $(this),
                uniqid = $toggleMenuIcon.data('uniqid'),
                $responsiveMenu = $('.lahfb-responsive-menu-wrap[data-uniqid="' + uniqid + '"]'),
                $closeIcon = $responsiveMenu.find('.close-responsive-nav');

            if ($responsiveMenu.hasClass('open') === false) {
                $toggleMenuIcon.addClass('open-icon-wrap').children().addClass('open');
                $closeIcon.addClass('open-icon-wrap').children().addClass('open');
                $responsiveMenu.animate({'left': 0}, 350).addClass('open');
            } else {
                $toggleMenuIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $closeIcon.removeClass('open-icon-wrap').children().removeClass('open');
                $responsiveMenu.animate({'left': -265}, 350).removeClass('open');
            }
        });

        $('.lahfb-responsive-menu-wrap').each(function () {
            var $this = $(this),
                uniqid = $this.data('uniqid'),
                $responsiveMenu = $this.clone(),
                $closeIcon = $responsiveMenu.find('.close-responsive-nav'),
                $toggleMenuIcon = $('.lahfb-responsive-menu-icon-wrap[data-uniqid="' + uniqid + '"]');

            // append responsive menu to lastudio header builder wrap
            $this.remove();
            $('#lastudio-header-builder').append($responsiveMenu);

            // add arrow down to parent menus
            $responsiveMenu.find('li').each(function () {
                var $list_item = $(this);

                if ($list_item.children('ul').length) {
                    $list_item.children('a').append('<i class="fa fa-angle-down respo-nav-icon"></i>');
                }

                $('> a > .respo-nav-icon', $list_item).on('click', function (e) {
                    e.preventDefault();
                    var $that = $(this);
                    if( $that.hasClass('active') ){
                        $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                        $('>ul', $list_item).stop().slideUp(350);
                    }
                    else{
                        $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                        $('>ul', $list_item).stop().slideDown(350);
                    }
                });
            });

            // close responsive menu
            $closeIcon.on('click', function () {
                if ($toggleMenuIcon.hasClass('open-icon-wrap')) {
                    $toggleMenuIcon.removeClass('open-icon-wrap').children().removeClass('open');
                    $closeIcon.removeClass('open-icon-wrap').children().removeClass('open');
                }
                else {
                    $toggleMenuIcon.addClass('open-icon-wrap').children().addClass('open');
                    $closeIcon.addClass('open-icon-wrap').children().addClass('open');
                }

                if ($responsiveMenu.hasClass('open') === true) {
                    $responsiveMenu.animate({'left': -265}, 350).removeClass('open');
                }
            });
        });

        // Login Dropdown
        $('.lahfb-login').each(function (index, el) {
            $(this).find('#la-login-dropdown-icon').on('click', function (event) {
                $(this).siblings('#lahfb-login').fadeToggle('fast', function () {
                    if ($("#lahfb-login").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-login').length)
                                return;
                            $("#lahfb-login").css({
                                display: 'none',
                            });
                        });
                    }
                });
            });
        });

        $('#loginform input[type="text"]').attr('placeholder', 'Username or Email');
        $('#loginform input[type="password"]').attr('placeholder', 'Password');


        // Contact Dropdown
        $('.lahfb-contact').each(function (index, el) {
            $(this).find('#la-contact-dropdown-icon').on('click', function (event) {
                $(this).siblings('#la-contact-form-wrap').fadeToggle('fast', function () {
                    if ($("#la-contact-form-wrap").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-contact').length)
                                return;
                            $("#la-contact-form-wrap").css({
                                display: 'none',
                            });
                        });
                    }
                });
            });
        });

        // Icon Menu Dropdown
        $('.lahfb-icon-menu-wrap').each(function (index, el) {
            $(this).find('#la-icon-menu-trigger').on('click', function (event) {
                $(this).siblings('.lahfb-icon-menu-content').fadeToggle('fast', function () {
                    if ($(".lahfb-icon-menu-content").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-icon-menu-wrap').length)
                                return;
                            $(".lahfb-icon-menu-content").css({
                                display: 'none',
                            });
                        });
                    }
                });
            });
        });

        // Wishlist Dropdown
        $('.lahfb-wishlist').each(function (index, el) {
            $(this).find('#la-wishlist-icon').on('click', function (event) {
                $(this).siblings('.la-header-wishlist-wrap').fadeToggle('fast', function () {
                    if ($(".la-header-wishlist-wrap").is(":visible")) {
                        $(document).on('click', function (e) {
                            var target = $(e.target);
                            if (target.parents('.lahfb-wishlist').length)
                                return;
                            $(".la-header-wishlist-wrap").css({
                                display: 'none'
                            });
                        });
                    }
                });
            });
        });

        $('.la-header-wishlist-content-wrap').find('.la-wishlist-total').addClass('colorf');


        /* Profile Socials */
        $('.lahfb-profile-socials-text').hover(function () {
            $(this).closest('.lahfb-profile-socials-wrap').find('.lahfb-profile-socials-icons').removeClass('profile-socials-hide').addClass('profile-socials-show');
        }, function () {
            $(this).closest('.lahfb-profile-socials-wrap').find('.lahfb-profile-socials-icons').removeClass('profile-socials-show').addClass('profile-socials-hide');
        });


        /* Vertical Header */
        // #wrap Class vertical

        if ($('.lahfb-desktop-view').find('.lahfb-row1-area').hasClass('lahfb-vertical-toggle')) {
            $('#page').addClass('lahfb-header-vertical-toggle');
        }
        else if ($('.lahfb-desktop-view').find('.lahfb-row1-area').hasClass('lahfb-vertical')) {
            $('#page').addClass('lahfb-header-vertical-no-toggle');
        }

        $(window).on('load', function (e) {
            setTimeout(function () {
                $(window).trigger('resize');
            }, 100)
        });


        // Toggle Vertical

        $document.on('click', '.vertical-menu-icon-foursome', function (event) {
            event.preventDefault();

            var $vertical_wrap = $('.lahfb-vertical.lahfb-vertical-toggle');
            var $vertical_contact_wrap = $('.lahfb-vertical-contact-form-wrap');

            if ($vertical_wrap.hasClass('is-open')) {
                $vertical_wrap.removeClass('is-open');
                $vertical_wrap.removeClass('lahfb-open-with-delay');
                $(this).siblings('.lahfb-vertical-logo-wrap,.vertical-contact-icon,.vertical-fullscreen-icon').removeClass('is-open');
                $(this).removeClass('is-open');
            }
            else {
                if ($vertical_contact_wrap.hasClass('is-open')) {
                    $vertical_contact_wrap.removeClass('is-open');
                    $vertical_wrap.addClass('lahfb-open-with-delay');
                    $('.vertical-contact-icon i').removeClass('is-open colorf');
                }
                $vertical_wrap.addClass('is-open');
                $(this).siblings('.lahfb-vertical-logo-wrap,.vertical-contact-icon,.vertical-fullscreen-icon').addClass('is-open');
                $(this).addClass('is-open');
            }
        })

        $document.on('click', '.vertical-menu-icon-triad', function (event) {
            event.preventDefault();

            // Toggle Vertical
            var $vertical_wrap = $('.lahfb-vertical.lahfb-vertical-toggle');
            var $vertical_contact_wrap = $('.lahfb-vertical-contact-form-wrap');


            if ($vertical_wrap.hasClass('is-open')) {
                $vertical_wrap.removeClass('is-open');
                $vertical_wrap.removeClass('lahfb-open-with-delay');
                $(this).removeClass('is-open');
            }
            else {
                if ($vertical_contact_wrap.hasClass('is-open')) {
                    $vertical_contact_wrap.removeClass('is-open');
                    $vertical_wrap.addClass('lahfb-open-with-delay');
                    $('.vertical-contact-icon i').removeClass('is-open colorf');
                }
                $vertical_wrap.addClass('is-open');
                $(this).addClass('is-open');
            }

        });

        // Vertical Contact Icon
        $document.on('click', '.vertical-contact-icon i', function (event) {
            event.preventDefault();
            var $vertical_wrap = $('.lahfb-vertical.lahfb-vertical-toggle');
            var $vertical_contact_wrap = $('.lahfb-vertical-contact-form-wrap');

            if ($vertical_contact_wrap.hasClass('is-open')) {
                $vertical_contact_wrap.removeClass('is-open');
                $(this).removeClass('is-open colorf');
            }
            else {
                if ($vertical_wrap.hasClass('is-open')) {
                    $vertical_wrap.removeClass('is-open');
                    $vertical_contact_wrap.addClass('lahfb-open-with-delay');
                    $('.vertical-menu-icon-triad').removeClass('is-open colorf');
                }
                $vertical_contact_wrap.addClass('is-open');
                $(this).addClass('is-open colorf');
            }
        });

        // Vertical Nicescroll
        $('.lahfb-vertical-contact-form-wrap').niceScroll({
            scrollbarid: 'lahfb-vertical-cf-scroll',
            cursorwidth: "5px",
            autohidemode: "hidden",
        });
        $('.lahfb-vertical').find('.lahfb-content-wrap').niceScroll({
            scrollbarid: 'lahfb-vertical-menu-scroll',
            cursorwidth: "5px",
            autohidemode: true
        });

        // Fullscreen Icon
        $document.on('click', '.vertical-fullscreen-icon i', function (e) {
            e.preventDefault();
            var $parent = $(this).closest('.vertical-fullscreen-icon');
            if($parent.hasClass('is-open')){
                $parent.removeClass('is-open');
                $.fullscreen.exit();
            }
            else{
                var site_document = document.documentElement,
                    site_screen = site_document.requestFullScreen || site_document.webkitRequestFullScreen || site_document.mozRequestFullScreen || site_document.msRequestFullscreen;
                if (typeof site_screen != "undefined" && site_screen) {
                    site_screen.call(site_document);
                }
                else if (typeof window.ActiveXObject != "undefined") {
                    // for Internet Explorer
                    var wscript = new ActiveXObject("WScript.Shell");
                    if (wscript != null) {
                        wscript.SendKeys("{F11}");
                    }
                }
            }
        });

        // Vertical Menu
        $('.lahfb-vertical').find('#nav').find('li').each(function () {
            var $list_item = $(this);

            if ($list_item.children('ul').length) {
                $list_item.children('a').removeClass('sf-with-ul').append('<i class="fa fa-angle-down lahfb-vertical-nav-icon"></i>');
            }

            $('> a > .lahfb-vertical-nav-icon', $list_item).on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if( $that.hasClass('active') ){
                    $that.removeClass('active fa-angle-up').addClass('fa-angle-down');
                    $('>ul', $list_item).stop().slideUp(350);
                }
                else{
                    $that.removeClass('fa-angle-down').addClass('fa-angle-up active');
                    $('>ul', $list_item).stop().slideDown(350);
                }
            });

        });

        // Buddypress user messages
        $('.la-bp-messages').find('#message-threads').children('li').each(function (index, el) {

            var wrap_element = $(this).data('count');
            console.log(wrap_element);

        });

    };

    LAHFB.core.reloadAllEvents = function(){
        LAHFB.core.init();
        console.log('ok -- reloadAllEvents!');
    };

    $(function () {
        LAHFB.core.init();
    });

})(jQuery);