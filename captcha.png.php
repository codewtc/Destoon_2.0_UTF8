<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
define('DT_NONUSER', true);
require 'common.inc.php';
check_referer() or exit;
$session = new dsession();
require DT_ROOT.'/include/captcha.class.php';
$do = new captcha;
if($action == crypt_action('question')) {
	$id = isset($id) ? trim($id) : 'questionstr';
	$do->question($id);
} else if($action == crypt_action('image')) {
	$do->image();
}
?>