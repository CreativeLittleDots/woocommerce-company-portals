<?php
/**
 * WooCommerce Company Portals Admin.
 *
 * @class       WC_Company_Portals_Admin
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce Company Portals/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Company_Portals_Admin class.
 */
class WC_Company_Portals_Admin extends WC_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {

		// Classes
		include_once( 'class-wc-company-portals-admin-meta-boxes.php' );
		
		// Taxonomies
		include_once( 'class-wc-company-portals-admin-taxonomies.php' );
		
	}

}

return new WC_Company_Portals_Admin();
