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


class StatusText_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Status Text', 'wcone' ),
            'icon'    => 'fa fa-pen',
            'id'      => 'statustext'
        ]);
        $this->text(
          [
            'title' => esc_html__( 'Order Cancel Text', 'wcone' ),
            'name'  => 'order-cancel-text',
            'placeholder' => esc_html__( 'Order Cancel', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Order Failed Text', 'wcone' ),
            'name'  => 'order-failed-text',
            'placeholder' => esc_html__( 'Order Failed', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'New Order Text', 'wcone' ),
            'name'  => 'new-order-text',
            'placeholder' => esc_html__( 'New Order', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Send To Packaging Text', 'wcone' ),
            'name'  => 'send-to-packaging-text',
            'placeholder' => esc_html__( 'Send To Packaging', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Packaging Processing Text', 'wcone' ),
            'name'  => 'packaging-processing-text',
            'placeholder' => esc_html__( 'Packaging Processing', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Packaging Completed Text', 'wcone' ),
            'name'  => 'packaging-completed-text',
            'placeholder' => esc_html__( 'Packaging Completed', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Waiting For Packaging Accept Text', 'wcone' ),
            'name'  => 'waiting-for-packaging-accept-text',
            'placeholder' => esc_html__( 'Waiting For Packaging Accept', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'On The Way Text', 'wcone' ),
            'name'  => 'on-the-way-text',
            'placeholder' => esc_html__( 'On The Way', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Ready To Delivery Text', 'wcone' ),
            'name'  => 'ready-to-delivery-text',
            'placeholder' => esc_html__( 'Ready To Delivery', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Delivery Completed Text', 'wcone' ),
            'name'  => 'delivery-completed-text',
            'placeholder' => esc_html__( 'Delivery Completed', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Order Placed Text', 'wcone' ),
            'name'  => 'order-placed-text',
            'placeholder' => esc_html__( 'Order Placed', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Accepted Packaging Text', 'wcone' ),
            'name'  => 'accepted-packaging-text',
            'placeholder' => esc_html__( 'Accepted Packaging', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Processing Text', 'wcone' ),
            'name'  => 'processing-text',
            'placeholder' => esc_html__( 'Processing', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'On The Way To Delivery Text', 'wcone' ),
            'name'  => 'way-to-delivery-text',
            'placeholder' => esc_html__( 'On The Way To Delivery', 'wcone' ),
            'is_pro'    => true
          ]
        );
        
        $this->end_fields_section(); // End fields section
   }
}

new StatusText_Settings_Tab();