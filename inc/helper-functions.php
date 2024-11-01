<?php 
/**
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

/**
 * [wcone_getOptionData description]
 * @return [type] [description]
 */
function wcone_getOptionData( $key, $defaultValue = '' ) {
  $data = get_option( 'wcone_options' );
  return !empty( $data[$key] ) ? $data[$key] : $defaultValue;
}

/**
 * [foodman_getSpecialOffer description]
 * @return array
 */
function wcone_getSpecialOffer() {

    $terms = wcone_get_terms('specialoffer');

    $output = $getList = '';
    $totalCat = 0;
    if( !empty( $terms ) ) {

      foreach( $terms as $term ){
        $getList .= wcone_product_cat_html( $term, 'rb_product_specialoffer', '' );
        $totalCat += $term->count;
      }

      $output .= '<li>
                  <input type="radio" name="rb_product_specialoffer" value="" id="offer-all">
                  <label for="offer-all">
                      <span class="rb_cat_name">'.esc_html__('All', 'wcone').'</span>
                      <span class="rb_cat_count">'.esc_html( $totalCat ).'</span>
                  </label>
              </li>';
      $output .= $getList;

    }

  return $output;
}

/**
 * [foodman_getSpecialOffer description]
 * @return array
 */
function wcone_getVisibility() {

    $terms = wcone_get_terms('product-visibility');

    $output = $getList = '';
    $totalCat = 0;
    if( !empty( $terms ) ) {

      foreach( $terms as $term ) {
        $totalCat += $term->count;
        $getList .= wcone_product_cat_html( $term, 'rb_product_visibility', '' );
      }

      $output .= '<li>
                  <input type="radio" name="rb_product_visibility" value="" id="visibility-all">
                  <label for="offer-all">
                      <span class="rb_cat_name">'.esc_html__('All', 'wcone').'</span>
                      <span class="rb_cat_count">'.esc_html( $totalCat ).'</span>
                  </label>
              </li>';

      $output .= $getList;
    }

  return $output;
}

/**
 * [wcone_get_specialOffer_terms description]
 * @return array
 */
function wcone_get_terms( $taxonomy ) {
  $terms = get_terms( array(
      'taxonomy' => $taxonomy,
      'hide_empty' => true,
  ) );
  return $terms;
}

/**
 * [wcone_get_pages description]
 * @return array
 */
function wcone_get_pages() {

  $pages = get_pages();
  $getPages = [];

  foreach( $pages as $page ) {
    $getPages[$page->post_name] = $page->post_title;
  }
  return $getPages;

}

/**
 * [wcone_rating_reviews description]
 * @param  string  $rating [description]
 * @param  boolean $echo   [description]
 * @return html          [description]
 */
function wcone_rating_reviews( $rating, $echo = true ) {

  $starRating = '';

	$j = 0;

    for( $i = 0; $i <= 4; $i++ ) {
      $j++;

      if( $rating  >= $j   || $rating  == '5'   ) {
        $starRating .= '<i class="fas fa-star"></i>';
      }elseif( $rating < $j && $rating  > $i ){
        $starRating .= '<i class="fas fa-star-half-alt"></i>';
      } else {
        $starRating .= '<i class="far fa-star"></i>';
      }

    }

    if( $echo == true ) {
      echo wp_kses( $starRating, [ 'i' => [ 'class' => [] ] ] );
    } else {
      return $starRating;
    }

}

/**
 * [wcone_getStatusText description]
 * @return [type] [description]
 */
