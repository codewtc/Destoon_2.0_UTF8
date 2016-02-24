<?php
defined('IN_DESTOON') or exit('Access Denied');
defined('DT_ADMIN') or exit('Access Denied');
$_groupid == 1 or exit('Access Denied');
$setting = array ('fee_view' => '0','fee_add' => '0','captcha_add' => '2','question_add' => '2','question_message' => '2','check_add' => '2','captcha_message' => '2','group_message' => '7,6,5,3,1','group_search' => '7,6,5,3,1','php_item_urlid' => '0','group_index' => '7,6,5,3,1','group_list' => '7,6,5,3,1','group_show' => '7,6,5,3,1','group_contact' => '7,6,1','htm_item_urlid' => '0','htm_item_prefix' => '','show_html' => '0','php_list_urlid' => '0','htm_list_urlid' => '0','htm_list_prefix' => '','list_html' => '0','index_html' => '0','seo_title_search' => '{关键词}{地区}{分类名称}{模块名称}搜索{分隔符}{页码}{网站名称}','seo_title_list' => '{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}','seo_title_show' => '{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}','seo_description' => '','seo_keywords' => '','seo_title_index' => '{模块名称}{分隔符}{页码}{网站名称}','max_days' => '180','over_days' => '30','text_data' => '0','clear_link' => '0','introduce_length' => '120','message_ask' => '请问我这个地方有加盟商了吗？|我想加盟，请来电话告诉我具体细节。|初步打算加盟贵公司，请寄资料。|请问贵公司哪里有样板店或直营店？|想了解加盟细节，请尽快寄一份资料。 ','save_remotepic' => '0','order' => 'edittime desc','pagesize' => '10','max_width' => '550','thumb_height' => '90','thumb_width' => '120','template' => '','seo_index' => '{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}','seo_list' => '{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}','seo_show' => '{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}','seo_search' => '{$seo_kw}{$seo_areaname}{$seo_catname}{$seo_modulename}搜索{$seo_delimiter}{$seo_page}{$seo_sitename}',);
update_setting($moduleid, $setting);
$db->query("UPDATE {$DT_PRE}module SET listorder=$moduleid WHERE moduleid=$moduleid");
install_file('index', $dir, 1);
install_file('list', $dir, 1);
install_file('show', $dir, 1);
install_file('search', $dir, 1);
file_copy(DT_ROOT.'/api/ajax.php', DT_ROOT.'/'.$dir.'/ajax.php');
if($db->version() > '4.1' && $CFG['db_charset']) {
	$this_sql = " ENGINE=MyISAM DEFAULT CHARSET=".$CFG['db_charset'];
} else {
	$this_sql = " TYPE=MyISAM";
}
$db->query("DROP TABLE IF EXISTS `".$DT_PRE."info_".$moduleid."`");
$db->query("CREATE TABLE `".$DT_PRE."info_".$moduleid."` (`itemid` bigint(20) unsigned NOT NULL auto_increment,`catid` smallint(6) unsigned NOT NULL default '0',`level` tinyint(1) unsigned NOT NULL default '0',`title` varchar(100) NOT NULL default '',`style` varchar(50) NOT NULL default '',`fee` float NOT NULL default '0',`keyword` varchar(255) NOT NULL default '',`hits` int(10) unsigned NOT NULL default '0',`thumb` varchar(255) NOT NULL default '',`username` varchar(30) NOT NULL default '',`addtime` int(10) unsigned NOT NULL default '0',`adddate` date NOT NULL default '0000-00-00',`totime` int(10) unsigned NOT NULL default '0',`areaid` smallint(6) unsigned NOT NULL default '0',`company` varchar(100) NOT NULL default '',`vip` smallint(2) unsigned NOT NULL default '0',`validated` tinyint(1) unsigned NOT NULL default '0',`truename` varchar(30) NOT NULL default '',`telephone` varchar(50) NOT NULL default '',`fax` varchar(50) NOT NULL default '',`mobile` varchar(50) NOT NULL default '',`address` varchar(255) NOT NULL default '',`email` varchar(50) NOT NULL default '',`qq` varchar(20) NOT NULL default '',`msn` varchar(50) NOT NULL default '',`introduce` varchar(255) NOT NULL default '',`editor` varchar(30) NOT NULL default '',`edittime` int(10) unsigned NOT NULL default '0',`editdate` date NOT NULL default '0000-00-00',`ip` varchar(15) NOT NULL default '',`template` varchar(30) NOT NULL default '0',`status` tinyint(1) NOT NULL default '0',`listorder` smallint(4) unsigned NOT NULL default '0',`islink` tinyint(1) unsigned NOT NULL default '0',`linkurl` varchar(255) NOT NULL default '',`note` varchar(255) NOT NULL default '',PRIMARY KEY  (`itemid`),KEY `keyword` (`keyword`),KEY `username` (`username`),KEY `editdate` (`editdate`,`vip`,`edittime`),KEY `edittime` (`edittime`))".$this_sql." COMMENT='".$modulename."'");

$db->query("DROP TABLE IF EXISTS `".$DT_PRE."info_data_".$moduleid."`");
$db->query("CREATE TABLE `".$DT_PRE."info_data_".$moduleid."` (`itemid` int(10) unsigned NOT NULL default '0',`content` mediumtext NOT NULL,UNIQUE KEY `itemid` (`itemid`))".$this_sql." COMMENT='".$modulename."内容'");
?>