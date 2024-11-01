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


class General_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([

            'title'   => esc_html__( 'General Settings', 'wcone' ),
            'class'   => 'active',
            'id'      => 'general',
            'icon'    => 'fa fa-home',
            'display' => 'block'

        ]);
        $this->selectbox(
          [
            'title' => esc_html__( 'Mini Cart Style', 'wcone' ),
            'name'  => 'cart-modal-style',
            'options'   => [
              'canvas'          => esc_html__( 'Canvas Modal', 'wcone' ), 
              'footer-fixed'    => esc_html__( 'Footer Fixed ( Pro )', 'wcone' )
            ]
          ]
        );
        $this->number(
          [
            'title' => esc_html__( 'Manager Page Order Notification Delay Time ( default 6 second )', 'wcone' ),
            'name'  => 'page-autoreload',
            'placeholder' => esc_attr__( '6', 'wcone' ),
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Notification Audio Loop', 'wcone' ),
            'name'  => 'audio-loop',
            'is_pro'    => true
          ]
        );
        $this->media(
          [
            'title'       => esc_html__( 'Upload Notification Audio MP3', 'wcone' ),
            'name'        => 'notification-audio',
            'is_pro'    => true
          ]
        );
        $this->end_fields_section(); // End fields section
   }
}

new General_Settings_Tab();