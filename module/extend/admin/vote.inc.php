<?php
defined('IN_DESTOON') or exit('Access Denied');
$TYPE = get_type('vote', 1);
$TYPE or msg('请先添加投票分类', '?file=type&item=vote');
require MOD_ROOT.'/vote.class.php';
$do = new vote();
$menus = array (
    array('添加投票', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('投票列表', '?moduleid='.$moduleid.'&file='.$file),
    array('更新地址', '?moduleid='.$moduleid.'&file='.$file.'&action=update'),
    array('生成投票', '?moduleid='.$moduleid.'&file='.$file.'&action=html'),
    array('投票分类', '?file=type&item=vote'),
);
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$vote_min = 1;
			$vote_max = 3;
			$addtime = timetodate($DT_TIME);
			include tpl('vote_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$addtime = timetodate($addtime);
			$fromtime = $fromtime ? timetodate($fromtime, 3) : '';
			$totime = $totime ? timetodate($totime, 3) : '';
			include tpl('vote_edit', $module);
		}
	break;
	case 'update':
		$do->update();
		dmsg('更新成功', $forward);
	break;
	case 'html':		
		$do->html();
		dmsg('生成成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择投票');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择投票');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'record':
		$itemid or msg();
		$do->itemid = $itemid;
		$item = $do->get_one();
		extract($item);
		$votes = array();
		for($i = 1; $i < 11; $i++) {
			$s = 's'.$i;
			if($$s) $votes[$i] = $$s;
		}
		$condition = "itemid=$itemid";
		if($keyword) $condition .= " AND (ip LIKE '%$keyword%' OR username LIKE '%$keyword%')";
		$lists = $do->get_list_record($condition);
		include tpl('vote_record', $module);
	break;
	default:	
		$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '投票次数降序', '投票次数升序', '浏览次数降序', '浏览次数升序', '开始时间降序', '开始时间升序', '到期时间降序', '到期时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'votes DESC', 'votes ASC', 'hits DESC', 'hits ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		isset($order) && isset($dorder[$order]) or $order = 0;
		isset($typeid) or $typeid = 0;
		$type_select = type_select('vote', 1, 'typeid', '请选择分类', $typeid);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid=$typeid";
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('vote', $module);
	break;
}
?>