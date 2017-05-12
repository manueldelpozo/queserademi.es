<?php 
//extend widget class
class WPVideosForPostsWidget extends WP_Widget
{
        function WPVideosForPostsWidget(){
                $widget_ops = array(    'classname' => 'wp-videos-posts',
                                        'description' => 'Displays the embedded videos from YouTube or Vimeo');
                $this->WP_Widget('WPVideosForPostsWidget','WP Videos Posts',$widget_ops);
        }
        function form($instance){
                $instance = wp_parse_args((array) $instance, array('title'=> '','width'=>'165','height'=>'125','cat_checkbox'=>'','num_posts'=>'5','display'=>'v','display_type'=>'th','post_title'=>'yes','author'=>'','excerpt'=>'yes','excerpt_length'=>10));
                $title = $instance['title'];
		$width = $instance['width'];
		$height = $instance['height'];
		$categories = $instance['cat_checkbox'];
		$num_posts = $instance['num_posts'];
		$display = $instance['display'];
		$display_type = $instance['display_type'];
		$post_title = $instance['post_title'];
		$author = $instance['author'];
		$excerpt = $instance['excerpt'];
		$excerpt_length = $instance['excerpt_length'];
		?>
                <p>
                        <label for="<?php echo $this->get_field_id('title');?>">
                        <?php _e('Title:');?>
                        </label>
			<?php 
		//	print_r($instance);
			?>
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			<p>
			<label for="<?php echo $this->get_field_id('width');?>">
			<?php _e('Width of a video item (px):');?>
			</label>
                        <input style="width:60px;" width="40" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('height');?>">
                        <?php _e('Height of a video item (px):');?>
                        </label>
                        <input style="width:60px;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('num_posts');?>">
                        <?php _e('Number of posts:');?>
                        </label>
			<input style="width:60px;" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo esc_attr($num_posts); ?>" />
			</p>	
			<p>
			<label for="<?php echo $this->get_field_id('display_type');?>">
                        <b><?php _e('Display Options:')?></b>
                        </label>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('display');?>">
                        <?php _e('Layout:');?>
			</label>
			<input type="radio" name="<?php echo $this->get_field_name('display');?>" value="v" <?php if($instance['display']=='v'){ echo 'checked="checked"'; }?>/><?php _e('Vertical');?>
			<input type="radio" name="<?php echo $this->get_field_name('display');?>" value="h" <?php if($instance['display']=='h'){ echo 'checked="checked"'; }?>/><?php _e('Horizontal');?>
			</p>
			<p>
			<label><?php _e('Display type:');?></label>
                        <input type="radio" name="<?php echo $this->get_field_name('display_type');?>" value="p" <?php if($instance['display_type']=='p'){ echo 'checked="checked"'; }?>/><?php _e('Player');?>
                        <input type="radio" name="<?php echo $this->get_field_name('display_type');?>" value="th" <?php if($instance['display_type']=='th'){ echo 'checked="checked"'; }?>/><?php _e('Thumbnails');?>
			</p>
			<p>
                        <input type="checkbox" name="<?php echo $this->get_field_name('post_title');?>" value="yes" <?php if($instance['post_title']=='yes'){ echo 'checked="checked"';};?>/> <?php _e('Display Post Title');?>
                        </p>
			<p>
			<input type="checkbox" name="<?php echo $this->get_field_name('author');?>" value="yes" <?php if($instance['author']=='yes'){ echo 'checked="checked"';};?>/> <?php _e('Display Author');?>
			</p>
			<p>
                        <input type="checkbox" name="<?php echo $this->get_field_name('excerpt');?>" value="yes" <?php if($instance['excerpt']=='yes'){ echo 'checked="checked"';};?>/> <?php _e('Display Excerpt');?>
                        </p>
			<p>
                        <input type="input" style="width:60px;" name="<?php echo $this->get_field_name('excerpt_length');?>" value="<?php echo esc_attr($excerpt_length);?>" /> <?php _e('excerpt length (number of words)');?>
                        </p>
			<p>
                        <strong><?php _e("Categories to display from: " ); ?></strong><br />
			<div style="height:145px;overflow:auto;">
                        <ul style="list-style-type:none;">
                        <?php
				$args = array('hide_empty'=>0);
				$categories = get_categories($args);
				foreach($categories as $category){
					$options .= '<li><input type="checkbox" id="'.$this->get_field_id('cat_checkbox').'[]" name="'.$this->get_field_name('cat_checkbox').'[]"';
					if(is_array($instance['cat_checkbox'])){
						foreach($instance['cat_checkbox'] as $cats){
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
                </p>
<?php
        }
        function update($new_instance, $old_instace){
                $instance = $old_instance;
                $instance['title'] = $new_instance['title'];
				$instance['width'] = $new_instance['width'];
				$instance['height'] = $new_instance['height'];
				$instance['cat_checkbox'] = $new_instance['cat_checkbox'];
				$instance['num_posts'] = $new_instance['num_posts'];
				$instance['display'] = $new_instance['display'];
				$instance['display_type'] = $new_instance['display_type'];
				$instance['post_title']= $new_instance['post_title'];
				$instance['author']=$new_instance['author'];
				$instance['excerpt']=$new_instance['excerpt'];
				$instance['excerpt_length']=$new_instance['excerpt_length'];
                return $instance;
        }
        function widget($args, $instance){
                extract($args, EXTR_SKIP);
                echo $before_widget;
                $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
                if(!empty($title))
                        echo $before_title .$title . $after_title;

        //widget code
                echo wpvp_widget_function($instance);
                echo $after_widget;
        }
}
//add_action('widgets_init', create_function('','return register_widget("WPVideosForPostsWidget");'));

function wpvp_widget_function($instance){
	$media = new WPVP_Encode_Media();
	$video_posts = $media->wpvp_widget_latest_posts($instance);	 
        //$video_posts = wpvp_widget_latest_posts($instance);
        return $video_posts;
}
?>
