<?php
namespace Wcone\Admin;
 /**
  * 
  * @package    Wcone 
  * @since      3.0.0
  * @version    3.0.0
  * @author     ThemeLooks
  * @Websites:  http://themelooks.com/
  *
  */


class Delivertimebranch_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Delivery Settings', 'wcone' ),
            'icon'    => 'fa fa-truck',
            'id'      => 'delivertimebranch'
        ]);

        $this->switcher(
          [
            'title' => esc_html__( 'Checkout Delivery Option Show/Hide', 'wcone' ),
            'name'  => 'checkout-delivery-option'
          ]
        );
        $this->selectbox(
          [
            'title'     => esc_html__( 'Set Delivery Options', 'wcone' ),
            'name'      => 'delivery-options',
            'options'   => [
              'all'   => esc_html__( 'Delivery/Pickup Both', 'wcone' ),
              'delivery' => esc_html__( 'Only Delivery', 'wcone' ),
              'pickup'   => esc_html__( 'Only Pickup', 'wcone' )
            ]
          ]
        );        
        $this->text(
          [
            'title' => esc_html__( 'Set Delivery Fee', 'wcone' ),
            'name'  => 'delivery-fee',
            'is_pro' => true
          ]
        );
        $this->number(
          [
            'title' => esc_html__( 'Minimum order amount', 'wcone' ),
            'name'  => 'minimum-order-amount'
          ]
        );
        $this->number(
          [
            'title' => esc_html__( 'Free shipping require minimum order amount', 'wcone' ),
            'name'  => 'free-shipping-amount'
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Deliver/Pickup Time and Date Show/Hide', 'wcone' ),
            'name'  => 'pickup-time-switch'
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Off current date order', 'wcone' ),
            'name'  => 'off-current-order',
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Pre order active', 'wcone' ),
            'name'  => 'pre-order-active',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Deliver/Pickup Time Option Note', 'wcone' ),
            'name'  => 'delivery-time-note'
          ]
        );
        // Check multibranch
        if( !wcone_is_multi_branch() ) {
          $this->day_based_time(
            [
              'title' => esc_html__( 'Delivery Time and Day ', 'wcone' ),
              'class' => 'delivery-time-day',
              'name'  => 'delivery-time-day'
            ]
          );
        }
        $this->selectbox(
          [
            'title'     => esc_html__( 'Delivery Time Format', 'wcone' ),
            'name'      => 'delivery-time-format',
            'options'   => [
              '12'    => esc_html__( '12 Hour', 'wcone' ), 
              '24'    => esc_html__( '24 Hour', 'wcone' )
            ]
          ]
        );
        $this->selectbox(
          [
            'title'     => esc_html__( 'Delivery Time Slot', 'wcone' ),
            'name'      => 'delivery-time-slot',
            'options'   => [
              '2,30'    => esc_html__( '30min', 'wcone' ), 
              '1'    => esc_html__( '60min ( Pro )', 'wcone' ),
              '2'   => esc_html__( '120min ( Pro )', 'wcone' ),
              '3'   => esc_html__( '180min ( Pro )', 'wcone' )
            ]
          ]
        );
        $this->number(
          [
            'title' => esc_html__( 'Order Limit On Time Slot', 'wcone' ),
            'name'  => 'order-limit-time-slot',
            'is_pro'    => true
          ]
        );
        $this->number(
          [
            'title' => esc_html__( 'Pre Order Days Limit', 'wcone' ),
            'name'  => 'date-days-limit',
            'is_pro'    => true
          ]
        );
        $this->timezone_select(
          [
            'title'     => esc_html__( 'Set Time Zone', 'wcone' ),
            'name'      => 'time-zone'
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Order Delivery Directions Map Active', 'wcone' ),
            'name'  => 'delivery-directions-map',
            'is_pro'    => true
          ]
        );
        $this->selectbox(
          [
            'title'     => esc_html__( 'Delivery Directions Map Transport Mode', 'wcone' ),
            'name'      => 'delivery-transport-mode',
            'options'   => [
              'driving'    => esc_html__( 'Driving', 'wcone' ), 
              'walking'    => esc_html__( 'Walking', 'wcone' ),
              'bicycling'  => esc_html__( 'Bicycling', 'wcone' )
            ],
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Closing Time Info Text', 'wcone' ),
            'name'  => 'closing-time-msg'
          ]
        );

        $this->end_fields_section(); // End fields section
   }
}

new Delivertimebranch_Settings_Tab();