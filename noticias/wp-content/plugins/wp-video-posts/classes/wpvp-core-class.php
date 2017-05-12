<?php 
class WPVP_Encode_Media{
	protected $options;
	function __construct($options=array()){
		$default = array(
			'api_key'=>'',
			'video_width'=>640,
			'video_height'=>360,
			'thumb_width'=>640,
			'thumb_height'=>360,
			'caption_image'=>5,
			'ffmpeg_path'=>'',
			'mp4box_path'=>'',
			'wpvp_ffmpeg_ar'=>44100,
			'wpvp_ffmpeg_b_a'=>384,
			'wpvp_ffmpeg_b_v'=>384,
			'wpvp_ffmpeg_ac'=>2,
			'wpvp_ffmpeg_acodec'=>'libfdk_aac',
			'wpvp_ffmpeg_vcodec'=>'libx264',
			'wpvp_ffmpeg_vpre'=>0,
			'wpvp_ffmpeg_other_flags'=>0
		);
		foreach ($default as $key => $value)
			$this->options[$key] = key_exists($key,$options) ? $options[$key] : $value;
	}
	/**
	*main function to process encoding via ffmpeg for the front end uploader and the backend
	*@access public
	*/
	public function wpvp_encode($ID,$front_end_postID=NULL){
		global $encodeFormat, $shortCode;
		$videoPostID = 0;
		//$ID is an attachment post passed from attachment processing
		$helper = new WPVP_Helper();
        $options = $helper->wpvp_get_full_options();
		$ffmpeg_exists = $options['ffmpeg_exists'];
	
		$width = $options['video_width'];
		$height = $options['video_height'];
		$ffmpeg_path = $options['ffmpeg_path'];
		$mp4box_path = $options['mp4box_path'];
		$debug_mode = $options['debug_mode'];
		$allowed_ext = array('mp4','flv');

		$encodeFormat = 'mp4'; // Other formats will be available soon...
		// Handle various formats options here...
		if ($encodeFormat=='flash') {
			$extension = '.flv'; 
			$thumbfmt  = '.jpg';
		}
		else if ($encodeFormat=='mp4') {
            $extension = '.mp4';
            $thumbfmt  = '.jpg';
		}
		
		//Get the attachment details (we can access the items individually)
        $postDetails = get_post($ID);
		//check if attachment is video
		if($helper->is_video($postDetails->post_mime_type)=='video') {
			$upload_dir = wp_upload_dir();
			$uploadPath = $upload_dir['path'];
			$uploadUrl = $upload_dir['url'];
			$originalFilePath = get_attached_file( $ID );
			//get the path to the ORIGINAL file
			$fileDetails = pathinfo($originalFilePath);
			
			$fileExtension = $fileDetails['extension'];
			//check if ffmpeg exists and if video extension is allowed
			if(!in_array($fileExtension,$allowed_ext)&&!$ffmpeg_exists){
				//do not proceed
				if($debug_mode){
                    $helper->wpvp_dump('No FFMPEG found. Only mp4 and flv extensions are supported. The currently uploaded extension ('.$fileExtension.') is not supported. Please encode the file manually and reupload.');
                }
				return;
			} else {
				//debug_mode is true
				if($debug_mode){
					$helper->wpvp_dump('Initial file details...');
					$helper->wpvp_dump($fileDetails);
				}
				//normalize the file name and make sure its not a duplicate
				$fileFound = true;
				$i = '';
				while($fileFound){
					$fname = $fileDetails['filename'].$i;
	        		$newFile = $uploadPath .'/'.$fname.$extension;
		        	$guid = $uploadUrl . '/' . $fname.$extension;
                	$newFileTB = $uploadPath .'/'.$fname.$thumbfmt;
	        	    $guidTB = $uploadUrl . '/' . $fname.$thumbfmt;
			        if ($ffmpeg_exists){
        		      	$file_encoded = 1;
							if(file_exists($newFile))
        	        		    $i = $i=='' ? 1 : $i+1;
	                        else
	                        	$fileFound = false;
					} else{
                		$file_encoded = 0;
		        		$fileFound = false;
                	}	
				}//while fileFound ends
				
				//debug_mode is true
                if($debug_mode){
        	        $helper->wpvp_dump('New files path on the server: video and image ...');
                    $helper->wpvp_dump('video: '.$newFile);
	                $helper->wpvp_dump('image: '.$newFileTB);
					$helper->wpvp_dump('New files url on the server: video and image ...');
        	        $helper->wpvp_dump('video: '.$guid);
                    $helper->wpvp_dump('image: '.$guidTB);
	            }
				if($file_encoded) {
					if($debug_mode){
						$helper->wpvp_dump('FFMPEG found on the server. Encoding initializing...');
					}
					//ffmpeg to get a thumb from the video
					$this->wpvp_convert_thumb($originalFilePath,$newFileTB);
					//ffmpeg to convert video
					$this->wpvp_convert_video($originalFilePath, $newFile, $encodeFormat);
					//pathinfo on the FULL path to the NEW file
					if($debug_mode){
						if(!file_exists($newFile)){
							$helper->wpvp_dump('Video file was not converted. Possible reasons: missing libraries for ffmpeg, permissions on the directory where the file is being written to...');
						} else {
							$helper->wpvp_dump('Video was converted: '.$newFile);
						}
						if(!file_exists($newFileTB)){
        	                $helper->wpvp_dump('Thumbnail was not created. Possible reasons: missing libraries for ffmpeg, permissions on the directory where the file is being written to...');
                	    } else {
							$helper->wpvp_dump('Thumbnail was created: '.$newFileTB);
						}
					}
					//update attachment file
					$updated = update_attached_file( $ID, $newfile );
				} else {
					if($debug_mode){
						$helper->wpvp_dump('FFMPEG is not found on the server. Possible reasons: not installed, not properly configured, the path is not provided correctly in the plugin\'s options settings...');
					}
					$defaultImg = get_option('wpvp_default_img','') ? get_option('wpvp_default_img') : '';
					if($defaultImg!=''):
						$newFileTB = $defaultImg;
						$guidTB = str_replace($uploadPath,$uploadUrl,$newFileTB);
					else: 
						$default_img_path = $uploadPath.'/default_image.jpg';
						copy(plugin_dir_path( dirname(__FILE__)).'images/default_image.jpg',$default_img_path);
						if(file_exists($default_img_path)):
							update_option('wpvp_default_img', $default_img_path);
							$newFileTB = $default_img_path;
							$guidTB = str_replace($uploadPath,$uploadUrl,$default_img_path);
						endif;
					endif;
					$guid = str_replace($uploadPath,$uploadUrl,$originalFilePath);
					$newFile = $originalFilePath;
				} //no ffmpeg - no encoding
				$newFileDetails = pathinfo($newFile);
                $newTmbDetails  = pathinfo($newFileTB);
				//shortcode for the player
				$shortCode  = '[wpvp_player src='.$guid.' width='.$width.' height='.$height.' splash='.$guidTB.']';	
				//update the auto created post with our data
				if(empty($front_end_postID)){
					$postID = intval($_REQUEST['post_id']);
				} else {
					$postID = $front_end_postID;
				}
				$videoPostID = $postID;
				$postObj = get_post($videoPostID);
                $currentContent = $postObj->post_content;
				$newContent = $shortCode.$currentContent;
				$videopost = array(
					'ID' => $postID,
					'post_content' => $newContent
				);
				//update video post with a shortcode inserted in the content
				$updatedPost = wp_update_post($videopost);
				//add a thumbnail attachment and set as featured image
				$img_filetype = wp_check_filetype($newTmbDetails['basename'], null );
				
				$attachment = array(
					'guid' => $guidTB, 
					'post_mime_type' => $img_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', $newTmbDetails['basename']),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$att_id = wp_insert_attachment( $attachment, $newFileTB, $updatedPost );
				if($att_id):
				$attach_data = wp_generate_attachment_metadata( $att_id, $newFileTB );
					wp_update_attachment_metadata( $att_id,  $attach_data );
					add_post_meta($updatedPost, '_thumbnail_id', $att_id);
					update_post_meta($updatedPost, '_wp_attached_file', $newFileTB);
				endif;
			
				//add the video file as attachment for the post 
				//$video_filetype = wp_check_filetype($newFileDetails['basename']);
				//hardcode for now
				$video_filetype['type'] = 'video/mp4';
				$video_post = array(
					'post_title' => preg_replace('/\.[^.]+$/', '', $newFileDetails['basename']),
					'guid' => $guid,
					'post_parent' => $updatedPost,
					'post_mime_type' => $video_filetype['type'],
					'ID' => $ID
				);
				wp_update_post( $video_post );
				update_post_meta($ID, '_wp_attached_file', $newFile);
			       
				if($file_encoded){
					//delete the original file 
					unlink($originalFilePath);
					//rename($originalFileUrl,$newFile);
				}
				if(!$ID){
					return false;
	            } else {
        	       	return $ID;
				}
			}//ffmpeg and uploaded extension is supported
		}//if uploaded attachment is a video
	}
	/**
	*get a thumbnail from the video file with ffmeg
	*@access protected
	*/
	protected function wpvp_convert_thumb($source,$target){
		$helper = new WPVP_Helper();
        $options = $helper->wpvp_get_full_options();
		$debug_mode = $options['debug_mode'];
		$width = $this->options['thumb_width'];
		$height = $this->options['thumb_height'];
		$capture_image = $this->options['capture_image'];
		$ffmpeg_path = $this->options['ffmpeg_path'];
		$dimensions = ($width!=''&&$height!='') ? '-s '.$width.'x'.$height : '';
		$capture_image = $capture_image ? $capture_image : 5;
		$extra = '-vframes 1 '.$dimensions.' -ss '.$capture_image.' -f image2';
		$str = $ffmpeg_path."ffmpeg -y -i ".$source." ". $extra ." ".$target." 2>&1";
		if($debug_mode)
			$helper->wpvp_dump('Image conversion command: '.$str);
		$output = shell_exec($str);
		if($debug_mode){
			$helper->wpvp_dump('Image conversion output:');
			$helper->wpvp_dump($output);
		}
		return $target;
	}
	/**
	*convert video to a specified format
	*@access protected
	*/
	protected function wpvp_convert_video($source,$target,$format='mp4'){
		global $encodeFormat;
		$helper = new WPVP_Helper();
        $options = $helper->wpvp_get_full_options();
		$debug_mode = $options['debug_mode'];
		if($debug_mode)
			$helper->wpvp_dump($this->options);
		$width = $this->options['video_width'];
		$height = $this->options['video_height'];
		$ffmpeg_path = $this->options['ffmpeg_path'];
		$mp4box_path = $this->options['mp4box_path'];
		$dimensions = ($width!=''&&$height!='') ? ' -s '.$width.'x'.$height : '';
		
		$ffmpeg_ar=$this->options['wpvp_ffmpeg_ar'];
		$ffmpeg_b_a=$this->options['wpvp_ffmpeg_b_a'];
		$ffmpeg_b_v=$this->options['wpvp_ffmpeg_b_v'];
		$ffmpeg_ac=$this->options['wpvp_ffmpeg_ac'];
		$ffmpeg_acodec=$this->options['wpvp_ffmpeg_acodec'];
		$ffmpeg_vcodec=$this->options['wpvp_ffmpeg_vcodec'];
		$ffmpeg_vpre=$this->options['wpvp_ffmpeg_vpre'];
		$ffmpeg_other_flags=(int)$this->options['wpvp_ffmpeg_other_flags'];
		
		$extra = $dimentions." ";
		if($ffmpeg_ar!='')
			$extra.=' -ar '.$ffmpeg_ar;
		if($ffmpeg_b_a!='')
			$extra.=' -b:a '.$ffmpeg_b_a.'k';
		if($ffmpeg_b_v!='')
			$extra.=' -b:v '.$ffmpeg_b_v.'k';
		if($ffmpeg_ac!='')
			$extra.=' -ac '.$ffmpeg_ac;
		if($ffmpeg_acodec!='')
			$extra.=' -acodec '.$ffmpeg_acodec;
		if($ffmpeg_vcodec!='')
			$extra.=' -vcodec '.$ffmpeg_vcodec;
		if($ffmpeg_vpre){
			if($ffmpeg_vpre!='0'||$ffmpeg_vpre!='none')
				$extra.=' -vpre '.$ffmpeg_vpre;
		}
		switch($format){
			case 'mp4':
				if(!$ffmpeg_other_flags)
					$extra.= " -refs 1 -coder 1 -level 31 -threads 8 -partitions parti4x4+parti8x8+partp4x4+partp8x8+partb8x8 -flags +mv4 -trellis 1 -cmp 256 -me_range 16 -sc_threshold 40 -i_qfactor 0.71 -bf 0 -g 250";
				$str = $ffmpeg_path."ffmpeg -i ".$source." $extra ".$target." 2>&1";
				break;
			case 'webm':
			
				break;
			case 'ogg':
			
				break;
		}
		if($debug_mode)
			$helper->wpvp_dump('Video conversion command: '.$str);
		$output = shell_exec($str);
		if($debug_mode){
			$helper->wpvp_dump('Video conversion output:');
			$helper->wpvp_dump($output);
		}
		//check for the file. If not created, attempt to execute a simpler command
		if(!file_exists($target)){
			$output = shell_exec($ffmpeg_path."ffmpeg -i ".$source.$dimensions." ".$ffmpeg_acodec." ".$ffmpeg_vcodec." ".$target." 2>&1");
			if($debug_mode)
				$helper->wpvp_dump($output);
		}
		//in case of MP4Box is installed, execute command to move the video data to the front
		$prepare = $mp4box_path."MP4Box -inter 100  ".$target;
		$output = shell_exec($prepare);
		if($debug_mode)
			$helper->wpvp_dump($output);
		return $target;
	}
	/**
	*insert short code into the video post
	*@access public
	*/
	public function wpvp_insert_video_into_post($html, $id, $attachment){
		$helper = new WPVP_Helper();
	    $width = $this->options['video_width'];
		$height = $this->options['video_height'];
		$options = $helper->wpvp_get_full_options();
		$ffmpeg_exists = $options['ffmpeg_exists'];
	    $attachmentID = $id;
        $content = $html;
	    $attachmentObj = get_post($attachmentID);
		         
		$allowed_ext = array('mp4','flv');
      	if($helper->is_video($attachmentObj->post_mime_type)=='video'){
	        $postParentID = $attachmentObj->post_parent;
            $postParentObj = get_post($postParentID);
			
			$attachmentURI = wp_get_attachment_url($attachmentID);
			$attachmentPathInfo = pathinfo($attachmentURI);
			$attachExt = $attachmentPathInfo['extension'];
			//check for allowed extensions without ffmpeg
			if(!in_array($attachExt,$allowed_ext)&&!$ffmpeg_exists){
				$content = __('WPVP_ERROR: FFMPEG is not found on the server. Allowed extensions for the upload are mp4 and flv. Please convert the video and re-upload.');
			} else {
	            //Video with attachment from Media Library
	            $attachments = get_posts(array(
					'post_type'=>'attachment',
					'posts_per_page'=>-1,
					'post_parent'=>$postParentID,
					'post_mime_type'=>'image/jpeg')
				);
				
                if($attachments){
					$imgAttachmentID = $attachments[0]->ID;
        	        $imgAttachment = wp_get_attachment_url($imgAttachmentID);
	            } else{
					$imgAttachment = plugins_url('/images/', dirname(__FILE__)).'default_image.jpg';
                }
                $content = '[wpvp_player src='.$attachmentURI.' width='.$width.' height='.$height.' splash='.$imgAttachment.']';
			}
	    } //Check post mime type = video
        return $content;
	}
	/**
	*embed video from YouTube or Vimeo
	*@access public
	*/
	public function wpvp_video_embed($video_code,$width,$height,$type){
		if($type){
			if($video_code){
				if($type=='youtube'){
					$embedCode = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_code.'" frameborder="0" allowfullscreen></iframe>';
				}
				elseif($type=='vimeo'){
					$embedCode = '<iframe width="'.$width.'" height="'.$height.'" src="http://player.vimeo.com/video/'.$video_code.'" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>';
				}
				$result = $embedCode;
			}
			else{
				$result = '<span style="color:red;">'._e('No video code is found').'</span>';
			}
		}
		else{
			$result = '<span style="color:red;">'._e('The video source is either not set or is not supported').'.</span>';
		}
		return $result;
	}
	/**
	*display widget for video posts
	*@access public
	*/
	public function wpvp_widget_latest_posts($instance){
		$width = $instance['width'] ? $instance['width'] : 165;
		$height = $instance['height'] ? $instance['height'] : 125;
		$num_posts= $instance['num_posts'] ? $instance['num_posts'] : '-1';
		$display = $instance['display'] ? $instance['display'] : 'v';
		$display_type = $instance['display_type'] ? $instance['display_type'] : 'th';
		$post_title = $instance['post_title'] ? $instance['post_title'] : '';
		$author = $instance['author'] ? $instance['author'] : '';
		$excerpt = $instance['excerpt'] ? $instance['excerpt'] : '';
		$excerpt_length = $instance['excerpt_length'] ? $instance['excerpt_length'] : 10;
		if(!empty($instance['cat_checkbox'])){
			$category__in = $instance['cat_checkbox'];
		}
		$args = array(
			'post_type' => 'videos',
			'post_status' => 'publish',
			'posts_per_page' => $num_posts,
			'category__in'=>$category__in
		);
		$vid_posts = new WP_Query($args);
		while($vid_posts->have_posts()):
			$vid_posts->the_post();
			$postID = get_the_ID();
			$video_meta_array = get_post_meta($postID, 'wpvp_video_code', false);
			$video_meta = array_pop($video_meta_array);
			$video_fp_meta_array = get_post_meta($postID, 'wpvp_fp_code',false);
			if(!empty($video_meta_array)||!empty($video_fp_meta_array)){
				if($display=='v'){
					$class = ' wpvp_widget_vert';
					$style = 'width:'.$width.'px';	
				}
				else if($display=='h'){
					$class = ' wpvp_widget_horiz';
					$style = 'width:'.$width.'px';
				}
				if(($display_type=='th')||($display_type=='')){
					if(is_numeric($video_meta)){
						$vimeo_hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_meta.php"));
						$video_img = $vimeo_hash[0]['thumbnail_medium'];
					}
					else if(preg_match('/[a-zA-Z0-9_-]{11}/',$video_meta)){ 
						$video_img = "http://img.youtube.com/vi/".$video_meta."/1.jpg";
					}
					else if($video_meta==''){
						$video_img_attrs = wp_get_attachment_image_src(get_post_thumbnail_id($postID), array($instance['width'],$instance['height']));
						$video_img = $video_img_attrs[0];
						if($video_img==''){
							$video_img = plugins_url('/images/', dirname(__FILE__)).'default_image.jpg';
						}
					}
					$video_item .= '<div class="wpvp_video_item'.$class.'" style="'.$style.'"><a href="'.get_permalink().'"><img src="'.$video_img.'" width="'.$width.'" height="'.$height.'" /></a>';
				} else if($display_type=='p'){
					if(is_numeric($video_meta)){
						//Vimeo code
						$video_player = '<iframe width="'.$width.'" height="'.$height.'" src="http://player.vimeo.com/video/'.$video_meta.'" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"></iframe>';
					}
					else if(preg_match('/[a-zA-Z0-9_-]{11}/',$video_meta)){
						//YouTube code
						$video_player = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_meta.'" frameborder="0" allowfullscreen></iframe>';
					}
					else if($video_meta==''){
						//use flowplayer meta code instead
						$video_meta_array = $video_fp_meta_array;
						$video_meta = array_pop($video_meta_array);
						$video_data_array = json_decode($video_meta,true);
						$src = $video_data_array['src'];
						$splash = $video_data_array['splash'];
						
						$wpvp_player = get_option('wpvp_player','videojs') ? get_option('wpvp_player','videojs') : 'videojs';
						if($wpvp_player=='flowplayer'){
							$flowplayer_code = '<a href="'.$src.'" class="myPlayer" style="display:block;width:'.$width.'px;height:'.$height.'px;margin:10px auto"><img width="'.$width.'" height="'.$height.'" src="'.$splash.'" alt="" /></a>';
						} else if($wpvp_player=='videojs'){
							$autoplay = get_option('wpvp_autoplay',false) ? get_option('wpvp_autoplay',false) : false;
							$splash_check = get_option('wpvp_splash',false) ? get_option('wpvp_splash',false) : false;
							if($autoplay)
								$ap = 'autoplay ';
							else
								$ap = '';
							if($splash_check)
								$sp = 'poster="'.$splash.'"';
							else
								$sp = '';
							$flowplayer_code = '<video id="wpvp_videojs_'.time().'" '.$ap.'class="video-js vjs-default-skin" controls preload="none" width="'.$width.'" height="'.$height.'"'.$sp.' data-setup="{}">
								<source src="'.$src.'" type="video/mp4" />
							</video>';
						}
						$video_player = $flowplayer_code;
					}
					$video_item .= '<div class="wpvp_video_item'.$class.'" style="'.$style.'">'.$video_player;
				}
				if($post_title!=''){
        			        $video_item .= '<div class="wpvp_video_title"><a class="wpvp_title" href="'.get_permalink().'">'.get_the_title().'</a></div>';
	        		}
				if($author!=''){
					$video_item .= '<span class="wpvp_author">'.get_the_author().'</span>';
				}
				if($excerpt!=''){
					$ct = strip_shortcodes(get_the_content());
					$helper = new WPVP_Helper();
					$excerpt_string = $helper->wpvp_string_limit_words($ct,$excerpt_length);
					$video_item .= '<br /><span class="wpvp_excerpt">'.$excerpt_string.'</span>';
				}
				$video_item .= '</div>';
			}//check if video_meta is not empty
		endwhile;
		wp_reset_postdata();
		echo $video_item;
		return;
	}
	/* END OF CODE FOR UPLOAD FROM THE DASHBOARD AND BASIC FUNCTIONALITY */
	/* BEGINNING OF CODE FOR FRONT-END UPLOADER */
	/**
	*process front end uploading
	*@access public
	*/
	public function wpvp_front_video_uploader(){
		$helper = new WPVP_Helper(); 
		$upload_size_unit = WPVP_Helper::wpvp_max_upload_size();
		if($helper->wpvp_is_allowed()) {
			WPVP_Helper::wpvp_load_template_file('wpvp-frontend-uploader',true);
		} else { //Display insufficient privileges message
			$denial_message = get_option('wpvp_denial_message');
			if(!$denial_message || $denial_message == "")
				echo '<h2>'.__('Sorry, you do not have sufficient privileges to use this feature').'</h2>';
			else
				echo '<h2>'.$denial_message.'</h2>';

        }
	}
	/**
	*Strip a single shortcode
	*@access public
	**/
	public function wpvp_strip_shortcode($code, $content){
		global $shortcode_tags;
		$stack = $shortcode_tags;
		$shortcode_tags = array($code => 1);
		$content = strip_shortcodes($content);
		$shortcode_tags = $stack;
		return $content;
	}
	/**
	*font end video edit processing
	*@access public
	*/
	public function wpvp_front_video_editor(){
		if($_REQUEST['video']!=''){
			//get current user id and check if the video belongs to that user
			$curr_user = wp_get_current_user();
			$user_id = $curr_user->ID;
			//get post Object based on post id
			$post_id = (int)$_GET['video'];
			$postObj = get_post($post_id);
			$post_author = $postObj->post_author;
			if(!current_user_can('administrator')&&$user_id!=$post_author){
				return __('Cheating, huh?!');
			} else {
				$video_data = get_post_meta($post_id,'wpvp_fp_code',true);
				$video_shortcode = '';
				if(!empty($video_data)){
					$data = json_decode(stripslashes($video_data),true);
					$src = isset($data['src']) ? $data['src'] : false;
					$splash = isset($data['splash']) ? $data['splash'] : false;
					$width = isset($data['width']) ? $data['width'] : false;
					$height = isset($data['height']) ? $data['height'] : false;
					if($src)
						$video_shortcode = '[wpvp_player src="'.$src.'"';
					if($splash)
						$video_shortcode .= ' splash="'.$splash.'"';
					if($width)
						$video_shortcode.= ' width="'.$width.'"';
					if($height)
						$video_shortcode.= ' height="'.$height.'"';
					if($src)
						$video_shortcode .= ']';
				}
				$video_content = $this->wpvp_strip_shortcode('wpvp_player',$postObj->post_content);
				$video_content = $this->wpvp_strip_shortcode('wpvp_flowplayer',$video_content);
				$video_title = $postObj->post_title;
				$post_tags = wp_get_post_tags($post_id);
				if(!empty($post_tags)){
					$tag_count = count($post_tags);
					$tags_list = array();
					foreach($post_tags as $key=>$tag){
						$tags_list[]=$tag->name;
					}
					$tags_list = implode(', ',$tags_list);
				}
				$post_category = wp_get_post_categories($post_id);
				$post_cat = $post_category[0];
				$data = array();
				$data['post_id'] = $post_id;
				$data['video_shortcode'] = $video_shortcode;
				$data['content'] = $video_content;
				$data['title'] = $video_title;
				$data['tags'] = $tags_list;
				$data['category'] = $post_cat;
				WPVP_Helper::wpvp_load_template_file('wpvp-frontend-editor',true,$data);
			} 
		} else{
			return __('Cheating, huh?!');
			exit;
		}
	}	
	/**
	*insert a new video post from the front end uploader
	*@access protected
	*/
	protected function wpvp_insert_init_post($data,$file){
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$wpvp_allow_guest = get_option('wpvp_allow_guest','no') ? get_option('wpvp_allow_guest','no') : 'no';
		if($wpvp_allow_guest=='yes')
			$user_id = (int)get_option('wpvp_guest_userid');
		$helper = new WPVP_Helper();
		if($data['wpvp_category']=='0'){
			$data['wpvp_category']='1';
		}
		$wpvp_post_status = get_option('wpvp_default_post_status','pending');
		$post = array(
			'comment_status' => 'open',
			'post_author' => $user_id,
			'post_category' => array($data['wpvp_category']),
			'post_content' => $data['wpvp_desc'],
			'post_title' => $data['wpvp_title'],
			'post_type' => 'videos',
			'post_status' => $wpvp_post_status,
			'tags_input' => $data['wpvp_tags']
		);
		$postID = wp_insert_post($post);
		if ( !empty( $file ) ) {
			require_once(ABSPATH . 'wp-admin/includes/admin.php');
			$upload_overrides = array( 'test_form' => FALSE );
			$id = media_handle_upload('async-upload', 0,$upload_overrides); //post id of Client Files page
			unset($file);
			if ( is_wp_error($id) ) {
				$errors['upload_error'] = $id;
				$id = false;
			}
			if ($errors) {
				return $errors;
			} else {
				$encodedVideoPost = $this->wpvp_encode($id,$postID);
				if(!$encodedVideoPost){
					$msg = _e('There was an error creating a video post.');
				} else{
					$msg = _e('Successfully uploaded. You will be redirected in 5 seconds.');
					echo '<script type="text/javascript"> jQuery(window).load(function(){ jQuery("#wpvp-upload-video").css("display","none"); setTimeout(function(){ window.location.href="'.get_permalink($postID).'"},5000);}); </script> '._e('If you are not redirected in 5 seconds, go to ').'<a href="'.get_permalink($postID).'">uploaded video</a>.';
				}
				return $postID;
			}
		}
	}
}
?>
