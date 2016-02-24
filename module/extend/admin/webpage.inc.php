<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/webpage.class.php';
isset($item) or $item = 1;
$do = new webpage();
$do->item = $item;
$menus = array (
    array('添加单页', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&action=add'),
    array('单页列表', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item),
    array('生成网页', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&action=html'),
);
$this_forward = '?moduleid='.$moduleid.'&file='.$file.'&item='.$item;
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
			$filepath = 'extend/';
			$filename = '';
			$menuid = 0;
			include tpl('webpage_edit', $module);
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
			if($islink) {
				$filepath = $filename = '';
			} else {
				$filestr = str_replace(DT_URL, '', $linkurl);
				$filepath = strpos($filestr, '/') !== false ? dirname($filestr).'/' : '';
				$filename = basename($filestr);
			}
			$menuid = 1;
			include tpl('webpage_edit', $module);
		}
	break;
	case 'order':
		$do->order($listorder);
		dmsg('排序成功', $forward);
	break;
	case 'html':		
		$do->html();
		dmsg('生成成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择单页');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择单页');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	default:
		$lists = $do->get_list("item='$item'", 'listorder DESC,itemid DESC');
		include tpl('webpage', $module);
	break;
}
?>