<?php
/**
 * The template for displaying company portals in widget
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul class="company-portals">
	
	<?php foreach($portals as $portal) : ?>
	
		<li class="portal">
		
			<a href="<?php echo get_term_link( $portal ); ?>"><?php echo $portal->name; ?></a>
		
		</li>
	
	<?php endforeach; ?>
	
</ul>