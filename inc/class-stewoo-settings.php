<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

class Stewoo_Settings
{
    private function __construct()
    {
    }
    
    private static function get_last_order_id()
    {
        $orders_list = get_posts( array(
            'numberposts' => 1,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ) );
        if ( !empty($orders_list) ) {
            return $orders_list[0]->ID;
        }
        return '';
    }
    
    public static function add_settings()
    {
        global  $wp_customize ;
        $wp_customize->add_setting( 'stewoo_display_order_items_images', array(
            'type'      => 'option',
            'transport' => 'refresh',
        ) );
        /**** Settings from WooCommerce ****/
        $wp_customize->add_setting( 'stewoo_base_color', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_base_color', '#557da1' ),
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_background_color', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_background_color', '#f5f5f5' ),
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_body_background_color', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_body_background_color', '#fdfdfd' ),
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_body_text_color', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_text_color', '#505050' ),
            'transport' => 'postMessage',
        ) );
        /**** Header Settings ****/
        $wp_customize->add_setting( 'woocommerce_email_header_image', array(
            'type'      => 'option',
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_header_background_color', array(
            'type'      => 'option',
            'transport' => 'postMessage',
            'default'   => get_option( 'woocommerce_email_base_color', '#557da1' ),
        ) );
        $wp_customize->add_setting( 'stewoo_header_text_color', array(
            'type'      => 'option',
            'transport' => 'postMessage',
            'default'   => '#ffffff',
        ) );
        $wp_customize->add_setting( 'stewoo_header_font_size', array(
            'type'      => 'option',
            'transport' => 'postMessage',
            'default'   => '30px',
        ) );
        $wp_customize->add_setting( 'stewoo_header_padding', array(
            'type'      => 'option',
            'transport' => 'postMessage',
            'default'   => '36px',
        ) );
        /**** Body Settings ****/
        $wp_customize->add_setting( 'stewoo_body_top_text_color', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_text_color', '#505050' ),
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_body_top_text_size', array(
            'type'      => 'option',
            'default'   => '14',
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_body_top_text_vertical_spacing', array(
            'type'      => 'option',
            'default'   => '48',
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_body_item_details_vertical_spacing', array(
            'type'      => 'option',
            'default'   => '12',
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_setting( 'stewoo_order_code', array(
            'type'      => 'option',
            'default'   => self::get_last_order_id(),
            'transport' => 'refresh',
        ) );
        // Custom CSS
        $wp_customize->add_setting( 'stewoo_custom_css', array(
            'type'      => 'option',
            'default'   => '',
            'transport' => 'refresh',
        ) );
        // Test Email
        $wp_customize->add_setting( 'stewoo_test_email', array(
            'type'      => 'option',
            'default'   => '',
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'woocommerce_email_footer_text', array(
            'type'      => 'option',
            'default'   => get_option( 'woocommerce_email_footer_text' ),
            'transport' => 'postMessage',
        ) );
    }

}