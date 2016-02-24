<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['announce_enable'] or dheader(DT_PATH);
$TYPE = get_type('announce', 1);
require MOD_ROOT.'/announce.class.php';
$do = new announce();
$typeid = isset($typeid) ? intval($typeid) : 0;
if($itemid) {
	$do->itemid = $itemid;
	$item = $do->get_one();
	$item or message('', DT_PATH);
	extract($item);
	$adddate = timetodate($addtime, 3);
	$fromdate = $fromtime ? timetodate($fromtime, 3) : '不限';
	$todate = $totime ? timetodate($totime, 3) : '不限';
	$db->query("UPDATE {$DT_PRE}announce SET hits=hits+1 WHERE itemid=$itemid");
	$head_title = $head_keywords = $head_description = $title.$DT['seo_delimiter'].'公告中心';
	$template = $item['template'] ? $item['template'] : 'announce';
	include template($template, $module);
} else {
	$head_title = $head_keywords = $head_description = '公告中心';
	$condition = '1';
	if($typeid) $condition .= " AND typeid=$typeid";
	$lists = $do->get_list($condition, 'listorder DESC,itemid DESC');
	include template('announce', $module);
}
?>