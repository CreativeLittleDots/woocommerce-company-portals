<?php
/**
 * WooCommerce Company Portal Product Meta Boxes
 *
 * Sets up the write panels used by products
 *
 * @author      Creative Little Dots
 * @category    Admin
 * @package     WooCommerce Company Portal/Admin/Meta Boxes
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Company_Portals_Meta_Box_Product_Data
 */
class WC_Company_Portals_Meta_Box_Product_Data  {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Product Data Filter Add Portal Prices
		add_filter( 'woocommerce_product_data_tabs', array($this, 'portal_prices_data_tab') );
		add_action( 'woocommerce_product_data_panels', array($this, 'portal_prices_html') );
		add_action( 'save_post_product', array($this, 'save_portal_prices'), 10, 2 );
		add_filter( 'woocommerce_companies_admin_company_fields', array($this, 'add_portal_field') );
		
	}
	
	/**
	 * Add Product Price Data Tab
	 */
	public function portal_prices_data_tab($tabs) {
		
		$tabs['portal_prices'] = array(
			'label'  => __( 'Portal Prices', 'woocommerce-company-portals' ),
			'target' => 'portal_prices_product_data',
		);
		
		return $tabs;

	}
	
	/**
	 * Add Product Price HTML
	 */
	public function portal_prices_html() {
		
		$product = wc_get_product( $_REQUEST['post'] );
		
		$portal_prices = $product->portal_prices && is_array( $product->portal_prices ) ? $product->portal_prices : array();
		
		$portals = wp_get_object_terms( $product->id, 'company_portal' );
		
		ob_start();
		
		include('views/html-product-portal-prices.php');
		
		$html = ob_get_contents();
		
		ob_end_clean();
		
		echo $html;
		
	}
	
	/**
	 * Save Portal Prices
	*/
	public function save_portal_prices($post_id, $post) {
		
		if( isset($_POST['portal_prices']) ) {
			
			update_post_meta($post_id, '_portal_prices', $_POST['portal_prices']);
			
		}
		
	}
	
	/**
	 * Add Portal Field to companies
	*/
	public function add_portal_field($fields) {
		
		$terms = get_terms('company_portal', array('hide_empty' => false));
		
		$portals = array(0 => 'None');
		
		foreach($terms as $term) {
			
			$portals[$term->term_id] = $term->name;
			
		}
		
		$fields['portal_id'] = array(
			'label' => __('Company Portal', 'woocommerce'),
			'type' => 'select',
			'options' =>  $portals,
			'input_class' => array('widefat', 'chosen'),
			'placeholder' => __('Please choose the portal for this company'),
			'public' => false
		);
		
		return $fields;
		
	}
	
}

new WC_Company_Portals_Meta_Box_Product_Data();