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


class Email_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Order Status Change Email Settings', 'wcone' ),
            'icon'    => 'fa fa-envelope',
            'id'      => 'emailsettings'
        ]);

        $this->switcher(
          [
            'title' => esc_html__( 'Active Email Notification', 'wcone' ),
            'name'  => 'active-e-notification',
            'is_pro'    => true
          ]
        );
        $this->switcher(
          [
            'title' => esc_html__( 'Active Delivery Boy Assign Email Notification', 'wcone' ),
            'name'  => 'active-order-assign-mail-notification',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Subject Text', 'wcone' ),
            'name'        => 'subject-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Order Cancel Notification Text', 'wcone' ),
            'name'        => 'on-cancel-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Send To Packaging Notification Text', 'wcone' ),
            'name'        => 'on-stc-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Accept Packaging Notification Text', 'wcone' ),
            'name'        => 'on-ac-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Packaging Complete Notification Text', 'wcone' ),
            'name'        => 'on-cc-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'On The Way Notification Text', 'wcone' ),
            'name'        => 'on-owd-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Delivery Complete Notification Text', 'wcone' ),
            'name'        => 'on-dc-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Email Template Header Text', 'wcone' ),
            'name'        => 'et-header-text',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
            'title'       => esc_html__( 'Email Template Footer Text', 'wcone' ),
            'name'        => 'et-footer-text',
            'is_pro'    => true
          ]
        );
        $this->colorpicker(
          [
            'title'       => esc_html__( 'Email Template Header Background Color', 'wcone' ),
            'name'        => 'et-bg-color',
            'is_pro'    => true
          ]
        );

        $this->end_fields_section(); // End fields section
   }
}

new Email_Settings_Tab();