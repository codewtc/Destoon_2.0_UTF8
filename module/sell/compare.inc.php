<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
check_group($_groupid, $MOD['group_compare']) or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
$DT_URL = $DT_REF;
$itemid && is_array($itemid) or dalert('请选择需要对比的信息', 'goback');
$itemid = array_unique(array_map('intval', $itemid));
$item_nums = count($itemid);
$item_nums < 9 or dalert('同时最多对比 8 条信息', 'goback');
$item_nums > 1 or dalert('同时最少对比 2 条信息', 'goback');
$itemid = implode(',', $itemid);
$tags = array();
$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemid) ORDER BY addtime DESC");
while($r = $db->fetch_array($result)) {
	$r['editdate'] = timetodate($r['edittime'], 3);
	$r['adddate'] = timetodate($r['addtime'], 3);
	$r['stitle'] = dsubstr($r['title'], 30);
	$r['stitle'] = set_style($r['stitle'], $r['style']);
	$r['userurl'] = userurl($r['username']);
	$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
	$tags[] = $r;
}
$head_title = '产品对比'.$DT['seo_delimiter'].$MOD['name'];
include template('compare', $module);
?>