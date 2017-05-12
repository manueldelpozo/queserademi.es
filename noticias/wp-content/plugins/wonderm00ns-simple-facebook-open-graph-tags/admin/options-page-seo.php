<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="menu_containt_div" id="tabs-5">
	<p><?php _e( 'SEO Meta Tags that are recommended ONLY if no other plugin is setting them already.', 'wd-fb-og' ); ?></p>
	<div class="postbox">
		<h3 class="hndle"><i class="dashicons-before dashicons-admin-site"></i> <?php _e( 'SEO Meta Tags', 'wd-fb-og' ) ?></h3>
		<div class="inside">
			<table class="form-table">
				<tbody>
					
					<tr>
						<th><?php _e( 'Set Canonical URL', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_url_canonical]" id="fb_url_canonical" value="1" <?php echo (intval($options['fb_url_canonical'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							<i>&lt;link rel="canonical" href="..."/&gt;</i>
							<?php
							if ( $webdados_fb->is_yoast_seo_active() ) {
								?>
								<br/>
								- <?php _e( 'Not recommended because you have Yoast SEO active', 'wd-fb-og' );?>
								<?php
							}
							?>
							<br/>
							- <?php printf( __( 'You can change this value using the <i>%1$s</i> filter', 'wd-fb-og' ), 'fb_og_url' ); ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Include Meta Description tag', 'wd-fb-og' ); ?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_desc_show_meta]" id="fb_desc_show_meta" value="1" <?php echo (intval($options['fb_desc_show_meta'])==1 ? ' checked="checked"' : '' ); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							<i>&lt;meta name="description" content="..."/&gt;</i>
							<?php
							if ( $webdados_fb->is_yoast_seo_active() ) {
								?>
								<br/>
								- <?php _e( 'Not recommended because you have Yoast SEO active', 'wd-fb-og' );?>
								<?php
							}
							?>
							<br/>
							- <?php printf( __( 'You can change this value using the <i>%1$s</i> filter', 'wd-fb-og' ), 'fb_og_desc' ); ?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Include Post/Page Author name', 'wd-fb-og' );?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_author_show_meta]" id="fb_author_show_meta" value="1" <?php echo (intval($options['fb_author_show_meta'])==1 ? ' checked="checked"' : ''); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							<i>&lt;meta name="author" content="..."/&gt;</i>
							<br/>
							- <?php _e( 'From the user Display name', 'wd-fb-og' );?>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Include Publisher', 'wd-fb-og' );?>:</th>
						<td>
							<input type="checkbox" name="wonderm00n_open_graph_settings[fb_publisher_show_meta]" id="fb_publisher_show_meta" value="1" <?php echo (intval($options['fb_publisher_show_meta'])==1 ? ' checked="checked"' : ''); ?>/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">
							<i>&lt;meta name="publisher" content="..."/&gt;</i>
							<br/>
							- <?php _e( 'From Settings &gt; General &gt; Site Title', 'wd-fb-og' );?>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>