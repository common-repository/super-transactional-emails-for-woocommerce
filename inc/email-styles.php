<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'stewoo_format_pxsize' ) ) {
    // add '.px' if missing
    function stewoo_format_pxsize( $str_pxsize ) {
        if ( $str_pxsize != '' ) {
            if ( substr( $str_pxsize, -2 ) != 'px' ) {
                $str_pxsize .= 'px';
            }
            return $str_pxsize;
        }
    }
}

$base            = get_option( 'stewoo_base_color', get_option( 'woocommerce_email_base_color', '#557da1' ) );
$bg              = get_option( 'stewoo_background_color', get_option( 'woocommerce_email_background_color', '#f5f5f5' ) );
$body            = get_option( 'stewoo_body_background_color', get_option( 'woocommerce_email_body_background_color', '#fdfdfd' ) );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
// $base_text = '#202020';
$text            = get_option( 'stewoo_body_text_color', get_option( 'woocommerce_email_text_color', '#505050' ) );


$bg_darker_10    = wc_hex_darker( $bg, 10 );

$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );


$header_background_color = get_option( 'stewoo_header_background_color', $base);
$header_text_color = get_option( 'stewoo_header_text_color', '#ffffff');

$header_text_lighter_20 = wc_hex_lighter( $header_text_color, 20 );

$header_font_size = get_option( 'stewoo_header_font_size', '30px' );

$header_padding = get_option( 'stewoo_header_padding', '36px' );

$stewoo_body_item_details_vertical_spacing = stewoo_format_pxsize( get_option( 'stewoo_body_item_details_vertical_spacing', '12px' ) );

if (strpos($header_font_size, 'px') === false) {
    $header_font_size .= 'px';
}

if (strpos($header_padding, 'px') === false) {
    $header_padding .= 'px';
}

// Body
$body_top_text_size = get_option( 'stewoo_body_top_text_size', '14px' );
if (strpos($body_top_text_size, 'px') === false) {
    $body_top_text_size .= 'px';
}

$body_top_text_color = get_option( 'stewoo_body_top_text_color', get_option( 'woocommerce_email_text_color', '#505050' ) );

$body_top_text_vertical_spacing = get_option( 'stewoo_body_top_text_vertical_spacing', '16px' );
if (strpos($body_top_text_vertical_spacing, 'px') === false) {
    $body_top_text_vertical_spacing .= 'px';
}

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$styles = <<<EOT
#wrapper {
    background-color: $bg;
    margin: 0;
    padding: 70px 0 70px 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
}

#template_container {
    box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
    background-color: $body;
    border: 1px solid $bg_darker_10;
    border-radius: 3px !important;
}

#template_header {
    background-color: $base;
    border-radius: 3px 3px 0 0 !important;
    color: $base_text;
    border-bottom: 0;
    font-weight: bold;
    line-height: 100%;
    vertical-align: middle;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1 {
    color: $base_text;
}

#template_footer td {
    padding: 0;
    -webkit-border-radius: 6px;
}

#template_footer #credit {
    border:0;
    color: $base_lighter_40;
    font-family: Arial;
    font-size:12px;
    line-height:125%;
    text-align:center;
    padding: 0 48px 48px 48px;
}

#body_content {
    background-color: $body;
}

#body_content table td {
    padding: 48px;
}

#body_content table td td {
    padding: 12px;
    padding-top: $stewoo_body_item_details_vertical_spacing;
    padding-bottom: $stewoo_body_item_details_vertical_spacing;
}

#body_content table td th {
    padding: 12px;
    padding-top: $stewoo_body_item_details_vertical_spacing;
    padding-bottom: $stewoo_body_item_details_vertical_spacing;
}

#body_content p {
    margin: 16px 0 16px 0;
}

#body_content_inner {
    color: $text_lighter_20;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 150%;
}

.td {
    color: $text_lighter_20;
    border: 1px solid $body_darker_10;
    border-color: $body_darker_10;
}

.text {
    color: $text;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
    color: $base;
}

#header_wrapper {
    padding: 36px 48px;
    display: block;
    background-color: $header_background_color;
}

