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

class Woo_Hooks {

  function __construct() {

    // Add custom field to order object action hook
    add_action( 'woocommerce_checkout_create_order_line_item', [ __CLASS__ ,'wcone_add_custom_data_to_order' ], 10, 4 );

    // Woocommerce Order meta data after shipping address action hook
    add_action( 'woocommerce_admin_order_data_after_shipping_address', [ __CLASS__, 'wcone_edit_woocommerce_checkout_page' ], 10, 1 );

    //Woocommerce Order meta data after shipping address action
    add_action( 'woocommerce_checkout_update_order_meta', [ __CLASS__, 'checkout_update_order_meta' ], 10, 2 );

    // Override WooCommerce Templates from plugin filter hook
    add_filter( 'woocommerce_locate_template', [ __CLASS__, 'wcone_woo_template' ], 1, 3 );

    // WooCommerce order meta query filter hook
    add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', [ __CLASS__, 'wcone_order_meta_query_var' ], 10, 2 );

    // WooCommerce product meta query filter hook
    add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ __CLASS__, 'wcone_product_meta_query_var'], 10, 2 );

    // Add product extra items price action hook
    add_action( 'woocommerce_before_calculate_totals', [ __CLASS__, 'add_product_extra_items_price' ], 10, 1);

    // Order status set ( order place  or pre order )
    add_action( 'woocommerce_order_status_processing', [ __CLASS__, 'order_status_changed' ], 10, 1 );
    add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'order_status_changed' ], 10, 1 );
    add_action( 'woocommerce_order_status_on-hold', [ __CLASS__, 'order_status_changed' ], 10, 1 );

    // Order cancelled hook
    add_action( 'woocommerce_cancelled_order', [ __CLASS__, 'wc_cancelled_order'], 10, 1 );

    // order status failed hook
    add_action('woocommerce_order_status_failed', [ __CLASS__, 'wc_failed_order'], 15, 2);
    
    // 
    add_action( 'woocommerce_checkout_update_order_review', [ $this, 'checkout_radio_choice_set_session' ] );

    //
    add_filter( 'wc_order_statuses', [ __CLASS__, 'add_pre_order_statuses' ] );
      
    //
    add_action( 'init', [ __CLASS__, 'register_pre_order_status' ] );
    
    //
    add_filter( 'woocommerce_add_to_cart_validation', [ __CLASS__, 'add_to_cart_validation' ], 10, 4 );

    //
    add_filter( 'manage_edit-product_columns', [__CLASS__, 'add_product_column'], 10, 1 );

    //
    add_action( 'manage_product_posts_custom_column', [__CLASS__, 'add_product_column_content'], 10, 2 );

    //
    add_filter( 'woocommerce_package_rates', [__CLASS__, 'hide_shipping_when_free_is_available'], 100 );

    //
    add_action( 'woocommerce_after_checkout_validation', [__CLASS__, 'checkout_page_validate' ], 10, 2);

    //
    add_action( 'woocommerce_checkout_process', [ __CLASS__, 'wc_checkout_minimum_order_amount' ] );

    //
    add_action( 'woocommerce_before_cart' , [ __CLASS__, 'wc_cart_minimum_order_amount' ] );

    // cancel unpaid order
    add_filter( 'woocommerce_cancel_unpaid_order', [ __CLASS__, 'action_woocommerce_cancel_unpaid_orders'], 10, 2 );
    //
    add_filter( 'woocommerce_add_to_cart_fragments', [ __CLASS__, 'cart_count_fragments' ], 10, 1 );
    //
    remove_action( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal' );
    add_action( 'woocommerce_widget_shopping_cart_total', [ __CLASS__, 'widget_shopping_cart_total' ], 10 );
    //
    remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
    remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
    //
    add_action( 'woocommerce_before_add_to_cart_button', [ __CLASS__, 'product_extra_before_add_to_cart_button' ], 25 );
    add_action( 'woocommerce_single_product_summary', [ __CLASS__, 'product_nutrition_after_single_title' ], 8 );
    add_action( 'woocommerce_checkout_before_order_review_heading', [ __CLASS__, 'checkout_page_before_order_review_heading' ] );

    add_filter( 'woocommerce_add_cart_item_data', [ __CLASS__, 'add_cart_item_data'], 10, 3 );


  }

  public static function getText() {
    return \Wcone\Inc\Text::getText();
  } 

  /**
   * Add custom field to order object
   */
  public static function wcone_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {

    foreach( $item as $cart_item_key => $values ) {

      // Item Instructions
      if( isset( $values['item_instructions'] ) ) {
        $item->add_meta_data( esc_html__( 'Item Instructions', 'wcone' ), sanitize_text_field( $values['item_instructions'] ), true );
      }

      // Item extra features
      if( isset( $values['product_extra'] ) ) {
        $item->add_meta_data( esc_html__( 'Item Extra:-', 'wcone' ), sanitize_text_field( $values['product_extra'] ), true );
      }

    }
  }
  
