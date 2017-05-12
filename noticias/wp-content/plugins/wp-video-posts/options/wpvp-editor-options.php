<?php 
if($_POST['wpvp_editor_hidden'] == 'Y') {
    $wpvp_editor_page = $_POST['wpvp_editor_page'];
	update_option('wpvp_editor_page', $wpvp_editor_page);
?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else{
	$wpvp_editor_page = get_option('wpvp_editor_page');
}
?>
<div class="wrap">
	<?php	echo "<h2>" . __( 'WP Video Posts - Front End Editor Options' ) . "</h2>";?>
	<!-- PayPal Donate -->
	<?php echo "<h3>Please donate if you enjoy this plugin (WPVP):</h3>"; ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="J535UTFPCXFQC">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	<hr>
	<p><?php _e('In order to display front end editor on a page, please insert the following shortcode into the page:<br /> <strong>[wpvp_edit_video]</strong>');?></p>
	<form name="wpvp_editor_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wpvp_editor_hidden" value="Y">
		<p><?php _e('Please choose the page you created and inserted the shortcode into:');?></p>
		<p>
            <strong><?php _e("Front End Editor Page: " ); ?></strong>
			<select name="wpvp_editor_page">
			<?php
				$args = array();
				$pages = get_pages($args);
				foreach($pages as $page){
					if($wpvp_editor_page==$page->ID){
						$selected = ' selected="selected"';
					} else { $selected = '';}
					$options .= '<option ';
					$options .= ' value="'.$page->ID.'"'.$selected.'>';
					$options .= $page->post_title.'</option>';
				}
				echo $options;
			?>
			</select>
		</p>
		<p class="submit">
        	<input type="submit" name="Submit" value="<?php _e('Update Options' ) ?>" />
	    </p>
    </form>
</div>
