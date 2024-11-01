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

class Shortcodelist_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'wcone_options'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([
            'title'   => esc_html__( 'Shortcode List', 'wcone' ),
            'icon'    => 'fa fa-code',
            'id'      => 'shortcodelist'
        ]);
        ?>
        <h3><?php esc_html_e( 'Shortcode List', 'wcone' ); ?></h3>
              <div class="shortcode-item-list">
              <div class="shortcode-item-list">
                <h4><?php esc_html_e( 'Delivery ability checker form:', 'wcone' ); ?></h4>
                <code>[wcone_delivery_ability_checker] ( pro )</code>
                <code>[wcone_delivery_ability_checker button_text="search"] ( pro )</code>
                <h5><?php esc_html_e( 'Attrubute List', 'wcone' ); ?></h5>
                <ul>
                  <li><pre><?php esc_html_e( 'button_text - button_text="search"', 'wcone' ); ?> </pre></li>
                </ul>
              </div>
              <div class="wcone-shortcode-generator-wrapper">
                  <h2><?php esc_html_e( 'Shortcode Generator', 'wcone' ); ?></h2>
                  <div class="wcone-shortcode-generator-inner">
                      <select id="shortcodeType">
                          <option value=""><?php esc_html_e( 'Shortcode For ?', 'wcone' ); ?></option>
                          <option value="wcone_delivery_ability_checker"><?php esc_html_e( 'Delivery ability checker form ( Pro )', 'wcone' ); ?></option>
                      </select>
                      <?php
                      do_action('wcone_shortcode_branch_list');
                      ?>
                  </div>
                  <div class="scode-show"></div>
              </div>
              
        <?php
        $this->end_fields_section(); // End fields section
   }
}

new Shortcodelist_Settings_Tab();