/**
 * Woocommerce Order meta data after shipping address
 *
 * 
 */
public static function wcone_edit_woocommerce_checkout_page( $order ){

  global $post_id;
  $order = new \WC_Order( $post_id );

  $orderID = $order->get_id();

  $time = get_post_meta ( absint( $orderID ) , '_pickup_time', true );

  $deliveryType = get_post_meta ( absint( $orderID ) , '_delivery_type', true );

  //
  if( !empty( $deliveryType ) ) {
    echo '<p><strong>'.esc_html__('Delivery Type', 'wcone' ).':</strong> ' . esc_html( $deliveryType ) . '</p>';
  }
  //
  if( !empty( $time ) ) {
    echo '<p><strong>'.esc_html__('Time to Deliver/Pickup', 'wcone' ).':</strong> ' . esc_html( $time ) . '</p>';
  }
  
}

/**
 * Woocommerce Add Order meta data 
 *
 */
public static function checkout_update_order_meta( $order_id, $posted ) {

    $order = wc_get_order( $order_id );

    //
    if( isset( $_POST['rb_delivery_time'] ) ) {
      $time = explode( ',', sanitize_text_field( $_POST['rb_delivery_time'] ) );
      $order->update_meta_data( '_pickup_time', sanitize_text_field( $time[0] ) );
    }
    //
    if( isset( $_POST['rb_delivery_options'] ) ) {
      $order->update_meta_data( '_delivery_type', sanitize_text_field( $_POST['rb_delivery_options'] ) );
    }
    //
    if( isset( $_POST['rb_delivery_date'] ) ) {
      $order->update_meta_data( '_delivery_date', sanitize_text_field( $_POST['rb_delivery_date'] ) );
    }
    
    $order->save();

} 

/**
 *
 * Override WooCommerce Templates
 * 
 */