function wcone_getStatusText() {

  return [

    'no'    => wcone_getOptionData( 'new-order-text', esc_html__( 'New Order', 'wcone' ) ),
    'op'    => wcone_getOptionData( 'order-placed-text', esc_html__( 'Order Placed', 'wcone' ) ),
    'po'    => wcone_getOptionData( 'pre-order-text', esc_html__( 'Pre Order', 'wcone' ) ),
    'oc'    => wcone_getOptionData( 'order-cancel-text', esc_html__( 'Order Cancel', 'wcone' ) ),
    'of'    => wcone_getOptionData( 'order-failed-text', esc_html__( 'Order Failed', 'wcone' ) ),
    'p'     => wcone_getOptionData( 'processing-text', esc_html__( 'Processing', 'wcone' ) ),
    'ac'    => wcone_getOptionData( 'accepted-packaging-text', esc_html__( 'Accepted Packaging', 'wcone' ) ),
    'stc'   => wcone_getOptionData( 'send-to-packaging-text', esc_html__( 'Send To Packaging', 'wcone' ) ),
    'wfka'  => wcone_getOptionData( 'waiting-for-packaging-accept-text', esc_html__( 'Waiting For Packaging Accept', 'wcone' ) ),
    'cc'    => wcone_getOptionData( 'packaging-completed-text', esc_html__( 'Packaging Completed', 'wcone' ) ),
    'rtd'   => wcone_getOptionData( 'ready-to-delivery-text', esc_html__( 'Ready To Delivery', 'wcone' ) ),
    'otw'   => wcone_getOptionData( 'on-the-way-text', esc_html__( 'On The Way', 'wcone' ) ),
    'owd'   => wcone_getOptionData( 'way-to-delivery-text', esc_html__( 'On The Way To Delivery', 'wcone' ) ),
    'dc'    => wcone_getOptionData( 'delivery-completed-text', esc_html__( 'Delivery Completed', 'wcone' ) ),
    'cp'    => wcone_getOptionData( 'packaging-processing-text', esc_html__( 'Packaging Processing', 'wcone' ) )

  ];

}

/**
 * [wcone_tracking_status description]
 * Order Tracking Status list
 * @return array
 */
function wcone_tracking_status() {

  $statusText = wcone_getStatusText();
  $stc = $statusText['stc'];

  if( wcone_is_user_role('packaging_manager') ) {
    $stc = $statusText['wfka'];
  } 

  return [

    'OP'    => $statusText['op'],
    'PO'    => $statusText['po'],
    'OC'    => $statusText['oc'],
    'OF'    => $statusText['of'],
    'PROC'  => $statusText['p'],
    'AC'    => $statusText['ac'],
    'STC'   => $stc,
    'CC'    => $statusText['cc'],
    'RD'    => $statusText['rtd'],
    'OWD'   => $statusText['owd'],
    'DC'    => $statusText['dc']

  ];

}

/**
 * [wcone_converted_tracking_status description]
 * Order Tracking Status convert
 * @param  string $val [description]
 * @return string      
 */
function wcone_converted_tracking_status( $val ) {

  $status = wcone_tracking_status();

  switch( $val ) {
    case  "OP" :
      return $status['OP'];
      break;
      case  "PO" :
      return $status['PO'];
      break;
      case  "OC" :
      return $status['OC'];
      break;
      case  "OF" :
      return $status['OF'];
      break;
      case  "PROC" :
      return $status['PROC'];
      break;
      case  "AC" :
      return $status['AC'];
      break;
      case  "STC" :
      return $status['STC'];
      break;
      case  "CC" :
      return $status['CC'];
      break;
      case  "RD" :
      return $status['RD'];
      break;
      case  "OWD" :
      return $status['OWD'];
      break;
      case  "DC" :
      return $status['DC'];
      break;
  }

}

/**
 * [wcone_branch_list description]
 * @return [type] [description]
 */
function wcone_branch_list() {

  $args = array(
    'posts_per_page' => '-1',
    'post_type' => 'branches',
  );

  $getBranch = get_posts( $args );
  $options = [];

  foreach( $getBranch as $branch ) {
      $options[$branch->ID] = $branch->post_title;
  }

  return $options;
}

/**
 * [wcone_branch_list_html description]
 * branch list with select box option html
 * @return [type] [description]
 */
function wcone_branch_list_html( $beforeText = '', $beforeValue = '', $selectedVal = '' ) {

  $args = array(
    'posts_per_page' => '-1',
    'post_type' => 'branches',
  );

  $getBranch = get_posts( $args );

  if( !empty( $beforeText ) ) {
    $output = '<option value="'.esc_attr( $beforeValue ).'">'.esc_html( $beforeText ).'</option>';
  } else {
    $output = '';
  }

  foreach( $getBranch as $branch ) {
    $output .= '<option value="'.esc_attr( $branch->ID ).'" '.selected( $selectedVal, $branch->ID, false ).'>'.esc_html( $branch->post_title ).'</option>';
  }

  return $output;
}

/**
 * [wcone_get_current_branch_id_by_manager description]
 * Get current branch ID
 * @return array
 */
