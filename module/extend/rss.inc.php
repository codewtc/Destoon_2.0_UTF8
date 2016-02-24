<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$mid = isset($mid) ? intval($mid) : 0;
if(!$mid) $mid = 4;
$areaid = isset($areaid) ? intval($areaid) : 0;
if($mid > 4 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) {
	$moduleid = $mid;
	$module = $MODULE[$mid]['module'];
	$table = in_array($module, array('article', 'info')) ? $module.'_'.$moduleid : $module;
	$rss_title = $MODULE[$mid]['name'];
	if($MOD['feed_enable']) {
		$pagesize = $MOD['feed_pagesize'] ? intval($MOD['feed_pagesize']) : 50;
		$condition = "status=3";
		$cat = '';
		if($MOD['feed_enable'] == 2) {
			if($kw) $rss_title = $rss_title.'_'.$kw;
			if($keyword) $condition .= " and keyword LIKE '%$keyword%'";
			if($catid) {
				$CATEGORY = cache_read('category-'.$mid.'.php');
				$cat .= "&catid=$catid";
				$rss_title = $rss_title.'_'.strip_tags(cat_pos($catid, '_'));
			}
			if($areaid) {
				$AREA = cache_read('area.php');
				$condition .= ($AREA[$areaid]['child']) ? " and areaid IN (".$AREA[$areaid]['arrchildid'].")" : " and areaid=$areaid";
				$rss_title = $rss_title.'_'.strip_tags(area_pos($areaid, '_'));
			}
		}
	}
	$rss_title = $rss_title.'_'.$DT['sitename'];
	$rss_link = DT_URL;
	header("content-type:application/xml");
	echo '<?xml version="1.0" encoding="'.$CFG['charset'].'"?>';
	echo '<rss version="2.0">';
	echo '<channel>';
	echo '<title>'.$rss_title.'</title>';
	echo '<link>'.$rss_link.'</link>';
	echo '<generator>Destoon B2B System. www.destoon.com</generator>';
	echo '<pubDate>'.timetodate($DT_TIME).'</pubDate>';	
	if($MOD['feed_enable']) {
		tag("moduleid=$moduleid&table=$table&condition=$condition&pagesize=$pagesize&order=addtime desc&template=null".$cat, -2);
		foreach($_tags as $t) {
			echo '<item id="'.$t['itemid'].'">';
			echo '<title><![CDATA['.$t['title'].']]></title>';
			echo '<link>'.linkurl($t['linkurl'], 1).'</link>';
			echo '<description><![CDATA['.$t['introduce'].']]></description>';
			echo '<pubDate>'.timetodate($t['addtime'], 6).'</pubDate>';
			echo '</item>';
		}
	} else {
		echo '<item id="0">';
		echo '<title><![CDATA[系统未开启RSS订阅]]></title>';
		echo '<link>'.linkurl(DT_PATH, 1).'</link>';
		echo '<description><![CDATA[系统未开启RSS订阅]]></description>';
		echo '<pubDate>'.timetodate($DT_TIME, 6).'</pubDate>';
		echo '</item>';
	}
	echo '</channel>';
	echo '</rss>';
} else {
	dheader('./');
}
?>