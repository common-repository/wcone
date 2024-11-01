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


class Location_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Location Settings', 'wcone' ),
            'icon'    => 'fa fa-map',
            'id'      => 'locationSettings'
        ]);

        $this->switcher(
          [
            'title' => esc_html__( 'Delivery Availability Checker Active', 'wcone' ),
            'name'  => 'availability-checker-active',
            'is_pro'    => true
          ]
        );
        $this->selectbox(
          [
            'title' => esc_html__( 'Location Type', 'wcone' ),
            'name'  => 'location_type',
            'options'   => [
              'address' => esc_html__( 'Address', 'wcone' ),
              'zip'     => esc_html__( 'Zip Code', 'wcone' )
            ],
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Set Google API Key', 'wcone' ),
            'name'  => 'google-api-key',
            'wrapperclass' => 'fb-address-conditional-field',
            'description' => '<a href="http://console.cloud.google.com/" target="_blank">'.esc_html__( 'Create google API ', 'wcone' ).'</a>',
            'is_pro'    => true
          ]
        );
        
        $this->switcher(
          [
            'title' => esc_html__( 'Location Modal Popup Active', 'wcone' ),
            'name'  => 'location-popup-active',
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Modal Location Checker Active', 'wcone' ),
            'name'  => 'popup-location-active',
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Modal Close Button Show', 'wcone' ),
            'name'  => 'modal-close-btn-show',
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Checkout Page Delivery Location Checker Active', 'wcone' ),
            'name'  => 'checkout-location-active',
            'is_pro'    => true
          ]
        );
        
        $this->multiple_select(
          [
            'title' => esc_html__( 'Set Delivery Availability Checker Modal Show Page', 'wcone' ),
            'name'  => 'availability-checker-modal',
            'options'   => wcone_get_pages(),
            'is_pro'    => true
          ]
        );

        $this->end_fields_section(); // End fields section
   }
}

new Location_Settings_Tab();