function wcone_get_current_branch_id_by_manager() {

    $currentUser = get_current_user_id();

    // User data
    $user_meta = get_userdata( $currentUser );
    $user_roles = $user_meta->roles;

    //
    $meta_key = '';

    // is branch manager
    if( $user_roles[0] == 'branch_manager' ) {
      $meta_key = 'wconebranch_manager';
    }
    // is packaging manager
    if( $user_roles[0] == 'packaging_manager' ) {
      $meta_key = 'wconepackaging_manager';
    }
    // is delivery boy
    if( $user_roles[0] == 'delivery_boy' ) {
      $meta_key = 'wconedelivery_boy';
    }

    // Get branch
    $args = array (
        'post_type'        => 'branches',
        'post_status'      => 'publish',
        'meta_key'         => $meta_key,
        'meta_value'       => esc_html( $currentUser ),
        'meta_compare' => 'LIKE'
    );

  $getBranchesId = get_posts( $args );
  $getBranchesId = array_column( $getBranchesId, 'ID' );
  $getBranchesId = !empty( $getBranchesId[0] ) ? $getBranchesId[0] : '';
  return $getBranchesId;
}

/**
 * [wcone_get_users_role_delivery_manager description]
 * Get delivery users
 * @return array
 */
function wcone_get_users_role_delivery_manager() {

  $users = get_users( [ 'role__in' => [ 'delivery_boy' ] ] );
  $getUser = [ '0' => 'Select Delivery Boy' ];

  foreach( $users as $user ) {
    $getUser[$user->ID] = $user->display_name;
  }

  return $getUser;

}

/**
 * [wcone_get_branch_delivery_boy description]
 * Get branch delivery boy
 * @param  string $branch_id [description]
 * @return array
 */
function wcone_get_branch_delivery_boy( $branch_id = '' ) {
  //
  if( wcone_is_multi_branch() )  {

    if( empty( $branch_id ) ) {
      $branch_id = wcone_get_current_branch_id_by_manager();
    }  

    $dIDs = get_post_meta( absint( $branch_id ), 'wconedelivery_boy', true );

    // User data
    $boy = [];

    if( !empty( $dIDs ) ) {

      foreach( $dIDs as $id ) {
        $user_meta = get_userdata( $id );
        $boy[$user_meta->ID] =  $user_meta->user_login;
      }

    }

  } else {
    $boy = wcone_get_users_role_delivery_manager();
  }

  return $boy;

}

/**
 * [wcone_current_date ]
 * @return string date
 */
function wcone_current_date( $is_wpdate = false ) {

  if( $is_wpdate ) {
    $zone = new DateTimeZone('UTC');
    $currentDate = wp_date( 'M d, Y', null, $zone );
  } else {
    $currentDate = \Wcone\Date_Time_Map::getDateTime();
    $currentDate = $currentDate->format('M d, Y');
  }
  //
  return $currentDate;

}

function wcone_display_date( $date ) {
  $getDateFormat = get_option('date_format');
  $zone = new DateTimeZone('UTC');
  return wp_date( $getDateFormat, strtotime( $date ), $zone );
}

/**
 * [wcone_time_elapsed_string description]
 * time elapsed string
 * @param  [type]  $datetime [description]
 * @param  boolean $full     [description]
 * @return string            [description]
 */
function wcone_time_elapsed_string( $datetime, $full = false ) {
    
    $getCurrentDateTime = \Wcone\Date_Time_Map::getDateTime();
    $getCurrentDateTime->format( "Y-m-d h:i:s" );
    $getDateTimeDiff    = \Wcone\Date_Time_Map::getDateTime();

    //
    $dateTimeExplode = explode(' ', $datetime);
    $getOrderDate = $dateTimeExplode[0];
    $getOrderDate = explode('-', $getOrderDate);
    //
    $getOrderTime = $dateTimeExplode[1];
    $getOrderTime = explode(':', $getOrderTime);

    // Set order date and time
    $getDateTimeDiff->setDate( $getOrderDate[0], $getOrderDate[1], $getOrderDate[2] );
    $getDateTimeDiff->setTime( $getOrderTime[0], $getOrderTime[1], $getOrderTime[2] );

    $diff = $getCurrentDateTime->diff($getDateTimeDiff);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => esc_html__( 'year', 'wcone' ),
        'm' => esc_html__( 'month', 'wcone' ),
        'w' => esc_html__( 'week', 'wcone' ),
        'd' => esc_html__( 'day', 'wcone' ),
        'h' => esc_html__( 'hour', 'wcone' ),
        'i' => esc_html__( 'minute', 'wcone' ),
        's' => esc_html__( 'second', 'wcone' ),
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) .' '.esc_html__( 'ago', 'wcone' ) : esc_html__( 'just now', 'wcone' );
}