public static function wcone_woo_template( $template, $template_name, $template_path ) {

     global $woocommerce;
     $_template = $template;
     if ( ! $template_path ) 
        $template_path = $woocommerce->template_url;
 
     $plugin_path  = untrailingslashit( WCONE_DIR_PATH )  . '/template/woocommerce/';
 
    // Look within passed path within the theme - this is priority
    $template = locate_template(
    array(
      $template_path . $template_name,
      $template_name
    )
   );
 
   if( file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;
 
   if ( ! $template )
    $template = $_template;

   return $template;

}

/**
 *
 * WooCommerce order meta query 
 * 
 */
public static function wcone_order_meta_query_var( $query, $query_vars ) {

  if ( ! empty( $query_vars['tracking_status'] ) ) {
    $query['meta_query'][] = array(
      'key' => '_order_tracking_status',
      'value' => esc_attr( $query_vars['tracking_status'] ),
    );
  }
  
  //
  if ( ! empty( $query_vars['delivery_boy'] ) ) {
    $query['meta_query'][] = array(
      'key' => '_order_delivery_boy',
      'value' => esc_attr( $query_vars['delivery_boy'] ),
    );
  }
  //
  if ( ! empty( $query_vars['delivery_date'] ) ) {
    $query['meta_query'][] = array(
      'key' => '_delivery_date',
      'value' => esc_attr( $query_vars['delivery_date'] ),
    );
  }
  //
  if ( ! empty( $query_vars['pre_order_status'] ) ) {
    $query['meta_query'][] = array(
      'key' => '_pre_order_status',
      'value' => esc_attr( $query_vars['pre_order_status'] ),
    );
  }
  //
  if ( ! empty( $query_vars['pickup_time'] ) ) {
    $query['meta_query'][] = array(
      'key' => '_pickup_time',
      'value' => esc_attr( $query_vars['pickup_time'] ),
    );
  }

  return $query;
}

/**
 *
 * WooCommerce product meta query 
 * 
 */

public static function wcone_product_meta_query_var( $query, $query_vars ) {

  // low to high price

  if ( ! empty( $query_vars['low_to_high_price'] ) ) {
    $query['meta_query'][] = array(
      'relation' => 'OR',
      array(
          'key' => '_price',
          'value' => esc_attr( $query_vars['low_to_high_price'] ),
          'compare' => '>',
          'type' => 'NUMERIC'
      ),         
      array(
          'key' => '_sale_price',
          'value' => esc_attr( $query_vars['low_to_high_price'] ),
          'compare' => '>',
          'type' => 'NUMERIC'
      )
    );
  }

  // Average rating
  if ( ! empty( $query_vars['average_rating_product'] ) ) {
    $query['meta_query'][] = array(
      array(
          'key' => '_wc_average_rating',
          'value' => esc_attr( $query_vars['average_rating_product'] ),
          'compare' => '>',
          'type' => 'NUMERIC'
      )
    );
  }

  // Product query by branch meta
  if ( ! empty( $query_vars['product_by_branch'] ) ) {
    $query['meta_query'][] = array(
      array(
          'key' => 'wconebranch_list',
          'value' => esc_attr( $query_vars['product_by_branch'] ),
          'compare' => 'LIKE'
      )
    );
  }

  // Average rating
  if ( ! empty( $query_vars['total_sales_product'] ) ) {
    $query['meta_query'][] = array(
      array(
          'key' => 'total_sales'
      )
    );
  }

  return $query;

}

/**
 *
 * Before calculate totals
 * Add product extra items price
 * 
 */
public static function add_product_extra_items_price( $cart_object ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    foreach ( $cart_object->get_cart() as $cart_item ) {
      if( !empty( $cart_item['product_extra_total_price'] ) ) {
        $cart_item['data']->set_price( $cart_item['data']->get_price() + $cart_item['product_extra_total_price'] );
      }
    }

}

public static function order_status_changed( $order_id ) {

  $time = current_time( "Y-m-d H:i:s" );
  $CurrentDate = current_time( "d-m-Y" );

  // Get delivery date
  $dDate = get_post_meta( absint($order_id), '_delivery_date', true );

  // Pre order status
  if( strtotime( $dDate ) >  strtotime( $CurrentDate ) ) {
    update_post_meta( absint( $order_id ), '_pre_order_status', sanitize_text_field( 'PO' ) );
  }

  // Update status
  update_post_meta( absint( $order_id ), '_order_tracking_status', sanitize_text_field( 'OP' ) );
  update_post_meta( absint( $order_id ), '_order_tracking_status_time', sanitize_text_field( $time ) );

}

/**
 * WooCommerce order cancelled callback
 * Update order tracking status
 * @param  int $order_id
 * @return void
 */
public static function wc_cancelled_order( $order_id ) {
  $time = current_time( "Y-m-d H:i:s" );
  update_post_meta( absint( $order_id ), '_order_tracking_status', sanitize_text_field( 'OC' ) );
  update_post_meta( absint( $order_id ), '_order_tracking_status_time', sanitize_text_field( $time ) );
}

/**
 * wc_failed_order 
 * Update order tracking status when order failed
 * @param  int $order_id and object $order
 * @return void
 */
public static function wc_failed_order( $order_id, $order ) {
  $time = current_time( "Y-m-d H:i:s" );
  update_post_meta( absint( $order_id ), '_order_tracking_status', sanitize_text_field( 'OF' ) );
  update_post_meta( absint( $order_id ), '_order_tracking_status_time', sanitize_text_field( $time ) );
}

/**
 * action_woocommerce_cancel_unpaid_orders
 * Update order tracking status to cancelled when unpaid order time limit reached.
 * @param  bool $cby and object $order
 * @return void
 */
public static function action_woocommerce_cancel_unpaid_orders( $cby, $order ) {

  if( $cby ) {
    update_post_meta( $order->get_id(), '_order_tracking_status', sanitize_text_field( 'OC' ) );
    $order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'wcone' ) );
  }
  
}


