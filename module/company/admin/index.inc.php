<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/company.class.php';
$menus = array (
    array($MOD['name'].'列表', '?moduleid='.$moduleid),
    array('移动'.$MOD['name'], '?moduleid='.$moduleid.'&action=move'),
    array(VIP.'管理', '?moduleid='.$moduleid.'&file=vip'),
    array('会员列表', '?moduleid=2'),
);
$do = new company;
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
switch($action) {
	case 'update':
		is_array($userid) or msg('请选择'.$MOD['name']);
		foreach($userid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'move':
		if($submit) {
			$fromids or msg('请填写来源ID');
			if($tocatid) $db->query("UPDATE {$table} SET catid=$tocatid WHERE `{$fromtype}` IN ($fromids)");
			if($toareaid) $db->query("UPDATE {$table} SET areaid=$toareaid WHERE `{$fromtype}` IN ($fromids)");
			dmsg('移动成功', $forward);
		} else {
			$userid = isset($userid) ? implode(',', $userid) : '';
			$menuid = 1;
			include tpl($action, $module);
		}
	break;
	default:
		$sfields = array('按条件', '公司名', '会员名', '注册城市', '公司类型', '公司规模', '销售', '采购', '主营行业', '经营模式', '电话', '传真',  'Email',  '地址',  '邮编',  '主页', '顶级域名');
		$dfields = array('keyword', 'company', 'username', 'regcity', 'type', 'size', 'sell', 'buy', 'business', 'mode', 'telephone', 'fax', 'mail', 'address', 'postcode', 'homepage', 'domain');
		$sorder  = array('结果排序方式', VIP.'指数降序', VIP.'指数升序', '注册年份降序', '注册年份升序', '注册资本降序', '注册资本升序', '服务开始降序', '服务开始升序', '服务结束降序', '服务结束升序');
		$dorder  = array('userid DESC', 'vip DESC', 'vip ASC', 'regyear DESC', 'regyear ASC', 'capital DESC', 'capital ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		$vip = isset($vip) ? ($vip === '' ? -1 : intval($vip)) : -1;
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$groupid = isset($groupid) ? intval($groupid) : 0;
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$group_select = group_select('groupid', '会员组', $groupid);
	
		$condition = 'groupid>5';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($groupid) $condition .= " AND groupid=$groupid";
		if($vip > -1) $condition .= " AND vip=$vip";
		if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
		$companys = $do->get_list($condition, $dorder[$order]);
		include tpl('index', $module);
	break;
}
?>