<?php 
defined('IN_DESTOON') or exit('Access Denied');
$could_buy = check_group($_groupid, $MOD['group_buy']);
if($username == $_username || $domain) $could_buy = true;
$could_buy or dalert('您所在的会员组无权查看采购清单', 'goback');
$could_price = check_group($_groupid, $MOD['group_price']);
if($username == $_username || $domain) $could_price = true;

$module = 'buy';
$moduleid = 6;
$MOD = cache_read('module-'.$moduleid.'.php');
$table = $DT_PRE.'buy';
$table_data = $DT_PRE.'buy_data';

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

	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	$todate = timetodate($totime, 3);
	$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);
	$expired = $totime && $totime < $DT_TIME ? true : false;
	$thumbs = get_albums($item);
	$albums =  get_albums($item, 1);
	$price_url = $MODULE[4]['linkurl'].'home.php?action=message&job=price&&itemid='.$itemid.'&template='.$template.'&skin='.$skin.'&title='.urlencode($title).'&username='.$username.'&sign='.crypt_sign($itemid.$template.$skin.$title.$username);

	$db->query("UPDATE {$table} SET hits=hits+1 WHERE itemid=$itemid");

	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $keyword;
	$head_description = $introduce ? $introduce : $title;

} else {
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($kw) {
		$keyword = $kw ? str_replace(array(' ','*'), array('%','%'), $kw) : '';
		$condition .= " AND keyword LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);

	$pagesize =intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 30;

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
include template('buy', $template);
?>