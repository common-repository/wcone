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

class Hooks {

  function __construct() {
    
    // Js template hook in footer action hook
    add_action( 'wp_footer', [ __CLASS__, 'rb_js_template'] );
    // Mini Cart
    add_action( 'wp_footer', [ __CLASS__, 'wcone_mini_cart'] );
    add_filter( 'woocommerce_checkout_redirect_empty_cart', [ __CLASS__, 'cart_empty'] );
    add_filter( 'woocommerce_checkout_update_order_review_expired', [ __CLASS__, 'cart_empty'] );

  }

  /**
   *
   * Js template hook in footer
   * 
   * 
   */  
  public static function rb_js_template() {

    include WCONE_DIR_PATH.'view/template-modal-wrapper.php';
    // Template Without Underscore
    include WCONE_DIR_PATH.'view/modal-cart.php';

  }

/**
 * wcone_mini_cart
 * @return html
 */
public static function wcone_mini_cart() {
  //
  global $wconeAttr;

  // Check cart modal style is not canvas
  $miniCartType = !empty( $wconeAttr['mini_cart_type'] ) ? $wconeAttr['mini_cart_type'] : '';
  $is_minicart = false;

  if( 'canvas' == $miniCartType ) {
    $is_minicart = true;
  } else if( 'canvas' == wcone_getOptionData('cart-modal-style') && !$miniCartType ) {
    $is_minicart = true;
  }
  //
  if( !$is_minicart ) {
    return;
  }

  ?>
  <!-- Cart Button -->
  <span class="rb_cart_count_btn rb_floating_cart_btn">
    <?php
    if( !is_admin() ):
    ?>
    <span class="rb_cart_count rb_cart_icon"><?php echo sprintf( esc_html__( '%s Items', 'wcone' ), esc_html( WC()->cart->get_cart_contents_count() ) ); ?></span>
    <?php
    endif;
    ?>
    <span class="rb_cart_icon">
      <?php 
      if( !empty( $options['cart-btn-icon'] ) ) {
        echo '<img src="'.esc_url( $options['cart-btn-icon'] ).'" class="rb_svg" alt="'.esc_attr( 'cart count', 'wcone' ).'" />';
      } else {
        $icon = WCONE_DIR_URL.'assets/img/icon/cart-btn-icon.svg';
        echo '<img src="'.esc_url( $icon ).'" class="rb_svg" alt="'.esc_attr( 'cart count', 'wcone' ).'" />';
      }
      ?>
    </span>
  </span>
  <!-- End Cart Button -->
  <?php
}

public static function cart_empty() {
  return false;
}


}

// Hooks class init
new Hooks();