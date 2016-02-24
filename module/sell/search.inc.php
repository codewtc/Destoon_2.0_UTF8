<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(!check_group($_groupid, $MOD['group_search'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	include template('noright', 'message');
	exit;
}

if($DT['rewrite'] && $_SERVER["REQUEST_URI"] && $_SERVER['QUERY_STRING']) {
	$url = rewrite($_SERVER["REQUEST_URI"]);
	if($url != $_SERVER["REQUEST_URI"]) dheader($url);;
}
require DT_ROOT.'/include/post.func.php';
$thumb = isset($thumb) ? intval($thumb) : 0;
$price = isset($price) ? intval($price) : 0;
$vip = isset($vip) ? intval($vip) : 0;
$day = isset($day) ? intval($day) : 0;
$list = isset($list) &&  in_array($list, array(0, 1, 2)) ? $list : 0;
$minprice = isset($minprice) ? dround($minprice) : '';
$minprice or $minprice = '';
$maxprice = isset($maxprice) ? dround($maxprice) : '';
$maxprice or $maxprice = '';
$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
$areaid = isset($areaid) ? intval($areaid) : 0;
if($day) $fromdate = timetodate($DT_TIME-$day*86400, 'Ymd');
$fromdate = isset($fromdate) && preg_match("/^([0-9]{8})$/", $fromdate) ? $fromdate : '';
$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
$todate = isset($todate) && preg_match("/^([0-9]{8})$/", $todate) ? $todate : '';
$totime = $todate ? strtotime($todate.' 23:59:59') : 0;
$area_select = ajax_area_select('areaid', '不限地区', $areaid);
$category_select = ajax_category_select('catid', '不限行业', $catid, $moduleid);
$type_select = dselect($TYPE, 'typeid', '信息类型', $typeid);
$sorder  = array('结果排序方式', '价格由高到低', '价格由低到高', VIP.'级别由高到低', VIP.'级别由低到高', '供货量由高到低', '供货量由低到高', '起订量由高到低', '起订量由低到高');
$dorder  = array($MOD['order'], 'price DESC', 'price ASC', 'vip DESC', 'vip ASC', 'amount DESC', 'amount ASC', 'minamount DESC', 'minamount ASC');
isset($order) && isset($dorder[$order]) or $order = 0;
$order_select  = dselect($sorder, 'order', '', $order);
$tags = array();
if($DT_QST) {
	if($kw) {
		if(strlen($kw) < $DT['min_kw'] || strlen($kw) > $DT['max_kw']) message('关键词长度应为'.$DT['min_kw'].'-'.$DT['max_kw'].'字符之间', $MOD['linkurl'].'search.php');
		if($DT['search_limit'] && $page == 1) {
			if(($DT_TIME - $DT['search_limit']) < get_cookie('last_search')) message('两次搜索时间间隔应大于'.$DT['search_limit'].'秒', $MOD['linkurl'].'search.php');
			set_cookie('last_search', $DT_TIME);
		}
	}
	$showpage = 1;
	$condition = 'status=3';
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($AREA[$areaid]['child']) ? " AND areaid IN (".$AREA[$areaid]['arrchildid'].")" : " AND areaid=$areaid";
	if($thumb) $condition .= " AND thumb!=''";
	if($vip) $condition .= " AND vip>0";
	if($price) $condition .= " AND price>0";
	if($minprice)  $condition .= " AND price>=$minprice";
	if($maxprice)  $condition .= " AND price<=$maxprice";
	if($typeid >=0) $condition .= " AND typeid=$typeid";
	if($fromtime) $condition .= " AND edittime>=$fromtime";
	if($totime) $condition .= " AND edittime<=$totime";
	require MOD_ROOT.'/sell.class.php';
	$do = new sell($moduleid);
	$tags = $do->get_list($condition, $dorder[$order], 'CACHE');
	if($tags && $kw) {
		foreach($tags as $k=>$v) {
			$tags[$k]['title'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['title']);
			if($v['introduce']) $tags[$k]['introduce'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['introduce']);
		}
		if($page == 1) keyword($kw, $items, $moduleid);
	}
}
$path = $MOD['linkurl'];
$datetype = 5;
$maincat = get_maincat(0, $CATEGORY);
if($catid) $CAT = $CAT = cache_read('category_'.$catid.'.php');

include DT_ROOT.'/include/seo.inc.php';
$seo_kw = $kw ? $kw.$seo_delimiter : '';
if($MOD['seo_search']) {
	eval("\$seo_title = \"$MOD[seo_search]\";");
} else {
	$seo_title = $seo_modulename.'搜索'.$seo_delimiter.$seo_page.$seo_sitename;
	if($catid) $seo_title = $seo_catname.$seo_title;
	if($areaid) $seo_title = $seo_areaname.$seo_title;
	if($kw) $seo_title = $kw.$seo_delimiter.$seo_title;
}
include template('search', $module);
?>