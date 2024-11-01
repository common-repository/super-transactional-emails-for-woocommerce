<?php

/**
 * Plugin Name: Super Transactional Emails for WooCommerce Pro
 * Text Domain: stewoo
 * Description: Enhance WooCommerce transactional emails sent to customers with suggested products, custom texts and pictures. Add CSS for custom and unique design.
 * Author: Boris Colombier
 * Author URI: https://stewoo.com
 * Version: 1.2.4
 * License: Commercial
 * WC requires at least: 2.3
 * WC tested up to: 4.8.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'stewoo_fs' ) ) {
    stewoo_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'stewoo_fs' ) ) {
        // Create a helper function for easy SDK access.
        function stewoo_fs()
        {
            global  $stewoo_fs ;
            
            if ( !isset( $stewoo_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $stewoo_fs = fs_dynamic_init( array(
                    'id'              => '2198',
                    'slug'            => 'super-transactional-emails-for-woocommerce',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_3794bde86a315c35d8760b4af0163',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'           => 'stewoo',
                    'override_exact' => true,
                    'first-path'     => 'options-general.php?page=stewoo_presentation',
                    'support'        => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $stewoo_fs;
        }
        
        // Init Freemius.
        stewoo_fs();
        // Signal that SDK was initiated.
        do_action( 'stewoo_fs_loaded' );
        function stewoo_fs_settings_url()
        {
            return admin_url( 'admin.php?page=stewoo' );
        }
        
        stewoo_fs()->add_filter( 'connect_url', 'stewoo_fs_settings_url' );
        stewoo_fs()->add_filter( 'after_skip_url', 'stewoo_fs_settings_url' );
        stewoo_fs()->add_filter( 'after_connect_url', 'stewoo_fs_settings_url' );
        stewoo_fs()->add_filter( 'after_pending_connect_url', 'stewoo_fs_settings_url' );
    }

}

stewoo_fs()->add_action( 'after_uninstall', array( 'Stewoo', 'uninstall_cleanup' ) );

if ( !class_exists( 'Stewoo' ) ) {
    /**
     * Super Transactional Emails for WC class.
     */
    class Stewoo
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            define( 'STEWOO_VERSION', '1.2.1' );
            define( 'STEWOO_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
            define( 'STEWOO_MAINFILE', plugin_basename( __FILE__ ) );
            define( 'STEWOO_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
            load_plugin_textdomain( 'stewoo', false, dirname( STEWOO_MAINFILE ) . '/languages' );
            
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                
                if ( version_compare( WOOCOMMERCE_VERSION, '2.3.0', '<' ) ) {
                    add_action( 'admin_notices', array( $this, 'woocommerce_badversion_notice' ) );
                    return;
                }
                
                include STEWOO_PATH . '/inc/class-stewoo-api.php';
                include STEWOO_PATH . '/inc/class-stewoo-products.php';
                include STEWOO_PATH . '/inc/class-stewoo-template.php';
                if ( current_user_can( 'manage_woocommerce' ) ) {
                    require_once STEWOO_PATH . '/inc/class-stewoo-admin.php';
                }
            } else {
                add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
            }
        
        }
        
        /**
         * WooCommerce fallback notice.
         *
         * @return string
         */
        public function woocommerce_missing_notice()
        {
            echo  '<div class="error"><p>' . __( 'Super Transactional Emails for WooCommerce requires WooCommerce to be installed and active.', 'stewoo' ) . '</p></div>' ;
        }
        
        public function woocommerce_badversion_notice()
        {
            echo  '<div class="error"><p>' . __( 'WooCommerce 2.3 is required for Super Emails for WooCommerce to run, so please update to the latest stable version of WooCommerce.', 'stewoo' ) . '</p></div>' ;
        }
        
        public static function uninstall_cleanup()
        {
            global  $wpdb ;
            $options = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'stewoo\\_%'" );
            array_map( 'delete_option', $options );
        }
    
    }
    add_action( 'plugins_loaded', 'stewoo_init', 0 );
    /**
     * init function.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    function stewoo_init()
    {
        new Stewoo();
        return true;
    }

}
