<?php
	
	function wc_get_product_price_for_company($product_id, $company_id, $type = 'regular') {
		
		$product = wc_get_product( $product_id );
		$company = wc_get_company( $company_id );
		
		if( $product && $company && $portal = wc_get_company_portal( $company ) ) {
			
			if( isset( $product->portal_prices[$portal->term_id][$type] ) ) {
				
				return $product->portal_prices[$portal->term_id][$type];
				
			}
			
		}
		
		return false;
		
	}
	
	function wc_get_portal_company( $portal = null ) {
		
		$portal = is_object( $portal ) ? $portal : ( is_int( $portal ) ? get_term_by('id', $portal, 'company_portal') : false);
		
		if( $portal ) {
			
			return wc_get_company( get_page_by_title( $portal->name, OBJECT, 'wc-company' ) );
			
		}
		
		return false;
		
	}
	
	function wc_get_company_portal( $company = null ) {
		
		$company = is_object( $company ) ? $company : ( is_int( $company ) ? wc_get_company( $company ) : false);
		
		if( $company ) {
			
			if( $company->company_portal_id ) {
				
				return get_term_by('id', $company->company_portal_id, 'company_portal');
				
			} else {
				
				return get_term_by('slug', $company->slug, 'company_portal');
				
			}
			
		}
		
		return false;
		
	}