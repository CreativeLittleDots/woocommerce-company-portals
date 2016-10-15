<?php
	
	add_filter( 'woocommerce_get_regular_price', 'woocommerce_company_portals_get_regular_price', 20, 2);
	
	function woocommerce_company_portals_get_regular_price($price, $product) {
		
		return woocommerce_company_portals_get_price($price, $product, 'regular');
		
	}

	add_filter( 'woocommerce_get_sale_price', 'woocommerce_company_portals_get_sale_price', 20, 2);
	
	function woocommerce_company_portals_get_sale_price($price, $product) {
		
		return woocommerce_company_portals_get_price($price, $product, 'sale');
		
	}
	
	add_filter( 'woocommerce_get_price', 'woocommerce_company_portals_get_price', 20, 2);
	
	function woocommerce_company_portals_get_price($price, $product, $type = '', $portal = null) {
		
		if( is_admin() ) {
			
			return $price;
			
		}
		
		if( is_user_logged_in() ) {

			global $current_user, $wc_cp_portal;
			
			if( $portal = $portal ? $portal : $wc_cp_portal ) {
				
				$product_price_for_portal = false;
				
				if( $type ) {
					
					$product_price_for_portal = wc_get_product_price_for_portal($product->id, $portal->term_id, $type);
					
				} else {
					
					$regular_price = wc_get_product_price_for_portal($product->id, $portal->term_id, 'regular');
					
					$sale_price = wc_get_product_price_for_portal($product->id, $portal->term_id, 'sale');
						
					$product_price_for_portal = $sale_price > 0 && $regular_price > $sale_price ? $sale_price : $regular_price;
					
				}
				
				if( $product_price_for_portal ) {
				
					$price = $product_price_for_portal;
					
				}
				
			}
			
		}
		
		return $price;
		
	}