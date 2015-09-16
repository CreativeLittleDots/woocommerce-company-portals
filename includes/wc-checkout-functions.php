<?php
	
	function wc_checkout_get_portal_company($company) {
		
		if( WC()->session->get('portal_id') && wc_get_portal_company( WC()->session->get('portal_id') ) ) {
			
			$company = wc_get_portal_company( WC()->session->get('portal_id') );
			
		}
		
		return $company;
		
	}
	
	add_filter( 'wc_companies_checkout_get_company', 'wc_checkout_get_portal_company', 10 );