/**
 * [wcone_page_permission description]
 * @param  [type] $role [description]
 * @return url       [description]
 */
function wcone_page_permission( $role ) {

  $url = home_url('/');

  if( is_user_logged_in() ) {

    $user = wp_get_current_user();

    $roles = $user->roles;

    if( $roles[0] != $role ) {
      wp_safe_redirect( $url );
    }

  } else {
    wp_safe_redirect( $url );
    exit;

  }

}

/**
 * wcone_is_user_role
 * @return bool
 */
function wcone_is_user_role( $role ) {

  if( is_user_logged_in() ) {

    $user = wp_get_current_user();

    $roles = $user->roles;

    if( $roles[0] == $role ) {
      return true;
    } else {
      return false;
    }

  }

}

/**
 * wcone_is_multi_branch 
 * check is set multi branch
 * @return bool
 */

function wcone_is_multi_branch() {

  if( ! wcone_is_active_multi_branch() ) {
    return false;
  }

  $options = get_option('wcone_options');

  if( !empty( $options['brunch-type'] ) && $options['brunch-type'] == 'multi' ) {
    return true;
  } else {
    return false;
  }

}


/**
 * wcone_is_active_multi_branch 
 * check is set active multi branch
 * @return bool
 */

function wcone_is_active_multi_branch() {

  if( ! class_exists('WconeMultibranch') ) {
    return false;
  } else {
    return true;
  }

}

/**
 * wcone_currency_symbol_position
 * currency symbol position
 * @return 
 */

function wcone_currency_symbol_position( $price , $format = true ) {

  $currencyPos = get_option( 'woocommerce_currency_pos' );
  $currency   = get_woocommerce_currency_symbol();

  if( !$price ) { return; }

  /*  if( $format ) {

    $price = wcone_woo_custom_number_format( $price );
  }*/

  $getPrice = $currency.$price;

  if( $currencyPos != 'left' ) {

    switch( $currencyPos ) {

      case 'right':
        $getPrice =  $price.$currency;
      break;
      case 'left_space':
        $getPrice =  $currency.' '.$price;
      break;
      case 'right_space':
        $getPrice =  $price.' '.$currency;
      break;
      default :
        $getPrice = $currency.$price;
      break;

    }

  }

  return $getPrice;
  
}

/**
 * wcone_bootstrap_column_map
 * bootstrap grid column maping
 * @return 
 */

function wcone_bootstrap_column_map( $col ) {

  switch( $col ) {
    case '2' :
      $setCol = '6';
      break;
    case '3' :
      $setCol = '4';
      break;
    case '4' :
      $setCol = '3';
      break;
      default: 
        $setCol = '4';
      break;
  }

  return $setCol;

}

/**
 * wcone_woo_custom_number_format
 * custom number format decimal, thousand separator  and Number of decimals set 
 * @return 
 */

function wcone_woo_custom_number_format( $number ) {

  if( empty( $number ) ) {
    return;
  }
  
  $decimal_separator  = wc_get_price_decimal_separator();
  $thousand_separator = wc_get_price_thousand_separator();
  $decimals           = wc_get_price_decimals();

  return number_format( $number, $decimals, $decimal_separator, $thousand_separator);

}

/**
 * wcone_extra_option_price_filter
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function wcone_extra_option_price_filter( $data ) {

  $explodeData = explode(':', $data);
  $arrayEnd = end($explodeData);
  return preg_replace('/[^0-9-.]+/', '', $arrayEnd );
}

/**
 * [wcone_date_format description]
 * @param  string $format [description]
 * @param  [type] $date   [description]
 * @return string         [description]
 */
function wcone_date_format( $date, $format = '' ) {
  $format = !empty( $format ) ? $format : 'M-d-Y';
  return date( $format, strtotime( $date ) );
}

function wcone_is_location_type_address() {

  $options = get_option('wcone_options');

  return !empty( $options['location_type'] ) && $options['location_type'] == 'address' ? true : false;

}

/**
 * [wcone_time_format description]
 * @return string
 */
function wcone_time_format() {

  $options = get_option('wcone_options');

  $timeFormat = 'h:ia';
  if( !empty( $options['delivery-time-format'] ) && $options['delivery-time-format'] == '24' ) {
    $timeFormat = 'H:i';
  }
  return $timeFormat;

}
/**
 * Convert php date format to js date format 
 * @param  $format php date
 * @return string
 */
