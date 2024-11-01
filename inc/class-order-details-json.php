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

class Order_Details_Json {

	/**
	 * [$order description]
	 * @var [object]
	 */
	private static $order;

	/**
	 * [$order_id description]
	 * @var [int]
	 */
	private static $orderID;

	/**
	 * [$trackingStatus description]
	 * @var [type]
	 */
	private static $trackingStatus;

	function __construct( $order_id ) {
		
		self::$orderID = $order_id;
		self::$trackingStatus = get_post_meta( absint( self::$orderID ), '_order_tracking_status', true );
		$order = wc_get_order( self::$orderID );
		self::$order = $order;
	}


	/**
	 * [getOrderData description]
	 * @return [array] [description]
	 */
	public function getOrderData() {

		$order = self::$order;
		$order_id = self::$orderID;

		// Fees
		$getFees = [];
        if( !empty( $order->get_total_fees() ) ){
            foreach( $order->get_items('fee') as $fee ) {
            	$getFees[] = [
            		'name' 	 => $fee->get_name(),
            		'amount' => esc_html( wcone_currency_symbol_position( $fee->get_total()  ) )
            	];
            }
        }

		//
		$orderData = [
			'order_id'		   => $order_id,
			'created_date'	   => wcone_display_date( $order->get_date_created()->format('M-d-Y') ),
			'created_time'	   => $order->get_date_created()->format(wcone_time_format()),
			'order_total_fees' => wcone_currency_symbol_position( wcone_woo_custom_number_format( $order->get_total_fees() ) ),
			'get_fees' 		   => $getFees,
			'order_shipping_total' => wcone_currency_symbol_position( $order->get_shipping_total() ),
			'order_total' 	   => wcone_currency_symbol_position( $order->get_total() ),
			'payment_method'   => $order->get_payment_method_title(),
			'pickup_time'	   => get_post_meta( absint( $order_id ) , '_pickup_time', true ),
			'delivery_date'	   => wcone_display_date( get_post_meta( absint( $order_id ) , '_delivery_date', true ) ),
			'delivery_type'	   => get_post_meta( absint( $order_id ) , '_delivery_type', true ),
			'tracking_status'  => self::$trackingStatus,
			'shop_address'	   => esc_html( self::shopAddress() ),
			'order_items'	   => self::getOrderItems(),
			'order_address'	   => self::getOrderBillingShippingDetails(),
			'delivery_boies'   => self::deliveryBoies(),
			'branch_list'	   => self::branchList(),
			'is_multi_branch'  => wcone_is_multi_branch(),
			'get_tax_totals'   => $order->get_tax_totals(),
			'discount_tax'      =>  $order->get_discount_tax(),
			'discount_display'  => $order->get_discount_to_display(),
			'discount_total'    => $order->get_discount_total(),
			'total_discount'    => $order->get_total_discount(),
			'used_coupons'      => $order->get_coupon_codes(),
			'item_total'      	=> $order->get_subtotal_to_display(),
			'customer_note'     => $order->get_customer_note(),
			'status_button'     => self::statusButton()
		];

		$getOrderData = apply_filters( 'wcone_order_data', $orderData, $order, $order_id );

		return json_encode( $getOrderData );

	}


	/**
	 * [getOrderItems description]
	 * @return {array} with order items data
	 */
	private static function getOrderItems() {

		$order = self::$order;

		$itemsData = [];

		foreach ( $order->get_items() as $item_id => $item ) {

			$itemMetaData = [];

			foreach( $item->get_meta_data() as $val ) {

				$data = $val->get_data();

				$itemMetaData[] = [
					'meta_key' 	 => str_replace(['pa_','-', ':'], ['',' ', ''], $data['key'] ),
					'meta_value' => $data['value']
				];

			}

			//
			$itemsData[] = [
				'item_name'			=> $item->get_name(),
				'item_qty'			=> $item->get_quantity(),
				'item_total_price' 	=> wcone_currency_symbol_position( wcone_woo_custom_number_format( $item->get_total() ) ),
				'item_meta_data' 	=> $itemMetaData
			];

		}

		return $itemsData;

	}

	/**
	 * [setOrderBillingShippingDetails description]
	 * @return {array} Order Billing Shipping Details
	 */
	
