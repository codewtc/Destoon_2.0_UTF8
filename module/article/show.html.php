<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status=3 AND islink=0");
if(!$item) return false;
extract($item);
$CAT = cache_read('category_'.$catid.'.php');

if($MOD['text_data']) {
	$content = text_read($itemid, $moduleid);
} else {
	$content = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
	$content = $content['content'];
}

$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
if($voteid) $voteid = explode(' ', $voteid);
$fileurl = $linkurl;
$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);

$fee = get_fee($item['fee'], $MOD['fee_view']);
if($fee) {
	$description = get_description($content, $MOD['pre_view']);
	$user_status = 4;
} else {
	$user_status = 3;
}

$pages = '';
$total = 1;
if(strpos($content, '[pagebreak]') !== false) {
	$contents = explode('[pagebreak]', $content);
	$total = count($contents);	
}

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_show']) {
	eval("\$seo_title = \"$MOD[seo_show]\";");
} else {
	$seo_title = $seo_showtitle.$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
}
$head_keywords = $keyword;
$head_description = $introduce ? $introduce : $title;

$template = $item['template'] ? $item['template'] : 'show';
for(; $page <= $total; $page++) {
	$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
	$filename = $total == 1 ? DT_ROOT.'/'.$MOD['moduledir'].'/'.$fileurl : DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($itemid, $catid, $addtime, $page);
	if($total > 1) {
		$pages = article_pages($itemid, $catid, $addtime, $total, $page);
		$content = $contents[$page-1];
	}
	if($MOD['keylink']) $content = keylink($content, $moduleid);
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	file_put($filename, $data);
	if($page == 1 && $total > 1) file_copy($filename, DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($itemid, $catid, $addtime, 0));
}
return true;
?>