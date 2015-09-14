<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WooCommerce WC_Company_Portals_Display
 *
 *
 * @class 		WC_Company_Portals_Display
 * @version		1.0.0
 * @package		WooCommerce Company Portals/Classes
 * @category	Class
 * @author 		Creative Little Dots
 */
class WC_Company_Portals_Display {
	
	/**
	 * Hook in methods
	 */
	public function __construct() {

		add_filter( 'woocommerce_companies_company_actions', array($this, 'add_view_portal_action'), 10, 2 );
						
	}
	
	/**
	 * Add View Portal Action
	*/
	public function add_view_portal_action( $actions, $company ) {
		
		$actions['view_company_portal'] = array(
			'classes' => apply_filters('woocommerce_companies_view_company_portal_button_classes', array('button view-company-portal') ),
			'url' => get_term_link( wc_get_company_portal( $company ), 'company_portal' ),
			'text' => 'View portal',
		); 
		
		return $actions;
		
	}

}