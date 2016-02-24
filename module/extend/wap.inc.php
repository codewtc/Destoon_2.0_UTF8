<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$wap_url = extendurl('wap');
$head_title = $head_keywords = $head_description = 'WAP浏览';
include template('wap', $module);
?>