<?php
/**
 * Portal Shortcode
 *
 * Shows the portal section where the customer can view company products
 *
 * @author 		Creative Little Dots
 * @category 	Shortcodes
 * @package 	WooCommerce Companies/Shortcodes/My_Companies
 * @version     1.0.0
 */
class WC_Shortcode_My_Portal {

	/**
	 * Get the shortcode content.
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		return WC_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		
		global $wp;

		if ( ! is_user_logged_in() ) {

			$message = apply_filters( 'woocommerce_my_portal_message', '' );

			if ( ! empty( $message ) ) {
				
				wc_add_notice( $message );
				
			}

			self::login_portal( ! empty( $wp->query_vars['company'] ) ? $wp->query_vars['company'] : false );

		} else {
			
			self::no_portal_found();
			
		}
		
	}
		
	public function login_portal( $company = false ) {
		
		$company = is_string( $company ) ? get_page_by_path($company, OBJECT, 'wc-company') : $company;
		
		wc_get_template('myportal/form-login.php', array(
			'current_user' 	=> get_user_by( 'id', get_current_user_id() ),
			'company' => wc_get_company( $company ),
		), '', WC_Company_Portals()->plugin_path() . '/templates/');
		
	}
	
	public function no_portal_found() {
		
		wc_get_template('myportal/no-portal-found.php', array(
			'current_user' 	=> get_user_by( 'id', get_current_user_id() ),
		), '', WC_Company_Portals()->plugin_path() . '/templates/');
		
	}
		
}
