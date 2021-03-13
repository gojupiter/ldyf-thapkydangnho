(function($) {
    'use strict';

    var $document = $(document),
        $menu = $('#menu-to-edit');

    $(function(){

        function update_menu_dependency( elem ){
            setTimeout(function(){
                if(elem.hasClass('menu-item-depth-1')){
                    $('.la-element-menu-type select', $('#menu-item-' + $('.menu-item-data-parent-id', elem).val())).trigger('la_admin_menu_event:update_dependency');
                }
                if(elem.hasClass('menu-item-depth-0')){
                    $('.la-element-menu-type select', elem).trigger('la_admin_menu_event:update_dependency');
                }
            },200);
        }

        $document
            .on('sortstart', '#menu-to-edit', function(e, ui){
                $('.menu-item-settings', ui.item).hide();
            })
            .on( 'sortstop','#menu-to-edit', function( event, ui ) {
                ui.item.removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
                update_menu_dependency(ui.item);
            })
            .on( 'la_admin_menu_event:update_dependency', '.la-element-menu-type select', function(e){
                var _select = $(this),
                    $li = _select.closest('li.menu-item'),
                    $sub = $('.menu-item-data-parent-id[value="'+$li.attr('id').replace('menu-item-','')+'"]').closest('.menu-item');
                if(_select.val() == 'wide'){
                    $('.la-element-popup-columns,.la-element-popup-max-width,.la-element-popup-background,.la-element-force-full-width', $li).addClass('show');
                    $('.la-element-columns,.la-element-custom-block-before-menu-item,.la-element-custom-block-after-menu-item', $sub).addClass('show');
                    $('.la-element-submenu-position', $sub).removeClass('show');
                }else{
                    $('.la-element-popup-columns,.la-element-popup-max-width,.la-element-popup-background,.la-element-force-full-width', $li).removeClass('show');
                    $('.la-element-columns,.la-element-custom-block-before-menu-item,.la-element-custom-block-after-menu-item', $sub).removeClass('show');
                    $('.la-element-submenu-position', $sub).addClass('show');
                }
            });
        $menu
            .on( 'click', 'a.item-edit', function(){
                var $menu_item = $(this).closest('li.menu-item');
                $('.la-field-upload', $menu_item).LA_FRAMEWORK_UPLOADER();
                $('.la-field-color-picker', $menu_item).LA_FRAMEWORK_COLORPICKER();
                ///$('.la-autocomplete', $menu_item).LA_FRAMEWORK_AUTOCOMPLETE();
                setTimeout(function(){
                    update_menu_dependency($menu_item);
                }, 100);
                //if($menu_item.hasClass('menu-item-depth-0')){
                //    $('.la-element-menu-type select', $menu_item).trigger('la_admin_menu_event:update_dependency');
                //}
            })
            .on( 'change', '.la-element-menu-type select', function(e){
                $(this).trigger('la_admin_menu_event:update_dependency');
            })
            .on( 'click', '.menus-move', function(){
                update_menu_dependency($(this).closest('li.menu-item'));
            })
    });

})(jQuery);

(function($) {
    'use strict';

    $(function () {

        if(typeof pagenow !== "undefined" && pagenow === "widgets"){
            $( '.widget-liquid-right' ).append( draven_sidebar_options.widget_info );

            var $create_box = $( '#la_pb_widget_area_create' ),
                $widget_name_input = $create_box.find( '#la_pb_new_widget_area_name' ),
                $la_pb_sidebars = $( 'div[id^=draven_widget_area_]' );

            $create_box.find( '.la_pb_create_widget_area' ).click( function( event ) {
                var $this_el = $(this);

                event.preventDefault();

                if ( $widget_name_input.val() === '' ) return;

                $.ajax( {
                    type: "POST",
                    url: draven_sidebar_options.ajaxurl,
                    data:
                        {
                            action : 'draven_core_action',
                            router : 'add_sidebar',
                            admin_load_nonce : draven_sidebar_options.admin_load_nonce,
                            widget_area_name : $widget_name_input.val()
                        },
                    success: function( data ){
                        if(data.success){
                            $this_el.closest( '#la_pb_widget_area_create' ).find( '.la_pb_widget_area_result' ).hide().html( data.data.message ).slideToggle();
                        }
                    }
                } );
            } );

            $la_pb_sidebars.each( function() {
                if ( $(this).is( '#la_pb_widget_area_create' ) || $(this).closest( '.inactive-sidebar' ).length ) return true;

                $(this).closest('.widgets-holder-wrap').find('.sidebar-name h2, .sidebar-name h3').before( '<a href="#" class="la_pb_widget_area_remove">' + draven_sidebar_options.delete_string + '</a>' );

                $( '.la_pb_widget_area_remove' ).click( function( event ) {
                    var $this_el = $(this);

                    event.preventDefault();

                    if(confirm(draven_sidebar_options.confirm_delete_string)){
                        $.ajax( {
                            type: "POST",
                            url: draven_sidebar_options.ajaxurl,
                            data:
                                {
                                    action : 'draven_core_action',
                                    router : 'remove_sidebar',
                                    admin_load_nonce : draven_sidebar_options.admin_load_nonce,
                                    widget_area_name : $this_el.closest( '.widgets-holder-wrap' ).find( 'div[id^=draven_widget_area_]' ).attr( 'id' )
                                },
                            success: function( data ){
                                if(data.success){
                                    $( '#' + data.data.sidebar_id ).closest( '.widgets-holder-wrap' ).remove();
                                }
                            }
                        } );
                    }

                    return false;
                } );
            } );
        }
    })

})(jQuery);