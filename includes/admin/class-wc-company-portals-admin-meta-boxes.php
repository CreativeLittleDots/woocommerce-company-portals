<?php
/**
 * WooCommerce Company Portal Meta Boxes
 *
 * Sets up the write panels used by products
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce Company Portal/Admin/Meta Boxes
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Admin_Meta_Boxes
 */
class WC_Company_Portals_Admin_Meta_Boxes  {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		include_once('meta-boxes/class-wc-meta-box-product-portal-prices.php');
		
	}
	
}

new WC_Company_Portals_Admin_Meta_Boxes();