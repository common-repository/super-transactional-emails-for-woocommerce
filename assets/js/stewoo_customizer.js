( function( $ ) {
    'use strict';

    /**** Color functions from WooCommerce ****/
    function wc_rgb_from_hex( color ) {
        var color = color.replace( '#', '' );
        var color = color.replace( /^(.)(.)(.)$/g, '$1$1$2$2$3$3' );
        var rgb = {
            'R': parseInt( '0x' + color[ 0 ] + color[ 1 ] ),
            'G': parseInt( '0x' + color[ 2 ] + color[ 3 ] ),
            'B': parseInt( '0x' + color[ 4 ] + color[ 5 ] )
        };
        return rgb;
    }

    function wc_hex_darker( color, factor ) {
        if ( factor == undefined ) {
            factor = 30
        };
        var base = wc_rgb_from_hex( color );
        var color = '#';
        for ( var prop in base ) {
            var amount = base[ prop ] / 100;
            amount = Math.round( amount * factor );
            var new_decimal = base[ prop ] - amount;
            var new_hex_component = new_decimal.toString( 16 );
            new_hex_component = ( new_hex_component.length < 2 ) ? ( '0' + new_hex_component ) : new_hex_component;
            color += new_hex_component;
        }
        return color;
    }

    function wc_hex_lighter( color, factor ) {
        if ( factor == undefined ) {
            factor = 30
        };
        var base = wc_rgb_from_hex( color );
        var color = '#';
        for ( var prop in base ) {
            var amount = 255 - base[ prop ];
            amount = amount / 100;
            amount = Math.round( amount * factor );
            var new_decimal = base[ prop ] + amount;
            var new_hex_component = new_decimal.toString( 16 );
            new_hex_component = ( new_hex_component.length < 2 ) ? ( '0' + new_hex_component ) : new_hex_component;
            color += new_hex_component;
        }
        return color;
    }

    function wc_light_or_dark( color, dark, light ) {
        if ( dark == undefined ) {
            dark = '#000000'
        };
        if ( light == undefined ) {
            light = '#FFFFFF'
        };
        var color = color.replace( '#', '' );

        var c_r = parseInt( '0x' + color[ 0 ] + color[ 1 ] );
        var c_g = parseInt( '0x' + color[ 2 ] + color[ 3 ] );
        var c_b = parseInt( '0x' + color[ 4 ] + color[ 5 ] );

        var brightness = ( ( c_r * 299 ) + ( c_g * 587 ) + ( c_b * 114 ) ) / 1000;

        return brightness > 155 ? dark : light;
    }
    /**** End Color functions from WooCommerce ****/
    $( function() {

        if ( !stewoo.display_order_items_images ) {
			$( '#body_content_inner .order_item div' ).hide()
        }

        wp.customize( 'stewoo_display_order_items_images', function( value ) {
            value.bind( function( newval ) {
                console.log( newval );
                if ( newval ) {
                    $( 'body > div .order_item > td img' ).show();
                } else {
                    $( 'body > div .order_item > td img' ).hide();
                }
            } );
        } );

        wp.customize( 'stewoo_base_color', function( value ) {
            value.bind( function( newval ) {
                $( '#template_header' ).css( 'background-color', newval );
                $( '#template_container h2, #template_container h3' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_background_color', function( value ) {
            value.bind( function( newval ) {
                $( 'body, body > div' ).css( 'background-color', newval );
                $( 'table#template_container' ).css( 'border-color', wc_hex_darker( newval, 10 ) );
            } );
        } );
        wp.customize( 'stewoo_body_background_color', function( value ) {
            value.bind( function( newval ) {
                $( '#template_container, #body_content' ).css( 'background-color', newval );
                $( '.td' ).css( 'border-color', wc_hex_darker( newval, 10 ) );
            } );
        } );
        wp.customize( 'stewoo_body_text_color', function( value ) {
            value.bind( function( newval ) {
                $( '.text' ).css( 'color', newval );
                $( '#body_content_inner, .td' ).css( 'color', wc_hex_lighter( newval, 20 ) );
            } );
        } );

        wp.customize( 'woocommerce_email_header_image', function( value ) {
            value.bind( function( newval ) {
                $( '#template_header_image' ).html( '<p style="margin:0"><img src="' + newval + '" /></p>' );
            } );
        } );

        
        wp.customize( 'stewoo_header_background_color', function( value ) {
            value.bind( function( newval ) {
                $( '#header_wrapper' ).css( 'background-color', newval );
            } );
        } );
        wp.customize( 'stewoo_header_text_color', function( value ) {
            value.bind( function( newval ) {
				if (newval == '') {
					newval = '#ffffff';
				}
                $( '#header_wrapper h1' ).css( {
					'color': newval,
					'text-shadow': wc_hex_lighter( newval, 20 ) + ' 0px 1px 0px'
				} );
            } );
        } );
        wp.customize( 'stewoo_header_font_size', function( value ) {

            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_header_font_size']", window.parent.document).next().html(newval+'px');
                $( '#header_wrapper h1' ).css( 'font-size', newval + 'px' );
            } );
        } );
        wp.customize( 'stewoo_header_padding', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_header_padding']", window.parent.document).next().html(newval+'px');
                $( '#header_wrapper' ).css( {
                    'padding-top': newval + 'px',
                    'padding-bottom': newval + 'px'
                } );
            } );
        } );

		/** Body **/
        wp.customize( 'stewoo_body_top_text_color', function( value ) {
            value.bind( function( newval ) {
                $( '#body_content_inner > p' ).css( {
					'color': newval
				} );
            } );
        } );
        wp.customize( 'stewoo_body_top_text_size', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_body_top_text_size']", window.parent.document).next().html(newval+'px');
                $( '#body_content_inner > p' ).css( {
					'font-size': newval + '.px'
				} );
            } );
        } );

        $("input[data-customize-setting-link='stewoo_products_description_length']", window.parent.document).on('change', function(){
            // input type range with 'option' set on refresh, not postMessage
            $(this).next().html( $(this).val() + ' words' );
        })

        wp.customize( 'stewoo_body_top_text_vertical_spacing', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_body_top_text_vertical_spacing']", window.parent.document).next().html(newval+'px');
                $( '#body_content_inner > p' ).css( {
                    'margin-top': newval + '.px',
					'margin-bottom': newval + '.px'
				} );
            } );
        } );

        wp.customize( 'stewoo_body_item_details_vertical_spacing', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_body_item_details_vertical_spacing']", window.parent.document).next().html(newval+'px');
                $( '#body_content_inner td' ).css( {
                    'padding-top': newval + '.px',
					'padding-bottom': newval + '.px'
				} );
            } );
        } );

        // Products informations
        wp.customize( 'stewoo_products_title', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_txt1' ).html( newval );
            } );
        } );
        wp.customize( 'stewoo_products_subtitle', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_txt2' ).html( newval );
            } );
        } );
        wp.customize( 'stewoo_products_button_text', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_button' ).text( newval );
            } );
        } );

        // Products design
        wp.customize( 'stewoo_products_title_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_txt1' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_title_background_color', function( value ) {
            value.bind( function( newval ) {
                newval == '' ? 'transparent' : newval;
                $( '.stewoo_product_txt1' ).css( 'background-color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_title_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_title_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_txt1' ).css( 'font-size', newval + 'px' );
            } );
        } );

        wp.customize( 'stewoo_products_subtitle_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_txt2' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_subtitle_background_color', function( value ) {
            value.bind( function( newval ) {
                newval == '' ? 'transparent' : newval;
                $( '.stewoo_product_txt2' ).css( 'background-color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_subtitle_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_subtitle_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_txt2' ).css( 'font-size', newval + 'px' );
            } );
        } );

        wp.customize( 'stewoo_products_container_background_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_global_container' ).css( 'background-color', newval );
            } );
        } );

        wp.customize( 'stewoo_products_product_title_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_title' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_product_title_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_product_title_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_title' ).css( 'font-size', newval + 'px' );
            } );
        } );

        wp.customize( 'stewoo_products_product_price_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_price' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_product_price_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_product_price_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_price' ).css( 'font-size', newval + 'px' );
            } );
        } );

        wp.customize( 'stewoo_products_product_description_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_description' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_product_description_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_product_description_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_description' ).css( 'font-size', newval + 'px' );
            } );
        } );

        wp.customize( 'stewoo_products_product_button_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_button' ).css( 'color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_product_button_background_color', function( value ) {
            value.bind( function( newval ) {
                $( '.stewoo_product_button' ).css( 'background-color', newval );
            } );
        } );
        wp.customize( 'stewoo_products_product_button_fontsize', function( value ) {
            value.bind( function( newval ) {
                $("input[data-customize-setting-link='stewoo_products_product_button_fontsize']", window.parent.document).next().html(newval+'px');
                $( '.stewoo_product_button' ).css( 'font-size', newval + 'px' );
            } );
        } );

        // Texts
        wp.customize('woocommerce_email_footer_text', function (value) {
            value.bind(function (newval) {
                $('#credit p').html(newval);
            });
        });

    } );

} )( jQuery );
