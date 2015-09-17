<?php
/**
 * Contains the query functions for WooCommerce Company Portals which alter the front-end post queries and loops.
 *
 * @class 		WC_Company_Portals_Query
 * @version		1.0.0
 * @package		WooCommerce Company Portals/Classes
 * @category	Class
 * @author 		Creatove Little Dots
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Company_Portals_Query' ) ) :

/**
 * WC_Company_Portals_Query Class
 */
class WC_Company_Portals_Query {


	public function __construct() {
		
		add_action( 'init', array($this, 'portal_rewrite_rules'), 20 );
		
	}
	
	public function portal_rewrite_rules() {
			
		if( is_user_logged_in() ) {
			
			global $wpdb, $current_user;
			
			add_rewrite_rule('my-portal/([^/]*)?','index.php?company_portal=$matches[1]','top');
			
			if( $current_user->primary_company ) {
				
				if( $company = $wpdb->get_var("SELECT post_name FROM {$wpdb->posts} WHERE ID = {$current_user->primary_company}") ) {
				
					add_rewrite_rule('my-portal?','index.php?company_portal='.$company,'top');
					
				}
				
			}
			
		}
			
	}

}

endif;

return new WC_Company_Portals_Query();
