<form id="wpvp-update-video" enctype="multipart/form-data" name="wpvp-update-video" class="wpvp-processing-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<div class="wpvp_block">
		<?php echo do_shortcode($data['video_shortcode']);?>
	</div>
	<div class="wpvp_block">
		<label><?php _e('Title');?><span>*</span></label>
		<input type="text" name="wpvp_title" class="wpvp_require" value="<?php echo $data['title'];?>" />
		<div class="wpvp_title_error wpvp_error"></div>
	</div>
	<div class="wpvp_block">
	<?php $desc_status = (int)get_option('wpvp_uploader_desc',false);?>
		<label><?php _e('Description');?><?php if(!$desc_status):?><span>*</span><?php endif;?></label>
		<textarea name="wpvp_desc" <?php if(!$desc_status):?>class="wpvp_require"<?php endif;?>><?php echo $data['content'];?></textarea>
		<div class="wpvp_desc_error wpvp_error"></div>
	</div>
	<div class="wpvp_block">
		<div class="wpvp_cat" style="float:left;width:50%;">
			<label><?php _e('Choose category');?></label>
			<?php WPVP_Helper::wpvp_upload_categories_dropdown(true,$data['category']);?>
		</div>
		<?php $hide_tags = get_option('wpvp_uploader_tags','');?>
		<?php if($hide_tags==''):?>
		<div class="wpvp_tag" style="float:right;width:50%;text-align:right;">
			<label><?php _e('Tags (comma separated)');?></label>
			<input type="text" name="wpvp_tags" value="<?php echo $data['tags'];?>" />
		</div>
		<?php endif;?>
	</div>
	<input type="hidden" name="wpvp_video_id" value="<?php echo $data['post_id'];?>" />
	<p class="wpvp_submit_block">
		<input type="submit" action="update" class="wpvp-submit" name="wpvp-update" value="<?php _e('Save Changes');?>" />
	</p>
	<?php wp_nonce_field('wpvp_video_update','wpvp_video_update_field',true);?>
	<div class="wpvp_msg"></div>
	<p class="wpvp_info"><span>*</span> = <?php _e('Required fields');?></p>
</form>