<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

require 'class-stewoo-custom-controls.php';
class Stewoo_Controls
{
    private function __construct()
    {
    }
    
    private static function list_orders()
    {
        $orders_list = get_posts( array(
            'numberposts' => -1,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ) );
        $orders_choices = array();
        foreach ( $orders_list as $orders_item ) {
            $orders_choices[$orders_item->ID] = '#' . $orders_item->ID;
        }
        return $orders_choices;
    }
    
    public static function add_controls()
    {
        global  $wp_customize ;
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'stewoo_order_code_control', array(
            'label'       => __( 'Order # to preview', 'stewoo' ),
            'priority'    => 10,
            'section'     => 'stewoo_section1',
            'settings'    => 'stewoo_order_code',
            'type'        => 'select',
            'description' => '',
            'choices'     => self::list_orders(),
        ) ) );
        /**** Settings from WooCommerce ****/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_base_color_control', array(
            'label'    => __( 'Base Colour', 'woocommerce' ),
            'priority' => 30,
            'section'  => 'stewoo_section1',
            'settings' => 'stewoo_base_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_background_color_control', array(
            'label'    => __( 'Background Colour', 'woocommerce' ),
            'priority' => 30,
            'section'  => 'stewoo_section1',
            'settings' => 'stewoo_background_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_body_background_color_control', array(
            'label'    => __( 'Body Background Colour', 'woocommerce' ),
            'priority' => 30,
            'section'  => 'stewoo_section1',
            'settings' => 'stewoo_body_background_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_body_text_color_control', array(
            'label'    => __( 'Body Text Colour', 'woocommerce' ),
            'priority' => 30,
            'section'  => 'stewoo_section1',
            'settings' => 'stewoo_body_text_color',
        ) ) );
        /**** Header Controls ****/
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'stewoo_header_image_control', array(
            'label'    => __( 'Header Image', 'woocommerce' ),
            'priority' => 10,
            'section'  => 'stewoo_section2',
            'settings' => 'woocommerce_email_header_image',
            'context'  => 'stewoo_header_image_logo',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_header_background_color_control', array(
            'label'    => __( 'Header Background Color', 'stewoo' ),
            'priority' => 30,
            'section'  => 'stewoo_section2',
            'settings' => 'stewoo_header_background_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_header_text_color_control', array(
            'label'    => __( 'Header Text Color', 'stewoo' ),
            'priority' => 30,
            'section'  => 'stewoo_section2',
            'settings' => 'stewoo_header_text_color',
        ) ) );
        $wp_customize->add_control( new Stewoo_Range_Customize_Control( $wp_customize, 'stewoo_header_font_size_control', array(
            'label'       => __( 'Header Font Size', 'stewoo' ),
            'priority'    => 30,
            'section'     => 'stewoo_section2',
            'settings'    => 'stewoo_header_font_size',
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 8,
            'max'  => 50,
            'step' => 1,
        ),
            'unit'        => 'px',
        ) ) );
        $wp_customize->add_control( new Stewoo_Range_Customize_Control( $wp_customize, 'stewoo_header_padding_control', array(
            'label'       => __( 'Header Padding', 'stewoo' ),
            'priority'    => 30,
            'section'     => 'stewoo_section2',
            'settings'    => 'stewoo_header_padding',
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ),
            'unit'        => 'px',
        ) ) );
        /**** Body Controls ****/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stewoo_body_top_text_color_control', array(
            'label'    => __( 'Top Text Color', 'stewoo' ),
            'priority' => 10,
            'section'  => 'stewoo_section3',
            'settings' => 'stewoo_body_top_text_color',
        ) ) );
        $wp_customize->add_control( new Stewoo_Range_Customize_Control( $wp_customize, 'stewoo_body_top_text_size_control', array(
            'label'       => __( 'Top Text Font Size', 'stewoo' ),
            'priority'    => 10,
            'section'     => 'stewoo_section3',
            'settings'    => 'stewoo_body_top_text_size',
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 8,
            'max'  => 30,
            'step' => 1,
        ),
            'unit'        => 'px',
        ) ) );
        $wp_customize->add_control( new Stewoo_Range_Customize_Control( $wp_customize, 'stewoo_body_top_text_vertical_spacing_control', array(
            'label'       => __( 'Top Text Padding', 'stewoo' ),
            'priority'    => 10,
            'section'     => 'stewoo_section3',
            'settings'    => 'stewoo_body_top_text_vertical_spacing',
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 0,
            'max'  => 70,
            'step' => 1,
        ),
            'unit'        => 'px',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'stewoo_display_order_items_images_control', array(
            'label'       => __( 'Show images on items order list', 'stewoo' ),
            'priority'    => 10,
            'section'     => 'stewoo_section3',
            'settings'    => 'stewoo_display_order_items_images',
            'type'        => 'checkbox',
            'description' => '',
        ) ) );
        $wp_customize->add_control( new Stewoo_Range_Customize_Control( $wp_customize, 'stewoo_body_item_details_vertical_spacing_control', array(
            'label'       => __( 'Order Details Vertical Spacing', 'stewoo' ),
            'priority'    => 10,
            'section'     => 'stewoo_section3',
            'settings'    => 'stewoo_body_item_details_vertical_spacing',
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 0,
            'max'  => 20,
            'step' => 1,
        ),
            'unit'        => 'px',
        ) ) );
        
        if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
            $wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'stewoo_custom_css_control', array(
                'label'       => __( 'Internal CSS code', 'stewoo' ),
                'section'     => 'stewoo_section5',
                'settings'    => 'stewoo_custom_css',
                'code_type'   => 'text/css',
                'description' => __( 'This code is combined in HTML tags style attribut.', 'stewoo' ),
                'input_attrs' => array(),
            ) ) );
        } else {
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'stewoo_custom_css_control', array(
                'label'       => __( 'Internal CSS code', 'stewoo' ),
                'priority'    => 10,
                'section'     => 'stewoo_section5',
                'settings'    => 'stewoo_custom_css',
                'type'        => 'textarea',
                'description' => __( 'This code is combined in HTML tags style attribut.', 'stewoo' ),
                'input_attrs' => array(),
            ) ) );
        }
        
        // Test Email
        $wp_customize->add_control( new Stewoo_Customizer_Send_Email_Control( $wp_customize, 'wc_email_send_email_control', array(
            'label'    => __( 'Send Test Email', 'stewoo' ),
            'section'  => 'stewoo_section8',
            'settings' => 'stewoo_test_email',
        ) ) );
        
        if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
            $wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'woocommerce_email_footer_text_control', array(
                'label'       => __( 'Footer Content', 'woocommerce' ),
                'section'     => 'stewoo_section9',
                'settings'    => 'woocommerce_email_footer_text',
                'code_type'   => 'text/html',
                'description' => __( 'You can use HTML tags', 'stewoo' ),
                'input_attrs' => array(),
            ) ) );
        } else {
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'woocommerce_email_footer_text_control', array(
                'label'       => __( 'Footer Content', 'woocommerce' ),
                'priority'    => 30,
                'section'     => 'stewoo_section9',
                'settings'    => 'woocommerce_email_footer_text',
                'type'        => 'textarea',
                'description' => __( 'You can use HTML tags', 'stewoo' ),
            ) ) );
        }
    
    }

}