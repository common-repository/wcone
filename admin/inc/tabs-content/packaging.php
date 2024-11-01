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


class Packaging_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Packaging Options', 'wcone' ),
            'icon'    => 'fa fa-tools',
            'id'      => 'packagingopt'
        ]);
        
        $this->switcher([
          'title' => esc_html__( 'Deliver Boy Assign Option', 'wcone' ),
          'name'  => 'packaging-boy-assign',
          'is_pro'    => true
        ]);
        $this->switcher([
          'title' => esc_html__( 'All Order Show In  Packaging', 'wcone' ),
          'name'  => 'packaging-all-order',
          'is_pro'    => true
        ]);

        $this->end_fields_section(); // End fields section
   }
}

new Packaging_Settings_Tab();