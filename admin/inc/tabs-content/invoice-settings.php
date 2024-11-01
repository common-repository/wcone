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


class Invoice_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Invoice Settings', 'wcone' ),
            'icon'    => 'fas fa-file-invoice',
            'id'      => 'invoicesettings'
        ]);

        $this->selectbox(
          [
          'title' => esc_html__( 'Invoice Type', 'wcone' ),
          'name'  => 'invoice_type',
          'options'   => [
            'normal' => esc_html__('Normal Printer', 'wcone'),
            'thermal' => esc_html__('Thermal/Receipt Printer', 'wcone'),
          ],
          'is_pro'    => true
          ]
        );
        $this->media(
          [
            'title' => esc_html__( 'Logo Upload', 'wcone' ),
            'name'  => 'invoice_logo',
            'is_pro'    => true
          ]
        );
        $this->text(
          [
          'title' => esc_html__( 'Invoice header restaurant name', 'wcone' ),
          'name'  => 'header_restaurant_name',
          'is_pro'    => true
          ]
        );
        $this->text(
          [
          'title' => esc_html__( 'Invoice header restaurant Address', 'wcone' ),
          'name'  => 'header_restaurant_address',
          'is_pro'    => true
          ]
        );
        $this->text(
          [
          'title' => esc_html__( 'Invoice Footer Text', 'wcone' ),
          'name'  => 'invoice_footer_text',
          'is_pro'    => true
          ]
        );   


        $this->end_fields_section(); // End fields section
   }
}

new Invoice_Settings_Tab();