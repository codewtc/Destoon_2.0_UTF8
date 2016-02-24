<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$head_title = '产品分类 - '.($MOD['seo_title'] ? $MOD['seo_title'] : $MOD['name']);
$head_keywords = $MOD['seo_keywords'];
$head_description = $MOD['seo_description'];
$template = 'product';
include template($template, $module);
?>