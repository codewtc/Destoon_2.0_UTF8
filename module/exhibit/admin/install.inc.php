<?php
defined('IN_DESTOON') or exit('Access Denied');
$setting = array(
  'template' => '0',
  'thumb_width' => '120',
  'thumb_height' => '90',
  'max_width' => '550',
  'pagesize' => '10',
  'save_remotepic' => '0',
  'introduce_length' => '200',
  'clear_link' => '0',
  'member_add' => '1',
  'member_check' => '0',
  'captcha_add' => '1',
  'rss_mode' => '1',
  'rss_length' => '300',
  'rss_num' => '50',
  'rss_time' => '1',
  'seo_title' => '',
  'seo_keywords' => '',
  'seo_description' => '',
  'index_html' => '0',
  'list_html' => '0',
  'htm_list_prefix' => 'list_',
  'htm_list_urlid' => '2',
  'php_list_urlid' => '4',
  'show_html' => '0',
  'htm_item_prefix' => 'show_',
  'htm_item_urlid' => '6',
  'php_item_urlid' => '4',
);
update_setting($moduleid, $setting);
$db->query("UPDATE {$DT_PRE}module SET listorder=$moduleid WHERE moduleid=$moduleid");
install_file('index', $dir, 1);
install_file('list', $dir, 1);
install_file('show', $dir, 1);
install_file('search', $dir, 1);
if($db->version() > '4.1' && $CFG['db_charset']) {
	$this_sql = " ENGINE=MyISAM DEFAULT CHARSET=".$CFG['db_charset'];
} else {
	$this_sql = " TYPE=MyISAM";
}
$db->query("DROP TABLE IF EXISTS `".$DT_PRE."exhibit`");
$db->query("CREATE TABLE `".$DT_PRE."exhibit` (`itemid` int(10) unsigned NOT NULL auto_increment,`catid` smallint(6) unsigned NOT NULL default '0',`level` tinyint(1) unsigned NOT NULL default '0',`title` varchar(100) NOT NULL default '',`style` varchar(50) NOT NULL default '',`introduce` varchar(255) NOT NULL default '',`tag` varchar(100) NOT NULL default '',`keyword` varchar(255) NOT NULL default '',`author` varchar(50) NOT NULL default '',`copyfrom` varchar(30) NOT NULL default '',`fromurl` varchar(255) NOT NULL default '',`hits` int(10) unsigned NOT NULL default '0',`thumb` varchar(255) NOT NULL default '',`username` varchar(20) NOT NULL default '',`addtime` int(10) unsigned NOT NULL default '0',`editor` varchar(25) NOT NULL default '',`edittime` int(10) unsigned NOT NULL default '0',`template` varchar(30) NOT NULL default '0',`status` tinyint(1) NOT NULL default '0',`listorder` smallint(4) unsigned NOT NULL default '0',`islink` tinyint(1) unsigned NOT NULL default '0',`linkurl` varchar(255) NOT NULL default '',`note` varchar(100) NOT NULL default '',PRIMARY KEY  (`itemid`))".$this_sql);

$db->query("DROP TABLE IF EXISTS `".$DT_PRE."exbibit`");
$db->query("CREATE TABLE `".$DT_PRE."exhibit` (`itemid` int(10) unsigned NOT NULL default '0',`content` mediumtext NOT NULL,UNIQUE KEY `itemid` (`itemid`))".$this_sql);
?>