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


class OrderReceived_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Order Received Pgae', 'wcone' ),
            'icon'    => 'fa fa-pager',
            'id'      => 'orderReceived'
        ]);
        
        $this->media(
          [
            'title' => esc_html__( 'Page Top Image', 'wcone' ),
            'name'  => 'received-page-img',
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Page Title', 'wcone' ),
            'name'  => 'received-page-title',
          ]
        );
        $this->textarea(
          [
            'title' => esc_html__( 'Page Description', 'wcone' ),
            'name'  => 'received-description'
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Active Invitation Option', 'wcone' ),
            'name'  => 'active-invitation'
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Invitation Header ( From ) Email', 'wcone' ),
            'name'  => 'invitation-from-email',
          ]
        );
        $this->textarea(
          [
            'title' => esc_html__( 'Invitation Message Subject', 'wcone' ),
            'name'  => 'invitation-subject'
          ]
        );
        $this->textarea(
          [
            'title' => esc_html__( 'Invitation Message', 'wcone' ),
            'name'  => 'invitation-message'
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Twitter Share Link', 'wcone' ),
            'name'  => 'twitter-share-link',
          ]
        );
        $this->text(
          [
            'title' => esc_html__( 'Facebook Share Link', 'wcone' ),
            'name'  => 'facebook-share-link',
          ]
        );


        $this->end_fields_section(); // End fields section
   }
}

new OrderReceived_Settings_Tab();