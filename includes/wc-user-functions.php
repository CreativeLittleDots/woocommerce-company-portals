<?php
	
	function wc_add_company_agent_user_role() {
		
		$result = add_role(
		   'company_agent',
		   apply_filters( 'woocommerce_company_portals_company_agent_roles_title', __( 'Company Agent', 'woocommerce' ) ),
		   apply_filters( 'woocommerce_company_portals_company_agent_roles_capabilities', array(
		      'read'         => true,  // true allows this capability
		      'company_agent' => true,
		   ))
		);
		
	}
	
	add_action( 'init', 'wc_add_company_agent_user_role' );