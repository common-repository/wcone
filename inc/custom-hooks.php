<?php 
namespace Wcone;
/**
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Custom_Hooks {

  function __construct() {
    // Delivery type html
    add_action( 'wcone_delivery_types', [ $this, 'delivery_types_html' ] );
    add_action( 'wcone_delivery_schedule_time', [ $this, 'delivery_schedule_time_html' ] );
  }

  public function delivery_types_html() {
    $deliveryTime = get_option('wcone_options');
    ?>
    <div class="rb_multiform">
          <!-- Form Selector Group -->  
      <label for="rb_delivery_type" class="rb_input_label rb_mb_0">
				<?php esc_html_e( 'Delivery Type', 'wcone' ) ?><span class="fb-required">*</span>
			</label>        
      <ul class="rb_list_unstyled rb_form_selector_list rb_mt_5">
			<?php 

      $types = [
        [
          'title' => esc_html__( 'Delivery', 'wcone' ),
          'class' => 'delivery-type-delivery',
          'value' => 'Delivery',
          'compare' => 'delivery'
        ],
        [
          'title' => esc_html__( 'Pickup by me', 'wcone' ),
          'class' => 'delivery-type-pickup',
          'value' => 'Pickup',
          'compare' => 'pickup'
        ]

      ];

      $getTypes = apply_filters( 'wcone_add_delivery_type', $types );

      foreach( $getTypes as $type ) {

        if( $deliveryTime['delivery-options'] == $type['compare'] || $deliveryTime['delivery-options'] == 'all' ) {

          $checked = $deliveryTime['delivery-options'] == $type['compare'] ? 'checked' : '';

          echo '<li class="rb_single_form_selector">
            <span class="rb_custom_checkbox">
              <label>
                <input type="radio" value="'.esc_attr( $type['value'] ).'" class="shipping_method '.esc_attr( $type['class'] ).'" name="rb_delivery_options" '.esc_attr( $checked ).'>
                <span class="rb_label_title">'.esc_html( $type['title'] ).'</span>
                <span class="rb_custom_checkmark"></span>
              </label>
            </span>
          </li>';
        }
        
      }
      ?>
      </ul>
          <!-- End Form Selector Group -->
    </div>
    <?php
  }

  public function delivery_schedule_time_html() {
    $deliveryTime = get_option('wcone_options');
    $getText = \Wcone\Inc\Text::getText();
    //
    if( !empty( $deliveryTime['pickup-time-switch'] ) && $deliveryTime['pickup-time-switch'] == 'yes' ):
        ?>
        <div class="rb_multiform delivery-schedule-options">
          <!-- Form Selector Group -->  
          <label for="rb_delivery_type" class="rb_input_label rb_mb_0">
          <?php esc_html_e( 'Delivery Schedule Type', 'wcone' ) ?><span class="fb-required">*</span>
          </label>
          <ul class="rb_list_unstyled rb_form_selector_list rb_mt_5">
            <li class="rb_single_form_selector">
                <span class="rb_custom_checkbox">
                  <label>
                    <input type="radio" value="todayDelivery" class="shipping_method" name="rb_delivery_schedule_options" checked>
                    <span class="rb_label_title"><?php echo esc_html( $getText ['dp_today_text'] ); ?></span>
                    <span class="rb_custom_checkmark"></span>
                  </label>
                </span>
            </li>
          </ul>
          <!-- End Form Selector Group -->
        </div>
      <div class="fb-delivery-time-wrapper">
        <label for="rb_delivery_time" class="rb_input_label">
        <?php echo esc_html( $getText ['dp_time_text'] ); ?><span class="fb-required">*</span>
        </label>
        <?php 
        if( !empty( $deliveryTime['delivery-time-note'] ) ) {
          echo '<p class="delivery-time-note">'.esc_html( $deliveryTime['delivery-time-note'] ).'</p>';
        }
        ?>
        <select name="rb_delivery_time" id="rb_delivery_time" class="rb_input_style" required>
        <?php
        $timeList = \Wcone\Date_Time_Map::getTimes();
        wcone_time_solt_options_html( $timeList );
        ?>
        </select>
      </div>
      <?php 
      endif; //
  }


}

// Hooks class init
new Custom_Hooks();