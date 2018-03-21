<?php
	
	function wc_get_product_price_for_company($product_id, $company_id, $type = 'regular') {
		
		$product = wc_get_product( $product_id );
		$is_queried_company = ! empty( $GLOBALS['wc_cp_company'] ) && $GLOBALS['wc_cp_company']->id == $company_id;
		$company = $is_queried_company ? $GLOBALS['wc_cp_company'] : wc_get_company( $company_id );
		$portal = $is_queried_company ? $GLOBALS['wc_cp_portal'] : wc_get_company_portal( $company );
		
		return wc_get_product_price_for_portal($product_id, $portal->term_id, $type);
		
	}
	
	function wc_get_product_price_for_portal($product_id, $portal_id, $type = 'regular') {
		
		if( $product = wc_get_product( $product_id ) ) {
			
			$prices = $product->get_meta('_portal_prices');
			
			if( isset( $prices[$portal_id][$type] ) ) {
				
				return $prices[$portal_id][$type];
				
			}
			
		}
		
		return false;
		
	}
	
	function wc_get_portal_company( $portal = null ) {
		
		$portal = is_object( $portal ) ? $portal : ( is_numeric( $portal ) ? get_term_by('id', $portal, 'company_portal') : false);
		
		if( $portal ) {
			
			$companies = wc_get_companies([
				'meta_key' => '_portal_id',
				'meta_value' => $portal->term_id
			]);
			
			return $companies ? wc_get_company( reset( $companies ) ) : null;
			
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
				
			} else if ( is_checkout() && WC_Companies()->checkout()->get_company() ) {
				
				$company = WC_Companies()->checkout()->get_company();
				
			} else if ( ! empty( $_REQUEST['company_portal_id'] ) ) {
				
				$company = wc_get_portal_company( $_REQUEST['company_portal_id'] );
				
			}
			
			$company = $company instanceof WC_Company && in_array( $company->id, $current_user->companies ) ? $company : false;
			
		}
		
		if( $company && in_array( $company->get_id(), get_user_meta( get_current_user_id(), 'companies', true ) ) ) {
		
			$GLOBALS['wc_cp_company'] = $company;
			$GLOBALS['wc_cp_portal'] = wc_get_company_portal( $company );
			
		}
		
	}