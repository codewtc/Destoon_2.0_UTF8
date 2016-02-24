<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/news.class.php';
$do = new news();
$menus = array (
    array('添加新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('新闻列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('未通过新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&action=recycle'),
);
if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$sfields = array('按条件', '标题', '会员名');
	$dfields = array('title', 'title', 'username');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序', '浏览次数降序', '浏览次数升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'hits DESC', 'hits ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
}
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catid='.$post['catid']);
			} else {
				msg($do->errmsg);
			}
		} else {
			$addtime = timetodate($DT_TIME);
			include tpl('news_add', $module);
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
			$menuon = array('4', '3', '2', '1');
			include tpl('news_edit', $module);
		}
	break;
	case 'update':		
		$result = $db->query("SELECT * FROM {$DT_PRE}news");
		while($r = $db->fetch_array($result)) {
			$do->update($r['itemid']);
		}
		dmsg('更新成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		include tpl('news_recycle', $module);
	break;
	case 'check':
		if($itemid) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			include tpl('news_check', $module);
		}
	break;
	case 'reject':
		if($itemid) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			include tpl('news_reject', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择新闻');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		include tpl('news', $module);
	break;
}
?>