h1 {
    color: $base;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    text-shadow: 0 1px 0 $base_lighter_20;
    -webkit-font-smoothing: antialiased;
}

h2 {
    color: $base;
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
}

h3 {
    color: $base;
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
}

a {
    color: $base;
    font-weight: normal;
    text-decoration: underline;
}

img {
    border: none;
    display: inline;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
}
#template_header_image p{
    margin-bottom: 0;
    font-size: 0;
}
#template_header_image img{
    max-width: 100% !important;
}
#header_wrapper{
    padding-top: $header_padding;
    padding-bottom: $header_padding;
}
#header_wrapper h1{
    color: $header_text_color;
    font-size: $header_font_size !important;
    text-shadow: 0 1px 0 $header_text_lighter_20;
}
#body_content > table > tr > td {
    padding: 0;
}
#body_content_inner{
    margin: 0 48px;
}
#body_content_inner .order_item div{
    display: inline-block;
    margin-top: 5px;
}
#body_content_inner > p {
    font-size: $body_top_text_size;
    line-height: 120%;
    color: $body_top_text_color;
    margin-top: $body_top_text_vertical_spacing;
    margin-bottom: $body_top_text_vertical_spacing;
}

#template_container {
    border: 0;
}

EOT;


// Stewoo Suggested Products


$stewoo_products_title_color = get_option( 'stewoo_products_title_color', $header_text_color );
$stewoo_products_title_background_color = get_option( 'stewoo_products_title_background_color', $header_background_color);
$stewoo_products_title_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_title_fontsize', '25px' ) );

$stewoo_products_subtitle_color = get_option( 'stewoo_products_subtitle_color', $header_text_color );
$stewoo_products_subtitle_background_color = get_option( 'stewoo_products_subtitle_background_color', $header_background_color);
$stewoo_products_subtitle_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_subtitle_fontsize', '16px' ) );

$stewoo_products_container_background_color = get_option( 'stewoo_products_container_background_color', '#f5f5f5' );

$stewoo_products_product_title_color = get_option( 'stewoo_products_product_title_color', '#202020' );
$stewoo_products_product_title_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_product_title_fontsize', '16px' ) );

$stewoo_products_product_price_color = get_option( 'stewoo_products_product_price_color', '#557da1' );
$stewoo_products_product_price_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_product_price_fontsize', '14px' ) );

$stewoo_products_product_description_color = get_option( 'stewoo_products_product_description_color', '#202020' );
$stewoo_products_product_description_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_product_description_fontsize', '12px' ) );

$stewoo_products_product_button_color = get_option( 'stewoo_products_product_button_color', '#f5f5f5' );
$stewoo_products_product_button_background_color = get_option( 'stewoo_products_product_button_background_color', '#557da1' );
$stewoo_products_product_button_fontsize = stewoo_format_pxsize( get_option( 'stewoo_products_product_button_fontsize', '12px' ) );


if ( get_option('stewoo_products_zone') != 'top' ) {
    $stewoo_global_container_margin_top = 'margin-top: 40px !important;';
} else {
    $stewoo_global_container_margin_top = '';
}



