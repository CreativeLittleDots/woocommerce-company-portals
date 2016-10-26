<?php
/**
 * The template for displaying hidden fields
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />