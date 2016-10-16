<?php
/*
* Plugin Name: WooCommerce Company Portals
* Description: Extends WooCommerce Companies to enable company specific product archives and prices
* Version: 1.0.0
* Author: Creative Little Dots
* Author URI: http://creativelittledots.co.uk
*
* Text Domain: woocommerce-company-portals
* Domain Path: /languages/
*
* Requires at least: 3.8
* Tested up to: 4.1.1
*
* Copyright: © 2009-2015 Creative Little Dots
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Company_Portals {
	
	/**
	 * @var string
	 */
	public $version 	= '1.0.0';
	
	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;
	
	/**
	 * Main WooCommerce Instance
	 *
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @since 2.1
	 * @static
	 * @see WC()
	 * @return WooCommerce - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	
	/**
	 * Define WC Companies Constants
	 */
	private function define_constants() {

		$this->define( 'WC_COMPANY_PORTALS_PLUGIN_FILE', __FILE__ );
		
	}
	
	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Cloning is forbidden.
	 * @since 2.1
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 2.1
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}
	
	public function __construct() {
    	
    	if( class_exists('WC_Companies') ) {
		
    		add_action( 'admin_init', array( $this, 'activate' ) );
    		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    		
    		add_action( 'init', array( $this, 'init' ), 2 );
    		add_action( 'widgets_init', array($this, 'register_widgets') );
    		add_action( 'init', array( 'WC_Company_Portals_Shortcodes', 'init' ), 3 );
    		
        }
		
	}
	
	public function register_widgets() {
		include_once( 'includes/widgets/class-wc-widget-company-portals.php' );
	}
	
	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
	
	public function activate() {
		
		global $wpdb;

		$version = get_option( 'woocommerce_company_portals', false );
		
		if ( $version == false ) {
			
			add_option( 'woocommerce_company_portals', $this->version );

			// Update from previous versions

			// delete old option
			delete_option( 'woocommerce_company_portals_active' );
				
		} elseif ( version_compare( $version, $this->version, '<' ) ) {

			update_option( 'woocommerce_company_portals', $this->version );
		}

	}
	
	/**
	 * Deactivate extension.
	 * @return void
	 */
	public function deactivate() {

		delete_option( 'woocommerce_company_portals' );
		
	}
	
	public function plugin_url() {
		
		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		
	}

	public function plugin_path() {
		
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
		
	}
	
	public function includes() {
		
		include_once( 'includes/class-wc-company-portals-autoloader.php' );
		
		include_once( 'includes/wc-portal-functions.php' );
		include_once( 'includes/wc-product-functions.php' );
		include_once( 'includes/wc-user-functions.php' );
		include_once( 'includes/wc-checkout-functions.php' );
		
		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-wc-company-portals-admin.php' );
		}
		
		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}
		
		$this->query = include( 'includes/class-wc-company-portals-query.php' );        // The main query class

		include_once( 'includes/class-wc-company-portals-taxonomies.php' );   // Registers post types		
		
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/class-wc-company-portals-display.php' );               		// Display class
		include_once( 'includes/class-wc-company-portals-shortcodes.php' );                   // Shortcodes class
	}

	public function init() {
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		// Check if WooCommerce is active
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			
			return;
			
		}
		
		$this->define_constants();
		$this->includes();
		
		// Set up localisation
		$this->load_plugin_textdomain();
		
		if ( $this->is_request( 'frontend' ) && class_exists('WC_Company_Portals_Display') ) {
			$this->display = new WC_Company_Portals_Display();		
		}
		
		if ( ( $this->is_request( 'frontend' ) || $this->is_request( 'cron' ) ) && class_exists('WC_Company_Portals_Cart') ) {
			$this->cart = new WC_Company_Portals_Cart();
		}
		
	}
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Admin Locales are found in:
	 * 		- WP_LANG_DIR/woocommerce-company-portals/woocommerce-company-portals-admin-LOCALE.mo
	 * 		- WP_LANG_DIR/plugins/woocommerce-company-portals-admin-LOCALE.mo
	 *
	 * Frontend/global Locales found in:
	 * 		- WP_LANG_DIR/woocommerce/woocommerce-company-portals-LOCALE.mo
	 * 	 	- woocommerce/i18n/languages/woocommerce-company-portals-LOCALE.mo (which if not found falls back to:)
	 * 	 	- WP_LANG_DIR/plugins/woocommerce-company-portals-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce' );

		if ( $this->is_request( 'admin' ) ) {
			load_textdomain( 'woocommerce-company-portals', WP_LANG_DIR . '/woocommerce-company-portals/woocommerce-company-portals-admin-' . $locale . '.mo' );
			load_textdomain( 'woocommerce-company-portals', WP_LANG_DIR . '/plugins/woocommerce-company-portals-admin-' . $locale . '.mo' );
		}

		load_textdomain( 'woocommerce', WP_LANG_DIR . '/woocommerce-company-portals/woocommerce-company-portals-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n/languages" );
	}	
	
}

function WC_Company_Portals() {
	return WC_Company_Portals::instance();
}

$GLOBALS[ 'woocommerce_company_portals' ] = WC_Company_Portals();