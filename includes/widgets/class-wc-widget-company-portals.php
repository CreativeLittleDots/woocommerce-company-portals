<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shopping Company Portals Widget.
 *
 * Displays Company Portals widget.
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class WC_Widget_Company_Portals extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_company_portals';
		$this->widget_description = __( "Display the Company Portals in the sidebar.", 'woocommerce' );
		$this->widget_id          = 'woocommerce_widget_company_portals';
		$this->widget_name        = __( 'WooCommerce Company Portals', 'woocommerce' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Company Portals', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			),
			'hide_if_empty' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Hide if there are no company portals', 'woocommerce' )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		
		if( ! is_user_logged_in() ) { return; }

		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		
		$portals = array();
		
		foreach( wc_get_user_companies() as $company ) {
			
			if( $portal = wc_get_company_portal( $company ) ) {
			
				$portals[] = $portal;
				
			}
			
		}
		
		if( ! $portals && $hide_if_empty ) { return; }

		$this->widget_start( $args, $instance );
		
		wc_get_template( 'global/company-portals.php', compact('portals'), '', WC_Company_Portals()->plugin_path() . '/templates/' );

		$this->widget_end( $args );
		
	}
	
}

register_widget( 'WC_Widget_Company_Portals' );