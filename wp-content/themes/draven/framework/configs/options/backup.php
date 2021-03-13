<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * Blog settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function draven_options_section_backup( $sections )
{
    $sections['backup'] = array(
        'name' => 'backup_panel',
        'title' => esc_html_x('Import / Export', 'admin-view', 'draven'),
        'icon' => 'fa fa-refresh',
        'fields' => array(
            array(
                'type'    => 'notice',
                'class'   => 'warning',
                'content' => esc_html_x('You can save your current options. Download a Backup and Import.', 'admin-view', 'draven'),
            ),
            array(
                'type'      => 'backup'
            )
        )
    );
    return $sections;
}