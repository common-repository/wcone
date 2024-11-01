<?php
/**
 * Enqueue scripts
 * @return 
 * 
 */

add_action( 'wp_enqueue_scripts', 'wcone_enqueue_scripts' );
function wcone_enqueue_scripts() {

    $getText = \Wcone\Inc\Text::getText();
    $options = get_option('wcone_options');
    $getDateFormat = get_option('date_format');

    $delivery_page        = !empty( $options['delivery'] ) ? $options['delivery'] : 'delivery';
    $checkoutDeliveryOption = !empty( $options['checkout-delivery-option'] ) ? $options['checkout-delivery-option'] : 'no';
    $checkoutDeliveryTimeSwitch = !empty( $options['pickup-time-switch'] ) ? $options['pickup-time-switch'] : 'no';
    $deliveryOptions      = !empty( $options['delivery-options'] ) ? $options['delivery-options'] : 'all';
    $modalCloseBtn        = !empty( $options['modal-close-btn-show'] ) ? $options['modal-close-btn-show'] : '';
    $cartModalStyle       = !empty( $options['cart-modal-style'] ) ? $options['cart-modal-style'] : 'canvas';
    
    // Is custom admin pages
    $is_page = false;

    //  Style enqueue
    wp_enqueue_style( 'fb-font-awesome', WCONE_DIR_URL.'assets/css/font-awesome.min.css', array(), '4.7.0', 'all' );
    wp_enqueue_style( 'datatables', WCONE_DIR_URL.'assets/css/datatables.css', array(), '1.10.18', 'all' );
    wp_enqueue_style( 'fbMyAccount', WCONE_DIR_URL.'assets/css/fbMyAccount.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'wcone', WCONE_DIR_URL.'assets/css/app.css', array(), '1.0.0', 'all' );
    // Scripts
    add_filter( 'woocommerce_is_checkout', 'wcone_is_checkout' );
    wp_enqueue_script( 'datatables', WCONE_DIR_URL.'assets/js/datatables.js', array('jquery' ), '1.10.18', true );
    wp_enqueue_script( 'print', WCONE_DIR_URL.'assets/js/jQuery.print.js', array('jquery' ), '1.6.0', true );
    wp_enqueue_script( 'wcone', WCONE_DIR_URL.'assets/js/wcone.js', array( 'jquery','wp-util','wc-checkout', 'underscore', 'jquery-ui-datepicker','jquery-effects-core' ), '1.0.0', true );

    wp_localize_script(
        'wcone', 
        'wconeobj',
        array(
            
            "ajaxurl"               => admin_url('admin-ajax.php'),
            'currency'              => get_woocommerce_currency_symbol(), 
            'currency_pos'          => get_option( 'woocommerce_currency_pos' ), 
            'datepicker_format'     => wcone_datepicker_format( esc_html( $getDateFormat ) ),
            'is_page_custom_admin'  => $is_page,
            'is_login'              => is_user_logged_in(),
            'woo_guest_user_allow'  => get_option('woocommerce_enable_guest_checkout'),
            'is_enable_reviews'     => get_option('woocommerce_enable_reviews'),
            'is_rating_verification_required'  => get_option('woocommerce_review_rating_verification_required'),
            'cart_url'              => wc_get_checkout_url(),
            'get_text'              => $getText,
            'view_cart_btn_text'    => esc_html( $getText['view_cart'] ), 
            'buy_more_btn_text'     => esc_html( $getText['buy_more'] ),
            'dont_cart_msg'         => esc_html( $getText['cart_added_error'] ),
            'is_checkout'           => is_checkout(),
            'wc_decimal_separator'      => wc_get_price_decimal_separator(),
            'wc_thousand_separator'     => wc_get_price_thousand_separator(),
            'price_decimals'            => wc_get_price_decimals(),
            'is_checkout_delivery_option'    => $checkoutDeliveryOption,
            'is_active_modal_close_btn'      => $modalCloseBtn,
            'delivery_options'               => $deliveryOptions,
            'is_multideliveryfees'           => get_option( 'wcone_multideliveryfees_option' ),
            'is_checkout_delivery_time_switch' => $checkoutDeliveryTimeSwitch,
            'cartModalStyle'                   => $cartModalStyle

        ) 
    );


    /**
     * Inline css for custom style
     *  
     */
    
    $mainColor = !empty( $options['main-color'] ) ? esc_html( $options['main-color'] ) : '';

    // Global Button
    $gobBtnBgColor        = !empty( $options['gob-btn-bg-color'] ) ? esc_html( $options['gob-btn-bg-color'] ) : '';
    $gobBtnColor          = !empty( $options['gob-btn-color'] ) ? esc_html( $options['gob-btn-color'] ) : '';
    $gobBtnHoverBgColor   = !empty( $options['gob-btn-hover-bg-color'] ) ? esc_html( $options['gob-btn-hover-bg-color'] ) : '';
    $gobBtnHoverColor     = !empty( $options['gob-btn-hover-color'] ) ? esc_html( $options['gob-btn-hover-color'] ) : '';

    $cartBtnBg         = !empty( $options['cart-btn-bg'] ) ? esc_html( $options['cart-btn-bg'] ) : '';
    $cartBtnCountBg    = !empty( $options['cart-btn-count-bg'] ) ? esc_html( $options['cart-btn-count-bg'] ) : '';
    $cartBtnCountColor = !empty( $options['cart-btn-count-color'] ) ? esc_html( $options['cart-btn-count-color'] ) : '';

    $custom_css = "
            .rb_category_list .rb_category_item .rb_category_quantity:before,
            .rb_custom_checkbox label .rb_custom_checkmark:after,
            .rb_pagination_list .rb_pagination_list_item.active, 
            .rb_pagination_list .rb_pagination_list_item:hover,
            .rb_single_product_item .rb_product_top .rb_badge {
                background-color: {$mainColor};
            }
            .rb_category_list .rb_category_item .rb_category_quantity,
            .rb_pagination_list .rb_pagination_list_item,
            .rb_custom_checkbox label input:checked~.rb_input_text, 
            .rb_custom_checkbox label input:checked~.rb_label_title .rb_input_text {
                color: {$mainColor};
            }
            .rb_custom_checkbox label input:checked~.rb_custom_checkmark,
            .rb_pagination_list .rb_pagination_list_item {
                border-color: {$mainColor};
            }
            .rb_btn_fill:not(.toggle),
            .rb_checkout_steps_content .woocommerce-checkout-review-order #place_order {
                background-color: {$gobBtnBgColor};
                color: {$gobBtnColor};
            }
            .rb_btn_fill:not(.toggle):active, 
            .rb_btn_fill:not(.toggle):focus, 
            .rb_btn_fill:not(.toggle):hover,
            .rb_checkout_steps_content .woocommerce-checkout-review-order #place_order:hover {
                background-color: {$gobBtnHoverBgColor};
                color: {$gobBtnHoverColor};
            }
            .rb_cart_count_btn.rb_floating_cart_btn {
                background-color: {$cartBtnBg}
            }
            .rb_cart_count_btn.rb_floating_cart_btn .rb_cart_count {
                background-color: {$cartBtnCountBg};
                color: {$cartBtnCountColor}
            }
            
            ";

    //
    wp_enqueue_style(
        'custom-style',
        WCONE_DIR_URL.'assets/css/custom.css'
    );
    wp_add_inline_style( 'custom-style', $custom_css );


}

function wcone_is_checkout() {
    return true;
}