<div id="portal_prices_product_data" class="panel woocommerce_options_panel">
	
	<table style="width:100%;text-align:left;" cellspacing="10">
		
		<thead>
			
			<th><?php _e('Company Name', 'woocommerce-company-portals'); ?></th>
			
			<th><?php _e('Regular Price', 'woocommerce-company-portals'); ?></th>
			
			<th><?php _e('Sale Price', 'woocommerce-company-portals'); ?></th>
			
		</thead>
		
		<tbody>
	
			<?php foreach($portals as $portal) : ?>
			
				<tr>
					
					<td>
						
						<?php echo $portal->name; ?>
						
					</td>
					
					<td>
						
						<input type="text" placeholder="Regular Price" name="portal_prices[<?php echo $portal->term_id; ?>][regular]" value="<?php echo isset($portal_prices[$portal->term_id]['regular']) ? $portal_prices[$portal->term_id]['regular'] : ''; ?>" class="widefat" />
						
					</td>
					
					<td>
						
						<input type="text" placeholder="Sale Price" name="portal_prices[<?php echo $portal->term_id; ?>][sale]" value="<?php echo isset($portal_prices[$portal->term_id]['sale']) ? $portal_prices[$portal->term_id]['sale'] : ''; ?>" class="widefat" />
						
					</td>
					
				</tr>
			
			<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
</div>