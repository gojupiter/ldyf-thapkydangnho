<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LaStudio
 * @subpackage LaStudio/admin/partials
 */
?>
<div id="la-icon-dialog" class="la-dialog" title="<?php esc_html_e( 'Add Icon', 'lastudio' ) ?>'">
    <div class="la-dialog-header la-text-center">
        <input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'lastudio' ) ?>" class="la-icon-search" />
    </div>
    <div class="la-dialog-load"><div class="la-icon-loading"><?php esc_html_e( 'Loading...', 'lastudio' ) ?></div></div>
</div>