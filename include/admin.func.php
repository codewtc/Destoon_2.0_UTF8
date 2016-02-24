<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function msg($msg = errmsg, $forward = 'goback', $time = '1') {
	global $CFG;
	if(!$msg && $forward && $forward != 'goback') dheader($forward);
	include DT_ROOT.'/admin/template/msg.tpl.php';
    exit;
}

function dialog($dcontent) {
	global $CFG;
	include DT_ROOT.'/admin/template/dialog.tpl.php';
    exit;
}

function tpl($file = 'index', $mod = 'destoon') {
	global $CFG, $DT;
	return $mod == 'destoon' ? DT_ROOT.'/admin/template/'.$file.'.tpl.php' : DT_ROOT.'/module/'.$mod.'/admin/template/'.$file.'.tpl.php';
}

function show_menu($menus = array()) {
	global $module, $file, $action;
    $menu = '';
    foreach($menus as $id=>$m) {
		if(isset($m[1])) {
			$extend = isset($m[2]) ? $m[2] : '';
			$menu .= '<td id="Tab'.$id.'" class="tab"><a href="'.$m[1].'" '.$extend.'>'.$m[0].'</a></td><td class="tab_nav">&nbsp;</td>';
		} else {
			$class = $id == 0 ? 'tab_on' : 'tab';
			$menu .= '<td id="Tab'.$id.'" class="'.$class.'"><a href="javascript:Tab('.$id.');">'.$m[0].'</a></td><td class="tab_nav">&nbsp;</td>';
		}
	}
	include DT_ROOT.'/admin/template/menu.tpl.php';;
}

function update_setting($item, $setting) {
	global $db, $DT_PRE;
	$db->query("DELETE FROM {$DT_PRE}setting WHERE item='$item'");
	foreach($setting as $k=>$v) {
		if(is_array($v)) $v = implode(',', $v);
		$db->query("INSERT INTO {$DT_PRE}setting (item,item_key,item_value) VALUES ('$item','$k','$v')");
	}
	return true;
}

function get_setting($item) {
	global $db, $DT_PRE;
	$setting = array();
	$query = $db->query("SELECT * FROM {$DT_PRE}setting WHERE item='$item'");
	while($r = $db->fetch_array($query)) {
		$setting[$r['item_key']] = $r['item_value'];
	}
	return $setting;
}

function tips($tips) {
	echo ' <img src="'.IMG_PATH.'help.png" width="11" height="11" title="'.$tips.'" alt="tips" class="c_p" onclick="Dconfirm(this.title);" />';
}

function array_save($array, $arrayname, $file) {
	$data = var_export($array,true);
	$data = "<?php\n".$arrayname." = ".$data.";\n?>";
	return file_put($file,$data);
}

function admin_log() {
	global $DT, $db, $DT_PRE, $file, $action, $_username, $DT_QST, $DT_IP, $DT_TIME;
	if(!$DT['admin_log'] || !$DT_QST || $file == 'index') return false;
	if($DT['admin_log'] == 2 || ($DT['admin_log'] == 1 && ($file == 'setting' || in_array($action, array('delete', 'edit', 'move', 'clear', 'add'))))) {
		$fpos = strpos($DT_QST, '&forward');
		if($fpos) $DT_QST = substr($DT_QST, 0, $fpos);
		$logstring = get_cookie('logstring');
		if($DT_QST == $logstring)  return false;
		$db->query("INSERT INTO {$DT_PRE}log(qstring, username, ip, logtime) VALUES('$DT_QST','$_username','$DT_IP','$DT_TIME')");
		set_cookie('logstring', $DT_QST);
	}
}

function admin_check() {
	global $CFG, $db, $DT_PRE, $_level, $_userid, $moduleid, $file, $action, $catid;
	if(in_array($file, array('index', 'logout', 'destoon', 'mymenu'))) return true;//All user
	if($CFG['founderid']) {
		if($CFG['founderid'] == $_userid) return true;//Founder
		if(in_array($file, array('admin', 'setting', 'module', 'database', 'template', 'skin', 'log', 'update', 'group', 'fields', 'loginlog'))) return false;//Founder Only
	}
	if($_level == 2) {
		$R = cache_read('right-'.$_userid.'.php');
		if(!$R) return false;
		if(!isset($R[$moduleid])) return false;
		if(!$R[$moduleid]) return true;//Module admin
		if(!isset($R[$moduleid][$file])) return false;
		if(!$R[$moduleid][$file]) return true;
		if($action && !in_array($action, $R[$moduleid][$file]['action'])) return false;
		if(!$R[$moduleid][$file]['catid']) return true;
		if($catid && !in_array($action, $R[$moduleid][$file]['catid'])) return false;
	}
	return true;
}

function seo_title($title, $show = '') {
	$SEO = array(
		'modulename'	=>	'模块名称',
		'page'			=>	'页码',
		'delimiter'		=>	'分隔符',
		'sitename'		=>	'网站名称',
		'sitetitle'		=>	'网站SEO标题',
		'catname'		=>	'分类名称',
		'cattitle'		=>	'分类SEO标题',
		'showtitle'		=>	'内容标题',
		'kw'			=>	'关键词',
		'areaname'		=>	'地区',
	);
	if(is_array($show)) {
		foreach($show as $v) {
			if(isset($SEO[$v])) echo '<a href="javascript:_into(\''.$title.'\', \'{'.$SEO[$v].'}\');" title="{'.$SEO[$v].'}">{'.$SEO[$v].'}</a>&nbsp;&nbsp;';
		}
	} else {
		foreach($SEO as $k=>$v) {
			$title = str_replace($v, '$seo_'.$k, $title);
		}
		return $title;
	}
}

function install_file($file, $dir, $extend = 0) {
	$content = "<?php\n";
	if($extend == 1) $content .= "define('PARSE_STR', true);\n";
	$content .= "require 'config.inc.php';\n";
	$content .= "require '../common.inc.php';\n";
	$content .= "require DT_ROOT.'/module/'.\$module.'/".$file.".inc.php';\n";
	$content .= '?>';
	return file_put(DT_ROOT.'/'.$dir.'/'.$file.'.php', $content);
}
?>