<?php 
namespace Wcone\Inc;
/**
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Text {

  public static function getText() {
    return self::definedText();
  }

  public static function definedText() {

    $getText = [
      'view_cart'           => esc_html__( 'View Cart', 'wcone' ),
      'buy_more'            => esc_html__( 'Buy More', 'wcone' ),
      'cart_added_error'    => esc_html__( 'Product don\'t added in the cart. please try again.', 'wcone' ),
      'review_success_msg'  => esc_html__( 'Review has been submitted successfully.', 'wcone' ),
      'review_error_msg'    => esc_html__( 'Review submission Failed. Please try again.', 'wcone' ),
      'show_more'           => esc_html__( 'Show More', 'wcone' ),
      'show_less'           => esc_html__( 'Less', 'wcone' ),
      'loading'             => esc_html__( 'Loading', 'wcone' ),
      'new_order_placed'    => esc_html__( 'New Order Placed', 'wcone' ),
      'start_order'         => esc_html__( 'Start Order', 'wcone' ),
      'delivery_available_success' => esc_html__( 'Delivery is available', 'wcone' ),
      'delivery_available_error'   => esc_html__( 'Sorry, We are not available to delivery in your area', 'wcone' ),
      'dp_date_text'       => esc_html__( 'Deliver/Pickup Date', 'wcone' ),
      'dp_time_text'       => esc_html__( 'Deliver/Pickup Time', 'wcone' ),
      'dp_today_text'      => esc_html__( 'Today Delivery/Pickup', 'wcone' ),
      'dp_schedule_text'   => esc_html__( 'Schedule Delivery/Pickup', 'wcone' ),
      'boy_assigned_success'   => esc_html__( 'Delivery boy assigned success', 'wcone' ),
      'boy_assigned_failed'   => esc_html__( 'Delivery boy assigned failed', 'wcone' ),
      'Order_transfer_success'   => esc_html__( 'Order transfer success', 'wcone' ),
      'Order_transfer_failed'    => esc_html__( 'Order transfer failed', 'wcone' ),
      'list_type'   => esc_html__( 'List Type', 'wcone' ),
      'checkbox'    => esc_html__( 'Checkbox', 'wcone' ),
      'radio'       => esc_html__( 'Radio', 'wcone' ),
      'feature_section_title'     => esc_html__( 'Feature Section Title', 'wcone' ),
      'min_required_number'       => esc_html__( 'Feature minimum required number', 'wcone' ),
      'max_required_number'       => esc_html__( 'Feature max required number', 'wcone' ),
      'frature_title'             => esc_html__( 'Frature Title', 'wcone' ),
      'price'                     => esc_html__( 'Price', 'wcone' ),
      'add_group'                 => esc_html__( 'Add Group', 'wcone' ),
      'remove_group'              => esc_html__( 'Remove Group', 'wcone' ),
      'add'                       => esc_html__( 'Add', 'wcone' ),
      'add_features'              => esc_html__( 'Add Features', 'wcone' ),
      'remove'                    => esc_html__( 'Remove', 'wcone' ),
      'slot_full_text'            => esc_html__( 'This time slot is full. Try another time slot', 'wcone' ),
      'valid_slot_not_available'  => esc_html__( 'Your selected time slot is not available for order.', 'wcone' ),
      'valid_break_time'          => esc_html__( 'This is break time. Not available for order.', 'wcone' ),
      'valid_delivery_time_field' => esc_html__( 'Deliver/Pickup Time is a required field.', 'wcone' ),
      'valid_delivery_type_field' => esc_html__( 'Deliver/Pickup type is a required field.', 'wcone' ),
      'valid_branch_field'        => esc_html__( 'Deliver/Pickup Branch Name is a required field.', 'wcone' ),
      'set_flash_sale'           => esc_html__( 'Set Meta', 'wcone' ),
      'nutrition_title'           => esc_html__( 'Nutrition Title', 'wcone' ),
      'quantity'                  => esc_html__( 'Quantity', 'wcone' ),
      'branch_select_msg'         => esc_html__( 'Please select the branch', 'wcone' ),
      'table_number_label'        => esc_html__( 'Select Table Number', 'wcone' ),
      'addcart_ranch_select_alert_msg' => esc_html__( 'Please Select the branch before add to cart', 'wcone' ),
      'closing_time_msg'   => wcone_getOptionData( 'closing-time-msg', esc_html__( 'This is closing time. So you can\'t order.', 'wcone' ) )
    ];

    return apply_filters( 'wcone_define_text', $getText );

  }


}