$styles .= <<<EOT
    .stewoo_global_container{
        font-size: 0 !important;
        margin: 0 !important;
        margin-bottom: 10px !important;
        width: 100%;
        background-color: $stewoo_products_container_background_color;
        $stewoo_global_container_margin_top
    }
    .stewoo_product_txt1{
        font-size: $stewoo_products_title_fontsize;
        font-weight: 300;
        text-align: center;
        color: $stewoo_products_title_color;
        background-color: $stewoo_products_title_background_color;
        padding: 15px;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        margin-bottom: 0;
        margin-top: 0px;
        line-height: 100%;
        -webkit-font-smoothing: antialiased;
        /*margin-top: 15px;*/
    }
    .stewoo_product_txt2{
        font-size: $stewoo_products_subtitle_fontsize;
        font-weight: normal;
        text-align: center;
        color: $stewoo_products_subtitle_color;
        background-color: $stewoo_products_subtitle_background_color;
        padding: 20px;
        padding-top: 5px;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        margin: 0;
        line-height: 20px;
        -webkit-font-smoothing: antialiased;
    }
    .stewoo_products_container{
        width: 100%;
        border-spacing: 0;
        padding-top: 20px;
        font-size: 0;
    }
    .stewoo_products_container td{
        vertical-align: top;
        text-align: center;
    }
    .stewoo_product_link{
        text-decoration: none;
    }
    .stewoo_product_link > img{
        max-width: 100% !important;
    }
    .stewoo_products_container.lattice2_1 td, .stewoo_products_container.lattice4_2 td, .stewoo_products_container.lattice2_1 > div, .stewoo_products_container.lattice4_2 > div{
        width: 50%;
    }
    .stewoo_products_container.lattice3_1 td, .stewoo_products_container.lattice6_2 td, .stewoo_products_container.lattice3_1 > div, .stewoo_products_container.lattice6_2 > div{
        width: 33%;
    }
    .stewoo_products_container.lattice4_1 td, .stewoo_products_container.lattice8_2 td, .stewoo_products_container.lattice4_1 > div, .stewoo_products_container.lattice8_2 > div{
        width: 25%;
        max-width: 25% !important;
    }
    .stewoo_product_image_container{
        text-align: center;
    }
    .stewoo_product_title{
        font-size: $stewoo_products_product_title_fontsize;
        color: $stewoo_products_product_title_color;
        margin: 0;
        margin-top: 4px;
        margin-bottom: 4px;
        text-align: center;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        display: block;
        max-width: 100% !important;
    }
    .stewoo_product_price{
        font-size: $stewoo_products_product_price_fontsize;
        color: $stewoo_products_product_price_color;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        text-align: center;
        margin: 0;
        margin-bottom: 4px;
        display: block;
        max-width: 100% !important;
    }
    .stewoo_product_description{
        font-size: $stewoo_products_product_description_fontsize !important;
        text-align: justify;
        padding: 0 12px;
        color: $stewoo_products_product_description_color;
        margin-top: 0px;
        margin-bottom: 7px;
        display: block;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        max-width: 100% !important;
    }
    .stewoo_product_button{
        font-size: $stewoo_products_product_button_fontsize;
        color: $stewoo_products_product_button_color;
        background-color: $stewoo_products_product_button_background_color;
        padding: 10px 20px;
        font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
        display: block;
        width: 60%;
        margin: auto;
        text-decoration: none;
        margin-bottom: 12px;
        font-weight: bold;
        text-align: center;
        max-width: 100% !important;
    }
    /* cells treatment */
    #body_content .stewoo_global_container td{
        padding: 0 5px;
        -webkit-border-radius: 0;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice8_2 .cell_1, #body_content .stewoo_global_container .stewoo_products_container.lattice8_2 .cell_5 {
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice8_2 .cell_4, #body_content .stewoo_global_container .stewoo_products_container.lattice8_2 .cell_8 {
        padding: 0 6px 0 4px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice4_1 .cell_1{
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice4_1 .cell_4{
        padding: 0 6px 0 4px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice6_2 .cell_1, #body_content .stewoo_global_container .stewoo_products_container.lattice6_2 .cell_4 {
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice6_2 .cell_3, #body_content .stewoo_global_container .stewoo_products_container.lattice6_2 .cell_6 {
        padding: 0 6px 0 4px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice3_1 .cell_1{
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice3_1 .cell_3{
        padding: 0 6px 0 4px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice4_2 .cell_1, #body_content .stewoo_global_container .stewoo_products_container.lattice4_2 .cell_3 {
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice4_2 .cell_2, #body_content .stewoo_global_container .stewoo_products_container.lattice4_2 .cell_4 {
        padding: 0 6px 0 4px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice2_1 .cell_1 {
        padding: 0 4px 0 6px;
    }
    #body_content .stewoo_global_container .stewoo_products_container.lattice2_1 .cell_2 {
        padding: 0 6px 0 4px;
    }
    /* end cells treatment */
EOT;

$styles .= get_option( 'stewoo_custom_css', '' );
