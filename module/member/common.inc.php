<?php 
defined('IN_DESTOON') or exit('Access Denied');
define('MOD_ROOT', DT_ROOT.'/module/'.$module);
if(!defined('DT_ADMIN')) {
	if($submit) {
		check_post() or dalert('数据发送自未被信任的域名，如有疑问，请联系管理员'); //safe
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

	$group_editor = $MG['editor'] ? 'Default' : 'Destoon';
	$show_menu = $MOD['show_menu'] ? true : false;

	$MENUMODS = $MYMODS;
	if($show_menu) {
		$MENUMODS = array();
		foreach($MODULE as $m) {
			if($m['moduleid'] > 4 && is_file(DT_ROOT.'/module/'.$m['module'].'/my.inc.php')) $MENUMODS[] = $m['moduleid'];
		}
	}
}
require DT_ROOT.'/include/module.func.php';
$GROUP = cache_read('group.php');
$AREA = cache_read('area.php');
$table = $DT_PRE.'member';
$table_company = $DT_PRE.'company';
?>