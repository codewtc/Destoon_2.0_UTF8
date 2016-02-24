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
	if($url != $_SERVER["REQUEST_URI"]) dheader($url);
}
require DT_ROOT.'/include/post.func.php';
$modes = explode('|', '经营模式|'.get_module_setting(2, 'com_mode'));
$vips = array('全部', VIP, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$thumb = isset($thumb) ? intval($thumb) : 0;
//$vip = isset($vip) ? intval($vip) : 0;
$mincapital = isset($mincapital) ? dround($mincapital) : '';
$mincapital or $mincapital = '';
$maxcapital = isset($maxcapital) ? dround($maxcapital) : '';
$maxcapital or $maxcapital = '';
$areaid = isset($areaid) ? intval($areaid) : 0;
isset($mode) && isset($modes[$mode]) or $mode = 0;
isset($vip) && isset($vips[$vip]) or $vip = 0;
$mode_select = dselect($modes, 'mode', '', $mode);
$vip_select = dselect($vips, 'vip', '', $vip);
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
	$condition = 'groupid>5';
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	if($mode) $condition .= " AND mode LIKE '%$modes[$mode]%'";
	if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($AREA[$areaid]['child']) ? " AND areaid IN (".$AREA[$areaid]['arrchildid'].")" : " AND areaid=$areaid";
	if($thumb) $condition .= " AND thumb!=''";
	if($vip) $condition .= $vip == 1 ? " AND vip>0" : " AND vip=$vip-1";
	if($mincapital)  $condition .= " AND capital>$mincapital";
	if($maxcapital)  $condition .= " AND capital<$maxcapital";
	require MOD_ROOT.'/company.class.php';
	$do = new company($moduleid);
	$tags = $do->get_list($condition, $MOD['order'], 'CACHE');
	if($tags && $kw) {
		foreach($tags as $k=>$v) {
			$tags[$k]['company'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['company']);
			if($v['introduce']) $tags[$k]['introduce'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['introduce']);
		}
		if($page == 1) keyword($kw, $items, $moduleid);
	}
}
$path = $MOD['linkurl'];
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