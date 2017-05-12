<?php
/*Helper class*/ 
class WPVP_Helper{
	/**
	*check if ffmpeg extension is installed on the server
	*@access public
	*/
    public function wpvp_command_exists_check($command,$force=false){
        $command = escapeshellarg($command);
		$ffmpeg_path = get_option('wpvp_ffmpeg_path','');
		$check = true;
		if(!$force){
			$wpvp_ffmpeg_exists = get_option('wpvp_ffmpeg_exists',false) ? get_option('wpvp_ffmpeg_exists',false) : false;
			if($wpvp_ffmpeg_exists){
				$return = true;
				$check = false;
			}
		} else {
			$check = true;
		}
		if($check):
			$extra = '-vframes 1 -ss 2 -f image2';
			$source = plugin_dir_path( __FILE__ ).'test/ffmpeg_test_video.mp4';
			if(file_exists($source.'.jpg'))
				unlink($source.'.jpg');
			$dir = plugin_dir_path( __FILE__ ).'test';
			if (!is_dir($dir)):
				chmod($dir, 0777);
			endif;
			$str = $ffmpeg_path."ffmpeg -y -i ".$source." ". $extra ." ".$source.'.jpg';
			exec($str);
			if(file_exists($source.'.jpg')){
				$return = true;
				update_option('wpvp_ffmpeg_exists', true);
			} else {
				update_option('wpvp_ffmpeg_exists', false);
			}
		endif;
		return $return;
    }
	/**
	*Check for exec being enabled
	*@access public
	*/
	public function wpvp_check_function($func){
		$enabled = false;
		if(function_exists($func)){
			if(!in_array($func, array_map('trim',explode(', ', ini_get('disable_functions'))))){
				$enabled = true;
			}
		}
		return $enabled;
	}
	/**
	*Check if extension is present (use 'which')
	*@access public
	*/
	public function wpvp_check_extension($ext){
		$ffmpeg_path = get_option('wpvp_ffmpeg_path','');
		$mp4box_path = get_option('wpvp_mp4box_path','');
		if($this->wpvp_check_function('exec')){
			if($ext=='ffmpeg')
				$ext = $ffmpeg_path.$ext;
			else if($ext=='MP4Box')
				$ext = $mp4box_path.$ext;
			exec("which ".$ext,$output);
			return $output;
		} else {
			return 'exec is disabled on the server';
		}
	}
	/**
	*Get plugin's options and return them in array
	*@access public
	*/		
	public function wpvp_get_full_options(){
        $wpvp_options = array();
		$default_ext = array('video/mpeg','video/mp4');	
	    $video_width = get_option('wpvp_video_width','640');
        $video_height = get_option('wpvp_video_height','360');
	    $thumb_width = get_option('wpvp_thumb_width','640');
        $thumb_height = get_option('wpvp_thumb_height','360');
	    $capture_image = get_option('wpvp_capture_image','5');
        $ffmpeg_path = get_option('wpvp_ffmpeg_path','');
		$mp4box_path = get_option('wpvp_mp4box_path','');
		$debug_mode = (bool)get_option('wpvp_debug_mode',0);
		$allowed_extensions = get_option('wpvp_allowed_extensions',$default_ext);
		$encode_formats = get_option('wpvp_encode_format',false) ? get_option('wpvp_encode_format') : array();
		$wpvp_ffmpeg_options = array();
		$wpvp_ffmpeg_options = get_option('wpvp_ffmpeg_options',array()) ? get_option('wpvp_ffmpeg_options') : array();
		$wpvp_ffmpeg_ar = isset($wpvp_ffmpeg_options['ar']) ? $wpvp_ffmpeg_options['ar'] : 44100;
		$wpvp_ffmpeg_b_a = isset($wpvp_ffmpeg_options['b_a']) ? $wpvp_ffmpeg_options['b_a'] : 384;
		$wpvp_ffmpeg_b_v = isset($wpvp_ffmpeg_options['b_v']) ? $wpvp_ffmpeg_options['b_v'] : 384;
		$wpvp_ffmpeg_ac = isset($wpvp_ffmpeg_options['ac']) ? $wpvp_ffmpeg_options['ac'] : 2;
		$wpvp_ffmpeg_acodec = isset($wpvp_ffmpeg_options['acodec']) ? $wpvp_ffmpeg_options['acodec'] : 'libfdk_aac';
		$wpvp_ffmpeg_vcodec = isset($wpvp_ffmpeg_options['vcodec']) ? $wpvp_ffmpeg_options['vcodec'] : 'libx264';
		$wpvp_ffmpeg_vpre = isset($wpvp_ffmpeg_options['vpre']) ? $wpvp_ffmpeg_options['vpre'] : 0;
		$wpvp_ffmpeg_other_flags = isset($wpvp_ffmpeg_options['other_flags']) ? $wpvp_ffmpeg_options['other_flags'] : 0;
		$wpvp_ffmpeg_exists = get_option('wpvp_ffmpeg_exists',0);
		
		$wpvp_options['wpvp_ffmpeg_ar']=$wpvp_ffmpeg_ar;
		$wpvp_options['wpvp_ffmpeg_b_a']=$wpvp_ffmpeg_b_a;
		$wpvp_options['wpvp_ffmpeg_b_v']=$wpvp_ffmpeg_b_v;
		$wpvp_options['wpvp_ffmpeg_ac']=$wpvp_ffmpeg_ac;
		$wpvp_options['wpvp_ffmpeg_acodec']=$wpvp_ffmpeg_acodec;
		$wpvp_options['wpvp_ffmpeg_vcodec']=$wpvp_ffmpeg_vcodec;
		$wpvp_options['wpvp_ffmpeg_vpre']=$wpvp_ffmpeg_vpre;
		$wpvp_options['wpvp_ffmpeg_other_flags']=$wpvp_ffmpeg_other_flags;
	    $wpvp_options['video_width']=$video_width;
        $wpvp_options['video_height']=$video_height;
	    $wpvp_options['thumb_width']=$thumb_width;
        $wpvp_options['thumb_height']=$thumb_height;
	    $wpvp_options['capture_image']=$capture_image;
        $wpvp_options['ffmpeg_path']=$ffmpeg_path;
		$wpvp_options['mp4box_path']=$mp4box_path;
		$wpvp_options['debug_mode']=$debug_mode;
		$wpvp_options['encode_formats']=$encode_formats;
		$wpvp_options['ffmpeg_exists'] = $wpvp_ffmpeg_exists;
	    return $wpvp_options;
	}
	/**
    *Call wpvp_dump($data) function for debugging
    *@access public
    */
    public function wpvp_dump($data){
        return error_log( date ( 'r -> ', time() ) . print_r($data,true) . "\n" , 3, "/tmp/debug.ffmpeg.log");
    }
	/**
	*Returns extension of the mime_type
	*@access public
	*/
    public function is_video($mime_type){
        $type = explode("/", $mime_type);
        return strtolower($type[0]);
    }
	/**
    *Return file extension
    *@access public
    */
    public function guess_file_type ($filename) {
        return strtolower(array_pop(explode('.',$filename)));
    }
	/**
	*limit words in string
	*@access public
	*/
    public function wpvp_string_limit_words($string, $word_limit){
        $words = explode(' ', $string, ($word_limit + 1));
        if(count($words) > $word_limit)
            array_pop($words);
        return implode(' ', $words);
    }
	/**
	*check if upload is allowed for this user role
	*@access public
	*/
    public function wpvp_is_allowed() {
        //get User Privileges Options
        $allow_guest = get_option('wpvp_allow_guest', 'yes');
        $allowed_user_roles = get_option('wpvp_uploader_roles');
        if(empty($allowed_user_roles) || !isset($allowed_user_roles))
			$allowed_user_roles[0] = "Administrator";
        if($allow_guest == 'yes') {
            return true;
        } else {
            global $user_login;
            if(!$user_login) {
                return false;
            } else {
				if(is_array($allowed_user_roles)) {
					$current_user_role = $this->wpvp_get_current_user_role();
					if(in_array($current_user_role, $allowed_user_roles))
						return true;
					else
						return false;
                }
            } //user login check
        } //guess check
    }
	/**
	*check current user role
	*@access public
	*/
	protected function wpvp_get_current_user_role() {
		global $wp_roles;
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);
		return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role]) : false;
	}
	/**
	*check for max upload size based on php.ini settings
	*@access static
	*/
	static function wpvp_max_upload_size($type=true) {
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		if($type)
			$upload_mb = $upload_mb.'MB';
		return $upload_mb;
	}
	/**
	*convert to bytes
	*@access static
	*/
	static function wpvp_return_bytes($val) {
		$val = trim($val);
		switch (strtolower(substr($val, -1))){
			case 'm': $val = (int)substr($val, 0, -1) * 1048576; break;
			case 'k': $val = (int)substr($val, 0, -1) * 1024; break;
			case 'g': $val = (int)substr($val, 0, -1) * 1073741824; break;
			case 'b':
			switch (strtolower(substr($val, -2, 1))){
				case 'm': $val = (int)substr($val, 0, -2) * 1048576; break;
				case 'k': $val = (int)substr($val, 0, -2) * 1024; break;
				case 'g': $val = (int)substr($val, 0, -2) * 1073741824; break;
				default : break;
			} break;
			default: break;
		}
		return $val;
	}
	/**
	*function to add code to the post meta and update on post update if needed on publish_videos custom post type action hook
	*@access public
	*/
	public function wpvp_video_code_add_meta($id){
		$id = (int)$id;
		if($_POST['post_content']==''){
			$postObj = get_post($id);
			$post_content = $postObj->post_content;
			$post_type = $postObj->post_type;
		} else {
			global $post;
			$post_content = $_POST['post_content'];
			$post_type = $_POST['post_type'];
		}
		if(!wp_is_post_revision($id)){
			$post_id = $id;
			if($post_type== 'videos'){
				//if( (preg_match('/youtube/', $post_content)) || (preg_match('/vimeo/', $post_content)) ){
				if(preg_match('/wpvp_embed/',$post_content)){
					$regex_pattern = get_shortcode_regex();
					preg_match ('/'.$regex_pattern.'/s', $post_content, $regex_matches);
					if ($regex_matches[2] == 'wpvp_embed' || $regex_matches[2] == 'wpvp_player') :
						$attributeStr = str_replace (" ", "&", trim(stripslashes($regex_matches[3])));
						$attributeStr = str_replace ('"', '', $attributeStr);
						$defaults = array (
							'video_code' => ''
						);
						$attributes = wp_parse_args ($attributeStr, $defaults);
					endif;
					$video_code = '';
					if(isset($attributes['video_code']))
						$video_code = $attributes['video_code'];
					update_post_meta($post_id, 'wpvp_video_code',$video_code);
				} else if(preg_match('/wpvp_flowplayer/',$post_content)||preg_match('/wpvp_player/',$post_content)){
					$regex_pattern = get_shortcode_regex();
					preg_match ('/'.$regex_pattern.'/s', $post_content, $regex_matches);
					if ($regex_matches[2] == 'wpvp_flowplayer' || $regex_matches[2] == 'wpvp_player') :
						$attributeStr = str_replace (" ", "&", trim(stripslashes($regex_matches[3])));
						$attributeStr = str_replace ('"', '', $attributeStr);
						$defaults = array (
							'src' => '',
							'splash' => '',
							'width' => '',
							'height' => ''
						);
						$attributes = wp_parse_args ($attributeStr, $defaults);
						$fl_codes = array();
						if(isset($attributes['src']))
							$fl_codes['src'] = $attributes['src'];
						if(isset($attributes['splash']))
							$fl_codes['splash'] = $attributes['splash'];
						if(isset($attributes['width']))
							$fl_codes['width'] = $attributes['width'];
						if(isset($attributes['height']))
							$fl_codes['height'] = $attributes['height'];
					endif;
					$fl_codes = json_encode($fl_codes);
					update_post_meta($post_id, 'wpvp_fp_code',$fl_codes);
				}
			}
		}
	}
	/**
	*Output categories allowed for front-end uploading
	*@access static
	**/
	static function wpvp_upload_categories_dropdown($echo=true,$selected=0){
		$html = '';
		$wpvp_uploader_cats = get_option('wpvp_uploader_cats','');
		if($wpvp_uploader_cats==''){
			$uploader_cats = '';
		} else {
			$uploader_cats = implode(", ",$wpvp_uploader_cats);
		}
		$args = array('hide_empty'=>0,'include'=>$uploader_cats);
		$categories = get_categories($args);
		if(is_array($categories)&&!empty($categories)):
			$html = '<select name="wpvp_category" class="wpvp_select">';
			foreach($categories as $category){
				$html .= '<option ';
				$html .= ' value="'.$category->term_id.'" '.selected($selected,$category->term_id,false).'>';
				$html .= $category->cat_name.'</option>';
			}
			$html.= '</select>';
		endif;
		if($echo)
			echo $html;
		else
			return $html;
	}
	/**
	*Load template file (include or require)
	*@access static
	**/
	static function wpvp_load_template_file($file_name,$include=true,$data=array()){
		$file_path = get_template_directory().'/'.$file_name.'.php';
		$plugin_base = plugin_basename( __FILE__ );
		$plugin_base = explode('/',$plugin_base);
		if(!file_exists($file_path)){
			$file_path = plugin_dir_path(dirname(__FILE__)).'templates/'.$file_name.'.php';
		}
		if(file_exists($file_path)):
			if($include)
				include($file_path);
			else
				require $file_path;
		endif;
	}
}
?>