	private static function getOrderBillingShippingDetails() {

		$order = self::$order;

		$billingName  = $order->get_billing_first_name().' '.$order->get_billing_last_name();
		$shippingName = $order->get_shipping_first_name().' '.$order->get_shipping_last_name();
		$billingPhone = $order->get_billing_phone();

		$billingAddress = [
			$order->get_billing_address_1(),
			$order->get_billing_address_2(),
			$order->get_billing_city(),
			$order->get_billing_postcode()
		];

		$shippingAddress = [
			$order->get_shipping_address_1(),
			$order->get_shipping_address_2(),
			$order->get_shipping_city(),
			$order->get_shipping_postcode()
		];


		$filterBillingAddress  = array_filter( $billingAddress );
		$filterShippingAddress = array_filter( $shippingAddress );

		// Check Billing Address
		$getBillingAddress = '';
		if( !empty( $filterBillingAddress ) ) {
			$getBillingAddress = implode( ', ' , array_filter( $billingAddress ) );
		}

		// Check Shipping Address
		$getShippingAddress = '';
		if( !empty( $filterShippingAddress ) ) {
			$getShippingAddress = implode( ', ' , array_filter( $shippingAddress ) );
		}

		return [
			'billing_name' 		=> trim( $billingName ),
			'shipping_name' 	=> trim( $shippingName ),
			'billing_phone' 	=> $billingPhone,
			'billing_address' 	=> $getBillingAddress,
			'billing_address_1' => $order->get_billing_address_1(),
			'shipping_address'  => $getShippingAddress,
			'shipping_address_1' => $order->get_shipping_address_1()
		];

	}

	private static function getInvoiceData() {

		$order = self::$order;
		$order_id = self::$orderID;

		$order_id = $order->get_id();
		$paymentMethod = $order->get_payment_method_title();
		$pickup_time  = get_post_meta( absint( $order_id ) , '_pickup_time', true );
		$delivery_type  = get_post_meta( absint( $order_id ) , '_delivery_type', true );


		$itemsData = [];

		foreach ( $order->get_items() as $item_id => $item ) {

			$itemMetaData = [];

			foreach( $item->get_meta_data() as $val ) {

				$data = $val->get_data();

				$itemMetaData[] = [
					'meta_key' 	 => str_replace(['pa_','-', ':'], ['',' ', ''], $data['key'] ),
					'meta_value' => $data['value']
				];

			}

			//
			$itemsData[] = [
				'item_name'			=> $item->get_name(),
				'item_total_price' 	=> wcone_currency_symbol_position( $item->get_total() ),
				'item_meta_data' 	=> $itemMetaData
			];

		}
		


		return [

			'order-date' => $order->get_date_created()->format ('M-d-Y'),
			'address' => self::getOrderBillingShippingDetails(),

		];

	}
	

	public static function deliveryBoies() {

		$branch_id = '';

		$boies = wcone_get_branch_delivery_boy( $branch_id );
    	$asigned_boy = get_post_meta( self::$orderID, '_order_delivery_boy', true );

		return [
			'boies'			=> $boies,
			'asigned_boy' 	=> $asigned_boy
		];

	}

	/**
	 * [branchList description]
	 * @return [type] [description]
	 */
	public static function branchList() {

    	$asigned_branch = get_post_meta( self::$orderID, '_order_branch_id', true );

		return [
			'branches'			=> wcone_branch_list(),
			'asigned_branch' 	=> $asigned_branch
		];
	}

	public static function statusButton() {

		$status = self::$trackingStatus;


		$oc = $stc = $ac = $cc = $OWD = $DC = '';

		//
		if( $status == 'OC' ) {
		  $oc = 'status-active';
		  $stc = $ac = $cc = $OWD = $DC = 'fb-d-none';
		}

		// 
		if( $status == 'STC' ) {
		  $oc = 'fb-d-none';
		  $stc = 'status-active';
		}
		// 
		if( $status == 'AC' ) {
		  $oc = $stc = 'fb-d-none';
		  $ac = 'status-active';
		}
		// 
		if( $status == 'CC' ) {
		  $oc = $stc = $ac = 'fb-d-none';
		  $cc = 'status-active';
		}
		// 
		if( $status == 'OWD' ) {
		  $oc = $stc = $ac = $cc = 'fb-d-none';
		  $OWD = 'status-active';
		}
		// 
		if( $status == 'DC' ) {
		  $oc = $stc = $ac = $cc = $OWD = 'fb-d-none';
		  $DC = 'status-active';
		}


		// user role
		$isDeliveryBoy 			= !wcone_is_user_role('delivery_boy') ? true : false;
		$isBranchManagerAdmin 	= wcone_is_user_role('branch_manager') ? true : false;
		$isPackagingManagerAdmin 	= wcone_is_user_role('packaging_manager')  ? true : false;

		return [

			'is_not_delivery_boy' 	=> $isDeliveryBoy,
			'is_branch_manager'		=> $isBranchManagerAdmin,
			'is_packaging_manager'	=> $isPackagingManagerAdmin,
			'order_status'			=> $status,
			'status_class'			=> [ 'oc' => $oc, 'stc' => $stc, 'ac' => $ac, 'cc' => $cc, 'owd' => $OWD, 'dc' => $DC ]
		];
	}
	public static function shopAddress() {
		// get shop address
		$shopAddress = wcone_getOptionData('branch-location');
		return $shopAddress;
	}


}
