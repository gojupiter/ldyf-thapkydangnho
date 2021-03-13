<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$footer_copyright = Draven()->settings()->get('footer_copyright');
?>
<?php if(!empty($footer_copyright)): ?>
    <div class="site-footer-default">
        <div class="footer-bottom-inner"><?php echo Draven_Helper::remove_js_autop( $footer_copyright );?></div>
    </div>
<?php endif; ?>