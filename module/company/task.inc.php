<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($html == 'list') {
	$catid or exit;
	if($MOD['list_html'] && $DT_TIME - @filemtime(DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($moduleid, $catid, $page, $CATEGORY, $MOD)) > $task_list) {
		$fid = $page;
		$num = 3;
		tohtml('list', $module);
	}
}
?>