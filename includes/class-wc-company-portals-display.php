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
		add_filter( 'woocommerce_product_add_to_cart_url', array($this, 'add_company_portal_parameter') );
		add_filter( 'post_type_link', array($this, 'add_company_portal_parameter') );
		add_action( 'woocommerce_after_add_to_cart_button', array($this, 'display_hidden_portal_field') );
		add_filter( 'woocommerce_loop_add_to_cart_link', array($this, 'add_portal_data_attribute' ) ); 
						
	}
	
	/**
	 * Add View Portal Action
	*/
	public function add_view_portal_action( $actions, $company ) {
		
		if( $portal = wc_get_company_portal( $company ) ) {
			
			$actions['view_company_portal'] = array(
				'classes' => apply_filters('woocommerce_companies_view_company_portal_button_classes', array('button view-company-portal') ),
				'url' => get_term_link( $portal ),
				'text' => 'View Portal',
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
				
				echo wp_get_attachment_image( $thumbnail_id, 'thumbnail' );
				
			}
			
		}
		
	}
	
	/**
	 * Add Company Hidden Field to Login
	*/
	public function add_company_hidden_field_to_login() {
		
		if( isset( $_REQUEST['company'] ) ) {
				
			if( $portal = get_term_by('slug', $_REQUEST['company'], 'company_portal') ) {
				
				$name = 'redirect';
				$value = get_term_link( $portal );
				
				if( $value && ! is_wp_error($value) ) {
				
					wc_get_template( 'global/hidden-field.php', compact('name', 'value'), '', WC_Company_Portals()->plugin_path() . '/templates/' );
					
				}
				
			}
				
		}
		
	}
	
	/**
	 * Add Company Portal ID to add_to_cart url
	*/
	public function add_company_portal_parameter($link) {
		
		if( is_tax( 'company_portal') ) {
			
			return add_query_arg('company_portal_id', get_queried_object()->term_id, $link);
			
		}
		
		return $link;
		
	}
	
	/**
	 * Display Company Portal ID hidden field on single product form
	*/
	public function display_hidden_portal_field() {
		
		if( ! empty( $_REQUEST['company_portal_id'] ) ) {
			
			$name = 'company_portal_id';
			$value = $_REQUEST['company_portal_id'];
			
			wc_get_template( 'global/hidden-field.php', compact('name', 'value'), '', WC_Company_Portals()->plugin_path() . '/templates/' );
			
		}
		
	}
	
	/**
	 * Display Company Portal ID data attribute on single product add to cart form
	*/
	public function add_portal_data_attribute($button) {
		
		global $wc_cp_portal;
		
		if( $wc_cp_portal ) {
			
			$company_portal_id = $wc_cp_portal->term_id;
			
			$attribute = "data-company_portal_id='$company_portal_id'";
			
			$button = str_replace( 'data-product_id', $attribute . ' data-product_id', $button );
			
		}
		
		return $button;
		
	}

}