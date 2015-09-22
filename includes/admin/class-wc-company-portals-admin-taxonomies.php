<?php
/**
 * Taxonomies Admin
 *
 * @author      Creative Little Dots
 * @category    Admin
 * @package     WooCommerce Company Portals/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Company_Portals_Admin_Taxonomies' ) ) :

/**
 * WC_Company_Portals_Admin_Taxonomies Class
 *
 * Handles the taxonomy functionality.
 */
class WC_Company_Portals_Admin_Taxonomies extends WC_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter('post_row_actions', array($this, 'generate_company_portal_action_link'), 10, 2);
		add_action('admin_action_generate_company_portal', array($this, 'generate_company_portal') );
		
		// Add form
		add_action( 'company_portal_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'company_portal_edit_form_fields', array( $this, 'edit_category_fields' ), 10 );
		
		// Add columns
		add_filter( 'manage_edit-company_portal_columns', array( $this, 'product_cat_columns' ) );
		add_filter( 'manage_company_portal_custom_column', array( $this, 'product_cat_column' ), 10, 3 );
		
	}
	
	/**
	 * Generate Company Portal Action Link
	 */
	public function generate_company_portal_action_link($actions, $post) {
		
		if ( $post->post_type == "wc-company" ) {
			
			if( $company = wc_get_company($post) ) {
				
				if( ! $company->portal_id ) { 
					
					$actions['generate_company_portal'] = '<a href="' . admin_url('admin.php?action=generate_company_portal&company_id=' . $post->ID) . '">Generate Company Portal</a>';
					
				}
				
			}
			
		}
		
		return $actions;
		
	}
	
	/**
	 * Generate Company Portal Action
	 */
	public function generate_company_portal() {
		
		if( isset( $_REQUEST['company_id'] ) ) {
			
			if( $company = wc_get_company( $_REQUEST['company_id']) ) {
				
				if( ! $company->portal_id && ! term_exists( $company->slug, 'company_portal' ) ) {
				
					if($portal_id = wp_insert_term(
					  $company->get_title(), // the term 
					  'company_portal', // the taxonomy
					  array(
					    'slug' => $company->slug,
					  )
					)) {
						
						update_post_meta($company->id, '_portal_id', $portal_id['term_id']);

					}
					
				}
				
			}
			
		}
		
		wp_redirect( admin_url( 'edit-tags.php?taxonomy=company_portal&post_type=product' ) );
		
	}
			
}

endif;

new WC_Company_Portals_Admin_Taxonomies();
