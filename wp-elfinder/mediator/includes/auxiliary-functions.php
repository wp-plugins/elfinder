<?php

// Striping array keys from slashes

function wpelf_slashesfree ($patient) {
	$uns_array = unserialize( get_option( $patient ) );
    $uns_keys = array_keys ($uns_array);
	$uns_values = array_values ($uns_array);
	$i=0;
	foreach ($uns_keys as $key) {
		$sane[stripslashes($key)] = $uns_values[$i];
		$i++;
	}
	return $sane;
}

/**
 * Display JavaScript on the page.
 *
 * @package WordPress
 * @subpackage General_Settings_Panel
 */
function add_wpelf_js() {
?>
<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($){
		$("input[name='wpelf_date_format']").click(function(){
			if ( "wpelf_date_custom_radio" != $(this).attr("id") )
				$("input[name='wpelf_date_custom']").val( $(this).val() );
		});
		$("input[name='wpelf_date_custom']").focus(function(){
			$("#wpelf_date_custom_radio").attr("checked", "checked");
		});

		$("input[name='wpelf_time_format']").click(function(){
			if ( "wpelf_time_custom_radio" != $(this).attr("id") )
				$("input[name='wpelf_time_custom']").val( $(this).val() );
		});
		$("input[name='wpelf_time_custom']").focus(function(){
			$("#wpelf_time_custom_radio").attr("checked", "checked");
		});
	});
//]]>
</script>
<?php
}
?>