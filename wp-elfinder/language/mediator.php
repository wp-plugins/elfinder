<?php
/**
 * elfinder
**/

// Hook for adding admin menus
if (function_exists('add_action')) {
	add_action('admin_init', 'init_wpelf_styles_js');
	add_action('admin_menu', 'elfinder_pages');
}

function init_wpelf_styles_js() {
   	wp_register_script( 'addfield', WPELF_URL . '/mediator/includes/addfield.js', array('jquery'), '0.1'/*, $in_footer */ );
	wp_register_style('wpelf_styles', WPELF_URL . '/mediator/css/style.css');
}

function elfinder_pages(){
	add_management_page('elFinder', 'elfinder', 'manage_options', 'elfinder', 'elfinder_mount_tools');
	$page = add_options_page(__('elFinder Settings','elfinder'), 'elfinder', 'manage_options', 'elfinder', 'elfinder_mount_settings');
	/* Using registered $page handle to hook script load */
    add_action('admin_print_scripts-' . $page, 'wpelf_admin_js');
	add_action('admin_print_styles-' . $page, 'wpelf_admin_styles');
}

function wpelf_admin_js(){
	wp_enqueue_script('addfield');
}

function wpelf_admin_styles(){
	wp_enqueue_style('wpelf_styles');
}

function elfinder_mount_tools(){
	require_once(WPELF_FILE_PATH . "/elfinder/elfinder.php");
}

include_once(WPELF_FILE_PATH . "/mediator/includes/auxiliary-functions.php");

add_filter('admin_head', 'add_wpelf_js');

