<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['ad_enable'] or dheader(DT_PATH);
require MOD_ROOT.'/ad.class.php';
$do = new ad();
$TYPE = array('广告类型', '代码广告', '文字链接', '图片广告', 'Flash广告', '排名广告', '幻灯片广告');
$typeid = isset($typeid) ? intval($typeid) : 0;
$pid = isset($pid) ? intval($pid) : 0;
$item = $db->get_one("SELECT pid FROM {$DT_PRE}ad_place ORDER BY rand()");
$destoon_task = "moduleid=$moduleid&html=ad&itemid=$item[pid]";
if($pid) {
	$MOD['ad_view'] or message('系统已关闭广告位预览功能，请直接与我们联系');
	$do->pid = $pid;
	$p = $do->get_one_place();
	$p or message('', $MOD['linkurl'].'ad.php');
	$ad = false;
	$filename = 'ad_'.$pid.'.htm';
	$typeid = $p['typeid'];
	if($typeid == 5) $filename = 'ad_m'.$p['moduleid'].'.htm';
	$head_title = '广告位 ['.$p['name'].'] 预览';
	$action = 'view';
	include template('ad', $module);
} else {
	$head_title = $head_keywords = $head_description = '广告中心';
	$condition = '1';
	if($typeid) $condition .= " AND typeid=$typeid";
	$ads = $do->get_list_place($condition, 'listorder DESC,pid DESC');
	include template('ad', $module);
}
?>