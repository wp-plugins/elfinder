<?php

//Loading WordPress and plugin environment
include_once(str_replace('/wp-content/plugins/wp-elfinder/elfinder/connectors/php','',dirname(__FILE__)) . "/wp-load.php");
include_once(str_replace('/elfinder/connectors/php','',dirname(__FILE__)) . "/mediator/includes/auxiliary-functions.php");

error_reporting(0); // Set E_ALL for debuging

if (function_exists('date_default_timezone_set')) {
	date_default_timezone_set('Europe/Moscow');
}

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';

/**
 * Simple example how to use logger with elFinder
 **/
class elFinderLogger implements elFinderILogger {
	
	public function log($cmd, $ok, $context, $err='', $errorData = array()) {
		if (false != ($fp = fopen('./log.txt', 'a'))) {
			if ($ok) {
				$str = "cmd: $cmd; OK; context: ".str_replace("\n", '', var_export($context, true))."; \n";
			} else {
				$str = "cmd: $cmd; FAILED; context: ".str_replace("\n", '', var_export($context, true))."; error: $err; errorData: ".str_replace("\n", '', var_export($errorData, true))."\n";
			}
			fwrite($fp, $str);
			fclose($fp);
		}
	}
	
}

$opts = array(
	'root'         => get_option('wpelf_root'),// path to root directory
	'URL'          => get_option('wpelf_url'), // root directory URL
	'rootAlias'    => get_option('wpelf_root_alias'),       // display this instead of root directory name
	'disabled'     => unserialize(get_option('wpelf_disabled')),      // list of not allowed commands
	'dotFiles'     => get_option('wpelf_dotfiles'),        // display dot files
	'dirSize'      => get_option('wpelf_dir_size'),         // count total directories sizes
	'fileMode'     => get_option('wpelf_file_mode'),         // new files mode
	'dirMode'      => get_option('wpelf_dir_mode'),         // new folders mode
	'mimeDetect'   => get_option('wpelf_mimedetect'),       // files mimetypes detection method (finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (by extensions))
	'uploadAllow'  => unserialize(get_option('wpelf_upload_allow')),      // mimetypes which allowed to upload
	'uploadDeny'   => unserialize(get_option('wpelf_upload_deny')),      // mimetypes which not allowed to upload
	'uploadOrder'  => get_option('wpelf_upload_order'), // order to proccess uploadAllow and uploadAllow options
	'imgLib'       => get_option('wpelf_imglib'),       // image manipulation library (imagick, mogrify, gd)
	'tmbDir'       => get_option('wpelf_tmb_dir'),       // directory name for image thumbnails. Set to "" to avoid thumbnails generation
	'tmbCleanProb' => get_option('wpelf_tmb_clean'),            // how frequiently clean thumbnails dir (0 - never, 100 - every init request)
	'tmbAtOnce'    => get_option('wpelf_tmb_atonce'),            // number of thumbnails to generate per request
	'tmbSize'      => get_option('wpelf_tmb_size'),           // images thumbnails size (px)
	'fileURL'      => get_option('wpelf_file_url'),         // display file URL in "get info"
	'dateFormat'   => get_option('wpelf_date_format'),  // file modification date format
	'logger'       => get_option('wpelf_logger'),         // object logger
	'defaults'     => unserialize(get_option('wpelf_defaults')),        // default permisions
	'perms'        => wpelf_slashesfree('wpelf_perms'),	  // individual folders/files permisions    
	'debug'        => get_option('wpelf_debug'),         // send debug to client
	'archiveMimes' => unserialize(get_option('wpelf_archive_mimes')),      // allowed archive's mimetypes to create. Leave empty for all available types.
	'archivers'    => unserialize(get_option('wpelf_archivers'))       // info about archivers to use. See example below. Leave empty for auto detect
	);


$fm = new elFinder($opts); 
$fm->run();

?>
