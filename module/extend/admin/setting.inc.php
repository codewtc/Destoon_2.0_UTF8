<?php
defined('IN_DESTOON') or exit('Access Denied');
$tab = isset($tab) ? intval($tab) : 0;
if($submit) {
	update_setting($moduleid, $setting);
	cache_module($moduleid);
	dmsg('更新成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
} else {
	extract(dhtmlspecialchars($MOD));
	include tpl('setting', $module);
}
?>