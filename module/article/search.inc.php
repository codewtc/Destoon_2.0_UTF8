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
$category_select = category_select('catid', '不限分类', $catid, $moduleid);
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
	require MOD_ROOT.'/article.class.php';
	$do = new article($moduleid);
	$tags = $do->get_list($condition, $MOD['order'], 'CACHE');
	if($tags && $kw) {
		foreach($tags as $k=>$v) {
			$tags[$k]['title'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['title']);
			//if($v['introduce']) $tags[$k]['introduce'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $v['introduce']);
		}
		if($page == 1) keyword($kw, $items, $moduleid);
	}
}

$path = $MOD['linkurl'];
$datetype = 5;
$target = '_blank';
$cols = 5;
$class = '';

if($catid) $CAT = $CAT = cache_read('category_'.$catid.'.php');

include DT_ROOT.'/include/seo.inc.php';
$seo_kw = $kw ? $kw.$seo_delimiter : '';
if($MOD['seo_search']) {
	eval("\$seo_title = \"$MOD[seo_search]\";");
} else {
	$seo_title = $seo_modulename.'搜索'.$seo_delimiter.$seo_page.$seo_sitename;
	if($catid) $seo_title = $seo_catname.$seo_title;
	if($kw) $seo_title = $kw.$seo_delimiter.$seo_title;
}

include template('search', $module);
?>