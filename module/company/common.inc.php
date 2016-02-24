<?php 
defined('IN_DESTOON') or exit('Access Denied');
define('MOD_ROOT', DT_ROOT.'/module/'.$module);
require DT_ROOT.'/include/module.func.php';
$CATEGORY = cache_read('category-'.$moduleid.'.php');
$ITEMS = cache_read('items-'.$moduleid.'.php');
foreach($CATEGORY as $c) {
	isset($ITEMS[$c['catid']]) or $ITEMS[$c['catid']] = 0;
}
if($MOD['seo_keywords']) $head_keywords = $MOD['seo_keywords'];
if($MOD['seo_description']) $head_description = $MOD['seo_description'];
$table = $DT_PRE.$module;
$table_member = $DT_PRE.'member';
$AREA = cache_read('area.php');
?>