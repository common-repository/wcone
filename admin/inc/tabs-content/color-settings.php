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


class Color_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Color Settings', 'wcone' ),
            'icon'    => 'fa fa-fill',
            'id'      => 'colorsettings'
        ]);

        $this->colorpicker(
          [
            'title' => esc_html__( 'Main Color', 'wcone' ),
            'name'  => 'main-color'
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Button Background Color', 'wcone' ),
            'name'  => 'gob-btn-bg-color',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Button Color', 'wcone' ),
            'name'  => 'gob-btn-color',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Button Hover Background Color', 'wcone' ),
            'name'  => 'gob-btn-hover-bg-color',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Button Hover Color', 'wcone' ),
            'name'  => 'gob-btn-hover-color',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Mini Cart Button background', 'wcone' ),
            'name'  => 'cart-btn-bg',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Mini Cart Button Count background', 'wcone' ),
            'name'  => 'cart-btn-count-bg',
          ]
        );
        $this->colorpicker(
          [
            'title' => esc_html__( 'Mini Cart Button Count Text Color', 'wcone' ),
            'name'  => 'cart-btn-count-color',
          ]
        );        
        $this->end_fields_section(); // End fields section
   }
}

new Color_Settings_Tab();