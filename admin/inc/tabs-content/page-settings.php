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


class Page_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Page Settings', 'wcone' ),
            'icon'    => 'fa fa-cog',
            'id'      => 'pagesettings'
        ]);
        $this->selectbox(
          [
          'title' => esc_html__( 'Select Branch Manager Page', 'wcone' ),
          'name'  => 'branch-manager',
          'options'   => wcone_get_pages(),
          'is_pro'    => true
          ]
        );
        $this->selectbox(
          [
          'title' => esc_html__( 'Select Packaging Manager Page', 'wcone' ),
          'name'  => 'packaging-manager',
          'options'   => wcone_get_pages(),
          'is_pro'    => true
          ]
        );
        $this->selectbox(
          [
          'title' => esc_html__( 'Select Delivery Page', 'wcone' ),
          'name'  => 'delivery',
          'options'   => wcone_get_pages(),
          'is_pro'    => true
          ]
        );
        $this->selectbox(
          [
          'title' => esc_html__( 'Select Admin Page', 'wcone' ),
          'name'  => 'admin',
          'options'   => wcone_get_pages(),
          'is_pro'    => true
          ]
        );
        


        $this->end_fields_section(); // End fields section
   }
}

new Page_Settings_Tab();