<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $webdados_fb;

?>
<div class="menu_containt_div" id="tabs-1">
	<p><?php _e( 'General settings that will apply to all tags types.', 'wd-fb-og' ); ?></p>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-editor-alignleft"></i> <?php _e( 'Description settings', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Description maximum length', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="text" name="wonderm00n_open_graph_settings[fb_desc_chars]" id="fb_desc_chars" size="3" maxlength="3" value="<?php echo (intval($options['fb_desc_chars'])>0 ? intval($options['fb_desc_chars']) : '' ); ?>"/> <?php _e( 'characters', 'wd-fb-og' ); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( '0 (zero) or blank for no maximum length', 'wd-fb-og' );?>
							<?php if ( $webdados_fb->is_yoast_seo_active() ) { ?>
								<div class="fb_wpseoyoast_options">
									- <?php _e( 'Because Yoast SEO integration is active, this value may be overwritten', 'wd-fb-og' );?>
								</div>
							<?php } ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Homepage description', 'wd-fb-og' ); ?>:</th>
						<td>
							<?php
							$hide_home_description=false;
							if ( get_option( 'show_on_front' )=='page' ) {
								$hide_home_description=true;
								_e( 'The description of your front page:', 'wd-fb-og' );
								echo ' <a href="'.get_edit_post_link(get_option( 'page_on_front' )).'" target="_blank">'.get_the_title(get_option( 'page_on_front' )).'</a>';
							}; ?>
							<div<?php if ($hide_home_description) echo ' style="display: none;"'; ?>>
								<select name="wonderm00n_open_graph_settings[fb_desc_homepage]" id="fb_desc_homepage">
									<option value=""<?php if (trim($options['fb_desc_homepage'])=='' ) echo ' selected="selected"'; ?>><?php _e( 'Website tagline', 'wd-fb-og' );?>&nbsp;</option>
									<option value="custom"<?php if (trim($options['fb_desc_homepage'])=='custom' ) echo ' selected="selected"'; ?>><?php _e( 'Custom text', 'wd-fb-og' );?>&nbsp;</option>
								</select>
								<div class="fb_desc_homepage_customtext_div">
									<textarea name="wonderm00n_open_graph_settings[fb_desc_homepage_customtext]" id="fb_desc_homepage_customtext" rows="3" cols="50"><?php echo trim(esc_attr($options['fb_desc_homepage_customtext'])); ?></textarea>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							<?php if ( $webdados_fb->is_yoast_seo_active() ) { ?>
								<div class="fb_wpseoyoast_options">
									- <?php _e( 'Because Yoast SEO integration is active, this value may be overwritten', 'wd-fb-og' );?>
								</div>
							<?php } ?>
							<?php if ( $webdados_fb->is_wpml_active() ) { ?>
								<div class="fb_desc_homepage_customtext_div">- 
								<?php printf(
									__( 'WPML users: Set the main language homepage description here, save changes and then go to <a href="%s" target="_blank">WPML &gt; String translation</a> to set it for other languages.', 'wd-fb-og' ),
									'admin.php?page=wpml-string-translation/menu/string-translation.php&amp;context=wd-fb-og'
								); ?>
								</div>
							<?php } ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Default description', 'wd-fb-og' ); ?>:</th>
						<td>
							<select name="wonderm00n_open_graph_settings[fb_desc_default_option]" id="fb_desc_default_option">
								<option value=""<?php if (trim($options['fb_desc_default_option'])=='' ) echo ' selected="selected"'; ?>><?php _e( 'Homepage description', 'wd-fb-og' );?>&nbsp;</option>
								<option value="custom"<?php if (trim($options['fb_desc_default_option'])=='custom' ) echo ' selected="selected"'; ?>><?php _e( 'Custom text', 'wd-fb-og' );?>&nbsp;</option>
							</select>
							<div class="fb_desc_default_customtext_div">
								<textarea name="wonderm00n_open_graph_settings[fb_desc_default]" id="fb_desc_default" rows="3" cols="50"><?php echo trim(esc_attr($options['fb_desc_default'])); ?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'The default description to be used on any post / page / cpt / archive / search / ... that has a blank description', 'wd-fb-og' ); ?>
							<?php if ( $webdados_fb->is_yoast_seo_active() ) { ?>
								<div class="fb_wpseoyoast_options">
									- <?php _e( 'Because Yoast SEO integration is active, this value may be overwritten', 'wd-fb-og' );?>
								</div>
							<?php } ?>
							<?php if ( $webdados_fb->is_wpml_active() ) { ?>
								<div class="fb_desc_default_customtext_div">- 
								<?php printf(
									__( 'WPML users: Set the main language default description here, save changes and then go to <a href="%s" target="_blank">WPML &gt; String translation</a> to set it for other languages.', 'wd-fb-og' ),
									'admin.php?page=wpml-string-translation/menu/string-translation.php&amp;context=wd-fb-og'
								); ?>
								</div>
							<?php } ?>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-format-image"></i> <?php _e( 'Image settings', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Default image', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="text" name="wonderm00n_open_graph_settings[fb_image]" id="fb_image" size="45" value="<?php echo trim(esc_attr($options['fb_image'])); ?>" class="<?php echo( trim($options['fb_image'])=='' ? 'error' : '' ); ?>"/>
							<input id="fb_image_button" class="button" type="button" value="<?php echo esc_attr( __('Upload/Choose', 'wd-fb-og')  ); ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'URL (with http(s)://)', 'wd-fb-og' );?>
							<br/>
							- <?php printf( __( 'Recommended size: %dx%dpx', 'wd-fb-og' ), WEBDADOS_FB_W, WEBDADOS_FB_H); ?>
							<br/>
							- <?php printf( __( 'You can change this value using the <i>%1$s</i> filter', 'wd-fb-og' ), 'fb_og_image' ); ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'On Post/Page, use image from', 'wd-fb-og' ); ?>:</th>
						<td>
							<div>
								1) <input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_use_specific]" id="fb_image_use_specific" value="1" <?php echo (intval($options['fb_image_use_specific'])==1 ? ' checked="checked"' : '' ); ?>/>
								<small><?php _e( '"Open Graph Image" custom field on the post', 'wd-fb-og' );?></small>
							</div>
							<div>
								2) <input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_use_featured]" id="fb_image_use_featured" value="1" <?php echo (intval($options['fb_image_use_featured'])==1 ? ' checked="checked"' : '' ); ?>/>
								<small><?php _e( 'Post/page featured image', 'wd-fb-og' );?></small>
							</div>
							<div>
								3) <input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_use_content]" id="fb_image_use_content" value="1" <?php echo (intval($options['fb_image_use_content'])==1 ? ' checked="checked"' : '' ); ?>/>
								<small><?php _e( 'First image from the post/page content', 'wd-fb-og' );?></small>
							</div>
							<div>
								4) <input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_use_media]" id="fb_image_use_media" value="1" <?php echo (intval($options['fb_image_use_media'])==1 ? ' checked="checked"' : '' ); ?>/>
								<small><?php _e( 'First image from the post/page media gallery', 'wd-fb-og' );?></small>
							</div>
							<div>
								5) <input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_use_default]" id="fb_image_use_default" value="1" <?php echo (intval($options['fb_image_use_default'])==1 ? ' checked="checked"' : '' ); ?>/>
								<small><?php _e( 'Default image specified above', 'wd-fb-og' );?></small>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'On posts/pages the first image found, using the priority above, will be used. On the homepage, archives and other website sections the default image is always used.', 'wd-fb-og' );?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Overlay PNG logo', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_overlay]" id="fb_image_overlay" value="1" <?php echo (intval($options['fb_image_overlay'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e('Experimental', 'wd-fb-og'); ?>
							<br/>
							- <?php printf( __( 'The original image will be resized/cropped to %dx%dpx and the chosen PNG (that should also have this size) will be overlaid on it. It will only work for locally hosted images.', 'wd-fb-og' ), WEBDADOS_FB_W, WEBDADOS_FB_H);?>
							<br/>
							- <?php printf( __( 'You can see an example of the end result <a href="%s" target="_blank">here</a>', '' ), 'https://www.flickr.com/photos/wonderm00n/29890263040/in/dateposted-public/' ); ?>
							<br/>
							- <?php printf( __( 'If you activate this option globally, you can disable it based on your conditions using the <i>%1$s</i> filter', 'wd-fb-og' ), 'fb_og_image_overlay' ); ?>
						</td>
					</tr>
					
					<tr class="fb_image_overlay_options">
						<th><?php _e( 'PNG logo', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="text" name="wonderm00n_open_graph_settings[fb_image_overlay_image]" id="fb_image_overlay_image" size="45" value="<?php echo trim(esc_attr($options['fb_image_overlay_image'])); ?>"/>
							<input id="fb_image_overlay_button" class="button" type="button" value="<?php echo esc_attr( __('Upload/Choose', 'wd-fb-og')  ); ?>" />
						</td>
					</tr>
					<tr class="fb_image_overlay_options">
						<td colspan="2" class="info">
							- <?php _e( 'URL (with http(s)://)', 'wd-fb-og' );?>
							<br/>
							- <?php printf( __( 'Size: %dx%dpx', 'wd-fb-og' ), WEBDADOS_FB_W, WEBDADOS_FB_H); ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Add image to RSS/RSS2 feeds', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_image_rss]" id="fb_image_rss" value="1" <?php echo (intval($options['fb_image_rss'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'For auto-posting apps like RSS Graffiti, twitterfeed, ...', 'wd-fb-og' ); ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Force getimagesize on local file', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_adv_force_local]" id="fb_adv_force_local" value="1" <?php echo (intval($options['fb_adv_force_local'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <b><?php _e( 'This is an advanced option: Don\'t mess with this unless you know what you\'re doing', 'wd-fb-og' ); ?></b>
							<br/>
							- <?php _e( 'Force getimagesize on local file even if allow_url_fopen=1', 'wd-fb-og' ); ?>
							<br/>
							- <?php _e( 'May cause problems with some multisite configurations but fixes "HTTP request failed" errors', 'wd-fb-og' );?>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-admin-links"></i> <?php _e( 'URL settings', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Add trailing slash at the end', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_url_add_trailing]" id="fb_url_add_trailing" value="1" <?php echo (intval($options['fb_url_add_trailing'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'If missing, a trailing slash will be added at the end', 'wd-fb-og' ); ?>
							<br/>
							- <?php _e( 'Homepage example:', 'wd-fb-og' ); ?>: <i><?php echo get_option( 'siteurl' ); ?><span id="fb_url_add_trailing_example">/</span></i>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-admin-users"></i> <?php _e( 'Author settings', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Hide Author on Pages', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_author_hide_on_pages]" id="fb_author_hide_on_pages" value="1" <?php echo (intval($options['fb_author_hide_on_pages'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'Hides all Author tags on Pages', 'wd-fb-og' );?>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-general"></i> <?php _e( 'Other settings', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Keep data on uninstall', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_keep_data_uninstall]" id="fb_keep_data_uninstall" value="1" <?php echo (intval($options['fb_keep_data_uninstall'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							- <?php _e( 'Keep the plugin settings on the database even if the plugin is uninstalled', 'wd-fb-og' );?>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>