<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($html == 'webpage') {
	$itemid or exit;
	$r = $db->get_one("SELECT linkurl FROM {$DT_PRE}webpage WHERE itemid=$itemid AND islink=0");
	$r or exit;
	$db->query("UPDATE {$DT_PRE}webpage SET hits=hits+1 WHERE itemid=$itemid");
	if($DT_TIME - @filemtime(DT_ROOT.'/'.$r['linkurl']) > $task_item) tohtml('webpage', $module);
} else if($html == 'announce') {
	$itemid or exit;
	$r = $db->get_one("SELECT linkurl,hits FROM {$DT_PRE}announce WHERE itemid=$itemid AND islink=0");
	$r or exit;
	echo 'try{$("hits").innerHTML = '.$r['hits'].';}catch(e){}';
	$db->query("UPDATE {$DT_PRE}announce SET hits=hits+1 WHERE itemid=$itemid");
	if($DT_TIME - @filemtime(DT_ROOT.'/announce/'.$itemid.'.'.$DT['file_ext']) > $task_item) tohtml('announce', $module);
} else if($html == 'spread') {
	$itemid or exit;
	$r = $db->get_one("SELECT mid,word FROM {$DT_PRE}spread WHERE itemid=$itemid");
	$r or exit;
	$filename = CACHE_ROOT.'/htm/m'.$r['mid'].'_k'.urlencode($r['word']).'.htm';
	if($DT_TIME - @filemtime($filename) > $task_item) {
		$MOD = cache_read('module-'.$r['mid'].'.php');
		$CATEGORY = cache_read('category-'.$r['mid'].'.php');
		tohtml('spread', $module);
	}
} else if($html == 'ad') {
	$itemid or exit;
	$r = $db->get_one("SELECT moduleid FROM {$DT_PRE}ad_place WHERE pid=$itemid");
	$r or exit;
	$pid = $itemid;
	if($r['moduleid']) {
		$MOD = cache_read('module-'.$r['moduleid'].'.php');
		$CATEGORY = cache_read('category-'.$r['moduleid'].'.php');
		tohtml('ad', $module);
	} else {
		$filename = CACHE_ROOT.'/htm/ad_'.$pid.'.htm';
		if($DT_TIME - @filemtime($filename) > $task_item) {
			tohtml('ad', $module);
		}
	}
}
?>