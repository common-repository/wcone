<?php
namespace Wcone;
/**
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


class Products{

	function __construct() {
    
    add_action( 'wp_ajax_woo_get_checkout_data', [ $this, 'get_checkout_data' ] );
    add_action( 'wp_ajax_nopriv_woo_get_checkout_data', [ $this, 'get_checkout_data' ] );

    add_action( 'wp_ajax_woo_order_place', [ $this, 'order_place' ] );
    add_action( 'wp_ajax_nopriv_woo_order_place', [ $this, 'order_place' ] );

    add_action( 'wp_ajax_woo_add_discount', [ $this, 'add_discount' ] );
    add_action( 'wp_ajax_nopriv_woo_add_discount', [ $this, 'add_discount' ] );

    add_action( 'wp_ajax_woo_get_cart_count', [ $this, 'getCartCount' ] );
    add_action( 'wp_ajax_nopriv_woo_get_cart_count', [ $this, 'getCartCount' ] );

    add_action( 'wp_ajax_woo_mini_cart_qty_update', [ $this, 'miniCartQtyUpdate' ] );
    add_action( 'wp_ajax_nopriv_woo_mini_cart_qty_update', [ $this, 'miniCartQtyUpdate' ] );

	}

  public function getCartCount() {
    echo WC()->cart->get_cart_contents_count();
    exit;
  }

  public function add_discount() {

    $code = '';

    if( isset( $_POST['coupon_code'] ) ) {
      $code = sanitize_text_field( $_POST['coupon_code'] );
    }
    
    $ret = WC()->cart->add_discount( $code );

    exit;

  }
  public function miniCartQtyUpdate() {

    $items = WC()->cart->get_cart();

    $itemQty = isset( $_POST['item_qty'] ) ? sanitize_text_field( $_POST['item_qty'] ) : '';
    $getItemId = isset( $_POST['item_id'] ) ? sanitize_text_field( $_POST['item_id'] ) : '';

    foreach ( $items as $cart_item_key => $item ) {

      $itemId = $item['data']->get_id();

      if( $itemId == $getItemId ) {
        WC()->cart->set_quantity( $cart_item_key, $itemQty );
      }

    }

    WC()->cart->calculate_totals();    

    die();

  }


}

// Products Class init
new Products();