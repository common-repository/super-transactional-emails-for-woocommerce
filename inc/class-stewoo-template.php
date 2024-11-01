<?php

if (!defined('ABSPATH')) {
    exit;
}

class Stewoo_Template
{
    // const SPACER = PHP_EOL;
    const SPACER = '';

    public static function get_tag($txt, $class)
    {
        return sprintf('<div class="stewoo_product_%2$s">%1$s</div>', $txt, $class);
    }
    public static function open_cell($index, $link)
    {
        // return sprintf(self::SPACER.'<div class="cell_%1$s"><a href="%2$s" target="_blank" class="stewoo_product_link">'.self::SPACER, $index, get_permalink($link));
        return sprintf(self::SPACER.'<td class="cell_%1$s"><a href="%2$s" target="_blank" class="stewoo_product_link">'.self::SPACER, $index, get_permalink($link));
    }
    public static function close_cell()
    {
        // return '</a></div>'.self::SPACER;
        return '</a></td>'.self::SPACER;
    }
    public static function get_image($id, $size, $image_id, $image_unique_id, $image_embedded)
    {
        if ( get_the_post_thumbnail( $id, array($size, $size) ) ) {
            if ( $image_embedded ) {
                return '<img src="cid:'.$image_unique_id.'" '.image_hwstring($size, $size).'/>';
            } else {
                return get_the_post_thumbnail($id, array($size, $size));
            }
        } else {
            return "<div style='width:${size}px;height:${size}px;display:inline-block'></div>";
        }
    }

    public static function check_new_table_line($index, $lattice)
    {
        $new_line = '</tr><tr>';

        if ( intval( $lattice[2] ) === 2) {
            $split_index = intval( $lattice[0] ) / 2;
            if ( $split_index === $index) {
                return $new_line;
            }
        }
    }

    public static function get_template($params, $email_id)
    {
        $stewoo_show_products_title = get_option( 'stewoo_products_display_title', 'yes' );
        $stewoo_show_products_intro = get_option( 'stewoo_products_display_subtitle', 'yes' );
        $stewoo_products_subtitle_position = get_option( 'stewoo_products_subtitle_position' );
        $stewoo_show_product_name = get_option( 'stewoo_products_display_name', 'yes' );
        $stewoo_show_product_price = get_option( 'stewoo_products_display_price', 'yes' );
        $stewoo_show_product_description = get_option( 'stewoo_products_display_description', 'yes' );
        $stewoo_show_product_button = get_option( 'stewoo_products_display_button', 'yes' );

        $images_sizes = apply_filters( 'stewoo_filter_promotional_products_images_sizes', array(
            '2_1'  => 242,
            '3_1'  => 158,
            '4_1'  => 116,
            '4_2'  => 242,
            '6_2'  => 158,
            '8_2'  => 116
        ) );

        $image_embedded = get_option( 'stewoo_embed_images', false );
        if ( isset( $_GET['stewoo-preview'] ) ) {
            $image_embedded = false;
        }

        $products_quantity = $params['lattice'][0];

        $output = '<div class="stewoo_global_container mail_type_' . $email_id . '">';
        $output .= '<!-- Email enhanced with stewoo.com -->';
        if ( $stewoo_show_products_title == 'yes' ) {
            $output .= self::get_tag($params['t1_text'], 'txt1').self::SPACER;
        }
        if ( ( $stewoo_show_products_intro == 'yes' ) and ( $stewoo_products_subtitle_position != 'after' ) ) {
            $output .= self::get_tag($params['t2_text'], 'txt2').self::SPACER;
        }

        $output .= '<table class="stewoo_products_container lattice'.$params['lattice'].'"></tr>';

        $index = 0;

        foreach ($params['products'] as $key => $value) {
            $index++;

            $output .= self::open_cell($index, $value['id']);

            $output .= '<div class="stewoo_product_image_container">';
            $output .= self::get_image($value['id'], $images_sizes[$params['lattice']], $value['image_id'], $value['image_unique_id'], $image_embedded);
            $output .= '</div>';
            if ( $stewoo_show_product_name == 'yes' ) {
                $output .= self::get_tag($value['title'], 'title');
            }
            if ( $stewoo_show_product_price == 'yes' ) {
                $output .= self::get_tag($value['price'], 'price');
            }
            if ( $stewoo_show_product_description == 'yes' ) {
                $output .= self::get_tag($value['description'], 'description');
            }
            if ( $stewoo_show_product_button == 'yes' ) {
                $output .= self::get_tag( get_option( 'stewoo_products_button_text', __('view more', 'stewoo') ), 'button' );
            }
            $output .= self::close_cell();
            $output .= self::check_new_table_line($index, $params['lattice']);
        }

        // $output .= '</div>'; // end stewoo_products_container
        $output .= '</tr></table>'; // end stewoo_products_container
        if ( ( $stewoo_show_products_intro == 'yes' ) and ( $stewoo_products_subtitle_position == 'after' ) ) {
            $output .= self::get_tag($params['t2_text'], 'txt2').self::SPACER;
        }
        $output .= '</div>'; // end stewoo_global_container
        $output .= '<style type="text/css">' . get_option( 'stewoo_custom_css_inline' ) .'</style>';

        return $output;
    }
}
