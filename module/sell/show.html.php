<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status>2");
if(!$item) return false;
extract($item);
$CAT = cache_read('category_'.$catid.'.php');

if($MOD['text_data']) {
	$content = text_read($itemid, $moduleid);
} else {
	$content = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
	$content = $content['content'];
}
if($MOD['product_option']) {
	$options = $pid ? cache_read('option-'.$pid.'.php') : array();
	$values = array();
	if($options) {
		$result = $db->query("SELECT * FROM {$DT_PRE}sell_value WHERE itemid=$itemid");
		while($r = $db->fetch_array($result)) {
			$values[$r['oid']] = $r['value'];
		}
	}
}

$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$todate = timetodate($totime, 3);
$expired = $totime && $totime < $DT_TIME ? true : false;
$fileurl = $linkurl;
$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);
$thumbs = get_albums($item);
$albums =  get_albums($item, 1);

$fee = get_fee($item['fee'], $MOD['fee_view']);
$user_status = 4;

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_show']) {
	eval("\$seo_title = \"$MOD[seo_show]\";");
} else {
	$seo_title = $seo_showtitle.$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
}
$head_keywords = $keyword;
$head_description = $introduce ? $introduce : $title;

$template = $item['template'] ? $item['template'] : 'show';
$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid";

ob_start();
include template($template, $module);
$data = ob_get_contents();
ob_clean();
file_put(DT_ROOT.'/'.$MOD['moduledir'].'/'.$fileurl, $data);
return true;
?>