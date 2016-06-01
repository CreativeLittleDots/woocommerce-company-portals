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
	
	function woocommerce_company_portals_get_price($price, $product, $type = '') {
		
		if( is_admin() ) {
			
			return $price;
			
		}
		
		if( is_user_logged_in() ) {

			global $current_user;
			
			if( ! empty( $GLOBALS['company_portals_company'] ) && $company = $GLOBALS['company_portals_company'] ) {
				
				$product_price_for_company = false;
				
				if( $type ) {
					
					$product_price_for_company = wc_get_product_price_for_company($product->id, $company->id, $type);
					
				} else {
					
					$regular_price = wc_get_product_price_for_company($product->id, $company->id, 'regular');
					
					$sale_price = wc_get_product_price_for_company($product->id, $company->id, 'sale');
						
					$product_price_for_company = $sale_price > 0 && $regular_price > $sale_price ? $sale_price : $regular_price;
					
				}
				
				if( $product_price_for_company ) {
				
					$price = $product_price_for_company;
					
				}
				
			}
			
		}
		
		return $price;
		
	}