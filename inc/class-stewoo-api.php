<?php

if (!defined('ABSPATH')) {
    exit;
}

class Stewoo_Api
{
    private $stewoo_trigger;

    public function __construct()
    {
        $this->stewoo_trigger = 'stewoo-preview';

        // add customizer settings
		add_filter( 'customize_register', array( $this, 'customizer_settings' ) );

        if ( isset( $_GET[ $this->stewoo_trigger ] ) ) {

			// add_filter( 'customize_register', array( $this, 'remove_sections' ), 60 );
			// add_filter( 'customize_register', array( $this, 'remove_panels' ), 60 );
			add_filter( 'customize_register', array( $this, 'customizer_sections' ), 40 );
			add_filter( 'customize_register', array( $this, 'customizer_controls' ), 50 );

            add_filter( 'customize_section_active', array( $this, 'select_customizer_sections' ), 10, 2 );
            add_filter( 'customize_panel_active', array( $this, 'select_customizer_panels' ), 10, 2 );
			add_filter( 'customize_control_active', array( $this, 'select_customizer_controls' ), 10, 2 );

            add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_script' ) );
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_custom_scripts_for_customizer' ) );

            add_filter( 'woocommerce_screen_ids', array( $this, 'add_screen_id' ) );

            add_action( 'template_redirect', array( $this, 'preview_emails' ) );

            add_filter( 'customize_previewable_devices', array( $this, 'set_previewable_devices' ) );

		}

        add_filter( 'woocommerce_email_styles', array( $this, 'add_styles' ) );

        /*** images on ordered products list ***/
        add_filter( 'woocommerce_email_order_items_table', array( $this, 'add_images_on_list_order_items' ), 10, 2 );


        add_filter( 'query_vars', array( $this, 'add_query_vars' ) );

        add_action( 'wp_ajax_stewoo_send_test_email', array( $this, 'send_test_email' ) );

        return true;
    }


    /**
	 * Add screen id to WooCommerce
	 *
	 * @param array $screen_ids
	 */
	public function add_screen_id( $screen_ids ) {
		$screen_ids[] = 'customize';
		return $screen_ids;
	}

    public function set_previewable_devices( $devices ) {
        // remove viewport changer
        return;
    }

    public function customizer_settings( $wp_customize ) {
		global $wp_customize;

		include( STEWOO_PATH . '/inc/class-stewoo-settings.php' );

		Stewoo_Settings::add_settings( $wp_customize );

		return true;
	}

    public function customizer_controls( $wp_customize ) {
		global $wp_customize;

		include( STEWOO_PATH . '/inc/class-stewoo-controls.php' );

		Stewoo_Controls::add_controls();

		return true;
	}

    public function customizer_sections( $wp_customize ) {
		global $wp_customize;

		include( STEWOO_PATH . '/inc/class-stewoo-sections.php' );

		Stewoo_Sections::add_sections();

		return true;
	}


    public function select_customizer_sections( $active, $section ){
        if ( in_array( $section->id, array( 'stewoo_section1', 'stewoo_section2', 'stewoo_section3', 'stewoo_section4', 'stewoo_section5', 'stewoo_section6', 'stewoo_section7', 'stewoo_section8', 'stewoo_section9') ) ) {
			return true;
		}
        return false;
    }

    public function select_customizer_panels( $active, $panel ){
        return false;
    }

    public function select_customizer_controls( $active, $control ){
        if ( in_array( $control->section, array( 'stewoo_section1', 'stewoo_section2', 'stewoo_section3', 'stewoo_section4', 'stewoo_section5', 'stewoo_section6', 'stewoo_section7', 'stewoo_section8', 'stewoo_section9' ) ) ) {
			return true;
		}
        return false;
    }

    public function preview_emails()
    {
        if (isset($_GET[ $this->stewoo_trigger ])) {
            if ( ! wp_verify_nonce($_GET['stewoo_nonce'], 'stewoo_nonce_check')) {
                die('Security check failed, close the customizer.');
            }

            if ( isset($_POST['customized'] ) and strlen( $_POST['customized'] ) > 2  ) {
                // process data for preview
                $customized_data = json_decode( stripslashes( $_POST['customized'] ), true );

            }

            global $wp_customize;

            $order_id = get_option( 'stewoo_order_code', $this->get_last_valid_order() );

            if ($order_id) {
// wp_head();
wp_footer();
                $email = WC()->mailer();
                if ( isset( $_GET[ 'email_type' ] ) && ( $_GET[ 'email_type' ] != '' ) ) {
                    $email_subject = $_GET[ 'email_type' ];
                } else {
                    $email_subject = 'WC_Email_Customer_Processing_Order';
                }
                $email->emails[$email_subject]->object = wc_get_order($order_id);
                $email_ouput =  $email->emails[$email_subject]->style_inline($email->emails[$email_subject]->get_content_html());

                $email_ouput = apply_filters( 'woocommerce_mail_content', $email_ouput );

                echo $email_ouput;

                exit;

            } else {
                echo __('You must have at least one valid order in WooCommerce to preview emails.', 'stewoo');
            }
            exit;
        }
    }

