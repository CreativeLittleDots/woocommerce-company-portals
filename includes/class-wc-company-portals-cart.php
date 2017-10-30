<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WooCommerce WC_Company_Portals_Cart
 *
 *
 * @class 		WC_Company_Portals_Cart
 * @version		1.0.0
 * @package		WooCommerce Company Portals/Classes
 * @category	Class
 * @author 		Creative Little Dots
 */
class WC_Company_Portals_Cart {
	
	/**
	 * Hook in methods
	 */
	public function __construct() {

		// Modify cart item data for portal price
		add_filter( 'woocommerce_add_cart_item', array( $this, 'wc_cp_add_cart_item_filter' ), 10, 2 );
		
		add_filter( 'woocommerce_get_item_data', array($this, 'wc_cp_display_cart_item_data'), 10, 2 );

		// Preserve data in cart
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'wc_cp_get_cart_data_from_session' ), 10, 2 );
		
		add_filter( 'woocommerce_product_get_price', array($this, 'wc_cp_get_price'), 10, 2 );
						
	}
	
	public function wc_cp_display_cart_item_data($item_data, $cart_item) {
		
		if( ! empty( $cart_item['company']['portal_id'] ) ) {
			
			foreach ( $cart_item['variation'] as $name => $value ) {

                if ( '' === $value )
                    continue;

                $taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

                // If this is a term slug, get the term's nice name
                if ( taxonomy_exists( $taxonomy ) ) {
                    $term = get_term_by( 'slug', $value, $taxonomy );
                    if ( ! is_wp_error( $term ) && $term && $term->name ) {
                        $value = $term->name;
                    }
                    $label = wc_attribute_label( $taxonomy );

                // If this is a custom option slug, get the options name
                } else {
                    $value              = apply_filters( 'woocommerce_variation_option_name', $value );
                    $product_attributes = $cart_item['data']->get_attributes();
                    if ( isset( $product_attributes[ str_replace( 'attribute_', '', $name ) ] ) ) {
                        $label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $name ) ]['name'] );
                    } else {
                        $label = $name;
                    }
                }

                $item_data[] = array(
                    'key'   => $label,
                    'value' => $value
                );
            }	
			
		}
		
		return $item_data;
		
	}
	
	/**
	 * Modifies cart item data
	 *
	 * @param  array $cart_item
	 * @param  string $cart_item_key
	 * @return array
	 */
	public function wc_cp_add_cart_item_filter( $cart_item, $cart_item_key ) {
		
		if( ! empty( $_REQUEST['company_portal_id'] ) ) { 
				
			$cart_item['company']['portal_id'] = $_REQUEST['company_portal_id'];
			
		} 

		if( ! empty( $cart_item['company']['portal_id'] ) ) {
			
			$portal = get_term_by( 'id', $cart_item['company']['portal_id'], 'company_portal' );
			
			if( $portal && ! is_wp_error( $portal ) ) {
			
				//$cart_item['data']->variation_id = 999;
				
				$cart_item['company']['price'] = ! empty( $cart_item['company']['price'] ) ? $cart_item['company']['price'] : woocommerce_company_portals_get_price($cart_item['data']->get_price(), $cart_item['data'], null, $portal);
				
				$cart_item['variation']['Company'] = ! empty( $cart_item['variation']['Company'] ) ? $cart_item['variation']['Company'] : $portal->name;
				
			}
			
		}	

		return $cart_item;

	}
	
	/**
	 * Retrieve company portal price
	 *
	 * @param  float 	$price
	 * @param  object 	$product
	 */
	public function wc_cp_get_price($price, $product) {
		
		if( ( is_singular( 'product' ) || is_post_type_archive( 'product' ) ) && in_the_loop() ) {
			
			return $price;
			
		}
		
		if( WC()->cart ) {
		
			foreach(WC()->cart->cart_contents as $item) {
			
				if( $product->get_id() == $item['data']->get_id() ) {
					
					$price = ! empty( $item['company']['price'] ) ? $item['company']['price'] : $price;
					
				}
				
			}
			
		}
		
		return $price;
		
	}

	/**
	 * Load all company-related session data.
	 *
	 * @param  array 	$cart_item
	 * @param  array 	$item_session_values
	 * @return array	$cart_item
	 */
	public function wc_cp_get_cart_data_from_session( $cart_item, $item_session_values ) {
		
		$cart_item = $this->wc_cp_add_cart_item_filter( $cart_item, null );

		return $cart_item;
	}
	
}