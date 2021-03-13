<?php

/**
 * Header Builder - Editor Template.
 *
 * @author LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

$frontend_components = LAHFB_Helper::get_data_frontend_components();
$editor_components = LAHFB_Helper::get_only_panels_from_settings($frontend_components);

// Desktop: Topbar settings
$desktopTopbarHidden = $editor_components['desktop-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$desktopTopbarUniqueID = $editor_components['desktop-view']['topbar']['settings']['uniqueId'];
// Desktop: Row1 settings
$desktopRow1Hidden = $editor_components['desktop-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow1UniqueID = $editor_components['desktop-view']['row1']['settings']['uniqueId'];
// Desktop: Row2 settings
$desktopRow2Hidden = $editor_components['desktop-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow2UniqueID = $editor_components['desktop-view']['row2']['settings']['uniqueId'];
// Desktop: Row3 settings
$desktopRow3Hidden = $editor_components['desktop-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow3UniqueID = $editor_components['desktop-view']['row3']['settings']['uniqueId'];

// Tablets: Topbar settings
$tabletsTopbarHidden = $editor_components['tablets-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$tabletsTopbarUniqueID = $editor_components['tablets-view']['topbar']['settings']['uniqueId'];
// Tablets: Row1 settings
$tabletsRow1Hidden = $editor_components['tablets-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow1UniqueID = $editor_components['tablets-view']['row1']['settings']['uniqueId'];
// Tablets: Row2 settings
$tabletsRow2Hidden = $editor_components['tablets-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow2UniqueID = $editor_components['tablets-view']['row2']['settings']['uniqueId'];
// Tablets: Row3 settings
$tabletsRow3Hidden = $editor_components['tablets-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow3UniqueID = $editor_components['tablets-view']['row3']['settings']['uniqueId'];

// Mobiles: Topbar settings
$mobilesTopbarHidden = $editor_components['mobiles-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$mobilesTopbarUniqueID = $editor_components['mobiles-view']['topbar']['settings']['uniqueId'];
// Mobiles: Row1 settings
$mobilesRow1Hidden = $editor_components['mobiles-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow1UniqueID = $editor_components['mobiles-view']['row1']['settings']['uniqueId'];
// Mobiles: Row2 settings
$mobilesRow2Hidden = $editor_components['mobiles-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow2UniqueID = $editor_components['mobiles-view']['row2']['settings']['uniqueId'];
// Mobiles: Row3 settings
$mobilesRow3Hidden = $editor_components['mobiles-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow3UniqueID = $editor_components['mobiles-view']['row3']['settings']['uniqueId'];

$class_frontend_builder = LAHFB_Helper::is_frontend_builder() ? ' lahfb-frontend-builder' : '';

?>

<!-- lastudio header builder wrap -->
<div class="lastudio-backend-header-builder-wrap wp-clearfix<?php echo esc_attr( $class_frontend_builder ); ?>" id="lastudio-backend-header-builder">
    <?php

    $export_current_header_link = add_query_arg(array(
        'action' => 'lahfb_ajax_action',
        'router' => 'export_header',
        'nonce'  => wp_create_nonce( 'lahfb-nonce' )
    ), admin_url( 'admin-ajax.php' ));



    if(is_admin() && isset($_REQUEST['prebuild_header'])){
        $prebuild_header_key = esc_attr($_REQUEST['prebuild_header']);
        $all_existing = LAHFB_Helper::get_prebuild_headers();
        if(LAHFB_Helper::is_prebuild_header_exists($prebuild_header_key) && !empty($all_existing[$prebuild_header_key]['name'])){

            $export_current_header_link = add_query_arg(array(
                'prebuild_header' => $prebuild_header_key
            ), $export_current_header_link);

            echo '<h4>'. esc_html__('You are editing ', 'lastudio-header-footer-builder') .'<strong>'.$all_existing[$prebuild_header_key]['name'].'</strong> <a class="button button-primary" href="'.esc_url(admin_url( 'admin.php?page=lastudio_header_builder_setting' )).'">'. esc_html__('Complete edit') .'</a></h4>';
        }
    }
    ?>

    <div class="lahfb-actions">

        <a href="#" id="lahfb-publish" class="button button-primary"><i class="dashicons dashicons-external"></i><?php esc_html_e( 'Publish', 'lastudio-header-footer-builder' ); ?></a>

        <?php if ( LAHFB_Helper::is_frontend_builder() ) : ?>
            <?php
            $option_page_url = admin_url( 'admin.php?page=lastudio_header_builder_setting' );
            if(!empty($_GET['prebuild_header'])){
                $option_page_url = add_query_arg(array('prebuild_header' => esc_attr($_GET['prebuild_header'])), $option_page_url);
            }
            ?>
            <div class="lahfb-action-collapse lahfb-tooltip lahfb-open" data-tooltip="<?php esc_html_e( 'Toggle', 'lastudio-header-footer-builder' ); ?>"><i class="dashicons dashicons-download"></i></div>
            <a href="<?php echo esc_url($option_page_url) ?>" class="btob-button lahfb-tooltip" data-tooltip="<?php esc_html_e( 'Backend editor', 'lastudio-header-footer-builder' ) ?>"><i class="dashicons dashicons-arrow-left-alt"></i></a>
        <?php else : ?>
        <?php
            $option_page_url = admin_url( 'admin.php?page=lastudio_header_builder' );
            if(!empty($_GET['prebuild_header'])){
                $option_page_url = add_query_arg(array('prebuild_header' => esc_attr($_GET['prebuild_header'])), $option_page_url);
            }

        ?>
            <a href="<?php echo esc_url($option_page_url) ?>" id="lahfb-f-editor" class="button button-primary"><i class="dashicons dashicons-arrow-right-alt"></i><?php esc_html_e( 'Front-end Header Builder', 'lastudio-header-footer-builder' ); ?></a>
        <?php endif; ?>

        <a href="#" id="lahfb-vertical-header" class="button" data-header_type="horizontal"><i class="fa fa-bars"></i><span><?php esc_html_e( 'Vertical Header', 'lastudio-header-footer-builder' ); ?></span></a>
        <a href="#" id="lahfb-predefined" class="button lahfb-full-modal-btn" data-modal-target="prebuilds-modal-content"><i class="fa fa-hdd-o"></i><?php esc_html_e( 'Pre-defined Headers', 'lastudio-header-footer-builder' ) ?></a>

        <div class="lahfb-full-modal" data-modal="prebuilds-modal-content">
            <i class="lahfb-full-modal-close fa fa-times"></i>
            <h4><?php esc_html_e( 'Pre-defined Headers', 'lastudio-header-footer-builder' ); ?></h4>
            <div class="lahfb-predefined-modal-contents wp-clearfix">
                <?php include LAHFB_Helper::get_file( 'includes/prebuilds/prebuilds.php' ); ?>
            </div>
        </div>

        <a href="#" id="lahfb-cleardata" class="button la-warning-primary"><i class="fa fa-ban"></i><?php esc_html_e( 'Clear Data', 'lastudio-header-footer-builder' ) ?></a>

        <!-- import export -->
        <div class="lahfb-import-export">
            <a id="lahfb-saveastpl" class="button button-primary" href="#"><i class="fa fa-floppy-o"></i><?php esc_html_e( 'Save as Header Template', 'lastudio-header-footer-builder' ) ?></a>
            <a id="lahfb-export" class="button" href="<?php echo esc_url( $export_current_header_link ); ?>"><i class="fa fa-cloud-upload"></i><?php esc_html_e( 'Export Header', 'lastudio-header-footer-builder' ) ?></a>
            <div class="lahfb-import-wrap">
                <a class="button lahfb-full-modal-btn" href="#" data-modal-target="import-modal-content"><i class="fa fa-cloud-download"></i><?php esc_html_e( 'Import Header', 'lastudio-header-footer-builder' ) ?></a>
                <input type="file" id="lahfb-import">
            </div>

        </div> <!-- end .lahfb-import-export -->

    </div><!-- .lahfb-actions -->

    <!-- tabs -->
    <div class="lahfb-tabs-wrap">

        <ul class="lahfb-tabs-list wp-clearfix">
            <li class="lahfb-tab w-active">
                <a href="#desktop-view" id="lahfb-desktop-tab">
                    <i class="fa fa-desktop" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Desktop', 'lastudio-header-footer-builder' ); ?></span>
                </a>
            </li>
            <li class="lahfb-tab">
                <a href="#tablets-view" id="lahfb-tablets-tab">
                    <i class="dashicons dashicons-tablet" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Tablets', 'lastudio-header-footer-builder' ); ?></span>
                </a>
            </li>
            <li class="lahfb-tab">
                <a href="#mobiles-view" id="lahfb-mobiles-tab">
                    <i class="dashicons dashicons-smartphone" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Mobiles', 'lastudio-header-footer-builder' ); ?></span>
                </a>
            </li>
        </ul> <!-- end .lahfb-tabs-list -->

        <div class="lahfb-tabs-panels">

            <!-- desktop panel -->
            <div class="lahfb-tab-panel lahfb-desktop-panel" id="desktop-view">

                <!-- topbar -->
                <div class="lahfb-columns" data-columns="topbar">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $desktopTopbarHidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 1 -->
                <div class="lahfb-columns" data-columns="row1">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow1Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'left');?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 2 -->
                <div class="lahfb-columns" data-columns="row2">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow2Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 3 -->
                <div class="lahfb-columns" data-columns="row3">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow3Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

            </div> <!-- end .lahfb-desktop-panel -->

            <!-- tablets panel -->
            <div class="lahfb-tab-panel lahfb-tablets-panel" id="tablets-view">

                <!-- topbar -->
                <div class="lahfb-columns" data-columns="topbar">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $tabletsTopbarHidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 1 -->
                <div class="lahfb-columns" data-columns="row1">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow1Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 2 -->
                <div class="lahfb-columns" data-columns="row2">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow2UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow2Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 3 -->
                <div class="lahfb-columns" data-columns="row3">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow3Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

            </div> <!-- end .lahfb-tablets-panel -->

            <!-- mobiles panel -->
            <div class="lahfb-tab-panel lahfb-mobiles-panel" id="mobiles-view">

                <!-- topbar -->
                <div class="lahfb-columns" data-columns="topbar">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $mobilesTopbarHidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 1 -->
                <div class="lahfb-columns" data-columns="row1">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow1Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 2 -->
                <div class="lahfb-columns" data-columns="row2">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow2Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

                <!-- header area row 3 -->
                <div class="lahfb-columns" data-columns="row3">
                    <div class="lahfb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow3Hidden; ?>">
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahfb-control lahfb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahfb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahfb-control lahfb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahfb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-footer-builder' ); ?></span>
                    <div class="lahfb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>left" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>center" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahfb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahfb-elements-place wp-clearfix">
                            <?php echo LAHFB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>right" class="w-edit-column lahfb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahfb-columns -->

            </div> <!-- end .lahfb-mobiles-panel -->

        </div> <!-- end .lahfb-tabs-panels -->

    </div> <!-- end .lahfb-tabs-wrap -->

    <?php
        // add element modal
        include LAHFB_Helper::get_file( 'includes/elements/add-elements.php' );
    ?>

    <div class="lahfb-modal-wrap lahfb-modal-save-header">
        <div class="lahfb-modal-header">
            <h4>Save as Pre-defined Headers</h4>
            <i class="fa fa-times"></i>
        </div>
        <div class="lahfb-modal-contents-wrap">
            <div class="lahfb-modal-contents w-row">
                <?php

                $lahfb_preheaders = LAHFB_Helper::get_prebuild_headers();
                $lahfb_preheader_opts = array();
                if(!empty($lahfb_preheaders)){
                    foreach ($lahfb_preheaders as $k => $v){
                        $lahfb_preheader_opts[$k] = $v['name'];
                    }
                }
                else{
                    $lahfb_preheader_opts = array(
                        '' => esc_html__( 'Default', 'lastudio-header-footer-builder' ),
                    );
                }

                lahfb_select( array(
                    'title'			=> esc_html__( 'Save Type', 'lastudio-header-footer-builder' ),
                    'id'			=> 'lahfb_save_header_type',
                    'options'		=> array(
                        'add_new' => 'Add New',
                        'update'  => 'Update Existing',
                    ),
                    'dependency'	=> array(
                        'add_new'	=> array( 'lahfb_save_header_type_new' ),
                        'update'  => array( 'lahfb_save_header_type_existing' ),
                    ),
                ));

                lahfb_select( array(
                    'title'			=> esc_html__( 'Select Existing header', 'lastudio-header-footer-builder' ),
                    'id'			=> 'lahfb_save_header_type_existing',
                    'options'		=> $lahfb_preheader_opts
                ));

                lahfb_textfield( array(
                    'title'			=> esc_html__( 'Enter New Header Name', 'lastudio-header-footer-builder' ),
                    'id'			=> 'lahfb_save_header_type_new',
                ));

                lahfb_image( array(
                    'title'			=> esc_html__( 'Custom Image', 'lastudio-header-footer-builder' ),
                    'id'			=> 'lahfb_save_header_custom_image',
                ));

                ?>
            </div>
        </div>
        <div class="lahfb-modal-footer">
            <input type="button" class="lahfb_close button" value="Close">
            <input type="button" class="lahfb_save_as_template button button-primary" value="Save">
        </div>
    </div>

</div> <!-- end .wn-header-builder-wrap -->
