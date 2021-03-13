<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?><form method="get" class="search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<input autocomplete="off" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here&hellip;', 'front-view', 'draven' ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'front-view', 'draven' ); ?>" />
	<button class="search-button" type="submit"><i class="dlicon ui-1_zoom"></i></button>
</form>
<!-- .search-form -->