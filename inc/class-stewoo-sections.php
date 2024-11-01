<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

class Stewoo_Sections
{
    private function __construct()
    {
    }
    
    public static function add_sections()
    {
        global  $wp_customize ;
        $wp_customize->add_section( 'stewoo_section1', array(
            'title'       => __( 'WooCommerce Base Design', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'priority'    => 10,
            'description' => '',
        ) );
        $wp_customize->add_section( 'stewoo_section2', array(
            'title'       => __( 'Header Design', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'description' => '',
            'priority'    => 10,
        ) );
        $wp_customize->add_section( 'stewoo_section3', array(
            'title'       => __( 'Body Design', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'description' => '',
            'priority'    => 10,
        ) );
        $wp_customize->add_section( 'stewoo_section9', array(
            'title'       => __( 'Texts Content', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'description' => '',
            'priority'    => 10,
        ) );
        $wp_customize->add_section( 'stewoo_section5', array(
            'title'       => __( 'Custom CSS', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'description' => '',
            'priority'    => 10,
        ) );
        $wp_customize->add_section( 'stewoo_section8', array(
            'title'       => __( 'Send Test Email', 'stewoo' ),
            'capability'  => 'edit_theme_options',
            'description' => '',
            'priority'    => 10,
        ) );
    }

}