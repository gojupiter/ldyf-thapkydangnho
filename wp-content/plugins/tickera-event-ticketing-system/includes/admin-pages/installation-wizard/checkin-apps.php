<?php
global $tc;
update_option( 'tc_wizard_step', 'checkin-apps' );
?>
<div class="tc-wiz-wrapper">

	<div class="tc-wiz-screen-wrap tc-finish-setup <?php echo tc_wizard_wrapper_class(); ?>">

		<h1><?php echo $tc->title; ?></h1>

		<?php tc_wizard_progress(); ?>

		<div class="tc-clear"></div>

		<div class="tc-wiz-screen">

			<div class="tc-wiz-screen-header">
				<h2><?php _e( 'Check-in Applications', 'tc' ); ?></h2>
			</div><!-- .tc-wiz-screen-header -->

			<div class="tc-wiz-screen-content">

                            <div class="tc-aplications-half tc-wiz-left-wrap">
                                
                                <div class="tc-feature-lock-wrap">                                    
                                    <div class="tc-lock-icon"></div>
                                    <span><?php _e('Free', 'tc'); ?></span>                                    
                                </div><!-- .tc-feature-lock-wrap -->
                                
                                <div class="tc-wiz-image-wrap">
                                    <img src="<?php echo $tc->plugin_url; ?>images/barcode-scanner.png" />     
                                </div><!-- .tc-wiz-image-wrap -->
                                
                                <h2>Check-in attendees with built-in barcode reader</h2>
                                <p>Connect any barcode scanner to your computer and check the tickets in from the back end of your website.
                                </p>
                                
                            </div><!-- .tc-aplications-half -->
                            
                            <div class="tc-aplications-or">                                
                                <div class="tc-app-or">
                                    <?php _e('OR', 'tc'); ?>
                                </div>                                
                            </div><!-- .tc-aplications-or -->
                            
                            <div class="tc-aplications-half tc-wiz-right-wrap">
                                
                                <div class="tc-feature-lock-wrap premium">                                    
                                    <div class="tc-lock-icon"></div>
                                    <span><?php _e('Premium', 'tc'); ?></span>                                    
                                </div><!-- .tc-feature-lock-wrap -->
                                
                                <div class="tc-wiz-image-wrap">
                                    <img src="<?php echo $tc->plugin_url; ?>images/scan-apps.png" />
                                </div><!-- .tc-wiz-image-wrap -->
                                
                                <h2>Check-in attendees using the app</h2>
                                <p>Use a camera of your iOS or Android based device or check the tickets in on any desktop computer using Checkinera - our premium solution for checking the tickets in.
                                    <br><a href="https://tickera.com/checkinera-app/" target="_blank">More info...</a>
                                </p>
                                
                                
                            </div><!-- .tc-aplications-half -->
                            
                    
				<?php
				tc_wizard_navigation();
				?>

				<div class="tc-clear"></div>

			</div><!-- .tc-wiz-screen-content -->

		</div><!-- tc-wiz-screen -->

	</div><!-- .tc-wiz-screen-wrap -->

</div><!-- .tc-wiz-wrapper -->