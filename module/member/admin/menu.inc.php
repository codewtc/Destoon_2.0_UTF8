<?php
defined('IN_DESTOON') or exit('Access Denied');
$menu = array(
	array("添加会员", "?moduleid=$moduleid&action=add"),
	array("会员列表", "?moduleid=$moduleid"),
	array("审核会员", "?moduleid=$moduleid&action=check"),
	array("会员组管理", "?moduleid=$moduleid&file=group"),
	array("管理员管理", "?file=admin"),
	array("模块设置", "?moduleid=$moduleid&file=setting"),
);
?>