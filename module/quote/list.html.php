<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['list_html'] || !$catid || !isset($CATEGORY[$catid])) return false;
$CAT = cache_read('category_'.$catid.'.php');
unset($CAT['moduleid']);
extract($CAT);

$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE catid=$catid AND status=3");
$items = $r['num'];
cache_item($moduleid, $catid, $items);
$maincat = get_maincat(0, $CATEGORY);
$childcat = array();
if($child && $page == 1) {
	$childcat = get_maincat($catid, $CATEGORY);
	$caturl = $MOD['linkurl'].listurl($moduleid, $catid, 2, $CATEGORY, $MOD);
}

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_list']) {
	eval("\$seo_title = \"$MOD[seo_list]\";");
} else {
	$seo_title = $seo_cattitle.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
}
if($CAT['seo_keywords']) $head_keywords = $CAT['seo_keywords'];
if($CAT['seo_description']) $head_description = $CAT['seo_description'];

$template = $CAT['template'] ? $CAT['template'] : 'list';
$total = ceil($items/$MOD['pagesize']);
$total = $total ? $total : 1;
if(isset($fid) && isset($num)) {
	$page = $fid;
	$topage = $fid + $num;
	$total = $topage < $total ? $topage : $total;
}
for(; $page <= $total; $page++) {
	$destoon_task = "moduleid=$moduleid&html=list&catid=$catid&page=$page";
	$filename = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($moduleid, $catid, $page, $CATEGORY, $MOD);
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	file_put($filename, $data);
	if($page == 1) file_copy($filename, DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($moduleid, $catid, 0, $CATEGORY, $MOD));
}
return true;
?>