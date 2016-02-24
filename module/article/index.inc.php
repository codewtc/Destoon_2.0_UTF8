<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($MOD['index_html']) {	
	$html_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
	if(!is_file($html_file)) tohtml('index', $module);
	include($html_file);
	exit;
}

if(!check_group($_groupid, $MOD['group_index'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	include template('noright', 'message');
	exit;
}

$maincat = get_maincat(0, $CATEGORY);

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_index']) {
	eval("\$seo_title = \"$MOD[seo_index]\";");
} else {
	$seo_title = $seo_modulename.$seo_delimiter.$seo_sitename;
}

$template = $MOD['template'] ? $MOD['template'] : 'index';
include template($template, $module);
?>