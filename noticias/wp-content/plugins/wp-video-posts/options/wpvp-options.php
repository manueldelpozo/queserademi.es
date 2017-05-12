<?php 
$helper = new WPVP_Helper();
$options = $helper->wpvp_get_full_options();
$ffmpeg_ext = $options['ffmpeg_exists'];
$mp4box_c = $helper->wpvp_check_extension('MP4Box');
$mp4box_ext = (is_array($mp4box_c)&&!empty($mp4box_c)) ? true : false;

if($_POST['wpvp_hidden'] == 'Y') {
    //Form data sent
    $wpvp_width = $_POST['wpvp_video_width'];
	$wpvp_height = $_POST['wpvp_video_height'];
    $wpvp_thumb_width = $_POST['wpvp_thumb_width'];
	$wpvp_thumb_height = $_POST['wpvp_thumb_height'];
	$wpvp_capture_image = $_POST['wpvp_capture_image'];
	$wpvp_ffmpeg_path = $_POST['wpvp_ffmpeg_path'];
	$wpvp_mp4box_path = $_POST['wpvp_mp4box_path'];
	$wpvp_main_loop_alter = ($_POST['wpvp_main_loop_alter']=='yes') ? true : false;
	$wpvp_debug_mode = ($_POST['wpvp_debug_mode']=='yes') ? true : false;
	$wpvp_player = $_POST['wpvp_player'];
	$wpvp_autoplay = ($_POST['wpvp_autoplay']=='yes') ? true : false;
	$wpvp_audio = $_POST['wpvp_audio'];
	$wpvp_splash = ($_POST['wpvp_splash']=='yes') ? true : false;
	$wpvp_clean_url = ($_POST['wpvp_clean_url']=='yes') ? true : false;
	$wpvp_encode_format = empty($_POST['wpvp_encode_format']) ? array() : $_POST['wpvp_encode_format'];
	/* FFMPEG options */
	$wpvp_ffmpeg_ar = isset($_POST['wpvp_ffmpeg_ar']) ? $_POST['wpvp_ffmpeg_ar'] : 44100;
	$wpvp_ffmpeg_b_a = isset($_POST['wpvp_ffmpeg_b_a']) ? $_POST['wpvp_ffmpeg_b_a'] : 384;
	$wpvp_ffmpeg_b_v = isset($_POST['wpvp_ffmpeg_b_v']) ? $_POST['wpvp_ffmpeg_b_v'] : 384;
	$wpvp_ffmpeg_ac = isset($_POST['wpvp_ffmpeg_ac']) ? $_POST['wpvp_ffmpeg_ac'] : 2;
	$wpvp_ffmpeg_acodec = isset($_POST['wpvp_ffmpeg_acodec']) ? $_POST['wpvp_ffmpeg_acodec'] : 'libfaac';
	$wpvp_ffmpeg_vcodec = isset($_POST['wpvp_ffmpeg_vcodec']) ? $_POST['wpvp_ffmpeg_vcodec'] : 'libx264';
	$wpvp_ffmpeg_vpre = isset($_POST['wpvp_ffmpeg_vpre']) ? $_POST['wpvp_ffmpeg_vpre'] : false;
	$wpvp_other_flags = ($_POST['wpvp_other_flags']=='yes') ? true : false; 
	
	$wpvp_ffmpeg_options = array(
		'ar'=>$wpvp_ffmpeg_ar,
		'b_a'=>$wpvp_ffmpeg_b_a,
		'b_v'=>$wpvp_ffmpeg_b_v,
		'ac'=>$wpvp_ffmpeg_ac,
		'acodec'=>$wpvp_ffmpeg_acodec,
		'vcodec'=>$wpvp_ffmpeg_vcodec,
		'vpre'=>$wpvp_ffmpeg_vpre,
		'other_flags'=>$wpvp_other_flags
	);
	update_option('wpvp_ffmpeg_options',$wpvp_ffmpeg_options);
	/* FFMPEG options */
	update_option('wpvp_video_width', $wpvp_width);
	update_option('wpvp_video_height', $wpvp_height);
	update_option('wpvp_thumb_width', $wpvp_thumb_width);
	update_option('wpvp_thumb_height', $wpvp_thumb_height);
	update_option('wpvp_capture_image', $wpvp_capture_image);
	update_option('wpvp_ffmpeg_path', $wpvp_ffmpeg_path);
	update_option('wpvp_mp4box_path', $wpvp_mp4box_path);
	update_option('wpvp_main_loop_alter', $wpvp_main_loop_alter);
	update_option('wpvp_debug_mode', $wpvp_debug_mode);
	update_option('wpvp_player',$wpvp_player);
	update_option('wpvp_autoplay',$wpvp_autoplay);
	update_option('wpvp_audio',$wpvp_audio);
	update_option('wpvp_splash',$wpvp_splash);
	update_option('wpvp_clean_url',$wpvp_clean_url);
	update_option('wpvp_encode_format',$wpvp_encode_format);
?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else {
	$wpvp_width = get_option('wpvp_video_width',640) ? get_option('wpvp_video_width',640) : 640;
	$wpvp_height = get_option('wpvp_video_height',360) ? get_option('wpvp_video_height',360) : 360;
	$wpvp_thumb_width = get_option('wpvp_thumb_width',640) ? get_option('wpvp_thumb_width',640) : 640;
	$wpvp_thumb_height = get_option('wpvp_thumb_height',360) ? get_option('wpvp_thumb_height','360') : 360;
	$wpvp_capture_image = get_option('wpvp_capture_image',5)? get_option('wpvp_capture_image','5') : 5;
	$wpvp_ffmpeg_path = get_option('wpvp_ffmpeg_path');
	$wpvp_mp4box_path = get_option('wpvp_mp4box_path');
	$wpvp_main_loop_alter = get_option('wpvp_main_loop_alter','yes') ? true : false;
	$wpvp_debug_mode = get_option('wpvp_debug_mode',0) ? 1 : 0;
	$wpvp_player = get_option('wpvp_player','videojs') ? get_option('wpvp_player','videojs') : 'videojs';
	$wpvp_autoplay = get_option('wpvp_autoplay',false) ? get_option('wpvp_autoplay',false) : false;
	$wpvp_audio = get_option('wpvp_audio',100) ? get_option('wpvp_audio',100) : 100;
	$wpvp_splash = get_option('wpvp_splash',true) ? get_option('wpvp_splash',true) : true;
	$wpvp_clean_url = get_option('wpvp_clean_url',false) ? get_option('wpvp_clean_url',false) : false;
	$wpvp_encode_format = get_option('wpvp_encode_format',false) ? get_option('wpvp_encode_format') : array();
	/* FFMPEG options */
	$wpvp_ffmpeg_options = array();
	$wpvp_ffmpeg_options = get_option('wpvp_ffmpeg_options',array()) ? get_option('wpvp_ffmpeg_options') : array();
	$wpvp_ffmpeg_ar = isset($wpvp_ffmpeg_options['ar']) ? $wpvp_ffmpeg_options['ar'] : 44100;
	$wpvp_ffmpeg_b_a = isset($wpvp_ffmpeg_options['b_a']) ? $wpvp_ffmpeg_options['b_a'] : 384;
	$wpvp_ffmpeg_b_v = isset($wpvp_ffmpeg_options['b_v']) ? $wpvp_ffmpeg_options['b_v'] : 384;
	$wpvp_ffmpeg_ac = isset($wpvp_ffmpeg_options['ac']) ? $wpvp_ffmpeg_options['ac'] : 2;
	$wpvp_ffmpeg_acodec = isset($wpvp_ffmpeg_options['acodec']) ? $wpvp_ffmpeg_options['acodec'] : 'libfdk_aac';
	$wpvp_ffmpeg_vcodec = isset($wpvp_ffmpeg_options['vcodec']) ? $wpvp_ffmpeg_options['vcodec'] : 'libx264';
	$wpvp_ffmpeg_vpre = isset($wpvp_ffmpeg_options['vpre']) ? $wpvp_ffmpeg_options['vpre'] : false;
	$wpvp_other_flags = isset($wpvp_ffmpeg_options['other_flags']) ? $wpvp_ffmpeg_options['other_flags'] : false;
}
?>
<div class="wrap">
	<?php	echo "<h2>" . __( 'WP Video Posts - General Options' ) . "</h2>";?>

	<!-- PayPal Donate -->
	<?php echo "<h3>Please donate if you enjoy this plugin (WPVP):</h3>"; ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="J535UTFPCXFQC">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	<hr>
	<?php 
	$exts = array(
		'FFMPEG'=>$ffmpeg_ext,
		'MP4Box'=>$mp4box_ext
	);
	?>
	<div class="wpvp_system_stats">
		<h4><?php _e('System Environment');?></h4>
		<ul>
			<?php 
			if(is_array($_SERVER)):?>
				<li><strong><?php _e('Server');?></strong> <span><?php echo $_SERVER['SERVER_SOFTWARE'];?></span></li>
			<?php
			endif;
			?>
			<?php if(function_exists('phpversion')):?>
			<li><strong><?php _e('PHP');?></strong> <span><?php echo phpversion();?></span></li>
			<?php endif;?>
			<?php 
			$exec_c = $helper->wpvp_check_function('exec');?>
			<li><strong><?php _e('EXEC');?></strong> <span><?php if($exec_c){ echo '<span class="true">ENABLED</span>';} else { echo '<span class="false">DISABLED</span>';}?></span></li>
			<?php 
			foreach($exts as $k=>$ext):?>
				<li><strong><?php echo $k;?></strong> <span class="<?php echo strtolower($k);?>"><?php if($ext) { echo '<span class="true">FOUND</span>';} else { echo '<span class="false">NOT FOUND</span>';}?></span></li>
	<?php	endforeach;?>
		</ul>
		<input type="button" value="Re-check FFMPEG" class="recheckExt" />
	</div>
	<?php 	
	if(!$ffmpeg_ext){
		echo '<h3 style="color: red;font-size: 12px;font-weight: normal;width: 300px;">FFMPEG test encoding failed. Possible reasons: restricted permissions on /test/ directory within the plugin, incorrectly configured ffmpeg, etc. FFMPEG is not found on the server. The only extensions available for uploading: mp4.<br />Please verify with your administrator or hosting provider to have this installed and configured. If ffmpeg is installed but you still see this message, specify the path to ffmpeg installation below:</h3><br />';
	}?>	
	<form name="wpvp_form" class="wpvp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wpvp_hidden" value="Y">
		<p>
            <strong><?php _e("Path to ffmpeg installation (optional): " ); ?></strong>
            <input type="text" name="wpvp_ffmpeg_path" value="<?php echo $wpvp_ffmpeg_path; ?>" size="25" /> <?php _e("(example: /usr/local/bin/)"); ?>
        </p>
		<p>
            <strong><?php _e("Path to MP4Box installation (optional): " ); ?></strong>
            <input type="text" name="wpvp_mp4box_path" value="<?php echo $wpvp_mp4box_path; ?>" size="25" /> <?php _e("(example: /usr/local/bin/)"); ?>
        </p>
		<p>
            <strong><?php _e("Converted video width: " ); ?></strong>
			<input type="text" class="wpvp_input_short" name="wpvp_video_width" value="<?php echo $wpvp_width; ?>" size="5" /> <?php _e("(in pixels) Default 640px"); ?>
		</p>
		<p>
            <strong><?php _e("Converted video height: " ); ?></strong>
            <input type="text" class="wpvp_input_short" name="wpvp_video_height" value="<?php echo $wpvp_height; ?>" size="5" /> <?php _e("(in pixels) Default 360px"); ?> 
        </p>
		<p>
            <strong><?php _e("Converted video thumbnail width: " ); ?></strong>
            <input type="text" class="wpvp_input_short" name="wpvp_thumb_width" value="<?php echo $wpvp_thumb_width; ?>" size="5" /> <?php _e("(in pixels) Default 640px"); ?> 
        </p>
		<p>
            <strong><?php _e("Converted video thumbnail height: " ); ?></strong>
            <input type="text" class="wpvp_input_short" name="wpvp_thumb_height" value="<?php echo $wpvp_thumb_height; ?>" size="5" /> <?php _e("(in pixels) Default 360px"); ?>
        </p>
		<p>
            <strong><?php _e("Capture Splash Image: " ); ?></strong>
            <input type="text" class="wpvp_input_short" name="wpvp_capture_image" value="<?php echo $wpvp_capture_image; ?>" size="5" /> <?php _e("(in seconds) Default 5 seconds"); ?>
        </p>
		<p>
            <strong><?php _e('Use the following player:');?>&nbsp;&nbsp;&nbsp;</strong>
			<input type="radio" value="videojs" name="wpvp_player" <?php if($wpvp_player == "videojs") { echo 'checked="checked"'; } ?>> Video JS (HTML5)&nbsp;&nbsp;
            <input type="radio" value="flowplayer" name="wpvp_player" <?php if($wpvp_player == "flowplayer") { echo 'checked="checked"'; } ?>> Flowplayer (Flash) <em>[<?php _e('not updated');?>]</em>
        </p>
		<p>
			<strong><?php _e('Video JS Controls');?></strong>
			<p><input type="checkbox" name="wpvp_autoplay" value="yes" <?php checked($wpvp_autoplay,1);?> /> <?php _e('Autoplay video');?></p>
			<p>
			<?php $range = range(0,100,10);?>
				<select name="wpvp_audio" id="wpvp_audio">
				<?php foreach($range as $num){
					echo '<option value="'.$num.'" '.selected($wpvp_audio,$num).'>'.$num.'</option>';
				}?>
  				</select> <?php _e('Audio Level');?>
			</p>
			<p><input type="checkbox" name="wpvp_splash" value="yes" <?php checked($wpvp_splash,1);?> /> <?php _e('Display splash image');?></p>
		</p>
		<p>
			<strong><?php _e('Video posts within the main loop (e.g. latest posts, tags, categories, etc.)');?></strong>
			<p><input type="checkbox" name="wpvp_main_loop_alter" value="yes" <?php checked($wpvp_main_loop_alter,1);?> /> <?php _e('display the video posts');?></p>
		</p>
		<p>
            <strong><?php _e('"Clean" url for video posts (no \'/videos/\' before post slug)');?></strong>
            <p><input type="checkbox" name="wpvp_clean_url" value="yes" <?php checked($wpvp_clean_url,1);?> /> <?php _e('use "clean" url');?></p>
        </p>
		<p>
			<strong><?php _e("Allowed video extensions for uploading");?></strong>
			<ul>
			<?php 
				$allowed = get_allowed_mime_types();
				foreach($allowed as $key=>$value){
					$t = explode('/',$value);
					if($t[0]=='video'){
						$types .= '<li>-'.$key.' ('.$value.')';
					}
				}	
				echo $types;
			?>
			</ul>
		</p>
		<?php if($ffmpeg_ext):
		?>
		<p style="display:none;">
			<strong><?php _e('Encode to additional formats:');?></strong>
			<input type="checkbox" name="wpvp_encode_format[]" value="webm" <?php checked(in_array('webm',$wpvp_encode_format),1);?> /> <?php _e('webm');?><br />
			<input type="checkbox" name="wpvp_encode_format[]" value="ogg" <?php checked(in_array('ogg',$wpvp_encode_format),1);?> /> <?php _e('ogg/ogv');?>
		</p>
		<p>
			<h3><?php _e('Advanced Options for FFMPEG');?></h3>
			<p><?php _e('Leave an input blank to disable the flag. Modify only if you know what you\'re doing');?></p>
			<ul class="wpvp_ffmpeg_options">
				<li>
					<strong><?php _e('Set the audio sampling frequency. Default is the freqency of the input stream');?></strong>
					<input type="text" name="wpvp_ffmpeg_ar" value="<?php echo $wpvp_ffmpeg_ar;?>" placeholder="44100" /> <?php _e('<span>(-ar)</span>');?>
				</li>
				<li>
					<strong><?php _e('Set the audio bitrate of the output file (in kbit/s)');?></strong>
					<input type="text" name="wpvp_ffmpeg_b_a" value="<?php echo $wpvp_ffmpeg_b_a;?>" placeholder="384" /> <?php _e('<span>(-b:a)</span>');?>
				</li>
				<li>
					<strong><?php _e('Set the video bitrate of the output file (in kbit/s)');?></strong>
					<input type="text" name="wpvp_ffmpeg_b_v" value="<?php echo $wpvp_ffmpeg_b_v;?>" placeholder="384" /> <?php _e('<span>(-b:v)</span>');?>
				</li>
				<li>
					<strong><?php _e('Set the number of audio channels');?></strong>
					<input type="text" name="wpvp_ffmpeg_ac" value="<?php echo $wpvp_ffmpeg_ac;?>" placeholder="2" /> <?php _e('<span>(-ac)</span>');?>
				</li>
				<li>
					<strong><?php _e('Use the following audio codec for encoding');?></strong>
					<input type="text" name="wpvp_ffmpeg_acodec" value="<?php echo $wpvp_ffmpeg_acodec;?>" placeholder="libfdk_aac" /> <?php _e('<span>(-acodec)</span>');?>
				</li>
				<li>
					<strong><?php _e('Use the following video codec for encoding');?></strong>
					<input type="text" name="wpvp_ffmpeg_vcodec" value="<?php echo $wpvp_ffmpeg_vcodec;?>" placeholder="libx264" /> <?php _e('<span>(-vcodec)</span>');?>
				</li>
				<li>
					<strong><?php _e('Specify a video preset (default: none)');?></strong>
					<?php $presets = array('ultrafast','superfast','veryfast','faster','fast','medium','normal','slow','slower','veryslow','placebo');?>
					<select name="wpvp_ffmpeg_vpre">
						<option value="0"<?php if(!$wpvp_ffmpeg_vpre){ echo ' selected';}?>><?php _e('none');?></option>
						<?php 
						foreach($presets as $preset):?>
							<option value="<?php echo $preset;?>" <?php selected($wpvp_ffmpeg_vpre,$preset);?>><?php echo $preset;?></option>
					<?php	endforeach;
						?>
					</select> <?php _e('<span>(-vpre)</span>');?>
				</li>
				<li>
					<strong><?php _e('Disable other passed flags <span>(useful when debugging ffmpeg)</span>');?></strong><br />
					<div class="wpvp-new-line"><input type="checkbox" name="wpvp_other_flags" value="yes" <?php checked($wpvp_other_flags,1);?> /> <?php _e('disable other passed flags <span>(-refs, -coder, -level, -threads, -partitions, -flags, -trellis, -cmp, -me_range, -sc_threshold, -i_qfactor, -bf, -g)</span>');?></div>
				</li>
			</ul>
		</p>
		<?php endif;?>
		<p>
			<strong><span style="color:red;"><?php _e('Debug Mode:');?></span></strong>
			<p><input type="checkbox" name="wpvp_debug_mode" value="yes" <?php checked($wpvp_debug_mode,1);?>/> <?php _e('enable <span>(the debugging results will be written to /tmp/debug.ffmpeg.log and when the file exists the contents will be displayed below)</span>');?></p>
		</p>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options' ) ?>" />
		</p>
    </form>
	<?php if($wpvp_debug_mode&&file_exists('/tmp/debug.ffmpeg.log')){
	?>
	<h3><?php _e('Contents of debug.ffmpeg.log:');?></h3>
	<div style="height:400px;overflow:scroll;width:700px;background-color: #ffffe0;border: 1px #e6db55 solid;padding: 0 15px;">
		<pre><?php echo file_get_contents('/tmp/debug.ffmpeg.log');?></pre>
	</div>
	<?php	
	}?>
</div>
