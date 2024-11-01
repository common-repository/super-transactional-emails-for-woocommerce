<?php

if (!defined('ABSPATH')) {
    exit;
}

class Stewoo_Admin
{
    private $customizer_url;

    public function __construct()
    {
        $this->set_customizer_url();

        add_filter('plugin_action_links_'.STEWOO_MAINFILE, array($this, 'plugin_settings_link'));

        add_action('admin_menu', array($this, 'add_admin_menu'));

        return true;
    }

    public function add_admin_menu()
    {
        global $submenu;

        if ( isset( $submenu['woocommerce'] ) ) {
            add_submenu_page(
                'woocommerce',
                __('Super Transactional Emails for WooCommerce', 'stewoo'),
                __('Super Emails', 'stewoo'),
                'edit_posts',
                'stewoo',
                array($this, 'set_up_admin_page')
            );
        }
        add_action( 'admin_init', array( $this, 'stewoo_register_setting' ) );

        add_action( 'admin_init', array( $this, 'do_export_import' ) );

        $stewoo_presentation_page = add_submenu_page(
            'options-general.php',
            __('Super Transactional Emails for WooCommerce', 'stewoo'),
            __('Super Emails', 'stewoo'),
            'edit_posts',
            'stewoo_presentation',
            array($this, 'set_up_stewoo_presentation_page')
        );
        add_action( 'load-' . $stewoo_presentation_page, array( $this, 'load_admin_assets' ) );
    }

