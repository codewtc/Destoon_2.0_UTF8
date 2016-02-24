<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/company.class.php';
$menus = array (
    array('添加'.VIP, '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array(VIP.'列表', '?moduleid='.$moduleid.'&file='.$file),
    array('过期'.VIP, '?moduleid='.$moduleid.'&file='.$file.'&action=expire'),
    array('受理申请', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
);
$do = new company;
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
$fromtime = timetodate($DT_TIME, 3);
$GROUP = cache_read('group.php');
switch($action) {
	case 'add':	
		if($submit) {		
			if(!$vip['username']) msg('会员名不能为空');
			$vip['username'] = trim($vip['username']);
			if(strpos($vip['username'], "\n") === false) {
				$do->vip_edit($vip);
			} else {
				$usernames = explode("\n", $vip['username']);
				foreach($usernames as $username) {
					$username = trim($username);
					if(!$username) continue;
					$vip['username'] = $username;
					$do->vip_edit($vip);
				}
			}
			dmsg('添加成功', $this_forward);
		} else {
			isset($username) or $username = '';
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT username FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						$username .= $r['username']."\n";
					}
				}
			}
			$totime = timetodate($DT_TIME+365*24*3600, 3);
			include tpl('vip_add', $module);
		}
	break;
	case 'edit':
		$userid or msg();
		$do->userid = $userid;
		if($submit) {
			if($do->vip_edit($vip)) {
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$fromtime = timetodate($fromtime, 3);
			$totime = timetodate($totime, 3);
			$validtime = $validtime ? timetodate($validtime, 3) : '';
			$n = $db->get_one("SELECT note FROM {$DT_PRE}vip WHERE username='$username'");
			include tpl('vip_edit', $module);
		}
	break;
	case 'delete':
		$userid or msg('请选择公司');
		$do->vip_delete($userid);
		dmsg('撤销成功', $this_forward);
	break;
	case 'update':
		is_array($userid) or msg('请选择公司');
		foreach($userid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'show':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->vedit($vip)) {
				dmsg('受理成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_vone());
			$adddate = timetodate($addtime, 5);
			if($edittime) $editdate = timetodate($edittime, 5);
			$content = nl2br($content);
			if($note) $note = nl2br($note);
			$totime = timetodate($DT_TIME+365*24*3600, 3);
			include tpl('vip_show', $module);
		}
	break;
	case 'move':
		$itemid or msg('请选择申请');
		if($do->vdelete($itemid)) {
			dmsg('删除成功', $forward);
		} else {
			msg($do->errmsg);
		}
	break;
	case 'check':
		$sfields = array('按条件', '公司名', '会员名', '申请内容');
		$dfields = array('company', 'company', 'username', 'content');	
		$sstatus = array('受理状态', '已通过', '审核中', '已拒绝');
		$dstatus = array('0', '3', '2', '1');
		$sorder  = array('结果排序方式', '申请时间降序', '申请时间升序', '受理时间降序', '受理时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC');
	
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($status) && isset($dstatus[$status]) or $status = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($sstatus, 'status', '', $status);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($status) $condition .= " AND status=$dstatus[$status]";
		$companys = $do->get_vlist($condition, $dorder[$order]);
		include tpl('vip_check', $module);
	break;
	default:
		$sfields = array('按条件', '公司名', '会员名');
		$dfields = array('keyword', 'company', 'username');
		$sorder  = array('结果排序方式', '服务开始降序', '服务开始升序', '服务结束降序', '服务结束升序', VIP.'指数降序', VIP.'指数升序', '理论值降序', '理论值升序', '修正值降序', '修正值升序', '会员ID降序', '会员ID升序');
		$dorder  = array('fromtime DESC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC', 'vip DESC', 'vip ASC', 'vipt DESC', 'vipt ASC', 'vipr DESC', 'vipr ASC', 'userid DESC', 'userid ASC');
	
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;	
		$groupid = isset($groupid) ? intval($groupid) : 0;
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$group_select = group_select('groupid', '会员组', $groupid);
		$vip = isset($vip) ? intval($vip) : 0;
		$condition = $vip ? "vip=$vip" : "vip>0";
		if($action == 'expire') $condition .= " AND totime<$DT_TIME";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($groupid) $condition .= " AND groupid=$groupid";
		$companys = $do->get_list($condition, $dorder[$order]);
		include tpl($action == 'expire' ? 'vip_expire' : 'vip', $module);
	break;
}
?>