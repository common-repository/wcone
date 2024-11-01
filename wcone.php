<?php
/**
 * Plugin Name:       wcOne
 * Plugin URI:        https://www.themelooks.com/blog/
 * Description:       Ultimate Addons For WooCommerce
 * Version:           1.0.1
 * Author:            ThemeLooks
 * Author URI:        https://themelooks.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wcone
 * Domain Path:       /languages
 * 
 */

/**
 * Define all constant
 *
 */

// Version constant
if( !defined( 'WCONE_VERSION' ) ) {
	define( 'WCONE_VERSION', '1.0.1' );
}

// Plugin dir path constant
if( !defined( 'WCONE_PRO_URL' ) ) {
	define( 'WCONE_PRO_URL', 'https://themelooks.com/wcone/' );
}
// Plugin dir path constant
if( !defined( 'WCONE_DIR_PATH' ) ) {
	define( 'WCONE_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}
// Plugin dir url constant
if( !defined( 'WCONE_DIR_URL' ) ) {
	define( 'WCONE_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}
// Plugin dir url constant
if( !defined( 'WCONE_DIR_ASSETS_URL' ) ) {
	define( 'WCONE_DIR_ASSETS_URL', trailingslashit( WCONE_DIR_URL.'assets' ) );
}
// Plugin dir admin assets url constant
if( !defined( 'WCONE_DIR_ADMIN_ASSETS_URL' ) ) {
	define( 'WCONE_DIR_ADMIN_ASSETS_URL', trailingslashit( WCONE_DIR_URL . 'admin/assets' ) );
}
// Admin dir path
if( !defined( 'WCONE_DIR_ADMIN' ) ) {
	define( 'WCONE_DIR_ADMIN', trailingslashit( WCONE_DIR_PATH.'admin' ) );
}
// Inc dir path
if( !defined( 'WCONE_DIR_INC' ) ) {
	define( 'WCONE_DIR_INC', trailingslashit( WCONE_DIR_PATH.'inc' ) );
}

final class Wcone {

	private static $instance = null;

	function __construct() {

		add_action( 'init', [ $this, 'wcone_load_textdomain' ] );
		register_deactivation_hook( __FILE__, [ $this, 'wcone_plugin_deactivate' ] );
		register_activation_hook( __FILE__, [ $this, 'wcone_plugin_activate' ] );
		add_action( 'plugins_loaded', [ $this, 'wcone_is_woocommerce_activated'] );

	}

	public static function getInstance() {
		
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load plugin textdomain.
	 */
	public function wcone_load_textdomain() {
	    load_plugin_textdomain( 'wcone', false, WCONE_DIR_PATH . 'languages' ); 
	}

	/**
	 * Check WooCommerce is activated or not
	 * 
	 */
	public function wcone_is_woocommerce_activated() {

		if ( class_exists( 'woocommerce' ) ) {
			require_once( WCONE_DIR_PATH.'wcone-init.php' );
		} else {
			add_action( 'admin_notices', [ $this, 'wcone_activation_admin_notice' ] );
		}

	}

	/**
	 * wcone_activation_admin_notice description
	 * 
	 * If wooocommerce plugin not active 
	 * show the admin notification to active woocommerce plugin 
	 * 
	 * @return 
	 */
	public function wcone_activation_admin_notice() {
	    $url = "https://wordpress.org/plugins/woocommerce/";
	    ?>
	    <div class="notice notice-error is-dismissible">
	        <h4><?php echo sprintf( esc_html__( 'Wcone requires the WooCommerce plugin to be installed and active. You can download %s woocommerce %s here. Thanks.', 'wcone' ), '<a href="'.esc_url( $url ).'" target="_blank">','</a>' ); ?></h4>
	    </div>
	    <?php
	}


	/**
	 * wcone_plugin_activate
	 * @return 
	 */
	public function wcone_plugin_activate() {

		// Default options set
		$defaultOption = array(
			"cart-modal-style"	=> 'canvas',
			"checkout-delivery-option" => 'yes',
			"delivery-options" 	 => 'all',
			"pickup-time-switch" => 'yes',
		);

		update_option( 'wcone_options', $defaultOption );

	}
	
	/**
	 * wcone_plugin_deactivate 
	 * @return 
	 */
	public function wcone_plugin_deactivate() {
		//
		delete_option('wcone_options');
	}
	
}

// Init Wcone class
Wcone::getInstance();