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
		add_action( 'woocommerce_login_form_start', array($this, 'add_company_logo_to_login') );
		add_action( 'woocommerce_login_form_end', array($this, 'add_company_hidden_field_to_login') );
						
	}
	
	/**
	 * Add View Portal Action
	*/
	public function add_view_portal_action( $actions, $company ) {
		
		if( $portal = wc_get_company_portal( $company ) ) {
			
			$actions['view_company_portal'] = array(
				'classes' => apply_filters('woocommerce_companies_view_company_portal_button_classes', array('button view-company-portal') ),
				'url' => get_term_link( $portal ),
				'text' => 'View portal',
			); 
			
		}
		
		return $actions;
		
	}
	
	/**
	 * Add Company Logo to Login
	*/
	public function add_company_logo_to_login() {
		
		if( isset( $_REQUEST['company'] ) ) {
				
			if( $portal = get_term_by('slug', $_REQUEST['company'], 'company_portal') ) {
				
				$thumbnail_id = get_woocommerce_term_meta( $portal->term_id, 'thumbnail_id', true );
				
				$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
				
				echo '<img src="' . $image[0] . '" />';
				
			}
			
		}
		
	}
	
	/**
	 * Add Company Hidden Field to Login
	*/
	public function add_company_hidden_field_to_login() {
		
		if( isset( $_REQUEST['company'] ) ) {
				
			if( $portal = get_term_by('slug', $_REQUEST['company'], 'company_portal') ) {
				
				echo '<input type="hidden" name="redirect" value="' . get_term_link( $portal ) . '" />';
				
			}
				
		}
		
	}

}