<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
define('DT_NONUSER', true);
if($_SERVER['QUERY_STRING'] && strpos($_SERVER['QUERY_STRING'], '$') === false) {
	$exprise = $_GET['tag_expires'] ? $_GET['tag_expires'] : 0;
	foreach($_GET as $k=>$v) { unset($$k); }
	$_GET = array();
	require 'common.inc.php';
	check_referer() or exit('document.write("<h2>Invalid Referer</h2>");');
	ob_start();
	tag($_SERVER['QUERY_STRING'], $exprise);
	$data = ob_get_contents();
	ob_clean();
	echo 'document.write(\''.dtrim($data, true).'\');';
} else {
	echo 'document.write("<h2>Bad Parameter</h2>");';
}
?>