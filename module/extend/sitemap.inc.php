<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$head_title = '网站地图';
include template('sitemap', $module);
?>