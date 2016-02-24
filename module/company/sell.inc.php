<?php 
defined('IN_DESTOON') or exit('Access Denied');
$could_inquiry = check_group($_groupid, $MOD['group_inquiry']);
if($username == $_username || $domain) $could_inquiry = true;
$module = 'sell';
$moduleid = 5;
$MOD = cache_read('module-'.$moduleid.'.php');
$table = $DT_PRE.'sell';
$table_data = $DT_PRE.'sell_data';

if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status>2 AND username='$username'");
	if(!$item) dheader($MENU[$menuid]['linkurl']);
	unset($item['template']);
	extract($item);
	$CAT = cache_read('category_'.$catid.'.php');
	if($MOD['text_data']) {
		$content = text_read($itemid, $moduleid);
	} else {
		$content = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
		$content = $content['content'];
	}

	if($MOD['product_option']) {
		$options = $pid ? cache_read('option-'.$pid.'.php') : array();
		$values = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}sell_value WHERE itemid=$itemid");
		while($r = $db->fetch_array($result)) {
			$values[$r['oid']] = $r['value'];
		}
	}

	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	$todate = timetodate($totime, 3);
	$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);
	$expired = $totime && $totime < $DT_TIME ? true : false;
	$thumbs = get_albums($item);
	$albums =  get_albums($item, 1);
	$inquiry_url = $MODULE[4]['linkurl'].'home.php?action=message&job=inquiry&&itemid='.$itemid.'&template='.$template.'&skin='.$skin.'&title='.urlencode($title).'&username='.$username.'&sign='.crypt_sign($itemid.$template.$skin.$title.$username);
	$order_url = $MODULE[4]['linkurl'].'home.php?action=message&job=order&&itemid='.$itemid.'&template='.$template.'&skin='.$skin.'&title='.urlencode($title).'&username='.$username.'&sign='.crypt_sign($itemid.$template.$skin.$title.$username);


	$db->query("UPDATE {$table} SET hits=hits+1 WHERE itemid=$itemid");

	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $keyword;
	$head_description = $introduce ? $introduce : $title;

} else {
	$mycatid = isset($mycatid) ? intval($mycatid) : 0;
	$view = isset($view) ? 1 : 0;
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($typeid) {
		$MTYPE = get_type('product-'.$userid);
		$condition .= " AND mycatid='$typeid'";
		$url .= "&typeid=$typeid";
		$head_title = $MTYPE[$typeid]['typename'].$DT['seo_delimiter'].$head_title;
	}
	if($kw) {
		$keyword = $kw ? str_replace(array(' ','*'), array('%','%'), $kw) : '';
		$condition .= " AND keyword LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	if($view) {
		$url .= "&view=$view";
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);

	$pagesize =intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 16;
	if($view) $pagesize = ceil($pagesize/2);

	$offset = ($page-1)*$pagesize;
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$pages = home_pages($r['num'], $pagesize, $demo_url, $page);
	$lists = array();
	$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY edittime DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = userurl($username, "file=$file&itemid=$r[itemid]", $domain);
		$lists[] = $r;
	}
}
include template('sell', $template);
?>