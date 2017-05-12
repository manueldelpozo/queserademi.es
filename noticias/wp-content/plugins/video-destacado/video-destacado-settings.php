<?php
/*  ==========================================================================
    Page Settings
    ========================================================================== */

	add_action( 'admin_menu', 'video_destacado_settings' );

    function video_destacado_settings() {
        // criamos a pagina de opções com esta função

        add_options_page(
        	esc_html__('Featured Video Settings', 'video-destacado'),
        	__('Featured Video', 'video-destacado'),
        	'manage_options',
        	'video-destacado',
        	'video_destacado_settings_content'
    	);

        //call register settings function
        add_action( 'admin_init', 'video_destacado_register' );

	}

    function video_destacado_register() {
        //register our settings
        $post_types = get_post_types( array( 'public' => true ) );
        foreach ( $post_types as $post_type ) {
            register_setting( 'video-destacado-settings-group', 'video_destacado_'.$post_type );
        }

        register_setting( 'video-destacado-settings-group', 'video_destacado_default' );
        if( get_option('video_destacado_active') != 'disable' ){
            update_option( 'video_destacado_active', 'active' );
        }
    }


	function video_destacado_settings_content() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Featured Video Settings', 'video-destacado'); ?></h2>
		<p><?php _e('Active the plugin to', 'video-destacado'); ?>:</p>
		<form action="options.php" method="post">

            <?php settings_fields( 'video-destacado-settings-group' ); ?>
            <?php do_settings_sections( 'video-destacado-settings-group' ); ?>

            <input type="hidden" value="<?php echo get_option('video_destacado_active');  ?>" name="video_destacado_active">

            <?php
                //$settings_update = $_GET['settings-updated'] ? $_GET['settings-updated'] : "";
                if (isset($_GET['settings-updated'])) {
                    update_option( 'video_destacado_active', 'disable' );
                }

                if( get_option('video_destacado_active') === 'active'){
                    $post_types = get_post_types( array( 'public' => true ) );
                    foreach ( $post_types as $post_type ) {
                        register_setting( 'video-destacado-settings-group', 'video_destacado_'.$post_type );
                        update_option( 'video_destacado_'.$post_type, $post_type );
                    }
                }

            ?>

			<table width="100%" class="widefat">
				<thead>
					<tr>
						<td style="" class="manage-column column-cb check-column" id="cb" scope="col">
							<label for="cb-select-all" class="screen-reader-text">Selecionar Tudo</label>
							<input type="checkbox" id="cb-select-all">
						</td>
						<td style="" class="manage-column column-title sortable desc" id="title" scope="col">
							<a href="<?php echo admin_url(); ?>&amp;orderby=title&amp;order=asc">
								<span><b><?php _e('Post Type', 'video-destacado') ?></b></span>
							</a>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php
						$post_types = get_post_types( array( 'public' => true ) );
					    foreach ( $post_types as $post_type ) {
							?>
							<tr>
								<td valign="top" width="20">
									<input type="checkbox" name="video_destacado_<?php echo $post_type; ?>" value="<?php echo $post_type; ?>" <?php if ( get_option('video_destacado_'.$post_type) ){ echo"checked"; } ?>>
								</td>
								<td valign="top">
									<strong><?php echo $post_type; ?></strong>
								</td>
							</tr>
							<?php
					    }
					?>
				</tbody>
			</table>
            <?php submit_button(); ?>
		</form>
	</div>
	<?php
	}
?>
