<?php
	
	function wc_get_product_price_for_company($product_id, $company_id, $type = 'regular') {
		
		$product = wc_get_product( $product_id );
		$is_queried_company = ! empty( $GLOBALS['company_portals_company'] ) && $GLOBALS['company_portals_company']->id == $company_id;
		$company = $is_queried_company ? $GLOBALS['company_portals_company'] : wc_get_company( $company_id );
		$portal = $is_queried_company ? $GLOBALS['company_portals_company_portal'] : wc_get_company_portal( $company );
		
		if( $product && $company && $portal ) {
			
			if( isset( $product->portal_prices[$portal->term_id][$type] ) ) {
				
				return $product->portal_prices[$portal->term_id][$type];
				
			}
			
		}
		
		return false;
		
	}
	
	function wc_get_portal_company( $portal = null ) {
		
		$portal = is_object( $portal ) ? $portal : ( is_numeric( $portal ) ? get_term_by('id', $portal, 'company_portal') : false);
		
		if( $portal ) {
			
			return wc_get_company( get_page_by_title( $portal->name, OBJECT, 'wc-company' ) );
			
		}
		
		return false;
		
	}
	
	function wc_get_company_portal( $company = null ) {
		
		$company = is_object( $company ) ? $company : ( is_numeric( $company ) ? wc_get_company( $company ) : false);
		
		if( $company ) {
			
			if( $company->portal_id ) {
				
				if( $portal = get_term_by('id', $company->portal_id, 'company_portal') ) {
				
					return $portal;
					
				}
				
			} else {
				
				if( $portal = get_term_by('slug', $company->slug, 'company_portal') ) {
				
					return $portal;
					
				}
				
			}
			
		}
		
		return false;
		
	}
	
	add_action( 'wp', 'woocommerce_set_company_portal_company');
	
	function woocommerce_set_company_portal_company() {
		
		$company = false;
		
		if( is_user_logged_in() ) {
			
			global $current_user;
		
			if( is_tax('company_portal') ) {
					
				$company = wc_get_portal_company( get_queried_object() );
				
			} else if ( WC_Companies()->checkout()->get_company() ) {
				
				$company = WC_Companies()->checkout()->get_company();
				
			} else if( $current_user->primary_company ) {
			
				$company = wc_get_company( $current_user->primary_company );
				
			}
			
		}
		
		$GLOBALS['company_portals_company'] = $company;
		$GLOBALS['company_portals_company_portal'] = wc_get_company_portal( $company );
		
	}