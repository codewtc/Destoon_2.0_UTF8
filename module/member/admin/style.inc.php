<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/style.class.php';
$do = new style();
$menus = array (
    array('添加模板', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('模板列表', '?moduleid='.$moduleid.'&file='.$file),
);

switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
			} else {
				msg($do->errmsg);
			}
		} else {
			$addtime = timetodate($DT_TIME);
			include tpl('style_add', $module);
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
			include tpl('style_edit', $module);
		}
	break;
	case 'order':
		$do->order($listorder);
		dmsg('更新成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择模板');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '模板名称', 'CSS文件', '模板目录', '作者');
		$dfields = array('title', 'title', 'skin', 'template', 'author');
		$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '使用人数降序', '使用人数升序');
		$dorder  = array('listorder DESC,addtime DESC', 'addtime DESC', 'addtime ASC', 'hits DESC', 'hits ASC');
	
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
	
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		$lists = $do->get_list('1'.$condition, $dorder[$order]);
		include tpl('style', $module);
	break;
}
?>