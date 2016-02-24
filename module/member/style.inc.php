<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['homepage'] && $MG['style'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
require MOD_ROOT.'/style.class.php';
$do = new style();
if($itemid) {
	$do->itemid = $itemid;
	$r = $do->get_one();
	$r or message('模板不存在');
	if($r['groupid']) {
		$groupids = explode(',', $r['groupid']);
		if(!in_array($_groupid, $groupids)) message('抱歉！此模板未对您所在的会员组开放');
	}

	$c = $db->get_one("SELECT skin FROM {$DT_PRE}company WHERE userid='$_userid'");
	$c['skin'] or $c['skin'] = 'default';

	$o = $db->get_one("SELECT itemid FROM {$DT_PRE}style WHERE skin='$c[skin]'");
	if($o) $db->query("UPDATE {$DT_PRE}style SET hits=hits-1 WHERE itemid=$o[itemid]");

	$db->query("UPDATE {$DT_PRE}style SET hits=hits+1 WHERE itemid=$itemid");
	$db->query("UPDATE {$DT_PRE}company SET template='$r[template]',skin='$r[skin]' WHERE userid='$_userid'");
	dmsg('模板启用成功', $forward);
} else {
	$pagesize = 9;
	$offset = ($page-1)*$pagesize;

	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '人气指数降序', '人气指数升序');
	$dorder  = array('listorder desc,itemid desc', 'addtime DESC', 'addtime ASC', 'hits DESC', 'hits ASC');

	isset($order) && isset($dorder[$order]) or $order = 0;

	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = "1";
	if($keyword) $condition .= " AND (title LIKE '%$keyword%' || author LIKE '%$keyword%')";
	$c = $db->get_one("SELECT skin,linkurl FROM {$DT_PRE}company WHERE userid='$_userid'");
	if(!$c['skin']) {
		if($MG['styleid']) {
			$o = $db->get_one("SELECT skin FROM {$DT_PRE}style WHERE itemid='$MG[styleid]'");
			if($o) $c['skin'] = $o['skin'];
		}
	}
	$c['skin'] or $c['skin'] = 'default';
	$lists = $do->get_list($condition, $dorder[$order]);
}
$head_title = '模板设置';
include template('style', $module);
?>