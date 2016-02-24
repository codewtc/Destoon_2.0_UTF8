<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(!check_group($_groupid, $MOD['group_index'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	include template('noright', 'message');
	exit;
}

$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
($typeid >=0 && isset($TYPE[$typeid])) or $typeid = -1;
($catid && isset($CATEGORY[$catid])) or $catid = 0;
$dtype = $typeid >= 0 ? " and typeid=$typeid" : '';
$maincat = get_maincat(0, $CATEGORY);

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_index']) {
	eval("\$seo_title = \"$MOD[seo_index]\";");
} else {
	$seo_title = $seo_modulename.$seo_delimiter.$seo_sitename;
}
if($catid) $seo_title = $seo_catname.$seo_title;
if($typeid > 0) $seo_title = $TYPE[$typeid].$seo_delimiter.$seo_title;

$template = $MOD['template'] ? $MOD['template'] : 'index';
include template($template, $module);
?>