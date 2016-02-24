<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
@set_time_limit(0);
@ignore_user_abort(true);
require 'common.inc.php';
check_referer() or exit;
include template('line', 'chip');
isset($html) or $html = '';
if($html) {
	$task_index = $DT['task_index'] ? $DT['task_index'] : 600;
	$task_list = $DT['task_list'] ? $DT['task_list'] : 1800;
	$task_item = $DT['task_item'] ? $DT['task_item'] : 3600;
	if($moduleid == 1) {
		if($DT['index_html'] && $DT_TIME - @filemtime(DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext']) > $task_index) tohtml('index');
	} else {
		$task_file = DT_ROOT.'/module/'.$module.'/task.inc.php';
		if(is_file($task_file)) include $task_file;
	}
}
if($DT['message_email'] && $DT['mail_type'] != 'close' && !$_userid) {
	$condition = 'isread=0 AND issend=0 AND status=3';
	if($DT['message_time']) {
		$time = $DT_TIME - $DT['message_time']*60;
		$condition .= " AND addtime<$time";
	}
	if($DT['message_type']) $condition .= " AND typeid IN ($DT[message_type])";
	$msg = $db->get_one("SELECT * FROM {$DT_PRE}message WHERE $condition ORDER BY itemid ASC");
	if($msg) {
		$db->query("UPDATE {$DT_PRE}message SET issend=1 WHERE itemid=$msg[itemid]");
		$user = $db->get_one("SELECT groupid,email,send FROM {$DT_PRE}member WHERE username='$msg[touser]'");
		if($user) {
			if($user['send']) {
				if(check_group($user['groupid'], $DT['message_group'])) {
					extract($msg);
					$NAME = array('信件', '询价', '报价', '留言', '信使');
					$member_url = linkurl($MODULE[2]['linkurl'], 1);
					$content = ob_template('message', 'mail');
					send_mail($user['email'], '['.$NAME[$typeid].']'.$title, $content);
				}
			}
		}
	}
}
?>