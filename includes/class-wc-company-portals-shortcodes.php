<?php
/**
 * WC_Companies_Shortcodes class.
 *
 * @class 		WC_Company_Portals_Shortcodes
 * @version		1.0.0
 * @package		WooCommerce Company Portals/Classes
 * @category	Class
 * @author 		Creative Little Dots
 */
class WC_Company_Portals_Shortcodes extends WC_Shortcodes {

	/**
	 * Init shortcodes
	 */
	public static function init() {
		// Define shortcodes
		$shortcodes = array(
			'woocommerce_my_portal'     => __CLASS__ . '::my_portal',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}
	
	/**
	 * My Portal shortcode.
	 *
	 * @return string
	 */
	public static function my_portal() {
		return self::shortcode_wrapper( array( 'WC_Shortcode_My_Portal', 'output' ) );
	}
	
}
