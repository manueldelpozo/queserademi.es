<form id="wpvp-upload-video" enctype="multipart/form-data" name="wpvp-upload-video" class="wpvp-processing-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<div class="wpvp_block">
		<label><?php printf( __( 'Choose Video (Max Size of %s):' ), esc_html(WPVP_Helper::wpvp_max_upload_size()) ); ?><span>*</span></label>
		<input type="file" id="async-upload" name="async-upload" class="wpvp_require" value="" />
		<div class="wpvp_file_error wpvp_error"></div>
		<div class="wpvp_upload_progress" style="display:none;">
			<?php _e('Please, wait while your video is being uploaded.');?>
		</div>
	</div>
	<div class="wpvp_block">
		<label><?php _e('Title');?><span>*</span></label>
		<input type="text" name="wpvp_title" class="wpvp_require" value="" />
		<div class="wpvp_title_error wpvp_error"></div>
	</div>
	<div class="wpvp_block">
	<?php $desc_status = (int)get_option('wpvp_uploader_desc',false);?>
		<label><?php _e('Description');?><?php if(!$desc_status):?><span>*</span><?php endif;?></label>
		<textarea name="wpvp_desc" <?php if(!$desc_status):?>class="wpvp_require"<?php endif;?>></textarea>
		<div class="wpvp_desc_error wpvp_error"></div>
	</div>
	<div class="wpvp_block">
		<div class="wpvp_cat" style="float:left;width:50%;">
			<label><?php _e('Choose category');?></label>
			<?php WPVP_Helper::wpvp_upload_categories_dropdown();?>
		</div>
		<?php   
		$hide_tags = get_option('wpvp_uploader_tags','');
		if($hide_tags==''){ ?>
		<div class="wpvp_tag" style="float:right;width:50%;text-align:right;">
			<label><?php _e('Tags (comma separated)');?></label>
			<input type="text" name="wpvp_tags" value="" />
		</div>
		<?php   
		} 
		?>
		<?php wp_nonce_field('wpvp_file_upload','wpvp_file_upload_field',true,true);?>
	</div>
	<input type="hidden" name="wpvp_action" value="wpvp_upload" />
	<p class="wpvp_submit_block">
		<input type="submit" action="create" class="wpvp-submit" name="wpvp-upload" value="Upload" />
	</p>
	<p class="wpvp_info"><span>*</span> = <?php _e('Required fields');?></p>
</form>