    public function get_last_valid_order()
    {
        $args = array(
            'numberposts' => 1,
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        );

        $orders = get_posts($args);

        if (!empty($orders)) {
            return  $orders[0]->ID;
        }

        return false;
    }

    public function add_images_on_list_order_items( $ob_get_clean, $order ) {

        ob_start();
        $show_image = get_option( 'stewoo_display_order_items_images', false );
        if ( is_customize_preview() ) {         
            if ( isset( $_POST['customized'] ) ) {
                $customized = json_decode( wp_unslash( $_POST['customized'] ), true );
                if ( isset($customized['stewoo_display_order_items_images'])) {
                    $show_image = $customized['stewoo_display_order_items_images'];
                }
            }
        }
        $defaults = array(
                'show_sku'   => false,
                'show_image' => $show_image,
                'image_size' => array( 100, 100 ),
                'plain_text' => false
            );

            $args     =  apply_filters( 'stewoo_filter_order_items_args', $defaults );
            $template = $args['plain_text'] ? 'emails/plain/email-order-items.php' : 'emails/email-order-items.php';

            wc_get_template( $template, array(
                'order'               => $order,
                'items'               => $order->get_items(),
                'show_download_links' => $order->is_download_permitted(),
                'show_sku'            => $args['show_sku'],
                'show_purchase_note'  => $order->is_paid(),
                'show_image'          => $args['show_image'],
                'image_size'          => $args['image_size'],
                'plain_text'          => false,
            ) );

        return ob_get_clean();
    }

    public function enqueue_customizer_script() {
        wp_enqueue_script( 'stewoo-customizer-live-preview', STEWOO_PLUGIN_URL . '/assets/js/stewoo_customizer.js', array( 'jquery', 'jquery-ui-sortable', 'customize-preview' ), STEWOO_VERSION, true );
        $params = array(
			'display_order_items_images' => get_option( 'stewoo_display_order_items_images', false )
		);
        wp_localize_script( 'stewoo-customizer-live-preview', 'stewoo', $params );
		return true;
    }

    public function enqueue_custom_scripts_for_customizer() {

        // auto-complete products
        $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        // Register scripts
        wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'customize-controls' ), WC_VERSION );

        wp_enqueue_script( 'stewoo-customizer-controls', STEWOO_PLUGIN_URL . '/assets/js/stewoo_customizer_controls_scripts.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'customize-controls' ), STEWOO_VERSION, true );

        // Get email type for test email sending
        $url_to_parse = $_GET['url'];
        parse_str ( $url_to_parse, $url_parameters );
        // $url_parameters = parse_url( $url_to_parse, PHP_URL_QUERY );
        $params = array(
            'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'ajaxSendEmailNonce' => wp_create_nonce( 'stewoo_send_email_nonce' ),
			'error'              => __( 'Sorry, an error occurred.', 'stewoo' ),
			'success'            => __( 'Email Sent.', 'stewoo' ),
			'saveFirstMessage'   => __( 'Please click on save/publish before sending the test email', 'stewoo' ),
            'emailSubject'      => $url_parameters['email_type'],
		);
        wp_localize_script( 'stewoo-customizer-controls', 'stewoo', $params );

        wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );

        wp_enqueue_style( 'stewoo-customizer-style', STEWOO_PLUGIN_URL . '/assets/css/customizer.css', array(), STEWOO_VERSION, 'all' );

		return true;
	}

    public function add_styles( $styles ) {

		include( STEWOO_PATH . '/inc/email-styles.php' );
		return $styles;
	}

    public function add_query_vars( $vars ) {
		$vars[] = $this->stewoo_trigger;
		return $vars;
	}

    public function send_test_email() {
		$nonce = $_POST['ajaxSendEmailNonce'];

		if ( ! wp_verify_nonce( $nonce, 'stewoo_send_email_nonce' ) ) {
			die ( 'error' );
		}

        $email_type = $_POST['email_type'];
        if ( $email_type == '' ) {
            $email_type = 'WC_Email_Customer_Processing_Order';
        }

        $order_id = get_option( 'stewoo_order_code', $this->get_last_valid_order() );
        $email = WC()->mailer();
        $email->emails[$email_type]->object = wc_get_order($order_id);
        $email_ouput =  $email->emails[$email_type]->style_inline($email->emails[$email_type]->get_content_html());

        $email_ouput = apply_filters( 'woocommerce_mail_content', $email_ouput );

        $headers = array();
        $headers[] = 'Content-Type: text/html' . PHP_EOL;

        if ( $_POST['email_to'] != '' ) {
            $mail_to = $_POST['email_to'];
        } else {
            $current_user = wp_get_current_user();
            $mail_to = $current_user->user_email;
        }
        
        if ( wp_mail( $mail_to, 'Test Email :: ' . $email->emails[$email_type]->subject, $email_ouput, $headers ) ) {
			echo 'success';
		} else {
			echo 'error';
		}

		exit;
    }
}

new Stewoo_Api();
