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

class Components_Ajax {

	function __construct() {

    add_action( 'wp_ajax_invitation_mail_action', [$this, 'invitation_mail'] );
    add_action( 'wp_ajax_nopriv_invitation_mail_action', [$this, 'invitation_mail'] );

    add_action( 'wp_ajax_update_order_review_action', [$this, 'update_order_review'] );
    add_action( 'wp_ajax_nopriv_update_order_review_action', [$this, 'update_order_review'] );

    add_action( 'wp_ajax_order_time_lists_action', [$this, 'order_time_lists'] );
    add_action( 'wp_ajax_nopriv_order_time_lists_action', [$this, 'order_time_lists'] );

    add_action( 'wp_ajax_holy_day_check_action', [__CLASS__, 'holy_day_check'] );
    add_action( 'wp_ajax_nopriv_holy_day_check_action', [__CLASS__, 'holy_day_check'] );

    add_action( 'wp_ajax_woo_update_fixed_cart_subtotal', [__CLASS__, 'update_fixed_cart_subtotal'] );
    add_action( 'wp_ajax_nopriv_woo_update_fixed_cart_subtotal', [__CLASS__, 'update_fixed_cart_subtotal'] );


	}

	
  /**
   * 
   * @return [string] [description]
   */
  public function invitation_mail() {

    $getData = get_option( 'wcone_options' );

    $headerMail = !empty( $getData['invitation-from-email'] ) ? $getData['invitation-from-email'] : get_option('admin_email');
    $subject = !empty( $getData['invitation-subject'] ) ? $getData['invitation-subject'] : "";
    $message = !empty( $getData['invitation-message'] ) ? $getData['invitation-message'] : "";
    // Mail header
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <'.$headerMail.'>' . "\r\n";

    if( isset( $_POST['mail'] ) ) {

      $res = mail( sanitize_email( $_POST['mail'] ) , sanitize_text_field( $subject ), sanitize_textarea_field($message), $headers );

      if( $res ) {
        esc_html_e( 'Thanks for sending invitation', 'wcone' );
      }else {
        esc_html_e( 'Invite failed, please try again.', 'wcone' );
      }
      
    } else {
      esc_html_e( 'E-mail id not found.', 'wcone' );
    }

    exit;

  }


/**
 * update_order_review
 * @return
 * 
 */
public function update_order_review() {

  WC()->cart->calculate_shipping();
  WC()->cart->calculate_totals();

  wp_die();
}

/**
 * [order_time_lists description]
 * @return [type] [description]
 */
public function order_time_lists() {

  $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
  $timeList = \Wcone\Date_Time_Map::getTimes( $date );
  wcone_time_solt_options_html( $timeList );
  die();
}


/**
 * [holy_day_check description]
 * @return [type] [description]
 */
public static function holy_day_check() {

  $date = $_POST['date'] ? sanitize_text_field( $_POST['date'] ) : '';

  echo  Date_Time_Map::is_holy_day( $date );
  die();
}

/**
 * [holy_day_check description]
 * @return [type] [description]
 */
public static function update_fixed_cart_subtotal() {
  echo WC()->cart->get_cart_subtotal();
  die();
}



} // End class

// Components_Ajax Class init
new Components_Ajax();
