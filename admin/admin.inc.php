<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/include/admin.class.php';
$admin = new admin;
$menus = array (
    array('添加管理员', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('管理员管理', '?moduleid='.$moduleid.'&file='.$file),
    array('权限&面板', '?moduleid='.$moduleid.'&file='.$file.'&action=right&userid='.$_userid),
);
$this_forward = '?file='.$file;
switch($action) {
	case 'add':
		if($submit) {
			$level = $level == 1 ? 1 : 2;
			if($admin->set_admin($username, $level)) {
				$r = $admin->get_one($username);
				$userid = $r['userid'];
				msg('管理员添加成功，下一步请分配权限和管理面板', '?file='.$file.'&action=right&userid='.$userid);
			}
			msg($admin->errmsg);
		} else {
			include tpl('admin_add');
		}
	break;
	case 'move':
		if($admin->move_admin($username)) dmsg('操作成功', $this_forward);
		msg($admin->errmsg);
	break;
	case 'delete':
		if($admin->delete_admin($username)) dmsg('撤销成功', $this_forward);
		msg($admin->errmsg);
	break;
	case 'right':
		if(!$userid) msg();
		if($submit) {
			$right[0]['action'] = implode('|', $right[0]['action']);
			$right[0]['catid'] = implode('|', $right[0]['catid']);
			if($admin->update($userid, $right)) {
				dmsg('更新成功', '?file='.$file.'&action=right&userid='.$userid);
			}
			msg($admin->errmsg);
		} else {
			$user = $admin->get_one($userid, 0);
			$username = $user['username'];
			$drights = $admin->get_right($userid);
			$dmenus = $admin->get_menu($userid);
			include tpl('admin_right');
		}
	break;
	case 'ajax':
		@include DT_ROOT.'/'.($mid == 1 ? 'admin' : 'module/'.$MODULE[$mid]['module'].'/admin').'/config.inc.php';
		if(isset($fi)) {
			if(isset($RT) && isset($RT['action'][$fi])) {
				$action_select = '<select name="right[0][action][]" size="2" multiple  style="height:200px;width:150px;"><option value="">选择动作[按Ctrl键多选]</option>';
				foreach($RT['action'][$fi] as $k=>$v) {
					$action_select .= '<option value="'.$k.'">'.$v.'['.$k.']</option>';
				}
				$action_select .= '</select>';
				echo $action_select;
			} else {
				echo '0';
			}
		} else {
			if(isset($RT)) {
				$file_select = '<select name="right[0][file]" size="2" style="height:200px;width:150px;" onchange="get_action(this.value, '.$mid.');"><option value="">选择文件[单选]</option>';
				foreach($RT['file'] as $k=>$v) {
					$file_select .= '<option value="'.$k.'">'.$v.'['.$k.']</option>';
				}
				$file_select .= '</select>';
				echo $file_select.'|';
				if($CT) {
					echo category_select('right[0][catid][]', '选择分类[按Ctrl键多选]', 0, $mid, 'size="2" multiple style="height:200px;width:300px;"');
				} else {
					echo '0';
				}
			} else {
				echo '0|0';
			}
		}
	break;
	default:
		$members = $admin->get_list();
		include tpl('admin');
	break;
}
?>