function wcone_datepicker_format($format) {

  $assoc = array(
      'Y' => 'yy',
      'y' => 'yy',
      'F' => 'MM',
      'm' => 'mm',
      'l' => 'DD',
      'd' => 'dd',
      'D' => 'D',
      'j' => 'd',
      'M' => 'M',
      'n' => 'm',
      'z' => 'o',
      'N' => '',
      'S' => '',
      'w' => '',
      'W' => '',
      't' => '',
      'L' => '',
      'o' => '',
      'a' => '',
      'A' => '',
      'B' => '',
      'g' => '',
      'G' => '',
      'h' => '',
      'H' => '',
      'i' => '',
      's' => '',
      'u' => ''
  );

  $keys = array_keys($assoc);

  $indeces = array_map(function($index) {
      return '{{' . $index . '}}';
  }, array_keys($keys));

  $format = str_replace($keys, $indeces, $format);

  return str_replace($indeces, $assoc, $format);

}

/**
 * [wcone_get_weekday description]
 * @return array
 */
function wcone_get_weekday() {

  return [
          '0' => esc_html__( 'Sunday', 'wcone' ),
          '1' => esc_html__( 'Monday', 'wcone' ),
          '2' => esc_html__( 'Tuesday', 'wcone' ),
          '3' => esc_html__( 'Wednesday', 'wcone' ),
          '4' => esc_html__( 'Thursday', 'wcone' ),
          '5' => esc_html__( 'Friday', 'wcone' ),
          '6' => esc_html__( 'Saturday', 'wcone' )
        ];

}

/**
 * [wcone_get_the_day description]
 * @return string
 */
function wcone_get_the_day( $number ) {
  $weekday = wcone_get_weekday();
  return !empty( $weekday[$number] ) ? $weekday[$number] : '';
}

/**
 * [wcone_get_day_number description]
 * @return [type] [description]
 */
function wcone_get_day_number( $dayName ) {
    $days = [
      'Sunday'    => 0,
      'Monday'    => 1,
      'Tuesday'   => 2,
      'Wednesday' => 3,
      'Thursday'  => 4,
      'Friday'    => 5,
      'Saturday'  => 6
    ];

  return $days[$dayName];
}
/**
 * [wcone_get_day_by_date description]
 * @param  [type] $date [description]
 * @return string
 */
function wcone_get_day_by_date( $date ) {

  $dayByDate = \Wcone\Date_Time_Map::getDay( $date );
  $dayNumber = wcone_get_day_number( $dayByDate );
  return wcone_get_the_day( $dayNumber );

}
/**
 * [wcone_display_day_by_date description]
 * @param  [type] $date [description]
 * @return [type]       [description]
 */
function wcone_display_day_by_date( $date ) {
  return wp_date( 'l', strtotime( $date ), null );
}

/**
 * [wcone_time_solt_options_html description]
 * @param  [type] $timeList [description]
 * @return [type]           [description]
 */
function wcone_time_solt_options_html( $timeList ) {

  if( !empty( $timeList ) ) {
    foreach ( $timeList as $time ) {

      $a = [ $time['times'], $time['slot_order_status'], $time['break_time'] ];
      $v = implode( ',', $a );

        echo '<option value="'.esc_html( $v ).'">'.esc_html( $time['times'].wcone_time_slot_status( $time ) ).'</option>';
    }
  }

}

/**
 * [wcone_time_slot_order_count description]
 * @param  string $date [description]
 * @param  string $time [description]
 * @return [type]       [description]
 */
function wcone_time_slot_order_count( $date = '', $time = '' ) {
  // 02/20/2021 10:30am
  
  if( $date && $time ) {
    $args = array(
      'limit'         => '-1',
      'date_created'  => esc_html( $date ),
      'pickup_time'   => esc_html( $time )
    );
    $orders = wc_get_orders( $args );
    return count( $orders );
  }
}
/**
 * [wcone_time_slot_status description]
 * @return [type] [description]
 */
function wcone_time_slot_status( $status ) {
  $text = Wcone\Inc\Text::getText();
  if( $status['slot_order_status'] == 'no' ) {
    return ' - '.$text['slot_full_text'];
  }

  if( $status['break_time'] == 'true' ) {
    return ' - '.$text['valid_break_time'];
  }

}

/**
 * [wcone_wc_before_cart_hook description]
 * @return [type] [description]
 */
function wcone_wc_before_cart_hook() {
  ob_start();
  do_action( 'woocommerce_before_cart' );
  return ob_get_clean();
}
