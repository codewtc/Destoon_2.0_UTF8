<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$pid) return false;
$p or $p = $db->get_one("SELECT * FROM {$DT_PRE}ad_place WHERE pid=$pid");
if(!$p) return false;
extract($p);
$fileroot = CACHE_ROOT.'/htm/';
if($typeid == 5) {
	$ad_moduleid = $p['moduleid']; 
	$ad_module = $MODULE[$ad_moduleid]['module'];
	$path = $MODULE[$ad_moduleid]['linkurl'];
	$id = $ad_moduleid == 4 ? 'userid' : 'itemid';
	$result = $db->query("SELECT * FROM {$DT_PRE}ad WHERE pid=$p[pid] AND fromtime<$DT_TIME AND totime>$DT_TIME ORDER BY fromtime ASC");//Note:Here Must ASC
	while($r = $db->fetch_array($result)) {
		if(!$r['key_id']) continue;
		$ad_catid = $r['key_catid'];
		$ad_keyword = $r['key_word'];
		$ad_itemid = explode(' ', $r['key_id']);
		$ad_itemids = implode(',', $ad_itemid);
		$tags = $tag = array();
		$pages = '';
		$ad_result = $db->query("SELECT * FROM {$DT_PRE}{$ad_module} WHERE `{$id}` IN ($ad_itemids)");
		while($ad_r = $db->fetch_array($ad_result)) {
			if(strpos($ad_r['linkurl'], '://') === false) $ad_r['linkurl'] = $path.$ad_r['linkurl'];
			$tag[$ad_r[$id]] = $ad_r;
		}
		foreach($ad_itemid as $v) {//Order
			if($tag[$v]) $tags[] = $tag[$v];
		}
		if($ad_keyword) {
			$filename = $fileroot.'ad_m'.$ad_moduleid.'_k'.urlencode($ad_keyword).'.htm';
		} else if($ad_catid) {
			$filename = $fileroot.'ad_m'.$ad_moduleid.'_c'.$ad_catid.'.htm';
		} else {
			$filename = $fileroot.'ad_m'.$ad_moduleid.'.htm';
		}
		ob_start();
		echo '<!--'.$r['totime'].'-->';
		include template('ad_code', $module);
		$data = ob_get_contents();
		ob_clean();
		file_put($filename, $data);
	}
} else {
	$filename = $fileroot.'ad_'.$pid.'.htm';
	$a = $db->get_one("SELECT * FROM {$DT_PRE}ad WHERE pid=$p[pid] AND fromtime<$DT_TIME AND totime>$DT_TIME ORDER BY fromtime DESC");
	if($a) {
		extract($a);
		if($url && $stat) $url = $MODULE[3]['linkurl'].'redirect.php?aid='.$aid;
		if($typeid == 6) {
			$pics = $links = array();
			$code = explode("\n", trim($code));
			foreach($code as $k=>$c) {
				$c = explode("|", $c);
				$links[] = $c[1];
				$pics[] = $c[2];
			}
		}
		ob_start();
		echo '<!--'.$totime.'-->';
		include template('ad_code', $module);
		$data = ob_get_contents();
		ob_clean();
		file_put($filename, $data);
	} else {
		@unlink($filename);
	}
}
return true;
?>