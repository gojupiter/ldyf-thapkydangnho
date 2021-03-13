jQuery(document).ready(function ($) {

    $('body').on('keyup change keydown', '#tc_options_search_val', function (e) {
        var searched_option = $(this).val();

        if (searched_option == '') {
            $(".form-table tr").show();
        } else {
            try {
                var search_key_match = new RegExp(searched_option, 'i');
            } catch (e) {
                var search_key_match = '';
            }
            
            $(".form-table label").each(function () {
                if (($(this).html().match(search_key_match))) {
                    $(this).parent().parent().show();
                }else{
                    $(this).parent().parent().hide();
                }
            });
        }
    });

    jQuery(".post-type-tc_tickets #post, .post-type-tc_events #post").validate({ignore: '[id^="acf"], #us_portfolio_settings input'});
    jQuery("#tc-general-settings, #tc_ticket_type_form, #tc_discount_code_form, .tc_form_validation_required").validate({
        rules: {
            field: {
                number: true
            },
            field: {
                required: true
            }
        }
    });

    if ($('#discount_type').length && $('#discount_value').length) {
        tc_check_discount_code_type();

        $('#discount_type').change(function () {
            tc_check_discount_code_type();
        });

    }

    function tc_check_discount_code_type() {
        if ($('#discount_type').length && $('#discount_value').length) {
            if ($("#discount_type option:selected").val() == '3') {
                $('tr.discount_availability').hide();
            } else {
                $('tr.discount_availability').show();
            }
        }
    }

    $(document).on('change','.has_conditional', function ( ) {
        tc_conditionals_init();
    });

    function tc_conditionals_init( ) {
        $('.tc_conditional').each(function (i, obj) {
            tc_conditional($(this));
        });
    }

    /**
     * Check if the field has a condition and execute the action based on its values.
     *
     * @param obj
     */
    function tc_conditional( obj ) {

        let field_name = $(obj).attr('data-condition-field_name'),
            selected_value;

        if (!$('.' + field_name).hasClass('has_conditional')) {
            $('.' + field_name).addClass('has_conditional');
        }

        let field_type = $(obj).attr('data-condition-field_type'),
            value = $(obj).attr('data-condition-value'),
            action = $(obj).attr('data-condition-action');

        switch ( field_type ) {
            case 'radio':
                selected_value = $('.' + field_name + ':checked').val();
                break;

            case 'text':
            case 'textarea':
            case 'select':
                selected_value = $('.' + field_name).val();
                break;

            default:
                selected_value = '';
        }

        if ( value == selected_value ) {

            if ( 'hide' == action ) {
                $(obj).hide().attr( 'disabled', true );
                $( '#' + $(obj).attr('id') + '-error' ).remove();

            } else if ( 'show' == action ) {
                $(obj).show(200).attr( 'disabled', false );
            }

        } else {

            if ( 'hide' == action ) {
                $(obj).show(200).attr( 'disabled', false );

            } else if ( 'show' == action ) {
                $(obj).hide().attr( 'disabled', true );
                $( '#' + $(obj).attr('id') + '-error' ).remove();
            }
        }

        fix_chosen();
    }

    tc_conditionals_init( );

    $('.tc_tooltip').tooltip({
        content: function () {
            return $(this).prop('title');
        },
        show: null,
        close: function (event, ui) {
            ui.tooltip.hover(
                    function () {
                        $(this).stop(true).fadeTo(100, 1);
                    },
                    function () {
                        $(this).fadeOut("100", function () {
                            $(this).remove();
                        })
                    });
        }
    });

    /**
     * Toggle Controls
     * @type {number}
     */
    var tc_event_id = 0;
    var tc_ticket_id = 0;
    var tc_event_status = 'publish';
    var tc_ticket_status = 'publish';

    var tc_toggle = {
        init: function () {
            $('body').addClass('tctgl');
            this.attachHandlers('.tctgl .tc-control');
        },
        tc_controls: {
            $tc_toggle_init: function (selector)
            {
                $(selector).click(function ()
                {
                    tc_event_id = $(this).attr('event_id');
                    tc_ticket_id = $(this).attr('ticket_id');

                    if ($(this).hasClass('tc-on')) {
                        $(this).removeClass('tc-on');
                        tc_event_status = 'private';
                        tc_ticket_status = 'private';
                    } else {
                        $(this).addClass('tc-on');
                        tc_event_status = 'publish';
                        tc_ticket_status = 'publish';
                    }

                    var attr = $(this).attr('event_id');
                    if (typeof attr !== typeof undefined && attr !== false) {//Event toggle
                        $.post(
                                tc_vars.ajaxUrl, {
                                    action: 'change_event_status',
                                    event_status: tc_event_status,
                                    event_id: tc_event_id,
                                }
                        );
                    } else {
                        $.post(
                                tc_vars.ajaxUrl, {
                                    action: 'change_ticket_status',
                                    ticket_status: tc_ticket_status,
                                    ticket_id: tc_ticket_id,
                                }
                        );
                    }


                });

            }
        },
        attachHandlers: function (selector) {
            this.tc_controls.$tc_toggle_init(selector);
        }
    };

    tc_toggle.init();

    $( document ).on( 'change', 'input.tc_active_gateways', function () {
        var currently_selected_gateway_name = $(this).val();

        if ( $(this).is(':checked') ) {
            $('#' + currently_selected_gateway_name).show(200);

        } else {
            $('#' + currently_selected_gateway_name).hide(200);
        }
    });


    if (tc_vars.animated_transitions) {
        $(".tc_wrap").fadeTo(250, 1);
        $(".tc_wrap #message").delay(2000).slideUp(250);

    } else {
        $(".tc_wrap").fadeTo(0, 1);
    }


    $( document ).on( 'click', '.tc_delete_link', function(event) {
        tc_delete(event);
    });

    function tc_delete_confirmed() {
        return confirm(tc_vars.delete_confirmation_message);
    }

    function tc_delete(event) {
        if (tc_delete_confirmed()) {
            return true;
        } else {
            event.preventDefault()
            return false;
        }
    }

    $('.file_url_button').click(function ()
    {
        var target_url_field = $(this).prevAll(".file_url:first");
        wp.media.editor.send.attachment = function (props, attachment)
        {
            $(target_url_field).val(attachment.url);
        };
        wp.media.editor.open(this);
        return false;
    });

    /**
     * Ticket Tempaltes
     * @type {any[]}
     */
    var ticket_classes = new Array();
    var parent_id = 0;

    $('.tc-color-picker').wpColorPicker();
    $("ul.sortables").sortable({
        connectWith: 'ul',
        forcePlaceholderSize: true,
        receive: function (template, ui) {
            update_li();
            $(".rows ul li").last().addClass("last_child");
        },
        stop: function (template, ui) {
            update_li();
        }
    });

    function update_li() {

        var children_num = 0;
        var current_child_num = 0;

        $(".rows ul").each(function () {

            // Empty the array
            ticket_classes.length = 0;

            children_num = $(this).children('li').length;
            $(this).children('li').removeClass();
            $(this).children('li').addClass("ui-state-default");
            $(this).children('li').addClass("cols cols_" + children_num);
            $(this).children('li').last().addClass("last_child");
            $(this).find('li').each(function (index, element) {
                if ($.inArray($(this).attr('data-class'), ticket_classes) == -1) {
                    ticket_classes.push($(this).attr('data-class'));
                }
            });
            $(this).find('.rows_classes').val(ticket_classes.join());
        });
        tc_fix_template_elements_sizes();

        $(".rows ul li").last().addClass("last_child");
        $(".tc_wrap select").css('width', '25em');
        $(".tc_wrap select").css('display', 'block');

        $(".tc_wrap select").chosen({disable_search_threshold: 5});
        $(".tc_wrap select").css('display', 'none');
        $(".tc_wrap .chosen-container").css('width', '100%');
        $(".tc_wrap .chosen-container").css('max-width', '25em');
        $(".tc_wrap .chosen-container").css('min-width', '1em');
    }

    function tc_fix_template_elements_sizes() {
        $(".rows ul").each(function () {
            var maxHeight = -1;

            $(this).find('li').each(function () {
                $(this).removeAttr("style");
                maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
            });

            $(this).find('li').each(function () {
                $(this).height(maxHeight);
            });
        });
    }

    if ($('#ticket_elements').length) {
        update_li();
        tc_fix_template_elements_sizes();
    }

    jQuery('.close-this').click(function (event) {
        event.preventDefault();
        jQuery(this).closest('.ui-state-default').appendTo('#ticket_elements');
        update_li();
        tc_fix_template_elements_sizes();
    });

    function fix_chosen() {
        $(".tc_wrap select").css('width', '25em');
        $(".tc_wrap select").css('display', 'block');
        $(".tc_wrap select").chosen({disable_search_threshold: 5, allow_single_deselect: false});
        $(".tc_wrap select").css('display', 'none');
        $(".tc_wrap .chosen-container").css('width', '100%');
        $(".tc_wrap .chosen-container").css('max-width', '25em');
        $(".tc_wrap .chosen-container").css('min-width', '1em');
    }

    $('#tc_order_resend_condirmation_email').on('click', function (event) {
        event.preventDefault();
        var new_status = $('.order_status_change').val();
        var order_id = $('#order_id').val();

        $(this).hide();
        $(this).after('<span id="tc_resending">' + tc_vars.order_confirmation_email_resending_message + '</a>');

        $.post(tc_vars.ajaxUrl, {action: "change_order_status", order_id: order_id, new_status: new_status}, function (data) {
            if (data != 'error') {

                $('.tc_wrap .message_placeholder').html('');
                $('.tc_wrap .message_placeholder').append('<div id="message" class="updated fade"><p>' + tc_vars.order_confirmation_email_resent_message + '</p></div>');
                $(".tc_wrap .message_placeholder").show(250);
                $(".tc_wrap .message_placeholder").delay(2000).slideUp(250);
                $('#tc_order_resend_condirmation_email').show();
                $('#tc_resending').remove();
            } else {
                // Show error message
            }
            $(this).fadeTo("fast", 1);
        });

    });

    /**
     * Payment Gateway Image Switch
     */
    jQuery(".tc_active_gateways").each(function () {

        if (this.checked) {
            jQuery(this).closest('.image-check-wrap').toggleClass('active-gateway');
        }

        jQuery(this).change(function () {
            fix_chosen();

            if (this.checked) {
                jQuery(this).closest('.image-check-wrap').toggleClass('active-gateway');
            } else {
                jQuery(this).closest('.image-check-wrap').toggleClass('active-gateway');
            }
        });
    })

    if (jQuery('#tickets_limit_type').val() == 'event_level') {
        jQuery('#event_ticket_limit').parent().parent().show();
    } else {
        jQuery('#event_ticket_limit').parent().parent().hide();
    }

    jQuery('#tickets_limit_type').on('change', function () {
        if (jQuery('#tickets_limit_type').val() == 'event_level') {
            jQuery('#event_ticket_limit').parent().parent().show();
        } else {
            jQuery('#event_ticket_limit').parent().parent().hide();
        }
    });

    $(".tc_wrap select").chosen({disable_search_threshold: 5, allow_single_deselect: false});

    /**
     * INLINE EDIT
     * @param replaceWith
     * @param connectWith
     */
    $.fn.inlineEdit = function (replaceWith, connectWith) {

        let clicked;

        $(this).hover( function ( ) {
            $(this).addClass('inline_hover');

        }, function () {
            $(this).removeClass('inline_hover');
        });

        $(this).click( function() {

            if ( ! $(this).hasClass( 'inline_clicked' ) ) {

                clicked = $(this);

                // Leave a mark on input
                $(this).addClass('inline_clicked');

                let orig_val = $(this).html();
                $(replaceWith).val( $.trim( orig_val) );

                $(this).hide();
                $(this).after( replaceWith );
                replaceWith.focus();

                /* Update Attendee Information */
                $( replaceWith ).blur( function () {

                    // Remove a mark of an input
                    clicked.removeClass('inline_clicked');

                    if ( clicked.val() != "" ) {
                        connectWith.val( $(this).val()).change();
                        clicked.text($(this).val());
                    }

                    clicked.text( $(this).val() );

                    var ticket_id = $(this).parent('tr').find('.ID');
                    ticket_id = ticket_id.attr('data-id');

                    save_attendee_info(ticket_id, $(this).prev().attr('class'), $(this).val());

                    $(this).remove();
                    clicked.show();

                });
            }
        });
    };

    /**
     * INLINE CHOSEN EDIT
     * @param replaceWith
     * @param connectWith
     */
    $.fn.inlineChosenEdit = function (replaceWith, connectWith) {

        let clicked;

        $(this).hover(function ( ) {
                $(this).addClass('inline_hover');
            }, function () {
                $(this).removeClass('inline_hover');
            }
        );

        $(this).click(function () {

            if ( ! $(this).hasClass( 'inline_clicked' ) ) {

                clicked = $(this);

                // Leave a mark on input
                $(this).addClass('inline_clicked');

                // Collect Related Ticket Types
                let ticket_instance_id = $(this).siblings('.ID').attr('data-id');
                $.post( tc_vars.ajaxUrl, {action: 'get_ticket_type_instances', tc_ticket_instance_id: ticket_instance_id }, function(response){
                    if ( !response.error ) {
                        clicked.hide().after(replaceWith);
                        initialize_chosen(replaceWith, response);
                    } else {
                        replaceWith = '<td>' + response.error + '</td>';
                        clicked.hide().after(replaceWith);
                    }
                });

                // Update Attendee Information
                replaceWith.on('change', function() {

                    // Remove a mark to an input
                    clicked.removeClass('inline_clicked');

                    let select_chosen = $(this).find(':selected');

                    if (select_chosen.val() != "") {
                        connectWith.val(select_chosen.val()).change();
                        clicked.text(select_chosen.text());
                    }

                    clicked.text(select_chosen.text());

                    var ticket_id = $(this).parent('tr').find('.ID');
                    ticket_id = ticket_id.attr('data-id');

                    save_attendee_info(ticket_id, $(this).prev().attr('class'), select_chosen.val());

                    $(this).chosen('destroy').empty().remove();
                    clicked.show();
                });
            }
        });
    };

    /**
     * Better Order: Update Ticket Instances Metabox
     * @type {jQuery|HTMLElement}
     */
    let replaceWithInput = $('<input name="temp" class="tc_temp_value" type="text" />'),
        replaceWithOption = $('<select class="ticket_type_id_chosen"></select>'),
        connectWith = $('input[name="hiddenField"]');

    $('#order-details-tc-metabox-wrapper .order-details tr').find('td.ticket_type_id:first').inlineChosenEdit(replaceWithOption, connectWith);
    $('td.first_name, td.last_name, td.owner_email').inlineEdit(replaceWithInput, connectWith);

    /**
     * Better Order: Update Temporary fields on keyup
     */
    $(".tc_temp_value").on('keyup', function (e) {
        if (e.keyCode == 13) {
            $(this).blur( );
        }
        e.preventDefault( );
    });

    /**
     * Initialize Chosen
     * @param elem
     * @param dataSource
     */
    function initialize_chosen(elem, dataSource) {

        for ( let i = 0;  i < dataSource.length; i++ ) {
            elem.append( '<option value="' + dataSource[i].id + '">' + dataSource[i].text + '</option>' );
        }

        $(".order-details select").chosen( {
            disable_search_threshold: 5,
            allow_single_deselect: false
        } );
    }

    function save_attendee_info(ticket_id, meta_name, meta_value) {
        var data = {
            action: 'save_attendee_info',
            post_id: ticket_id,
            meta_name: meta_name,
            meta_value: meta_value
        }
        $.post(tc_vars.ajaxUrl, data);
    }

    jQuery(document).ready(function () {

        if (tc_vars.tc_check_page == 'tc_settings') {
            jQuery(".nav-tab-wrapper").sticky({
                topSpacing: 30,
                bottomSpacing: 50
            });
        }

    });

    jQuery(window).resize(function () {
        tc_page_names_width();
    });

    // JS for trash confirmation
    jQuery(document).ready(function($){

        $( document ).on( 'click', '.post-type-tc_tickets a.submitdelete', function(e){
            e.preventDefault();

            var href = $(this).attr('href');
            splt_hrf = href.split('=');
            splt_hrf = splt_hrf[1].split('&');
            id = splt_hrf[0];
            $.post(tc_vars.ajaxUrl, {action: "trash_post_before", trash_id: id, btn_action: 'trash'}, function (data) {

                var sold = data;
                if(sold >0){

                    if(sold == 1){
                        var r = confirm(tc_vars.single_sold_ticket_trash_message.replace("%s",sold));
                    }else{
                        var r = confirm(tc_vars.multi_sold_tickets_trash_message.replace("%s",sold));
                    }

                    if(r){
                        window.location = href;
                    }
                }
                else{
                    window.location = href;
                }
            });
        });

        $( document ).on( 'click', '#doaction', function(e){

            if($('#bulk-action-selector-top').val() == 'trash'){
                e.preventDefault();
                if($('input[name="post[]"]:checked').length > 0){
                    var tids = [];
                    $.each($('input[name="post[]"]:checked'), function(){
                        tids.push($(this).val());
                    });
                    $.post(tc_vars.ajaxUrl, {action: "trash_post_before", multi_trash_id: tids, btn_action: 'multi_trash'}, function (data) {
                        var sold = data;

                        if(sold > 0){
                            var r = confirm(tc_vars.multi_check_tickets_trash_message);
                            if(!r){
                                e.preventDefault();
                            }
                            else{
                                $('#doaction').unbind(e);
                                $('#doaction').click();
                            }
                        }
                        else{
                            $('#doaction').unbind(e);
                            $('#doaction').click();
                        }
                    });
                }
            }
        });
    });

    function tc_page_names_width() {
        jQuery('.tc_wrap .nav-tab-wrapper ul').width(jQuery('.tc_wrap .nav-tab-wrapper').width());
    }

    tc_page_names_width();

    /**
     * API Key - Event Category/Name Fields
     * Dynamically Update a Chosen Fields
     */
    $( '#tc-event-category-field' ).chosen().change( function() {

        let event_name = $( '#tc-event-name-field' ),
            category_value = this.value;

        // Clear all options
        event_name.empty();

        // Temporary Disable Event Field
        event_name.prop( 'disabled', true ).trigger( 'chosen:updated' );

        $.post( tc_vars.ajaxUrl, { action: 'change_apikey_event_category', event_term_category: this.value }, function( response ) {

            if ( 'object' === typeof response ) {

                // Update select name
                let new_event_name = event_name.attr( 'name' ).split( '[' )[0];
                $( '#tc-event-name-field' ).attr( 'name', new_event_name + '[' + category_value + ']' + '[]' );

                // Insert new options
                event_name.append('<option value="all" selected>All Events</option>');
                $.each( response, function( index, value ) {
                    event_name.append( '<option value=' + index + '>' + value + '</option>' );
                });

                // Enable and update event field with chosen
                $( '#tc-event-name-field' ).prop( 'disabled', false ).trigger( 'chosen:updated' );
            }
        });
    });
});

