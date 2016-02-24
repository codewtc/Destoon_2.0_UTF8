<?php
defined('IN_DESTOON') or exit('Access Denied');
$tab = isset($tab) ? intval($tab) : 0;
if($submit) {
	update_setting($moduleid, $setting);
	cache_module($moduleid);
	if($update_url) {
		cache_category($moduleid);
		msg('设置保存成功，开始更新地址', '?moduleid='.$moduleid.'&file=html&action=show&update=1');
	} else {
		dmsg('更新成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
	}
} else {
	extract(dhtmlspecialchars($MOD));
	include tpl('setting', $module);
}
?>