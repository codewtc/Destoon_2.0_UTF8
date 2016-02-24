<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$itemid or message('', $MOD['linkurl']);

$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status>2");
if($item) {
	if($MOD['show_html'] && is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl'])) dheader($MOD['linkurl'].$item['linkurl']);
} else {
	$head_title = '抱歉，您要访问的信息不存在或被删除';
	exit(include template('show-notfound', 'message'));
}

extract($item);
$CAT = cache_read('category_'.$catid.'.php');
if(!check_group($_groupid, $MOD['group_show']) || !check_group($_groupid, $CAT['group_show'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	exit(include template('noright', 'message'));
}

if($MOD['text_data']) {
	$content = text_read($itemid, $moduleid);
} else {
	$content = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
	$content = $content['content'];
}

$process = get_process($fromtime, $totime);
$fromtime = timetodate($fromtime, 3);
$totime = timetodate($totime, 3);
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);
$maincat = get_maincat(0, $CATEGORY);

$fee = get_fee($item['fee'], $MOD['fee_view']);
$update = "hits=hits+1";
if(check_group($_groupid, $MOD['group_contact'])) {
	if($fee) {
		$user_status = 4;
		$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid";
		$description = get_description($content, $MOD['pre_view']);
	} else {
		$user_status = 3;
		if($item['totime'] && $item['totime'] < $DT_TIME && $status == 3) $update .= ",status=4";
	}
} else {
	$user_status = $_userid ? 1 : 0;
}
$db->query("UPDATE {$table} SET $update WHERE itemid=$itemid");

include DT_ROOT.'/include/seo.inc.php';
if($MOD['seo_show']) {
	eval("\$seo_title = \"$MOD[seo_show]\";");
} else {
	$seo_title = $seo_showtitle.$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
}
$head_keywords = $keyword;
$head_description = $title.','.$hallname.','.$address;

$template = $item['template'] ? $item['template'] : 'show';
include template($template, $module);
?>