function elfinder_mount_settings(){

	//must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	
	$title = __('elFinder Settings', 'elfinder');
	
    // variables for the field and option names 
	$hidden_field = 'wpelf_submit_hidden';
	
    $wpelf_root_name = 'wpelf_root';
    $wpelf_root_field = 'wpelf_root';
	
    $wpelf_url_name = 'wpelf_url';
    $wpelf_url_field = 'wpelf_url';
	
    $wpelf_root_alias_name = 'wpelf_root_alias';
    $wpelf_root_alias_field = 'wpelf_root_alias';
	
    $wpelf_disabled_name = 'wpelf_disabled';
    $wpelf_disabled_field = 'wpelf_disabled';
	
    $wpelf_dotfiles_name = 'wpelf_dotfiles';
    $wpelf_dotfiles_field = 'wpelf_dotfiles';
	
    $wpelf_dir_size_name = 'wpelf_dir_size';
    $wpelf_dir_size_field = 'wpelf_dir_size';
	
    $wpelf_file_mode_name = 'wpelf_file_mode';
    $wpelf_file_mode_field = 'wpelf_file_mode';
	
    $wpelf_dir_mode_name = 'wpelf_dir_mode';
    $wpelf_dir_mode_field = 'wpelf_dir_mode';
	
    $wpelf_mimedetect_name = 'wpelf_mimedetect';
    $wpelf_mimedetect_field = 'wpelf_mimedetect';
	
    $wpelf_upload_allow_name = 'wpelf_upload_allow';
    $wpelf_upload_allow_field = 'wpelf_upload_allow';
	
    $wpelf_upload_deny_name = 'wpelf_upload_deny';
    $wpelf_upload_deny_field = 'wpelf_upload_deny';
	
    $wpelf_upload_order_name = 'wpelf_upload_order';
    $wpelf_upload_order_field = 'wpelf_upload_order';
	
    $wpelf_imglib_name = 'wpelf_imglib';
    $wpelf_imglib_field = 'wpelf_imglib';
	
    $wpelf_tmb_dir_name = 'wpelf_tmb_dir';
    $wpelf_tmb_dir_field = 'wpelf_tmb_dir';
	
    $wpelf_tmb_clean_name = 'wpelf_tmb_clean';
    $wpelf_tmb_clean_field = 'wpelf_tmb_clean';
	
    $wpelf_tmb_atonce_name = 'wpelf_tmb_atonce';
    $wpelf_tmb_atonce_field = 'wpelf_tmb_atonce';
	
    $wpelf_tmb_size_name = 'wpelf_tmb_size';
    $wpelf_tmb_size_field = 'wpelf_tmb_size';
	
    $wpelf_file_url_name = 'wpelf_file_url';
    $wpelf_file_url_field = 'wpelf_file_url';
	
    $wpelf_date_format_name = 'wpelf_date_format';
    $wpelf_date_format_field = 'wpelf_date_format';

    $wpelf_time_format_name = 'wpelf_time_format';
    $wpelf_time_format_field = 'wpelf_time_format';
	
	$wpelf_date_custom_field = 'wpelf_date_custom';
	$wpelf_time_custom_field = 'wpelf_time_custom';
	
    $wpelf_logger_name = 'wpelf_logger';
    $wpelf_logger_field = 'wpelf_logger';
	
    $wpelf_defaults_name = 'wpelf_defaults';
    $wpelf_defaults_field = 'wpelf_defaults';
	$wpelf_defaults_r_field = 'wpelf_defaults_read';
	$wpelf_defaults_w_field = 'wpelf_defaults_write';
	$wpelf_defaults_rm_field = 'wpelf_defaults_remove';
	
    $wpelf_perms_name = 'wpelf_perms';
    $wpelf_perms_field = 'wpelf_perms';
	$wpelf_perms_r_field = 'wpelf_perms_read';
	$wpelf_perms_w_field = 'wpelf_perms_write';
	$wpelf_perms_rm_field = 'wpelf_perms_remove';
	
    $wpelf_debug_name = 'wpelf_debug';
    $wpelf_debug_field = 'wpelf_debug';
	
    $wpelf_archive_mimes_name = 'wpelf_archive_mimes';
    $wpelf_archive_mimes_field = 'wpelf_archive_mimes';
	
    $wpelf_archivers_name = 'wpelf_archivers';
    $wpelf_archivers_field = 'wpelf_archivers';
	$wpelf_crule_name = 'wpelf_create_rule';
	$wpelf_crule_field = 'wpelf_create_rule';
	$wpelf_erule_name = 'wpelf_extract_rule';
	$wpelf_erule_field = 'wpelf_extract_rule';
	
	
    // Read in existing option value from database
    $wpelf_root_val = get_option( $wpelf_root_name );
    $wpelf_url_val = get_option( $wpelf_url_name );
    $wpelf_root_alias_val = get_option( $wpelf_root_alias_name );
    $wpelf_disabled_val = implode(",", unserialize(get_option( $wpelf_disabled_name )));
    $wpelf_dotfiles_val = get_option( $wpelf_dotfiles_name );
    $wpelf_dir_size_val = get_option( $wpelf_dir_size_name );
    $wpelf_file_mode_val = '0' . decoct(get_option( $wpelf_file_mode_name ));
    $wpelf_dir_mode_val = '0' . decoct(get_option( $wpelf_dir_mode_name ));
    $wpelf_mimedetect_val = get_option( $wpelf_mimedetect_name );
    $wpelf_upload_allow_val = implode(",", unserialize(get_option( $wpelf_upload_allow_name )));
    $wpelf_upload_deny_val = implode(",", unserialize(get_option( $wpelf_upload_deny_name )));
    $wpelf_upload_order_val = get_option( $wpelf_upload_order_name );
    $wpelf_imglib_val = get_option( $wpelf_imglib_name );
    $wpelf_tmb_dir_val = get_option( $wpelf_tmb_dir_name );
    $wpelf_tmb_clean_val = get_option( $wpelf_tmb_clean_name );
    $wpelf_tmb_atonce_val = get_option( $wpelf_tmb_atonce_name );
    $wpelf_tmb_size_val = get_option( $wpelf_tmb_size_name );
    $wpelf_file_url_val = get_option( $wpelf_file_url_name );
    $wpelf_date_format_val = get_option( $wpelf_date_format_name );
    $wpelf_time_format_val = get_option( $wpelf_time_format_name );
    $wpelf_logger_val = get_option( $wpelf_logger_name );
    $wpelf_defaults_val = unserialize( get_option( $wpelf_defaults_name ) );
	$wpelf_perms_val = wpelf_slashesfree($wpelf_perms_name);
	$wpelf_debug_val = get_option( $wpelf_debug_name );
    $wpelf_archive_mimes_val = implode(",", unserialize(get_option( $wpelf_archive_mimes_name )));
    $wpelf_archivers_val = unserialize( get_option( $wpelf_archivers_name ) );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field ]) && $_POST[ $hidden_field ] == 'Y' ) {
        // Read their posted values
        $wpelf_root_val = $_POST[ $wpelf_root_field ];
        $wpelf_url_val = $_POST[ $wpelf_url_field ];
        $wpelf_root_alias_val = $_POST[ $wpelf_root_alias_field ];
        $wpelf_disabled_val = $_POST[ $wpelf_disabled_field ];
        $wpelf_dotfiles_val = $_POST[ $wpelf_dotfiles_field ];
        $wpelf_dir_size_val = $_POST[ $wpelf_dir_size_field ];
        $wpelf_file_mode_val = $_POST[ $wpelf_file_mode_field ];
        $wpelf_dir_mode_val = $_POST[ $wpelf_dir_mode_field ];
        $wpelf_mimedetect_val = $_POST[ $wpelf_mimedetect_field ];
        $wpelf_upload_allow_val = $_POST[ $wpelf_upload_allow_field ];
        $wpelf_upload_deny_val = $_POST[ $wpelf_upload_deny_field ];
        $wpelf_upload_order_val = $_POST[ $wpelf_upload_order_field ];
        $wpelf_imglib_val = $_POST[ $wpelf_imglib_field ];
        $wpelf_tmb_dir_val = $_POST[ $wpelf_tmb_dir_field ];
        $wpelf_tmb_clean_val = $_POST[ $wpelf_tmb_clean_field ];
        $wpelf_tmb_atonce_val = $_POST[ $wpelf_tmb_atonce_field ];
        $wpelf_tmb_size_val = $_POST[ $wpelf_tmb_size_field ];
        $wpelf_file_url_val = $_POST[ $wpelf_file_url_field ];
        
		if ( !empty($_POST[$wpelf_date_format_field]) && isset($_POST[$wpelf_date_format_field]) && '\c\u\s\t\o\m' == stripslashes( $_POST[$wpelf_date_format_field] ) )
			$_POST[$wpelf_date_format_field] = $_POST[$wpelf_date_custom_field];
		if ( !empty($_POST[$wpelf_time_format_field]) && isset($_POST[$wpelf_time_format_field]) && '\c\u\s\t\o\m' == stripslashes( $_POST[$wpelf_time_format_field] ) )
			$_POST[$wpelf_time_format_field] = $_POST[$wpelf_time_custom_field];
		
		$wpelf_date_format_val = $_POST[$wpelf_date_format_field];
		$wpelf_time_format_val = $_POST[$wpelf_time_format_field];		
        
		$wpelf_logger_val = $_POST[ $wpelf_logger_field ];
        $wpelf_defaults_r_val = $_POST[ $wpelf_defaults_r_field ];
		$wpelf_defaults_w_val = $_POST[ $wpelf_defaults_w_field ];
		$wpelf_defaults_rm_val = $_POST[ $wpelf_defaults_rm_field ];
        
	    for ( $i = 0; $i < 99; $i++){
			if (isset($_POST[ $wpelf_perms_field . '-' . $i ])) {
				if (isset($_POST[ $wpelf_perms_r_field . '-' . $i ])){
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'read' ] = $_POST[ $wpelf_perms_r_field . '-' . $i ];
				} else {
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'read' ] = false;
				}
				if (isset($_POST[ $wpelf_perms_w_field . '-' . $i ])){
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'write' ] = $_POST[ $wpelf_perms_w_field . '-' . $i ];
				} else {
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'write' ] = false;
				}
				if (isset($_POST[ $wpelf_perms_rm_field . '-' . $i ])){			
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'rm' ] = $_POST[ $wpelf_perms_rm_field . '-' . $i ];
				} else {
					$wpelf_perms_val_[$_POST[ $wpelf_perms_field . '-' . $i ]][ 'rm' ] = false;
				}
			}
		}

		$wpelf_perms_val = $wpelf_perms_val_;
        $wpelf_debug_val = $_POST[ $wpelf_debug_field ];
        $wpelf_archive_mimes_val = $_POST[ $wpelf_archive_mimes_field ];
		
		for ( $i = 0; $i < 9; $i++){
			if (isset($_POST[ $wpelf_crule_field . '-' . $i ])){
				if (isset($_POST[ 'wpelf-create-cmd-' . $i ]) && isset($_POST[ 'wpelf-create-argc-' . $i ]) && isset($_POST[ 'wpelf-create-ext-' . $i ])) {
						$wpelf_crule_val[$_POST[ $wpelf_crule_field . '-' . $i ]][ 'cmd' ] = $_POST[ 'wpelf-create-cmd-' . $i ];
						$wpelf_crule_val[$_POST[ $wpelf_crule_field . '-' . $i ]][ 'argc' ] = $_POST[ 'wpelf-create-argc-' . $i ];
						$wpelf_crule_val[$_POST[ $wpelf_crule_field . '-' . $i ]][ 'ext' ] = $_POST[ 'wpelf-create-ext-' . $i ];
				} else { /*Settings Allert!*/}
			}
		}
		
		for ( $i = 0; $i < 9; $i++){
			if (isset($_POST[ $wpelf_erule_field . '-' . $i ])){
				if (isset($_POST[ 'wpelf-extract-cmd-' . $i ]) && isset($_POST[ 'wpelf-extract-argc-' . $i ]) && isset($_POST[ 'wpelf-extract-ext-' . $i ])) {
						$wpelf_erule_val[$_POST[ $wpelf_erule_field . '-' . $i ]][ 'cmd' ] = $_POST[ 'wpelf-extract-cmd-' . $i ];
						$wpelf_erule_val[$_POST[ $wpelf_erule_field . '-' . $i ]][ 'argc' ] = $_POST[ 'wpelf-extract-argc-' . $i ];
						$wpelf_erule_val[$_POST[ $wpelf_erule_field . '-' . $i ]][ 'ext' ] = $_POST[ 'wpelf-extract-ext-' . $i ];
				} else { /*Settings Allert!*/}
			}
		}
		unset ($wpelf_archivers_val);
		$wpelf_archivers_val['create'] = $wpelf_crule_val;
		$wpelf_archivers_val['extract'] = $wpelf_erule_val;
		
        // Save the posted values in the database
        update_option( $wpelf_root_name, $wpelf_root_val );
        update_option( $wpelf_url_name, $wpelf_url_val );
        update_option( $wpelf_root_alias_name, $wpelf_root_alias_val );
        update_option( $wpelf_disabled_name, serialize(explode(',', str_replace(' ','',$wpelf_disabled_val))) );
        update_option( $wpelf_dotfiles_name, $wpelf_dotfiles_val );
        update_option( $wpelf_dir_size_name, $wpelf_dir_size_val );
        update_option( $wpelf_file_mode_name, octdec($wpelf_file_mode_val) );
        update_option( $wpelf_dir_mode_name, octdec($wpelf_dir_mode_val) );
        update_option( $wpelf_mimedetect_name, $wpelf_mimedetect_val );
        update_option( $wpelf_upload_allow_name, serialize(explode(',', str_replace(' ','',$wpelf_upload_allow_val))) );
        update_option( $wpelf_upload_deny_name, serialize(explode(',', str_replace(' ','',$wpelf_upload_deny_val))) );
        update_option( $wpelf_upload_order_name, $wpelf_upload_order_val );
        update_option( $wpelf_imglib_name, $wpelf_imglib_val );
        update_option( $wpelf_tmb_dir_name, $wpelf_tmb_dir_val );
        update_option( $wpelf_tmb_clean_name, $wpelf_tmb_clean_val );	
        update_option( $wpelf_tmb_atonce_name, $wpelf_tmb_atonce_val );
        update_option( $wpelf_tmb_size_name, $wpelf_tmb_size_val );
        update_option( $wpelf_file_url_name, $wpelf_file_url_val );
        update_option( $wpelf_date_format_name, $wpelf_date_format_val );
		update_option( $wpelf_time_format_name, $wpelf_time_format_val );
        update_option( $wpelf_logger_name, $wpelf_logger_val );	
        
		$wpelf_defaults_val['read'] = $wpelf_defaults_r_val;
		$wpelf_defaults_val['write'] = $wpelf_defaults_w_val;
		$wpelf_defaults_val['rm'] = $wpelf_defaults_rm_val;
		update_option( $wpelf_defaults_name, serialize($wpelf_defaults_val) );
		update_option( $wpelf_perms_name, serialize($wpelf_perms_val) );
        update_option( $wpelf_debug_name, $wpelf_debug_val );
        update_option( $wpelf_archive_mimes_name, serialize(explode(',', str_replace(' ','',$wpelf_archive_mimes_val))) );
        update_option( $wpelf_archivers_name, serialize($wpelf_archivers_val) );			

	?>
	<div id="setting-error-settings_updated" class="updated settings-error"> 
	<p><strong><?php _e('Settings saved.', 'elfinder' ); ?></strong></p></div>
	<?php

    }
	
    // Now display the settings editing screen

    echo '<div class="wrap">';

	// header
    screen_icon('options-general');
	echo "<h2>" . esc_html( $title ) . "</h2>";

    // settings form
    
    ?>

	<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field; ?>" value="Y">


	<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Path to root directory', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_root_field; ?>" value="<?php echo $wpelf_root_val; ?>" class="regular-text code"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Root directory URL', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_url_field; ?>" value="<?php echo $wpelf_url_val; ?>" class="regular-text code"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Alias', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_root_alias_field; ?>" value="<?php echo $wpelf_root_alias_val; ?>" class="regular-text">
	<span class="description"><?php _e('Display this instead of root directory name.', 'elfinder'); ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('List of not allowed commands', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_disabled_field; ?>" value="<?php echo $wpelf_disabled_val; ?>" class="regular-text">
	<span class="description"><?php _e('Divided by commas — here and below.', 'elfinder'); ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Display dot files', 'elfinder' ); ?></th> 
	<td><input type="checkbox" name="<?php echo $wpelf_dotfiles_field; ?>" value="true" <?php checked('true', $wpelf_dotfiles_val); ?>></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Count total directories sizes', 'elfinder' ); ?></th> 
	<td><input type="checkbox" name="<?php echo $wpelf_dir_size_field; ?>" value="true" <?php checked('true', $wpelf_dir_size_val); ?>></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('New files mode', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_file_mode_field; ?>" value="<?php echo $wpelf_file_mode_val; ?>" class="small-text"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('New folders mode', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_dir_mode_field; ?>" value="<?php echo $wpelf_dir_mode_val; ?>" class="small-text"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('MIME-type detection method', 'elfinder' ); ?></th> 
	<td><input id="mime-detect-finfo" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="finfo" <?php checked('finfo', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-finfo"><?php _e('finfo', 'elfinder');?></label><br />
	<input id="mime-detect-mct" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="php" <?php checked('php', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-mct"><?php _e('mime_content_type', 'elfinder'); ?></label><br />
	<input id="mime-detect-linux" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="linux" <?php checked('linux', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-linux"><?php _e('linux (file -ib)', 'elfinder'); ?></label><br />
	<input id="mime-detect-bsd" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="bsd" <?php checked('bsd', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-bsd"><?php _e('bsd (file -Ib)', 'elfinder'); ?></label><br />
	<input id="mime-detect-int" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="internal" <?php checked('internal', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-int"><?php _e('internal (based on file extensions)', 'elfinder'); ?></label><br />
	<input id="mime-detect-auto" type="radio" name="<?php echo $wpelf_mimedetect_field; ?>" value="auto" <?php checked('auto', $wpelf_mimedetect_val); ?> />
	<label for="mime-detect-auto"><?php _e('auto detect', 'elfinder'); ?></label></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('List of mime-types allowed to upload', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_upload_allow_field; ?>" value="<?php echo $wpelf_upload_allow_val; ?>" class="regular-text">
	<span class="description"><?php _e('Can be set exactly <b>image/jpeg</b> or to group <b>application</b>.', 'elfinder') ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('List of mime-types disallowed to upload', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_upload_deny_field; ?>" value="<?php echo $wpelf_upload_deny_val; ?>" class="regular-text"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Order of upload rules execution', 'elfinder' ); ?></th> 
	<td><input id="order-deny" type="radio" name="<?php echo $wpelf_upload_order_field; ?>" value="deny,allow" <?php checked('deny,allow', $wpelf_upload_order_val); ?> />
	<label for="order-deny"><?php _e('Deny,allow &mdash; what is not disallowed or allowed (OR).', 'elfinder');?></label><br />
	<input id="order-allow" type="radio" name="<?php echo $wpelf_upload_order_field; ?>" value="allow,deny" <?php checked('allow,deny', $wpelf_upload_order_val); ?> />
	<label for="order-allow"><?php _e('Allow,deny &mdash; only what is allowed, except what is disallowed (AND).', 'elfinder'); ?></label></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Library for thumbnail creation', 'elfinder' ); ?></th> 
	<td><input id="imagick" type="radio" name="<?php echo $wpelf_imglib_field; ?>" value="imagick" <?php checked('imagick', $wpelf_imglib_val); ?> />
	<label for="imagick"><?php _e('imagick', 'elfinder');?></label><br />
	<input id="mogrify" type="radio" name="<?php echo $wpelf_imglib_field; ?>" value="mogrify" <?php checked('mogrify', $wpelf_imglib_val); ?> />
	<label for="mogrify"><?php _e('mogrify', 'elfinder'); ?></label><br />
	<input id="gd" type="radio" name="<?php echo $wpelf_imglib_field; ?>" value="gd" <?php checked('gd', $wpelf_imglib_val); ?> />
	<label for="gd"><?php _e('gd', 'elfinder'); ?></label><br />
	<input id="auto" type="radio" name="<?php echo $wpelf_imglib_field; ?>" value="auto" <?php checked('auto', $wpelf_imglib_val); ?> />
	<label for="auto"><?php _e('try detect automatically', 'elfinder'); ?></label></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Directory name for image thumbnails', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_tmb_dir_field; ?>" value="<?php echo $wpelf_tmb_dir_val; ?>" class="regular-text">
	<span class="description"><?php _e('Leave the field blank to avoid thumbnails generation.', 'elfinder') ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('How often to clean thumbnails', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_tmb_clean_field; ?>" value="<?php echo $wpelf_tmb_clean_val; ?>" class="small-text">
	<span class="description"><?php _e('Possible values: from 0 to 200. 0 - never, 200 - on each client init request.', 'elfinder') ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('How many thumbnails to create per background request', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_tmb_atonce_field; ?>" value="<?php echo $wpelf_tmb_atonce_val; ?>" class="small-text"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Thumbnail size', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_tmb_size_field; ?>" value="<?php echo $wpelf_tmb_size_val; ?>" class="small-text"> <?php _e('pixels', 'elfinder' ); ?></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Show real URLs to files in client', 'elfinder' ); ?></th> 
	<td><input type="checkbox" name="<?php echo $wpelf_file_url_field; ?>" value="true" <?php checked('true', $wpelf_file_url_val); ?>></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('File modification date format. Default: j M Y H:i', 'elfinder' ); ?></th> 
	<td><div style="float:left; margin-right:20px">
	<?php

		$date_formats = apply_filters( 'date_formats', array(
			__('F j, Y'),
			'Y/m/d',
			'm/d/Y',
			'd/m/Y',
		) );

		$custom = true;
		$date_tmplt = $wpelf_date_format_val;

		foreach ( $date_formats as $format ) {
			echo "\t<label title='" . esc_attr($format) . "'><input type='radio' name='" . $wpelf_date_format_field . "' value='" . esc_attr($format) . "'";
			if ( $date_tmplt === $format ) { // checked() uses "==" rather than "==="
				echo " checked='checked'";
				$custom = false;
			}
			echo ' /> ' . date_i18n( $format ) . "</label><br />\n";
		}

		echo '	<label><input type="radio" name="' . $wpelf_date_format_field . '" id="wpelf_date_custom_radio" value="\c\u\s\t\o\m"';
		checked( $custom );
		echo '/> ' . __('Custom:') . ' </label><input type="text" name="' . $wpelf_date_custom_field . '" value="' . esc_attr( $date_tmplt ) . '" class="small-text" /><small><em> ' . date_i18n( $date_tmplt ) . "</em></small>\n";

	?>
	</div>
	<div>
	<?php

		$time_formats = apply_filters( 'time_formats', array(
			__('g:i a'),
			'g:i A',
			'H:i:s',
			'H:i',
		) );

		$custom = true;
		$time_tmplt = $wpelf_time_format_val;

		foreach ( $time_formats as $format ) {
			echo "\t<label title='" . esc_attr($format) . "'><input type='radio' name='" . $wpelf_time_format_field . "' value='" . esc_attr($format) . "'";
			if ( $time_tmplt === $format ) { // checked() uses "==" rather than "==="
				echo " checked='checked'";
				$custom = false;
			}
			echo ' /> ' . date_i18n( $format ) . "</label><br />\n";
		}

		echo '	<label><input type="radio" name="' . $wpelf_time_format_field . '" id="wpelf_time_custom_radio" value="\c\u\s\t\o\m"';
		checked( $custom );
		echo '/> ' . __('Custom:') . ' </label><input type="text" name="' . $wpelf_time_custom_field . '" value="' . esc_attr( $time_tmplt ) . '" class="small-text" /><small><em> ' . date_i18n( $time_tmplt ) . "</em></small>\n";	

	?>
	</div>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Object logger', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_logger_field; ?>" value="<?php echo $wpelf_logger_val; ?>" class="regular-text"></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e("Default access for files/directories", 'elfinder' ); ?></th> 
	<td><label for="wpelf-read">
	<input name="<?php echo $wpelf_defaults_r_field; ?>" type="checkbox" id="wpelf-read" value="true" <?php checked('true', $wpelf_defaults_val['read']); ?> />
	<?php _e('read', 'elfinder') ?></label>
	<br />
	<label for="wpelf-write">
	<input name="<?php echo $wpelf_defaults_w_field; ?>" type="checkbox" id="wpelf-write" value="true" <?php checked('true', $wpelf_defaults_val['write']); ?> />
	<?php _e('write' , 'elfinder') ?></label>
	<br />
	<label for="wpelf-rm">
	<input name="<?php echo $wpelf_defaults_rm_field; ?>" type="checkbox" id="wpelf-rm" value="true" <?php checked('true', $wpelf_defaults_val['rm']); ?> />
	<?php _e('remove', 'elfinder') ?></label></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Individual folders/files permisions', 'elfinder' ); ?></th>
	<td><div id="a4">
	<?php if (isset($wpelf_perms_val) && $wpelf_perms_val != ""){ ?>
	<input type="hidden" id="hidden-1" value="<?php echo count($wpelf_perms_val)-1;?>">
	
	<?php 
		$ii=0;
		$ff_keys=array_keys($wpelf_perms_val);
		foreach ($wpelf_perms_val as $ff_perms){ ?>
			<div id="<?php echo 'wpelf-perms-div-' . $ii ?>"><input type="text" id="<?php echo $wpelf_perms_field . '-' . $ii; ?>" name="<?php echo $wpelf_perms_field . '-' . $ii; ?>" value="<?php if (substr_count($ff_keys[$ii],'\\\\') > 0){ echo stripslashes($ff_keys[$ii]); } else { echo $ff_keys[$ii]; } ?>" class="regular-text code"> <label for="<?php echo 'wpelf-perms-read-' . $ii ?>">
			<input name="<?php echo $wpelf_perms_r_field . '-' . $ii; ?>" type="checkbox" id="<?php echo 'wpelf-perms-read-' . $ii; ?>" value="true" <?php checked('true', $ff_perms['read']); ?> />
			<?php _e('read', 'elfinder') ?></label>
			<label for="<?php echo 'wpelf-perms-write-' . $ii ?>">
			<input name="<?php echo $wpelf_perms_w_field . '-' . $ii; ?>" type="checkbox" id="<?php echo 'wpelf-perms-write-' . $ii ?>" value="true" <?php checked('true', $ff_perms['write']); ?> />
			<?php _e('write' , 'elfinder') ?></label>
			<label for="<?php echo 'wpelf-perms-rm-' . $ii ?>">
			<input name="<?php echo $wpelf_perms_rm_field . '-' . $ii; ?>" type="checkbox" id="<?php echo 'wpelf-perms-rm-' . $ii ?>" value="true" <?php checked('true', $ff_perms['rm']); ?> />
			<?php _e('remove', 'elfinder') ?></label>
			<?php if ($ii==0) { ?> <input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addperms()"></div>
			<?php } else { ?> <input type="button" class="button" value="<?php _e('Remove', 'elfinder') ?>" onclick="removeperms('<?php echo $ii ?>')"></div> <?php } ?> 
			<?php $ii++;
			} ?>

	<?php } else { ?>
	<input type="hidden" id="hidden-1" value="0">
		<div id="wpelf-perms-div-0"><input type="text" id="wpelf_perms-0" name="wpelf_perms-0" value="<?php if (substr_count($ff_keys[$ii],'\\\\') > 0){ echo stripslashes($ff_keys[$ii]); } else { echo $ff_keys[$ii]; } ?>" class="regular-text code"> <label for="wpelf-perms-read-0">
		<input name="<?php echo $wpelf_perms_r_field . '-0'; ?>" type="checkbox" id="<?php echo $wpelf_perms_r_field . '-0'; ?>" value="true" <?php checked('true', $ff_perms['read']); ?> />
		<?php _e('read', 'elfinder') ?></label>
		<label for="wpelf-perms-write-0">
		<input name="<?php echo $wpelf_perms_w_field . '-0'; ?>" type="checkbox" id="wpelf-perms-write-0" value="true" <?php checked('true', $ff_perms['write']); ?> />
		<?php _e('write' , 'elfinder') ?></label>
		<label for="wpelf-perms-rm-0">
		<input name="<?php echo $wpelf_perms_rm_field . '-0'; ?>" type="checkbox" id="wpelf-perms-rm-0" value="true" <?php checked('true', $ff_perms['rm']); ?> />
		<?php _e('remove', 'elfinder') ?></label> <input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addperms()"></div>
	<?php } ?>
	</div><div><em>/\.(jpg|gif|png)$/i + r w <s>rm</s> &rarr; <?php _e('disallow delete jpeg/png/gif', 'elfinder') ?></em><br />
		<em>/\.(txt|html|php|py|pl|sh|xml)$/i + r <s>w</s> rm &rarr; <?php _e("disallow write to text files (also you won't be able to rename them)", 'elfinder') ?></em><br />
		<em>/^user_dir\/.*/ + r w rm + <?php _e('«default access..» is set to «read» only &rarr; read — all, write and delete only in personal directory', 'elfinder') ?></em>
	</div></td></tr>

	<tr valign="top">
	<th scope="row"><?php _e('Send debug information to client', 'elfinder' ); ?></th> 
	<td><input type="checkbox" name="<?php echo $wpelf_debug_field; ?>" value="true" <?php checked('true', $wpelf_debug_val); ?>></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('List of file archives allowed to create', 'elfinder' ); ?></th> 
	<td><input type="text" name="<?php echo $wpelf_archive_mimes_field; ?>" value="<?php echo $wpelf_archive_mimes_val; ?>" class="regular-text">
	<span class="description"><?php _e('If not set will allow all detected archvies.', 'elfinder') ?></span></td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Info about archivers to use', 'elfinder' ); ?></th> 
	<td><table class="archivers"><tr><th><br /><?php _e('create', 'elfinder'); ?> &rarr; </th><td><div id="b5">
	<?php if (isset($wpelf_archivers_val['create']) && $wpelf_archivers_val['create'] != ""){ ?>
	<?php 
		$ii=0;
		$crule_apps=array_keys($wpelf_archivers_val['create']);
		foreach ($wpelf_archivers_val['create'] as $crule_opt){
			if ($ii == 0) { ?> 	<input type="hidden" id="hidden-2" value="<?php echo count($wpelf_archivers_val['create'])-1;?>">
			<div><div class="wpelf-regular-text"><?php _e('application mime-type', 'elfinder' ) ?></div> <div class="wpelf-small-text"><?php _e('cmd', 'elfinder') ?></div> <div class="wpelf-small-text"><?php _e('argc' , 'elfinder') ?></div> <div class="wpelf-tiny-text"><?php _e('ext', 'elfinder') ?></div></div> <?php } ?>
			
			<div id="<?php echo 'wpelf-create-div-' . $ii; ?>"><input type="text" id="<?php echo $wpelf_crule_field . '-' . $ii; ?>" name="<?php echo $wpelf_crule_field . '-' . $ii; ?>" value="<? echo $crule_apps[$ii]; ?>" class="regular-text code">
			<input name="<?php echo 'wpelf-create-cmd-' . $ii; ?>" type="text" id="<?php echo 'wpelf-create-cmd-' . $ii; ?>" value="<?php echo $crule_opt['cmd']; ?>" class="small-text code" />
			<input name="<?php echo 'wpelf-create-argc-' . $ii; ?>" type="text" id="<?php echo 'wpelf-create-argc-' . $ii; ?>" value="<?php echo $crule_opt['argc']; ?>" class="small-text code" />
			<input name="<?php echo 'wpelf-create-ext-' . $ii; ?>" type="text" id="<?php echo 'wpelf-create-ext-' . $ii; ?>" value="<?php echo $crule_opt['ext']; ?>" class="small-text code" />
			<?php if ($ii==0) { ?> <input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addcreate()">
			<?php } else { ?> <input type="button" class="button" value="<?php _e('Remove', 'elfinder') ?>" onclick="removecreate('<?php echo $ii ?>')"><?php } ?>
			</div>
			<?php $ii++;
			} ?>

	<?php } else { ?> 	<input type="hidden" id="hidden-2" value="0">
			<div><div class="wpelf-regular-text"><?php _e('application mime-type', 'elfinder' ) ?></div> <div class="wpelf-small-text"><?php _e('cmd', 'elfinder') ?></div> <div class="wpelf-small-text"><?php _e('argc' , 'elfinder') ?></div> <div class="wpelf-tiny-text"><?php _e('ext', 'elfinder') ?></div></div>
			<div id="wpelf-create-div-0"><input type="text" id="<?php echo $wpelf_crule_field . '-0'; ?>" name="<?php echo $wpelf_crule_field . '-0'; ?>" class="regular-text code">
			<input name="wpelf-create-cmd-0" type="text" id="wwpelf-create-cmd-0" class="small-text code" />
			<input name="wpelf-create-argc-0" type="text" id="wpelf-create-argc-0" class="small-text code" />
			<input name="wpelf-create-ext-0" type="text" id="wpelf-create-ext-0" class="small-text code" />
			<input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addcreate()"></div>
	<?php } ?>
			</div><div><em><div class="wpelf-regular-text">application/x-gzip</div> <div class="wpelf-small-text">tar</div> <div class="wpelf-small-text">-czf</div> <div class="wpelf-tiny-text">tar.gz</div></em></div></td></tr>

	
	<tr><th><?php _e('extract', 'elfinder'); ?> &rarr; </th><td><div id="c7">
	<?php if (isset($wpelf_archivers_val['extract']) && $wpelf_archivers_val['extract'] != ""){ ?>
	
	<?php 
		$ii=0;
		$erule_apps=array_keys($wpelf_archivers_val['extract']);
		foreach ($wpelf_archivers_val['extract'] as $erule_opt){
			if ($ii == 0) { ?> 	<input type="hidden" id="hidden-3" value="<?php echo count($wpelf_archivers_val['extract'])-1;?>">	 <?php } ?>		
			<div id="<?php echo 'wpelf-extract-div-' . $ii; ?>"><input type="text" id="<?php echo $wpelf_erule_field . '-' . $ii; ?>" name="<?php echo $wpelf_erule_field . '-' . $ii; ?>" value="<?php echo $erule_apps[$ii]; ?>" class="regular-text code">
			<input name="<?php echo 'wpelf-extract-cmd-' . $ii; ?>" type="text" id="<?php echo 'wpelf-extract-cmd-' . $ii; ?>" value="<?php echo $erule_opt['cmd']; ?>" class="small-text code" />
			<input name="<?php echo 'wpelf-extract-argc-' . $ii; ?>" type="text" id="<?php echo 'wpelf-extract-argc-' . $ii; ?>" value="<?php echo $erule_opt['argc']; ?>" class="small-text code" />
			<input name="<?php echo 'wpelf-extract-ext-' . $ii; ?>" type="text" id="<?php echo 'wpelf-extract-ext-' . $ii; ?>" value="<?php echo $erule_opt['ext']; ?>" class="small-text code" />
			<?php if ($ii==0) { ?> <input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addextract()">
			<?php } else { ?> <input type="button" class="button" value="<?php _e('Remove', 'elfinder') ?>" onclick="removeextract('<?php echo $ii ?>')"><?php } ?>
			</div>
			<?php $ii++;
			} ?>

	<?php } else { ?>
			<input type="hidden" type="hidden" id="hidden-3" value="0"> 	
			<div id="wpelf-extract-div-0"><input type="text" id="<?php echo $wpelf_erule_field . '-0'; ?>" name="<?php echo $wpelf_erule_field . '-0'; ?>" class="regular-text code">
			<input name="wpelf-extract-cmd-0" type="text" id="wwpelf-extract-cmd-0" class="small-text code" />
			<input name="wpelf-extract-argc-0" type="text" id="wpelf-extract-argc-0" class="small-text code" />
			<input name="wpelf-extract-ext-0" type="text" id="wpelf-extract-ext-0" class="small-text code" />
			<input type="button" class="button" value="<?php _e('   Add   ', 'elfinder') ?>" onclick="addextract()"></div>
		<?php } ?>
			</div><div><em><div class="wpelf-regular-text">application/x-gzip</div> <div class="wpelf-small-text">tar</div> <div class="wpelf-small-text">-xzf</div> <div class="wpelf-tiny-text">tar.gz</div></em><br />
			<!--<em><div class="wpelf-regular-text">application/x-bzip2</div> <div class="wpelf-small-text">tar</div> <div class="wpelf-small-text">-xjf</div> <div class="wpelf-tiny-text">tar.bz</div></em>-->
			</div></td></tr>
	</table>
	
	<span class="description"><?php _e('Leave the fields blank for auto detect.', 'elfinder') ?></span></td>
	</tr>
	</table>

	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</tr>

	</form>
	</div>
<?php
}

?>