<?php
namespace Wcone;

/**
 * wcone admin class
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Admin_Menu') ) {
	class Admin_Menu {
		
		private static $instance = null;

		function __construct() {
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu_page' ) );
			add_action( 'admin_init', array( __CLASS__, 'page_settings_init' ) );
		}

		public static function getInstance() {
			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function admin_menu_page() {

			// add top level menu page
			add_menu_page(
				esc_html__( 'Wcone Settings', 'wcone' ),
				esc_html__( 'Wcone', 'wcone' ),
				'manage_options',
				'wcone',
				array( __CLASS__, 'admin_view' ),
				WCONE_DIR_ADMIN_ASSETS_URL.'menu-icon.png'
			);
			add_submenu_page( 'wcone', esc_html__( 'Wcone Settings', 'wcone' ), esc_html__( 'Settings', 'wcone' ),'manage_options', 'wcone');
			do_action('wcone_admin_menu');
			add_submenu_page(
		        'wcone',
		        esc_html__( 'Order Manage', 'wcone' ), //page title
		        esc_html__( 'Orders', 'wcone' ), //menu title
		        'manage_options', //capability,
		        'wcone-branch-order',//menu slug
		        array( __CLASS__, 'branch_order_submenu_page' ) //callback function
		        
		    );
		    add_submenu_page(
            'wcone',
            esc_html__( 'Recommended Plugins', 'wcone' ), //page title
            esc_html__( 'Recommended Plugins', 'wcone' ), //menu title
            'manage_options', //capability,
            'wcone-recommended-plugin',//menu slug
            array( __CLASS__, 'recommended_plugin_submenu_page' ) //callback function
        	);
		}
		public static function recommended_plugin_submenu_page() {
	        echo '<div class="dl-main-wrapper" style="margin-top: 50px;">';
	            \Wcone\Orgaddons\Org_Addons::getOrgItems();
	        echo '</div>';
    	}
		public static function admin_view() {
			$Admin_Templates = new Admin_Templates_Map();
			$Admin_Templates->admin_page_init();
		}
		public static function page_settings_init() {
			register_setting(
	            'wcone_settings_option_group', // Option group
	            'wcone_options' // Option name
	        );  
		}

		public static function branch_order_submenu_page() {
			echo '<div class="admin-promo-wrapper"><div class="fbl-overlay"><div class="fbl-promo-inner"><h3>Order management system is a pro version features </h3><a href="'.esc_url( WCONE_PRO_URL ).'" class="button button-primary fbl-buy" target="_blank">Buy Now</a></div></div><img src="'.WCONE_DIR_ASSETS_URL.'img/order-admin.png"></div>';
		}

	}

	Admin_Menu::getInstance();
}