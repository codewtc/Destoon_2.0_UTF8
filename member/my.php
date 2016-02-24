<?php 
require 'config.inc.php';
require '../common.inc.php';
$mid = isset($mid) ? intval($mid) : 0;
if($mid) {
	$group_editor = $MG['editor'] ? 'Default' : 'Destoon';
	$MST = cache_read('module-2.php');
	$show_menu = $MST['show_menu'] ? true : false;
	if(!$_userid) $action = 'add';//Guest
	if($_groupid > 5 && !$_edittime && $action == 'add') dheader($MODULE[2]['linkurl'].'edit.php?tab=2');
	if($submit) {
		check_post() or message('数据发送自未被信任的域名，如有疑问，请联系管理员'); //safe
		$BANWORD = cache_read('banword.php');
		if($BANWORD && isset($post)) {
			$keys = array('title', 'tag', 'introduce', 'content');
			foreach($keys as $v) {
				if(isset($post[$v])) $post[$v] = banword($BANWORD, $post[$v]);
			}
		}
	}

	$MYMODS = array();
	if(isset($MG['moduleids']) && $MG['moduleids']) {
		$MYMODS = explode(',', $MG['moduleids']);
	}
	if($MYMODS) {
		foreach($MYMODS as $k=>$v) {
			$v = abs($v);
			if(!is_file(DT_ROOT.'/module/'.$MODULE[$v]['module'].'/my.inc.php')) unset($MYMODS[$k]);
		}
	}

	$MENUMODS = $MYMODS;
	if($show_menu) {
		$MENUMODS = array();
		foreach($MODULE as $m) {
			if($m['moduleid'] > 4 && is_file(DT_ROOT.'/module/'.$m['module'].'/my.inc.php')) $MENUMODS[] = $m['moduleid'];
		}
	}


	$vid = $mid;
	if($mid == 9 && isset($resume)) $vid = -9;
	if(!$MYMODS || !in_array($vid, $MYMODS)) message('', $MODULE[2]['linkurl'].'my.php');

	$IMVIP = isset($MG['vip']) && $MG['vip']; 
	$moduleid = $mid;
	$module = $MODULE[$moduleid]['module'];
	if(!$module) message();
	$MOD = cache_read('module-'.$moduleid.'.php');
	$my_file = DT_ROOT.'/module/'.$module.'/my.inc.php';
	if(is_file($my_file)) {
		require $my_file;
	} else {
		dheader($MODULE[2]['linkurl']);
	}
} else {
	require DT_ROOT.'/module/'.$module.'/my.inc.php';
}
?>