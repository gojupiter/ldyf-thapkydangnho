<div class="wrap tc_wrap">
    <div class="barcode_api_keys">

        <?php if (!tc_iw_is_pr() || tets_fs()->is_free_plan()) { ?>
            <a class="tc_link tc_checkinera_link" target="_blank" href="https://tickera.com/checkinera-app/"><?php _e('Check in attendees faster with the premium Checkinera app', 'tc'); ?></a>
        <?php } ?>


		<?php
		$current_user = wp_get_current_user();
		$current_user_name	 = $current_user->user_login;
		$staff_api_keys_num	 = 0; //set 0 for number of current user API key available

		$wp_api_keys_search = new TC_API_Keys_Search( '', '', '', 9999 ); //$ticket_event_id

		if ( !current_user_can( 'manage_options' ) ) { //count current user API keys available for non-admin users
			foreach ( $wp_api_keys_search->get_results() as $api_key ) {
				$api_key_obj = new TC_API_Key( $api_key->ID );
				if ( ($api_key_obj->details->api_username == $current_user_name ) ) {
					$staff_api_keys_num++;
				}
			}
		}

		if ( count( $wp_api_keys_search->get_results() ) > 0 && (current_user_can( 'manage_options' ) || (!current_user_can( 'manage_options' ) && $staff_api_keys_num > 0)) ) {
			?>
			<form action="" method="post" enctype="multipart/form-data">
				<table class="checkin-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="api_key"><?php _e( 'API Key', 'tc' ) ?></label></th>
							<td>
								<select name="api_key" id="api_key">
									<?php
									foreach ( $wp_api_keys_search->get_results() as $api_key ) {
										$api_key_obj = new TC_API_Key( $api_key->ID );
										if ( current_user_can( 'manage_options' ) || ($api_key_obj->details->api_username == $current_user_name) ) {
											?>
											<option value="<?php echo $api_key->ID; ?>"><?php echo $api_key_obj->details->api_key_name; ?></option>
											<?php
										}
									}
									?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		<?php } ?>




    </div>

    <div class="barcode_holder">
        <h1><?php _e( 'Barcode Reader', 'tc' ); ?></h1>
        <div><input type="text" name="barcode" id="barcode" /></div>
        <p class="barcode_status"><?php _e( 'Select input field and scan a barcode located on the ticket.', 'tc' ); ?></p>
    </div>



</div>
