<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
if(preg_match('/(mozilla|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
	require '../extend/config.inc.php';
	require '../common.inc.php';
	require DT_ROOT.'/module/'.$module.'/wap.inc.php';
	exit;
} else {
	define('DT_MEMBER', true);
	define('DT_WAP', true);
	require '../common.inc.php';
}
header("Content-type:text/html; charset=utf-8");
$WCF = cache_read('module-3.php');
require DT_ROOT.'/include/module.func.php';
require 'global.func.php';
$WCF['wap_enable'] or wap_msg('系统未开启WAP浏览');
$wap_modules = array('member', 'sell', 'buy', 'quote', 'company', 'exhibit', 'article', 'info', 'job');
$pagesize = $WCF['wap_pagesize'] ? $WCF['wap_pagesize'] : 10;
$offset = ($page-1)*$pagesize;
$maxlength = $WCF['wap_maxlength'] ? $WCF['wap_maxlength'] : 200;
$kw = $kw ? trim($kw) : '';
$pages = '';
$now = timetodate($DT_TIME, 'm/d H:i');
$areaid = isset($areaid) ? intval($areaid) : 0;
$head_title = $DT['sitename'];
if(strtolower($CFG['charset'] != 'utf-8') && $kw) {
	$kw = convert($kw, 'utf-8', $CFG['charset']);
	$DT_URL = convert(urldecode($DT_URL), 'utf-8', $CFG['charset']);
}
if(in_array($module, $wap_modules)) {
	include $module.'.inc.php';
} else {
	$WAP_MODULE = array();
	foreach($MODULE as $v) {
		if(in_array($v['module'], $wap_modules) && $v['module'] != 'member') $WAP_MODULE[] = $v;
	}
	include template('index', 'wap');
}
wap_output();
?>