( function( $ ) {
    'use strict';
    $( function() {
        

        $("#stewoo_specific_products_id_selector").bind("change", function() {
            // the auto-complete fill the hidden field for specific products
            $( 'input[data-customize-setting-link="stewoo_specific_products_id"]' ).val( $( this ).val() ).trigger( 'change' );
        });

        // Send Test Email
        $( document.body ).on( 'click', '.stewoo_send_test_email', function( e ) {
            e.preventDefault();
            if ( ! wp.customize.state( 'saved' )() ) {
			    alert( stewoo.saveFirstMessage );
    			return false;
            }
            $('.stewoo_send_test_email_message').removeClass('updated').html('');
            $('.stewoo_send_test_email').addClass('loading');
            var data = {
				action: 'stewoo_send_test_email',
				ajaxSendEmailNonce : stewoo.ajaxSendEmailNonce,
				email_to: $( '.stewoo_send_test_email.button' ).prev( 'p' ).find( 'input[name="send_test_email_to"]' ).val(),
                email_type: stewoo.emailSubject
			};
            $.post( stewoo.ajaxurl, data, function( response ) {
			if ( 'success' === response ) {
                    $('.stewoo_send_test_email').removeClass('loading');
                    $('.stewoo_send_test_email_message').addClass('updated').html(stewoo.success);
    			} else {
                    $('.stewoo_send_test_email').removeClass('loading');
                    $('.stewoo_send_test_email_message').addClass('error').html(stewoo.error);
    			}
    		} );
        })
    });
})( jQuery );
