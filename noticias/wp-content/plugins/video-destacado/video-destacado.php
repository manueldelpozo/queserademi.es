<?php
/**
 * @package   Video Destacado
 * @author    Airton Vancin Junior <airtonvancin@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/airton/video-destacado
 * @copyright 2016 Airton
 *
 * @wordpress-plugin
 * Plugin Name:       Video Destacado
 * Plugin URI:        https://github.com/airton/video-destacado
 * Description:       Insert a video posted to Youtube for posts, pages and custom post
 * Version:           1.4.0
 * Author:            Airton Vancin Junior
 * Author URI:        http://airtonvancin.com
 * Text Domain:       video-destacado
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/airton/video-destacado
 */

// Previne acesso direto
if( ! defined( 'ABSPATH' ) ){
    exit;
}

/**
 * video_destacado_load_textdomain()
 *
 * Carrega arquivos de traduções.
 */
function video_destacado_load_textdomain() {
    load_plugin_textdomain( 'video-destacado', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'video_destacado_load_textdomain' );

/**
 * Settings
 *
 *
 */
include_once ('video-destacado-settings.php');


/**
 * video_destacado_settings_link
 *
 * Add settings link on plugin page
 */
function video_destacado_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=video-destacado.php">'.__('Settings', 'video-destacado').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'video_destacado_settings_link' );


add_action( 'add_meta_boxes', 'video_add_metaboxes' );
function video_add_metaboxes(){
    $post_types = get_post_types( array( 'public' => true ) );
    foreach ( $post_types as $post_type ) {
        if ( get_option('video_destacado_'.$post_type) ) {
            add_meta_box( 'video_destaque_metabox', 'Vídeo Destacado', 'video_destaque_metabox', $post_type, 'side', 'default' );
        }
    }
}

function video_destaque_metabox($post){
    $values         = get_post_custom( $post->ID );
    $id_video       = isset( $values['id_video'] ) ? esc_attr( $values['id_video'][0] ) : '';
    $width_video    = isset( $values['width_video'] ) ? esc_attr( $values['width_video'][0] ) : '';
    $height_video   = isset( $values['height_video'] ) ? esc_attr( $values['height_video'][0] ) : '';
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>

        <img style="<?php if(empty($id_video)){echo 'display: none;' ;} else {echo 'display: block' ;}  ?>" class="thumb" src="http://img.youtube.com/vi/<?php echo $id_video; ?>/0.jpg" alt="<?php echo $titulo_video; ?>" />

        <ul id='video-destaque'>
            <li><span><?php _e('ID do Video', 'video-destacado'); ?>:</span> <input type="text" id="id_video" name="id_video" value="<?php echo $id_video; ?>" /><small>Ex: www.youtube.com/watch?v=<b>XdMD4LrC4wY</b></small></li>
            <li>
                <div class="vd-options">
                    <a href="#"><?php _e('More Options', 'video-destacado'); ?></a>
                </div>
                <div class="vd-more">
                    <div class="box">
                        <span><?php _e('Width', 'video-destacado'); ?>:</span>
                        <input type="text" id="width_video" name="width_video" value="<?php echo $width_video; ?>" />
                        <small><?php _e('Default', 'video-destacado'); ?>: <b>560</b></small>
                    </div>
                    <div class="box">
                        <span><?php _e('Height', 'video-destacado'); ?>:</span>
                        <input type="text" id="height_video" name="height_video" value="<?php echo $height_video; ?>" />
                        <small><?php _e('Default', 'video-destacado'); ?>: <b>315</b></small>
                    </div>
                </div>
            </li>
            <li>
                <!-- <input type="button" tabindex="3" value="Adicionar" class="button add">
                <input type="button" tabindex="3" value="Remover" class="button del"> -->
                <?php submit_button(__('Add', 'video-destacado'), 'secondary small', 'add', false); ?>
                <?php submit_button(__('Remove', 'video-destacado'), 'secondary small', 'del', false); ?>
            </li>


        </ul>
  <?php
}

/**
 * video_destaque_metabox_save()
 *
 *
 */

function video_destaque_metabox_save( $post_id ){
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    if( !current_user_can( 'edit_post' ) ) return;

    $allowed = array(
    'a' => array(
    'href' => array()
    )
    );

    if( isset( $_POST['texto_meta_box'] ) )
    update_post_meta( $post_id, 'texto_meta_box', wp_kses( $_POST['texto_meta_box'], $allowed ) );
    update_post_meta( $post_id, 'id_video', wp_kses( $_POST['id_video'], $allowed ) );
    update_post_meta( $post_id, 'width_video', wp_kses( $_POST['width_video'], $allowed ) );
    update_post_meta( $post_id, 'height_video', wp_kses( $_POST['height_video'], $allowed ) );
}

add_action( 'save_post', 'video_destaque_metabox_save' );

function video_destacado(){
    $values = get_post_custom( $post->ID );
    $id_video = isset( $values['id_video'] ) ? esc_attr( $values['id_video'][0] ) : '';

    $width_video = isset( $values['width_video'] ) ? esc_attr( $values['width_video'][0] ) : '';
    if(empty($width_video)){ $width_video = 560; }
    $height_video = isset( $values['height_video'] ) ? esc_attr( $values['height_video'][0] ) : '';
    if(empty($height_video)){ $height_video = 315; }

    if(!empty($id_video)):
    $iframe = "<iframe width='".$width_video."' height='".$height_video."' src='http://www.youtube.com/embed/".$id_video."' frameborder='0' allowfullscreen></iframe>";
    echo $iframe;
    endif;
}

function get_video_destacado(){
	$values = get_post_custom( $post->ID );
    $id_video = isset( $values['id_video'] ) ? esc_attr( $values['id_video'][0] ) : '';

    $width_video = isset( $values['width_video'] ) ? esc_attr( $values['width_video'][0] ) : '';
    if(empty($width_video)){ $width_video = 560; }
    $height_video = isset( $values['height_video'] ) ? esc_attr( $values['height_video'][0] ) : '';
    if(empty($height_video)){ $height_video = 315; }

    if(!empty($id_video)):
    $iframe = "<iframe width='".$width_video."' height='".$height_video."' src='http://www.youtube.com/embed/".$id_video."' frameborder='0' allowfullscreen></iframe>";
    return $iframe;
    endif;
}

/**
 *
 *
 * Add Scripts and CSS
 */

function vide_destacado_scripts() {
	wp_register_script('my-script', plugins_url('video-destacado') . '/js/vd-admin.js');
	wp_enqueue_script('my-script');
	//wp_enqueue_script('jquery');
}
function video_destacado_styles() {
	wp_register_style('my-css', plugins_url('video-destacado') . '/css/vd-admin.css');
	wp_enqueue_style('my-css');
}
add_action('admin_print_scripts', 'vide_destacado_scripts');
add_action('admin_print_styles', 'video_destacado_styles');

