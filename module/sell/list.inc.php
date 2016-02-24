<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(!$catid || !isset($CATEGORY[$catid])) {
	$head_title = '抱歉，您要访问的分类不存在';
	exit(include template('list-notfound', 'message'));
}

if($MOD['list_html']) {
	$html_file = listurl($moduleid, $catid, $page, $CATEGORY, $MOD);
	if(is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$html_file)) dheader($MOD['linkurl'].$html_file);
}

$CAT = cache_read('category_'.$catid.'.php');
if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问分类 '.$CAT['catname'];
	exit(include template('noright', 'message'));
}

unset($CAT['moduleid']);
extract($CAT);
$maincat = get_maincat($child ? $catid : $parentid, $CATEGORY);

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_list']) {
	eval("\$seo_title = \"$MOD[seo_list]\";");
} else {
	$seo_title = $seo_cattitle.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
}
if($CAT['seo_keywords']) $head_keywords = $CAT['seo_keywords'];
if($CAT['seo_description']) $head_description = $CAT['seo_description'];

$template = $CAT['template'] ? $CAT['template'] : 'list';
include template($template, $module);
?>