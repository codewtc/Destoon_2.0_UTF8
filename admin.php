<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
@set_time_limit(0);
define('DT_ADMIN', true);
define('DT_MEMBER', true);
require 'common.inc.php';
$session = new dsession();
require DT_ROOT.'/include/admin.func.php';
require_once DT_ROOT.'/include/cache.func.php';
require DT_ROOT.'/include/post.func.php';
define('IMG_PATH', DT_PATH.'admin/image/');
isset($file) or $file = 'index';
$_dt_admin = isset($_SESSION['dt_admin']) ? intval($_SESSION['dt_admin']) : 0;
admin_log();
if($file != 'login') {
	if($_groupid != 1 || $_level < 1 || !$_dt_admin) msg('', '?file=login&forward='.urlencode($DT_URL));
	admin_check() or msg('您无权进行此操作');
}
if($module == 'destoon') {
	(include DT_ROOT.'/admin/'.$file.'.inc.php') or msg();
} else {
	require DT_ROOT.'/module/'.$module.'/common.inc.php';
	(include MOD_ROOT.'/admin/'.$file.'.inc.php') or msg();
}
?>