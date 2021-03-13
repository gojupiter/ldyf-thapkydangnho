jQuery( document ).ready( function() {

//Position barcode box to center
    jQuery( '.barcode_holder' ).css( {
        position: 'absolute',
        left: ( jQuery( '#wpcontent' ).width() - jQuery( '.barcode_holder' ).outerWidth() ) / 2,
        top: ( jQuery( window ).height() - jQuery( '.barcode_holder' ).outerHeight() ) / 2
    } );

//Position barcode box to center while resizing window
    jQuery( window ).resize( function() {
        jQuery( '.barcode_holder' ).css( {
            position: 'absolute',
            left: ( jQuery( '#wpcontent' ).width() - jQuery( '.barcode_holder' ).outerWidth() ) / 2,
            top: ( jQuery( window ).height() - jQuery( '.barcode_holder' ).outerHeight() ) / 2
        } );
    } );

    //jQuery( window ).resize();

    jQuery( function( $ )
    {
        var barcode_input_field = jQuery( '#barcode' );//Get barcode input field object
        var barcode_status = jQuery( '.barcode_status' );//Get status object
        var code = null; //Set null for keybord key for start
        var api_key = jQuery( '#api_key' ).val();
        barcode_input_field.keypress( function( e )//detect input key
        {
            code = ( e.keyCode ? e.keyCode : e.which );
            if ( code == 13 ) {//submit barcode for check-in if enter it's enter / return key

                barcode_status.html( tc_barcode_reader_vars.message_checking_in );//set checking in... message on enter
                barcode_status.removeAttr( "class" );//remove all element classes
                barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                barcode_status.addClass( 'default' );//add status css class

                if ( barcode_input_field.val().trim() == '' ) {
                    barcode_status.html( tc_barcode_reader_vars.message_barcode_cannot_be_empty );
                    barcode_status.removeAttr( "class" );//remove all element classes
                    barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                    barcode_status.addClass( 'error' );//add status css class
                } else {

                    if ( isNaN( parseFloat( jQuery( '#api_key' ).val() ) ) ) {
                        barcode_status.html( tc_barcode_reader_vars.message_barcode_api_key_not_selected );
                        barcode_status.removeAttr( "class" );//remove all element classes
                        barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                        barcode_status.addClass( 'error' );//add status css class
                    } else {

                        jQuery.post(
                            tc_barcode_reader_vars.admin_ajax_url, //wp ajax url
                            {
                                action: 'check_in_barcode', //action
                                api_key: jQuery( '#api_key' ).val(), //api key field value
                                barcode: barcode_input_field.val(), //barcode input value
                            },
                            function( data, textStatus, jqXHR )
                            {
                                if ( parseFloat( data ) == 1 ) {//means OK
                                    barcode_status.html( tc_barcode_reader_vars.message_barcode_status_success );
                                    barcode_status.removeAttr( "class" );//remove all element classes
                                    barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                                    barcode_status.addClass( 'success' );//add status css class
                                } else if ( parseFloat( data ) == 2 ) {//means ERROR
                                    barcode_status.html( tc_barcode_reader_vars.message_barcode_status_error );
                                    barcode_status.removeAttr( "class" );//remove all element classes
                                    barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                                    barcode_status.addClass( 'error' );//add status css class
                                } else if ( parseFloat( data ) == 403 ) {//means insufficient permissions
                                    barcode_status.html( tc_barcode_reader_vars.message_insufficient_permissions );
                                    barcode_status.removeAttr( "class" );//remove all element classes
                                    barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                                    barcode_status.addClass( 'error' );//add status css class
                                } else {//means that code does not exists or expired
                                    barcode_status.html( tc_barcode_reader_vars.message_barcode_status_error_exists );
                                    barcode_status.removeAttr( "class" );//remove all element classes
                                    barcode_status.addClass( 'barcode_status' );//add standard css class for the barcode status message
                                    barcode_status.addClass( 'error_exists' );//add status css class
                                }
                            } );
                    }

                    barcode_input_field.val( '' );//clear barcode input field after submit
                }
            }

        } );

        jQuery( ".barcode_holder" ).fadeTo( "slow", 1 );

    } );


} );