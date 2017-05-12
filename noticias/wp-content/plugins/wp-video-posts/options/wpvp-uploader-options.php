<?php 
$helper = new WPVP_Helper();
$options = $helper->wpvp_get_full_options();
$ffmpeg_ext = $options['ffmpeg_exists'];
if($_POST['wpvp_uploader_hidden'] == 'Y') {
	//Form data sent
	$wpvp_allow_guest = $_POST['wpvp_allow_guest'];
	$wpvp_guest_userid = $_POST['wpvp_guest_userid'];
	$wpvp_uploader_roles = $_POST['wpvp_uploader_roles'];
	$wpvp_denial_message = $_POST['wpvp_denial_message'];
	$wpvp_default_post_status = $_POST['wpvp_default_post_status'];
	$wpvp_published_notification = $_POST['wpvp_published_notification'];
	$wpvp_uploader_cats = $_POST['wpvp_uploader_cats'];
	$wpvp_uploader_tags = $_POST['wpvp_uploader_tags'];
	$wpvp_uploader_desc = (int)$_POST['wpvp_uploader_desc'];
	$wpvp_allowed_extensions = $_POST['wpvp_allowed_extensions'];
	update_option('wpvp_allow_guest', $wpvp_allow_guest);
	update_option('wpvp_guest_userid',$wpvp_guest_userid);
	update_option('wpvp_uploader_roles', $wpvp_uploader_roles);
	update_option('wpvp_denial_message', $wpvp_denial_message);
	update_option('wpvp_default_post_status', $wpvp_default_post_status);
	update_option('wpvp_published_notification', $wpvp_published_notification);
	update_option('wpvp_uploader_cats', $wpvp_uploader_cats);
	update_option('wpvp_uploader_tags', $wpvp_uploader_tags);
	update_option('wpvp_uploader_desc', $wpvp_uploader_desc);
	update_option('wpvp_allowed_extensions', $wpvp_allowed_extensions);
?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else {
	$wpvp_allow_guest = get_option('wpvp_allow_guest','no') ? get_option('wpvp_allow_guest','no') : 'no';
	$wpvp_guest_userid = get_option('wpvp_guest_userid');
	$wpvp_uploader_roles = get_option('wpvp_uploader_roles');
	$wpvp_denial_message = get_option('wpvp_denial_message');
	$wpvp_default_post_status = get_option('wpvp_default_post_status','pending') ? get_option('wpvp_default_post_status','pending') : 'pending';
	$wpvp_published_notification = get_option('wpvp_published_notification','no') ? get_option('wpvp_published_notification','no') : 'no';
	$wpvp_uploader_cats = get_option('wpvp_uploader_cats');
	$wpvp_uploader_tags = get_option('wpvp_uploader_tags');
	$wpvp_uploader_desc = (int)get_option('wpvp_uploader_desc');
	$wpvp_allowed_extensions = get_option('wpvp_allowed_extensions');
}
?>
<div class="wrap">
	<?php	echo "<h2>" . __( 'WP Video Posts - Front End Uploader Options' ) . "</h2>";?>
	<!-- PayPal Donate -->
	<?php echo "<h3>Please donate if you enjoy this plugin (WPVP):</h3>"; ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="J535UTFPCXFQC">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	<hr>
<?php   if(!$ffmpeg_ext){
			echo '<h3 style="color: red;">FFMPEG is not found on the server. The only extensions available for uploading: mp4.<br />Please verify with your administrator or hosting provider to have this installed and configured. If ffmpeg is installed but you still see this message, specify the path to ffmpeg installation.</h3><br />';
		} ?>
	<p><?php _e('In order to display front end uploader on a page, please insert the following shortcode into the page:<br /> <strong>[wpvp_upload_video]</strong>');?></p>
	<form name="wpvp_uploader_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wpvp_uploader_hidden" value="Y">
		<p><strong><?php _e('User Upload Preferences:');?></strong>
		<p>
			<?php _e('Allow guests to upload videos:');?>&nbsp;&nbsp;&nbsp;
			<input type="radio" value="yes" name="wpvp_allow_guest" <?php if($wpvp_allow_guest == "yes") { echo "checked=\"checked\""; } ?>>Yes&nbsp;&nbsp;&nbsp;
			<input type="radio" value="no" name="wpvp_allow_guest" <?php if($wpvp_allow_guest == "no") { echo "checked=\"checked\""; } ?>>No
			<br />
			<?php _e('Assign guest uploaded videos to: ');?>
			<?php 
			$args = array(
				'orderby'=>'ID',
				'name'=>'wpvp_guest_userid',
				'selected'=>$wpvp_guest_userid
			);
			wp_dropdown_users($args);?>
		</p>
		<p><?php _e('Choose the user roles allowed to upload videos:');?></p>
		<p>
			<strong><?php _e('Allowed User Roles: '); ?></strong>
			<div style="max-height: 145px; overflow: auto;">
			<ul style="list-style-type:none; margin-top: 0px;">
			<?php 
			$options = '';
			$roles = get_editable_roles();
			foreach($roles as $role) {
				$options .= '<li><input type="checkbox" id="wpvp_uploader_roles[]" name="wpvp_uploader_roles[]"';
				if(is_array($wpvp_uploader_roles)){
					if(in_array($role['name'],$wpvp_uploader_roles)){
						$options .= ' checked="checked"';
					}
				}
				$options .= ' value="'.$role['name'].'" /> ';
				$options .= $role['name'].'</li>';
			}
			echo $options;
			?>
			</ul>
			</div>
		</p>
		
		<p>
				<p><?php _e('Message to display to the user who does not have sufficient privileges to use front end uploader:');?></p>
				<input type="text" id="wpvp_denial_message" name="wpvp_denial_message" size="100" value="<?php echo stripslashes($wpvp_denial_message); ?>" />
		</p>
		<br />
		
		<p>
				<p><strong><?php _e('Default Status for Uploaded Videos'); ?></strong></p>
				<select name="wpvp_default_post_status" id="wpvp_default_post_status">
						<option <?php if($wpvp_default_post_status == "publish") echo "selected=\"selected\""; ?> value="publish">Published</option>
						<option <?php if($wpvp_default_post_status == "pending") echo "selected=\"selected\""; ?> value="pending">Pending Review</option>
						<option <?php if($wpvp_default_post_status == "draft") echo "selected=\"selected\""; ?> value="draft">Draft</option>
				</select>
		</p>
		<br />
		
		<p>
			<strong><?php _e('Published Video Notification:');?></strong>&nbsp;&nbsp;&nbsp;
			<input type="radio" value="yes" name="wpvp_published_notification" <?php if($wpvp_published_notification == "yes") { echo "checked=\"checked\""; } ?>>Yes&nbsp;&nbsp;&nbsp;
			<input type="radio" value="no" name="wpvp_published_notification" <?php if($wpvp_published_notification == "no") { echo "checked=\"checked\""; } ?>>No
			<br /><?php _e('If the default status for uploaded video is set to any option other than \'Published\', then you can send an email notification to the user indicating of this video being published.'); ?>
		</p>
		<br />
		<p><?php _e('Choose the categories to be available for a user from Front End Uploader (all categories are displayed by default if none is chosen)');?></p>
		<p>
			<strong><?php _e('Categories: '); ?></strong>
			<div style="max-height:145px;overflow:auto;">
				<ul style="list-style-type:none; margin-top: 0px;">
				<?php
					$options = '';
					$args = array('hide_empty'=>0);
					$categories = get_categories($args);
					foreach($categories as $category){
						$options .= '<li><input type="checkbox" id="wpvp_uploader_cats[]" name="wpvp_uploader_cats[]"';
						if(is_array($wpvp_uploader_cats)){
							foreach($wpvp_uploader_cats as $cats){
								if($cats == $category->term_id){
									$options .= ' checked = "checked"';
								}
							}
						}
						$options .= ' value="'.$category->term_id.'" /> ';
						$options .= $category->cat_name.'</li>';
					}
					echo $options;
				?>
				</ul>
			</div>
		</p>
		<p>
			<strong><?php _e('Make video description optional:');?></strong>
			<p><input type="checkbox" name="wpvp_uploader_desc" value="1" <?php checked($wpvp_uploader_desc,true);?>/> <?php _e('Make optional');?></p>	
		</p>
		<p>
			<strong><?php _e('Disable Tags for Video Front Uploader:');?></strong>
			<p><input type="checkbox" name="wpvp_uploader_tags" value="yes"<?php if($wpvp_uploader_tags=='yes'){ echo ' checked="checked"';}?>/> <?php _e('<b>do not</b> allow tags');?></p>	
		</p>
		<p>
			<strong><?php _e('Select allowed extensions to upload:');?><br /></strong>
		<?php	$allowed = get_allowed_mime_types();
			if(!$ffmpeg_ext){
				$allowed_types = array('flv'=>'video/x-flv','mp4|m4v'=>'video/mp4');
				echo '<div style="color:red;font-style:italic;">No ffmpeg detected. Only mp4 and flv are available for the upload.</div>';
			} else {
				$allowed_types = $allowed;
			}
			foreach($allowed as $key=>$value){
				$t = explode('/',$value);
				if($t[0]=='video'){
					$types .= '<input type="checkbox" name="wpvp_allowed_extensions[]"';
					if(is_array($allowed_types)){
						if(!in_array($value,$allowed_types)){
							$types .= ' disabled="true"';
						}
					}
					if(is_array($wpvp_allowed_extensions)) {
						if(in_array($value,$wpvp_allowed_extensions)){
							$types .= ' checked="checked"';
						}
					}
					$types .= ' value="'.$value.'" /> '.$key.'<br />';
				}
			}
			echo $types;
			?>
		</p>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options' ) ?>" />
		</p>
	</form>
</div>