/**
 *
 * Add Radio Choice to Session
 * 
 */
function checkout_radio_choice_set_session( $posted_data ) {
  parse_str( $posted_data, $output );
  if ( isset( $output['rb_delivery_options'] ) ){
      WC()->session->set( 'radio_chosen', $output['rb_delivery_options'] );
  }
}

/**
 * [register_pre_order_status description]
 * @return [type] [description]
 */
public static  function register_pre_order_status() {
 
  register_post_status( 'wc-pre-order', array(

  'label' => esc_html__( 'Pre Order', 'wcone' ),

  'public' => true,

  'show_in_admin_status_list' => true,

  'show_in_admin_all_list' => true,

  'exclude_from_search' => false,

  'label_count' => _n_noop( 'Pre Order <span class="count">(%s)</span>', 'Pre Order <span

  class="count">(%s)</span>' )

  ) );

}

/**
 * [add_pre_order_statuses description]
 * @param [type] $order_statuses [description]
 */
public static function add_pre_order_statuses( $order_statuses ) {

  $new_order_statuses = array();

  foreach ( $order_statuses as $key => $status ) {

    $new_order_statuses[ $key ] = $status;
    if ( 'wc-processing' === $key ) {
      $new_order_statuses['wc-pre-order'] = esc_html__( 'Pre Order', 'wcone' );

    }

  }

  return $new_order_statuses;

}


/**
 * Validate product extra features option
 */
public static function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {

  // Check product extra required item selected
  $requiredStatus = !empty( $_POST['items_required_status'] ) ? sanitize_text_field( $_POST['items_required_status'] ) : [];
  if( in_array( 'false' , $requiredStatus ) ) {
    $passed = false;
    wc_add_notice( esc_html__( 'Features items is a required field.', 'wcone' ), 'error' );
  }
  return $passed;
}

/**
 * [add_product_column description]
 * @param [type] $columns [description]
 */
public static function add_product_column( $columns ) {
    //add branch column 
    
    if( wcone_is_multi_branch() ) {
      $columns['branch_name'] = esc_html__( 'Branch Name', 'wcone' );
    }

    return $columns;
}

/**
 * [add_product_column_content description]
 * @param [type] $column [description]
 * @param [type] $postid [description]
 */
public static function add_product_column_content( $column, $postid ) {

    if ( wcone_is_multi_branch() && $column == 'branch_name' ) {

      $branch = get_post_meta( $postid, 'wconebranch_list', true );

      if( !empty( $branch ) ) {
        $BranchName = [];
        foreach( $branch as $branchId ) {
          $BranchName[] = get_the_title( $branchId );
        }

        echo implode(', ', $BranchName);
      }
        
    }

}

/**
 * [hide_shipping_when_free_is_available description]
 * @param  [type] $rates [description]
 * @return [type]        [description]
 */
public static function hide_shipping_when_free_is_available( $rates ) {
  $free = array();

  $options = get_option('wcone_options');
  $freeShippingAmount = isset( $options['free-shipping-amount'] ) ? $options['free-shipping-amount'] : '';

  // Free shipping
  if( !empty( $freeShippingAmount ) && WC()->cart->get_subtotal() >= $freeShippingAmount ) {

    foreach ( $rates as $rate_id => $rate ) {
      if ( 'free_shipping' == $rate->method_id ) {
        $free[ $rate_id ] = $rate;
        break;
      }
    }

    return ! empty( $free ) ? $free : $rates;
  } else {
    return $rates;
  }

}

/**
  * Validate checkout branch select option
  */
public static function checkout_page_validate( $fields, $errors ) {
  
  $options = get_option('wcone_options');
  $text = self::getText();

  // Check delivery option status
  if( empty( $options['checkout-delivery-option'] ) ) {
    return;
  }

  // Check branch 
  if( wcone_is_multi_branch() &&  empty( $_POST['rb_pickup_branch'] ) ) {
    $errors->add( 'validation', esc_html( $text['valid_branch_field'] ) );
  }

  // Check delivery pickup type
  if( empty( $_POST['rb_delivery_options'] ) ) {
    $errors->add( 'validation', esc_html( $text['valid_delivery_type_field'] ) );
  }
  
  // Check delivery time validation 
  if( !empty( $options['pickup-time-switch'] ) && !empty( $_POST['rb_delivery_options'] ) ) {

    // delivery time empty check 
    if( empty( $_POST['rb_delivery_time'] ) ) {
      $errors->add( 'validation', esc_html( $text['valid_delivery_time_field'] ) );
    }
    //
    if( !empty( $_POST['rb_delivery_time'] ) && in_array( 'no', explode(',', sanitize_text_field( $_POST['rb_delivery_time'] ) ) ) ) {
      $errors->add( 'validation', esc_html( $text['valid_slot_not_available'] ) );
    }
    //
    if( !empty( $_POST['rb_delivery_time'] ) && in_array( 'true', explode(',', sanitize_text_field( $_POST['rb_delivery_time'] ) ) ) ) {
      $errors->add( 'validation', esc_html( $text['valid_break_time'] ) );
    }

  }

  //
  if( !empty( $options['availability-checker-active'] ) && $_POST['rb_delivery_options'] == 'Delivery' ) {

    $dAvailabilityStatus = get_transient('d_availability_status');

    if( empty( $dAvailabilityStatus ) ) {
        $errors->add( 'validation', esc_html__( 'Please check delivery availability on your location, Before place order', 'wcone' ) );
    } else {
      if( $dAvailabilityStatus == 'no' ) {
        $errors->add( 'validation', esc_html( $text['delivery_available_error'] ) );
      }
    }

  }
  
}

/**
 * Set a minimum order amount for checkout
 * @return [type] [description]
 */
public static function wc_cart_minimum_order_amount() {
    // Set this variable to specify a minimum order value
  if( ! WC()->cart ) {
    return;
  }
    $minimum = wcone_getOptionData( 'minimum-order-amount' );
    $cartSubtotal = WC()->cart->get_subtotal();

    if ( !empty( $minimum ) && $cartSubtotal < $minimum ) {

      $notice = sprintf(
        esc_html__( 'Your current cart total is %s — you must have an order with a minimum of %s to place your order', 'wcone' ) , 
        wc_price( $cartSubtotal ), 
        wc_price( $minimum )
      );

      wc_print_notice( $notice, 'error' );

    }
}

/**
 * [wc_checkout_minimum_order_amount description]
 * @return [type] [description]
 */
public static function wc_checkout_minimum_order_amount() {
    // Set this variable to specify a minimum order value
    $minimum = wcone_getOptionData( 'minimum-order-amount' );
    $cartSubtotal = WC()->cart->get_subtotal();

    if ( !empty( $minimum ) && $cartSubtotal < $minimum ) {

      $notice = sprintf(
        esc_html__( 'Your current order sub total is %s — you must have an order with a minimum of %s to place your order', 'wcone' ) , 
        wc_price( $cartSubtotal ), 
        wc_price( $minimum )
      );

      wc_add_notice( $notice, 'error' );

    }
}


// Update Cart Count & Mini Cart

public static function cart_count_fragments( $fragments ) {

 ob_start();
    ?>
    <span class="rb_cart_count rb_cart_icon"><?php echo sprintf( esc_html__( '%s Items', 'wcone' ), esc_html( WC()->cart->get_cart_contents_count() ) ); ?> </span>
    <?php
        $fragments['.rb_cart_count'] = ob_get_clean();
    return $fragments;
    
}

public static function widget_shopping_cart_total() {
  echo '<div class="cart_table_item cart_table_item_subtotal">
      <div class="rb_product_info">
        <img src="'.esc_url( WCONE_DIR_URL. 'assets/img/icon/subtotal.png' ).'">
        <h4>'.esc_html__( 'Subtotal :', 'wcone' ).'</h4>
    </div>
    <h6 class="rb_Price_subtotal">'.WC()->cart->get_cart_subtotal().'</h6>
  </div>';
}

public static function product_extra_before_add_to_cart_button() {

  global $product;

    $featured = get_post_meta( get_the_ID(), '_extra_featured', true );

    $decodedFeaturedData = json_decode( $featured, true );

  if( !empty( $decodedFeaturedData ) ) {
    ?>
  <div class="extra-items-group-wrapper">
      <h4><?php esc_html_e( 'Extra Item', 'wcone' ); ?></h4>
      <!-- Extra features List -->
      <?php 
      foreach( $decodedFeaturedData as $key => $featuredData ):

        $getListType = $featuredData['list_type'];
        $listType = 'checkbox';
        $inputName = 'rb_product_extra_options[checkbox]';
        $parentIndex = $key;

      if( $getListType != 'checkbox' ) {

        $listType =  'radio';
        $inputName =  'rb_product_extra_options[radio_'.$key.']';

      }

      ?>
      <div class="fb-wrap-selector rb_form_extra_input_list">
        <div class="rb_features_list_title_wrap">
            <h5 class="input_list_title fb-product-extra-group-title">
              <span><?php echo esc_html( $featuredData['group_title'] ); ?>
                <?php 
                if( !empty( $featuredData['group_required_number'] ) ) {
                  echo '<span>*</span>';
                }
                ?>
              </span>
              <span class="icon-set">
              <i class="fas fa-angle-up"></i>
              <i class="fas fa-angle-down"></i>
              </span>
            </h5>
            <?php
            if( !empty( $featuredData['group_required_number'] ) || !empty( $featuredData['group_required_number_max'] ) ) {
              echo '<input type="hidden" name="items_required_status[]" value="false" class="selectedcount">';
            }
            ?>
        </div>
        
        <ul class="rb_list_unstyled rb_extra_group_wrap fb-d-none" data-extra-group="group_<?php echo esc_attr( $parentIndex ); ?>" data-extra-max-count="<?php echo esc_attr( $featuredData['group_required_number_max'] ); ?>" data-extra-required-count="<?php echo esc_attr( $featuredData['group_required_number'] ); ?>">
          <?php
          if( !empty( $featuredData['group_required_number'] ) && !empty( $featuredData['group_required_number_max'] ) ){ 
          ?>
          <p class="required-msg"> <?php echo sprintf( __( 'Please choose at list %s and max %s options.', 'wcone' ), $featuredData['group_required_number'],$featuredData['group_required_number_max'] ); ?></p>
          
          <?php }
          //
          if( empty( $featuredData['group_required_number'] ) && !empty( $featuredData['group_required_number_max'] ) ){
          ?>
          <p class="required-msg"> <?php echo sprintf( __( 'You could choose max %s options.', 'wcone' ), $featuredData['group_required_number_max'] ); ?></p>
          <?php 
          }
          //
          if( !empty( $featuredData['group_required_number'] ) && empty( $featuredData['group_required_number_max'] ) ){
          ?>
          <p class="required-msg"> <?php echo sprintf( __( 'Please choose at list %s options.', 'wcone' ), $featuredData['group_required_number'] ); ?></p>
          <?php
          } 
          //
          foreach( $featuredData['group_feature'] as $feature  ):
            $price = $feature['price'];
            $dataPrice = str_replace( ',', '.', $price );
            $formatType = 'en-IN';

            if( wc_get_price_decimal_separator() == ',' ) {
                $formatType = 'de-DE';
            }
            
          ?>
          <li>
            <span class="rb_custom_checkbox extra_item_checkbox rb_w_100">
              <label>
                <input
                  class="product-extra-options"
                  type="<?php echo esc_attr( $listType ); ?>"
                  data-price="<?php echo esc_attr( $feature['price'] ); ?>"
                  value="<?php echo esc_html($feature['title']); ?> : <?php echo wcone_currency_symbol_position( $feature['price'], false ); ?>"
                  name="<?php echo esc_attr( $inputName ); ?>[]"
                />
                <span
                  class="rb_input_text rb_d_flex rb_align_items_center rb_justify_content_between rb_w_100"
                  ><?php echo esc_html( $feature['title'] ); ?>
                  <span>+ <?php echo wcone_currency_symbol_position( $feature['price'] , false ); ?></span>
                </span>
                <span class="rb_custom_checkmark"></span>
              </label>
            </span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
      <input type="hidden" class="wcone_total_extra_price" value="0" name="product_extra_total_price" />
      <div class="rb_label_title rb_total_price rb_d_flex rb_align_items_center rb_justify_content_between">
        <span><?php esc_html_e( 'Total Product Extra Price', 'wcone' ); ?></span>
        <span class="rb_total_product_extra_price"></span>
      </div>
      <div class="rb_label_title rb_total_price rb_d_flex rb_align_items_center rb_justify_content_between">
        <span><?php esc_html_e( 'Total Price', 'wcone' ); ?></span>
        <span class="rb_total_Price" data-item-price="<?php echo esc_attr( $product->get_price() ); ?>"></span>
      </div>

  </div>
<?php
  }// End condition

}

public static function product_nutrition_after_single_title() {

  $nutritionData = get_post_meta( get_the_ID(), '_nutrition_information', true );
  $nutritionData = json_decode( $nutritionData, true );

  echo '<div class="product-nutrition">';
    echo '<ul>';
      if( !empty( $nutritionData ) ) {
        foreach( $nutritionData as $nutrition ) {
          echo '<li><span>'.esc_html( $nutrition['title'] ).'</span><span class="nutrition-qty">'.esc_html( $nutrition['quantity'] ).'</span></li>';
        }
      }
      
    echo '</ul>';
  echo '</div>';
}

public static function checkout_page_before_order_review_heading() {
  echo '<div class="wcone-checkout-before-review-heading">';
  do_action( 'wcone_delivery_types' );
  do_action( 'wcone_delivery_schedule_time' );
  do_action( 'wcone_checkout_page_delivery_ability_checker' );
  echo '</div>';
}

/**
 * Add Extra option in cart item data
 */

public static function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

  if( !empty( $_POST['rb_product_extra_options'] ) && !empty( $_POST['product_extra_total_price'] ) ) {
    $extraOptions = call_user_func_array( 'array_merge', $_POST['rb_product_extra_options'] );
    $extraOptions = implode(' | ', $extraOptions);
    $cart_item_data['product_extra'] = sanitize_text_field( $extraOptions );
    $cart_item_data['product_extra_total_price'] = sanitize_text_field( $_POST['product_extra_total_price'] );
  }
 return $cart_item_data;
}



}//

// Woo_Hooks class init

new Woo_Hooks();