<?php
namespace Wcone;
/**
 * Wcone admin class
 *
 * @package     Wcone
 * @author      ThemeLooks
 * @copyright   2020 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Admin') ) {
	class Admin {

		private static $instance = null;

		function __construct() {
			
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_scripts' ] );
			self::include_file();
		}
		public static function getInstance() {

			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		public static function admin_scripts( $hooks ) {

			if( strpos( $hooks, 'wcone' ) !== false || ( $hooks == 'edit-tags.php' || $hooks == 'term.php' ) ) {
				
				$getText = \Wcone\Inc\Text::getText();
				$getDateFormat = get_option('date_format');
				$pluginDirUrl = plugin_dir_url( __FILE__ );
				// WP Admin branch order page
				$isBranchOrder = false;
				$getAdminSlug = strstr( $hooks, 'wcone-branch-order' );
				
				if( $getAdminSlug == 'wcone-branch-order' ) {
					$isBranchOrder = true;
				}

				$options = get_option('wcone_options');
	    		$locationType = !empty( $options['location_type'] ) ? $options['location_type'] : '';
				$timeFormat           = !empty( $options['delivery-time-format'] ) ? $options['delivery-time-format'] : '12';
				
				// 
				wp_enqueue_media();
				// Add the color picker css file       
	        	wp_enqueue_style( 'wp-color-picker' );

	        	if( !empty( $options['location_type'] ) && 'address' == $options['location_type'] ) {

	        		if( $hooks == 'toplevel_page_wcone' || $hooks == 'post.php' || $hooks == 'post-new.php' ) {

			        wp_enqueue_script( 'googleapis-place','//maps.googleapis.com/maps/api/js?key='.\Wcone\Inc\Google_API::getApiKey().'&callback=initMap&libraries=places&v=weekly', array('location'), '1.0.0', true );
			        wp_enqueue_script( 'location', WCONE_DIR_URL.'assets/js/location.js', array('jquery' ), '1.0.0', true );

			        }

			    }

				wp_enqueue_style( 'datatables-admin', $pluginDirUrl. 'assets/datatables.css', array(), '1.0.0', false );
				wp_enqueue_style( 'font-awesome', $pluginDirUrl. 'assets/font-awesome.min.css', array(), '5.13.0', false );
				wp_enqueue_style( 'mdtimepicker', $pluginDirUrl. 'assets/mdtimepicker.css', array(), '1.0.0', false );
				wp_enqueue_style( 'wcone-admin', $pluginDirUrl. 'assets/admin.css', array(), '1.0.0', false );
				wp_enqueue_script( 'wcone-print', $pluginDirUrl. 'assets/jQuery.print.js', array( 'jquery' ), '1.0.0', true );
				wp_enqueue_script( 'mdtimepicker', $pluginDirUrl. 'assets/mdtimepicker.min.js', array( 'jquery' ), '1.0.0', true );
				wp_enqueue_script( 'datatables-admin', $pluginDirUrl. 'assets/datatables.js', array( 'jquery' ), '1.0.0', true );
				wp_enqueue_script( 'wcone-admin', $pluginDirUrl. 'assets/admin.js', array('jquery', 'jquery-ui-datepicker', 'wp-color-picker'), '1.0.0', true );
				
				wp_localize_script(
					'wcone-admin', 
					'adminWconeobj', 
					array(
						"ajaxurl"			=> admin_url('admin-ajax.php'),
						"currency"			=> get_woocommerce_currency_symbol(),
						'datepicker_format' => wcone_datepicker_format( esc_html( $getDateFormat ) ),
						"currency_pos"		=> get_option( 'woocommerce_currency_pos' ),
						'get_text'              => $getText,
						'location_type'			=> esc_html( $locationType ),
						'time_format' => $timeFormat
					) 
				);

			} // End $hook Check

		}
		public static function include_file() {

			/**
			 * Include files
			 *
			 */ 
			require_once( WCONE_DIR_ADMIN.'inc/settings-fields/class-settings-fields.php' );
			require_once( WCONE_DIR_ADMIN.'inc/class-admin-menu.php' );
			require_once( WCONE_DIR_ADMIN.'inc/admin-template.php' );

		}

	}

	Admin::getInstance();
}
