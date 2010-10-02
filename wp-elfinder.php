<?php
/*
Plugin Name: elfinder
Plugin URI: http://www.elrte.ru/elfinder
Description: Operate your files right from Wordpress admin panel using gorgeous elFinder.
Version: 0.1
Author: studio 42 + jibiel
Author URI: http://www.elrte.ru/elfinder
*/

register_activation_hook(__FILE__, 'wpelf_activation');

define('WPELF_VERSION', '0.1');

//Define the path to the plugin folder
define('WPELF_FILE_PATH', dirname(__FILE__));
define('WPELF_DIR_NAME', basename(WPELF_FILE_PATH));

//Define the url to the plugin folder
define('WPELF_FOLDER', dirname(plugin_basename(__FILE__)));
define('WPELF_URL', get_bloginfo('wpurl').'/wp-content/plugins/' . WPELF_FOLDER);

// Translation functions used to set up localization and define some translation variables
$wpelf_domain = 'elfinder';
$wpelf_is_setup = 0;

function wpelf_activation() {
	
	// Set options
	if (get_option('wpelf_root') === false) {
		add_option('wpelf_root', $_SERVER['DOCUMENT_ROOT'] . '/', __('Path to root directory', 'elfinder'));
	}
	if (get_option('wpelf_url') === false) {
		add_option('wpelf_url', get_bloginfo('url') . '/', __('Root directory URL', 'elfinder'));
	}
	if (get_option('wpelf_root_alias') === false) {
		add_option('wpelf_root_alias', 'home', __('Display this instead of root directory name', 'elfinder'));
	}
	if (get_option('wpelf_disabled') === false) {
		add_option('wpelf_disabled', array(), __('List of not allowed commands', 'elfinder'));
	}
	if (get_option('wpelf_dotfiles') === false) {
		add_option('wpelf_dotfiles', true, __('Display dot files', 'elfinder'));
	}
	if (get_option('wpelf_dir_size') === false) {
		add_option('wpelf_dir_size', true, __('Count total directories sizes', 'elfinder'));
	}
	if (get_option('wpelf_file_mode') === false) {
		add_option('wpelf_file_mode', 0666, __('New files mode', 'elfinder'));
	}
	if (get_option('wpelf_dir_mode') === false) {
		add_option('wpelf_dir_mode', 0777, __('New folders mode', 'elfinder'));
	}
	if (get_option('wpelf_mimedetect') === false) {
		add_option('wpelf_mimedetect', 'auto', __('MIME-type detection method (possible values: finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (based on file extensions))', 'elfinder'));
	}	
	if (get_option('wpelf_upload_allow') === false) {
		add_option('wpelf_upload_allow', array('images/*'), __('List of mime-types allowed to upload. Can be set exactly image/jpeg or to group application', 'elfinder'));
	}
	if (get_option('wpelf_upload_deny') === false) {
		add_option('wpelf_upload_deny', array('all'), __('List of mime-types disallowed to upload', 'elfinder'));
	}
	if (get_option('wpelf_upload_order') === false) {
		add_option('wpelf_upload_order', 'deny,allow', __('Order of upload rules execution. allow,deny only what is allowed, except what is disallowed (AND). deny,allow what is not disallowed or allowed (OR)', 'elfinder'));
	}
	if (get_option('wpelf_imglib') === false) {
		add_option('wpelf_imglib', 'auto', __('Library for thumbnail creation (possible values: imagick, mogrify, gd). If not set will try detect automatically', 'elfinder'));
	}
	if (get_option('wpelf_tmb_dir') === false) {
		add_option('wpelf_tmb_dir', '.tmb', __('Directory name for image thumbnails. Set to "" to avoid thumbnails generation', 'elfinder'));
	}
	if (get_option('wpelf_tmb_clean') === false) {
		add_option('wpelf_tmb_clean', 1, __('How often to clean thumbnails. Possible values: from 0 to 200. 0 - never, 200 - on each client init request', 'elfinder'));
	}
	if (get_option('wpelf_tmb_atonce') === false) {
		add_option('wpelf_tmb_atonce', 5, __('How many thumbnails to create per background request', 'elfinder'));
	}
	if (get_option('wpelf_tmb_size') === false) {
		add_option('wpelf_tmb_size', 48, __('Thumbnail size in pixels', 'elfinder'));
	}
	if (get_option('wpelf_file_url') === false) {
		add_option('wpelf_file_url', true, __('Show real URLs to files in client. Default: true', 'elfinder'));
	}
	if (get_option('wpelf_date_format') === false) {
		add_option('wpelf_date_format', 'j M Y', __('File modification date format. Default: j M Y', 'elfinder'));
	}
	if (get_option('wpelf_time_format') === false) {
		add_option('wpelf_time_format', 'H:i', __('File modification time format. Default: H:i', 'elfinder'));
	}
	if (get_option('wpelf_logger') === false) {
		add_option('wpelf_logger', null, __('Object logger', 'elfinder'));
	}
	if (get_option('wpelf_defaults') === false) {
		add_option('wpelf_defaults', array( 'read' => true, 'write' => true, 'rm' => true ), __('Default access for files/directories. Default: array( "read" => true, "write" => true, "rm" => true )', 'elfinder'));
	}
	if (get_option('wpelf_perms') === false) {
		add_option('wpelf_perms', array(), __('Individual folders/files permisions', 'elfinder'));
	}
	if (get_option('wpelf_debug') === false) {
		add_option('wpelf_debug', true, __('Send debug information to client', 'elfinder'));
	}
	if (get_option('wpelf_archive_mimes') === false) {
		add_option('wpelf_archive_mimes', array(), __('List of file archives allowed to create. If not set will allow all detected archvies', 'elfinder'));
	}
	if (get_option('wpelf_archivers') === false) {
		add_option('wpelf_archivers', array(), __('Info about archivers to use. See example below. Leave empty for auto detect', 'elfinder'));
	}
    // Example:
	//
	// 'archivers' => array(
	// 	'create' => array(
	// 		'application/x-gzip' => array(
	// 			'cmd' => 'tar',
	// 			'argc' => '-czf',
	// 			'ext'  => 'tar.gz'
	// 			)
	// 		),
	// 	'extract' => array(
	// 		'application/x-gzip' => array(
	// 			'cmd'  => 'tar',
	// 			'argc' => '-xzf',
	// 			'ext'  => 'tar.gz'
	// 			),
	// 		'application/x-bzip2' => array(
	// 			'cmd'  => 'tar',
	// 			'argc' => '-xjf',
	// 			'ext'  => 'tar.bz'
	// 			)
	// 		)
	// 	)

} 

function wpelf_multilang_setup(){
	global $wpelf_domain, $wpelf_is_setup;

	if($wpelf_is_setup) {
		return;
	} 
	load_plugin_textdomain($wpelf_domain, 'wp-content/plugins/wp-elfinder/language/','wp-elfinder/language/');
}

wpelf_multilang_setup();

// if we are in the admin section, include the admin code
if(WP_ADMIN == true) {
	require_once(WPELF_FILE_PATH . "/mediator/mediator.php");
}
?>