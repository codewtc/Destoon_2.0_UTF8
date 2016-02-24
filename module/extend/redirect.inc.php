<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$url = isset($url) ? (strpos($url, '://') !== false ? $url : 'http://'.$url) : DT_PATH;
if(isset($username)) {
	if(preg_match("/^[a-z0-9]+$/i", $username)) {
		$r = $db->get_one("SELECT linkurl FROM {$DT_PRE}company WHERE username='$username'");
		if($r) $url = $r['linkurl'] ? $r['linkurl'] : userurl($username);
	} else {
		$url = userurl('');
	}
} else if(isset($aid)) {
	$aid = intval($aid);
	if($aid) {
		$r = $db->get_one("SELECT url FROM {$DT_PRE}ad WHERE aid=$aid AND url!='' AND fromtime<$DT_TIME AND totime>$DT_TIME");
		if($r) {
			$url = $r['url'];
			$db->query("UPDATE {$DT_PRE}ad SET hits=hits+1 WHERE aid=$aid");
		}
	}
} else if(isset($mid)) {
	if(isset($MODULE[$mid]) && $itemid) {
		$condition = $mid == 4 ? "userid=$itemid" : "itemid=$itemid";
		$r = $db->get_one("SELECT linkurl FROM ".get_module_table($mid)." WHERE $condition");
		if($r) $url = strpos($r['linkurl'], '://') === false ? $MODULE[$mid]['linkurl'].$r['linkurl'] : $r['linkurl'];
	}
}
header('location:'.$url);
?>