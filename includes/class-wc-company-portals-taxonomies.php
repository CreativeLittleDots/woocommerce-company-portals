<?php
/**
 * Taxonomies
 *
 * Registers taxonomies
 *
 * @class       WC_Company_Portals_Taxonomies
 * @version     1.0.0
 * @package     WooCommerce Company Portals/Classes/Taxonomies
 * @category    Class
 * @author      WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Post_types Class
 */
class WC_Company_Portal_Taxonomies {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'wp', array( __CLASS__, 'redirect_if_not_company_member') );
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {

		do_action( 'woocommerce_company_portals_register_taxonomy' );

		register_taxonomy( 'company_portal',
			apply_filters( 'woocommerce_company_portals_taxonomy_objects_company_portal', array( 'product' ) ),
			apply_filters( 'woocommerce_company_portals_taxonomy_args_company_portal', array(
				'hierarchical'          => true,
				'update_count_callback' => '_wc_term_recount',
				'label'                 => __( 'Company Portals', 'woocommerce' ),
				'labels' => array(
						'name'              => __( 'Company Portals', 'woocommerce' ),
						'singular_name'     => __( 'Company Portal', 'woocommerce' ),
						'menu_name'         => _x( 'Portals', 'Admin menu name', 'woocommerce' ),
						'search_items'      => __( 'Search Company Portals', 'woocommerce' ),
						'all_items'         => __( 'All Company Portals', 'woocommerce' ),
						'parent_item'       => __( 'Parent Company Portal', 'woocommerce' ),
						'parent_item_colon' => __( 'Parent Company Portal:', 'woocommerce' ),
						'edit_item'         => __( 'Edit Company Portal', 'woocommerce' ),
						'update_item'       => __( 'Update Company Portal', 'woocommerce' ),
						'add_new_item'      => __( 'Add New Company Portal', 'woocommerce' ),
						'new_item_name'     => __( 'New Company Portal Name', 'woocommerce' )
					),
				'show_ui'               => true,
				'query_var'             => true,
				'capabilities'          => array(
					'manage_terms' => 'manage_product_terms',
					'edit_terms'   => 'edit_product_terms',
					'delete_terms' => 'delete_product_terms',
					'assign_terms' => 'assign_product_terms',
				),
				'rewrite'               => array(
					'slug'         => _x( 'company-portal', 'slug', 'woocommerce-company-portals' ),
					'with_front'   => false,
					'hierarchical' => true,
				),
			) )
		);
	
		do_action( 'woocommerce_company_portals_after_register_taxonomy' );

	}
	
	/**
	* Redirect to homepage if user is not a member of the company
	*/
	public function redirect_if_not_company_member() {
		
		if( is_tax('company_portal') ) {
			
			if( ! is_user_logged_in() ) {
				
				wp_redirect( site_url() );
				
			}
			
			if( current_user_can('manage_options') ) {
				
				return;
				
			}
			
			$portal = wc_get_portal_company( get_queried_object() );
			
			global $current_user;
			
			if( ! $company || ! $current_user->companies || ! is_array($current_user->companies) || ! in_array($company->id, $current_user->companies) ) {
				
				wp_redirect( site_url() );
				
			}
			
		}
		
	}
	
}

WC_Company_Portal_Taxonomies::init();
