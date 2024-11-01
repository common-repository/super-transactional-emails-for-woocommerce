<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Stewoo_Products
{
    private  $order_id = 0 ;
    private  $promoted_products_data = array() ;
    private  $cart_products_data = array() ;
    private  $email_id ;
    private  $email_class ;
    public function __construct()
    {
        add_action(
            'woocommerce_email_header',
            array( $this, 'get_notification_email_type' ),
            10,
            2
        );
        add_action( 'woocommerce_email_order_meta', array( $this, 'get_order_id' ), 10 );
        // Test for custom template files
        // add_filter( 'wc_get_template', array($this, 'filter_wc_template'), 10, 3);
        // END Test for custom template files
    }
    
    // Test for custom template files
    public function filter_wc_template( $full_template_path, $short_template_path, $args )
    {
        if ( $short_template_path == 'emails/email-header.php' ) {
            // error_log( print_r( 'filter_wc_template', true ) );
            // error_log( print_r( $full_template_path, true ) );
            // error_log( print_r( $short_template_path, true ) );
            // error_log( print_r( $args, true ) );
            // return STEWOO_PATH . '/templates/emails/email-header.php';
        }
        return $full_template_path;
    }
    
    // END Test for custom template files
    public function get_notification_email_type( $email_heading, $email = false )
    {
        if ( !$email ) {
            return;
        }
        $this->email_id = $email->id;
        $this->email_class = get_class( $email );
    }
    
    public function get_order_id( $order )
    {
        $this->order_id = $order->get_id();
    }
    
    public function embed_images( $email )
    {
        foreach ( $this->promoted_products_info as $key => $value ) {
            
            if ( $value['image_id'] ) {
                $images_sizes = array(
                    '2_1' => 242,
                    '3_1' => 158,
                    '4_1' => 116,
                    '4_2' => 242,
                    '6_2' => 158,
                    '8_2' => 116,
                );
                $size = $images_sizes[get_option( 'stewoo_products_quantity', '4_1' )];
                $image_full = wp_get_attachment_image_src( $value['image_id'], 'full' );
                $image_resized = image_get_intermediate_size( $value['image_id'], array( $size, $size ) );
                $full_name = wp_basename( $image_full[0] );
                $resized_name = $image_resized['file'];
                $resized_path = str_replace( $full_name, $resized_name, get_attached_file( $value['image_id'] ) );
                $email->AddEmbeddedImage( $resized_path, $value['image_unique_id'], get_the_title( $value['image_id'] ) );
            }
        
        }
    }
    
    public function get_promoted_products( $product )
    {
        $this->new_data = array();
        $this->current_product = $product;
        $wc_se_selection_orders = explode( ',', get_option( 'stewoo_products_selection_order', 'specific_products,cross_sells_products,related_products,up_sells_products,random_products' ) );
        foreach ( $wc_se_selection_orders as $wc_se_selection_order ) {
            call_user_func( array( $this, $wc_se_selection_order . '_getter' ) );
            $this->clean_data();
        }
        $max_products = get_option( 'stewoo_products_quantity', '4_1' );
        $max_products = $max_products[0];
        $this->promoted_products_data = array_chunk( $this->promoted_products_data, $max_products, true );
        $this->promoted_products_data = $this->promoted_products_data[0];
    }
    
    public function specific_products_getter()
    {
        $this->max_data = 100;
        $specific_products = get_option( 'stewoo_specific_products_id' );
        if ( !empty($specific_products) ) {
            $this->new_data = explode( ',', $specific_products );
        }
    }
    
    public function up_sells_products_getter()
    {
        $this->max_data = get_option( 'stewoo_up_sells_products_max', 8 );
        $this->new_data = $this->current_product->get_upsell_ids();
    }
    
    public function cross_sells_products_getter()
    {
        $this->max_data = get_option( 'stewoo_cross_sells_products_max', 8 );
        $this->new_data = $this->current_product->get_cross_sell_ids();
    }
    
    public function related_products_getter()
    {
        $this->max_data = get_option( 'stewoo_related_products_max', 8 );
        $this->new_data = wc_get_related_products( $this->current_product->get_id(), $this->max_data );
    }
    
    public function random_products_getter()
    {
        $this->max_data = get_option( 'stewoo_random_products_max', 8 );
        $args = array(
            'posts_per_page' => get_option( 'stewoo_random_products_max', 8 ),
            'orderby'        => 'rand',
            'post_type'      => 'product',
            'fields'         => 'ids',
            'id__not_in'     => $this->promoted_products_data,
        );
        $random_products = get_posts( $args );
        $this->new_data = $random_products;
    }
    
    public function clean_data()
    {
        
        if ( isset( $this->new_data[0] ) and $this->new_data[0] ) {
            // get only new products
            $this->new_data = array_diff( $this->new_data, $this->promoted_products_data, $this->cart_products_data );
            
            if ( count( $this->new_data ) ) {
                // limit new products size
                $this->new_data = array_chunk( $this->new_data, $this->max_data, true );
                $this->new_data = $this->new_data[0];
                // concate products
                $this->promoted_products_data = array_merge( $this->promoted_products_data, $this->new_data );
            }
        
        }
    
    }

}
new Stewoo_Products();