    public function load_admin_assets(){
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function enqueue_admin_assets(){
        wp_enqueue_style( 'stewoo-customizer-style', STEWOO_PLUGIN_URL . '/assets/css/admin.css', array(), STEWOO_VERSION, 'all' );
    }

    public function stewoo_register_setting()
    {
        register_setting('stewoo-settings-group', 'stewoo_embed_images');
        register_setting('stewoo-settings-group', 'stewoo_email_type_selector');

        // Emails settings
        $mailer = WC()->mailer();
        $email_templates = $mailer->get_emails();
        foreach ($email_templates as $email_key => $email) {
            if ($email->is_customer_email()) {
                register_setting('stewoo-settings-group', 'stewoo_embed_'.$email->id);
            }
        }
    }


    public function do_export_import()
    {
        if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'stewoo_save_settings' ) ) {
            if ( current_user_can( 'manage_woocommerce' ) ) {
                if ( wp_verify_nonce( $_GET['_wpnonce'] ) ) {
                    $settings = array(
                        'label' => $_GET['label'],
                        'values' =>serialize( $this->get_options_for_export() )
                    );
                    add_option( 'stewoo_export_' . time(), $settings, '', 'no' );
                    wp_redirect( add_query_arg( array('page' => 'stewoo'), admin_url('admin.php') ) );
                };
            }
            die();
        }
        if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'stewoo_apply_settings' ) ) {
            if ( current_user_can( 'manage_woocommerce' ) ) {
                if ( wp_verify_nonce( $_GET['_wpnonce'] ) ) {
                    $settings = get_option( $_GET['key'] );
                    $values = $settings['values'];
                    $options = unserialize( $values );
                    if ($options) {
                        foreach ($options as $option) {
                            if ( substr( $option->option_name, 0, 7 ) === "stewoo_" ) {
                                update_option($option->option_name, $option->option_value);
                            }
                        }
                    }
                    wp_redirect( add_query_arg( array('page' => 'stewoo'), admin_url('admin.php') ) );
                };
            }
            die();
        }
        
        if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'stewoo_download_settings' ) ) {
            if ( current_user_can( 'manage_woocommerce' ) ) {
                if ( wp_verify_nonce( $_GET['_wpnonce'] ) ) {
                    $option = get_option( $_GET['key'] );
                    $values = $option['values'];
                    header("Cache-Control: public, must-revalidate");
                    header("Pragma: hack");
                    header("Content-Type: text/plain");
                    header('Content-Disposition: attachment; filename="STEWoo-'.date("dMy").'.txt"');
                    echo $values;
                };
            }
            die();
        }
        
        if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'stewoo_delete_settings' ) ) {
            if ( current_user_can( 'manage_woocommerce' ) ) {
                if ( wp_verify_nonce( $_GET['_wpnonce'] ) ) {
                    delete_option( $_GET['key'] );
                    wp_redirect( add_query_arg( array('page' => 'stewoo'), admin_url('admin.php') ) );
                };
            }
            die();
        }
        
        if (isset($_POST['upload']) && check_admin_referer('stewoo_import_settings', 'stewoo_import_settings')) {
			if ($_FILES["settings_to_import"]["error"] > 0) {
				// error
			} else {
				$options = unserialize(file_get_contents($_FILES["settings_to_import"]["tmp_name"]));
				if ($options) {
					foreach ($options as $option) {
                        if ( substr( $option->option_name, 0, 7 ) === "stewoo_" ) {
                            update_option($option->option_name, $option->option_value);
                        }
					}
				}
			}
			wp_redirect( add_query_arg( array('page' => 'stewoo'), admin_url('admin.php') ) );
			exit;
		}
    }

    public function get_options_for_export() {
		global $wpdb;
		return $wpdb->get_results(
            "SELECT option_name, option_value FROM {$wpdb->options} 
            WHERE option_name LIKE 'stewoo\_%' AND 
            option_name NOT LIKE 'stewoo_embed\_%' AND 
            option_name != 'stewoo_email_type_selector' AND 
            option_name != 'stewoo_order_code' AND 
            option_name != 'stewoo_products_quantity' AND 
            option_name != 'stewoo_specific_products_id' AND 
            option_name != 'stewoo_products_selection_order' AND 
            option_name != 'stewoo_products_title' AND 
            option_name != 'stewoo_products_subtitle' AND 
            option_name != 'stewoo_products_button_text' AND 
            option_name NOT LIKE 'stewoo\_export\_%'"
        );
	}

    public function set_up_admin_page()
    {
        require_once STEWOO_PATH.'/inc/stewoo-options-page.php';
    }
    
    public function set_up_stewoo_presentation_page(){
        ?>
        <div id="stewoo_wrap">
            <div class="stewoo_100_hld stewoo_top">
                <h2 class="title"><b>S</b>uper <b>T</b>ransactional <b>E</b>mails for <b>Woo</b>Commerce</h2>
                <h4 class="center upper"><b>STEWoo</b> greatly enhances your WooCommerce emails!</h4>
            </div>
            <?php
            if ( ! stewoo_fs()->can_use_premium_code() ) {
            ?>
            <div class="stewoo_100_hld">
                <div class="stewoo_w1_hld">
                    <p>
                        <h3>Thank you for using STEWoo</h3>
                        <h4>To start customizing your emails:</h4>
                        <ul>
                            <li>Click on the 'Super Emails' link under the WooCommerce item of the left menu or here: <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo',
                    ), admin_url( 'admin.php' ) ); ?>" class="button">Super Emails</a></li>
                            <li>Select the email type to preview</li>
                            <li>Click on the 'Customize Emails' button to use the WordPress Customizer</li>
                        </ul>
                    </p>
                    <h4>If you have any questions: <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo-contact',
                    ), admin_url( 'options-general.php' ) ); ?>" class="button">Contact Us</a></h4>
                    <h4 class="upper">Find more information, tutorials and free templates on <a href="https://stewoo.com" target="_blank">stewoo.com</a></h4>
                </div>
                <div class="stewoo_w2_hld">
                    <div class="video_hld">
                        <iframe src="https://stewoo.com/videos/stewoo_plugin_video_1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="video"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="stewoo_100_hld">
                <h2><b>Take it to the next level with STEWoo</b> PRO</h2>
                <h3 class="center">Promote your products within Transactional Emails and improve yours sales</h3>
                <p style="color: black;font-size: 16px;">
                    Transactional emails have high open and click rates.<br>
                    This rate can be more than 100% because the same email can be opened several times by its recipient.<br>
                    You don't want to miss this opportunity to boost orders.
                </p>
            </div>
            <div class="stewoo_100_hld">
                <div class="stewoo_w1_hld">
                    <h4>The PRO version of STEWoo adds the following features :</h4>
                    <p>
                        <ul>
                            <li>Promote your products within your transactional emails</li>
                            <li>Change the default email texts</li>
                            <li>Include your own custom HTML code</li>
                            <li>Add custom fonts</li>
                            <li>Add responsive rules</li>
                        </ul>
                    </p>
                    <p class="center">
                        You are just one click away to try it<br>
                    <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo-pricing',
                        'trial' => 'true',
                    ), admin_url( 'options-general.php' ) ); ?>" class="button button-primary stewoo_trial_button">Start your free trial</a><br>no credit card needed
                    </p>
                </div>
                <div class="stewoo_w2_hld">
                    <div class="video_hld">
                        <iframe src="https://stewoo.com/videos/stewoo_plugin_video_2" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="video"></iframe>
                    </div>
                </div>
            </div>
            <?php
            }else{
            ?>
            <div class="stewoo_100_hld">
                <div class="stewoo_w1_hld">
                    <p>
                        <h3>Thank you for using STEWoo Pro</h3>
                        <h4>To start customizing your emails:</h4>
                        <ul>
                            <li>Click on the 'Super Emails' link under the WooCommerce item of the left menu or here: <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo',
                    ), admin_url( 'admin.php' ) ); ?>" class="button">Super Emails</a></li>
                            <li>Select the email type to preview</li>
                            <li>Click on the 'Customize Emails' button to use the WordPress Customizer</li>
                        </ul>
                    </p>
                    <h4>If you have any questions: <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo-contact',
                    ), admin_url( 'options-general.php' ) ); ?>" class="button">Contact Us</a></h4>
                    <h4 class="upper">Find more information, tutorials and free templates on <a href="https://stewoo.com" target="_blank">stewoo.com</a></h4>
                </div>
                <div class="stewoo_w2_hld">
                    <div class="video_hld">
                        <iframe src="https://stewoo.com/videos/stewoo_plugin_video_2" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="video"></iframe>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <a href="<?php echo add_query_arg( array(
                        'page' => 'stewoo-affiliation',
                        'trial' => 'true',
                    ), admin_url( 'options-general.php' ) ); ?>"  class="center affiliation_display">Like STEWoo Pro? Become our ambassador and earn cash</a>
        </div>
        <?php
    }
    private function set_customizer_url()
    {
        $preview_url = wp_nonce_url(site_url('/'), 'stewoo_nonce_check', 'stewoo_nonce');
        $preview_url = add_query_arg('stewoo-preview', 1, $preview_url);
        $preview_url = add_query_arg('email_type', get_option('stewoo_email_type_selector', 'WC_Email_Customer_Processing_Order'), $preview_url);

        $url = admin_url('customize.php');
        $url = add_query_arg('stewoo-preview', 1, $url);
        $url = add_query_arg('url', urlencode($preview_url), $url);
        $url = add_query_arg('return', urlencode(add_query_arg(array('page' => 'stewoo'), admin_url('admin.php'))), $url);
        $url = add_query_arg('email_type', get_option('stewoo_email_type_selector', 'WC_Email_Customer_Processing_Order'), $url);

        $this->customizer_url = esc_url_raw($url);

        return true;
    }

    public function plugin_settings_link($links)
    {
        $settings_url = add_query_arg( array('page' => 'stewoo'), admin_url('admin.php') );
        $settings_link = sprintf( '<a href="%s">Settings</a>', $settings_url );
        array_unshift( $links, $settings_link );

        return $links;
    }
}

new Stewoo_Admin();
