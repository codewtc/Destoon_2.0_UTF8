<?php 
defined('IN_DESTOON') or exit('Access Denied');
//MAIN	
isset($HOME['main_show']) or $HOME['main_show'] = $_main_show;
$main_show = explode(',', $HOME['main_show']);
isset($HOME['main_order']) or $HOME['main_order'] = $_main_order;
$main_order = explode(',', $HOME['main_order']);
isset($HOME['main_num']) or $HOME['main_num'] = $_main_num;
$main_num = explode(',', $HOME['main_num']);
isset($HOME['main_name']) or $HOME['main_name'] = $_main_name;
$main_name = explode(',', $HOME['main_name']);
$_HMAIN = array();
asort($main_order);
foreach($main_order as $k=>$v) {
	if(in_array($k, $main_show)) $_HMAIN[$k] = $main_name[$k];
}
$HMAIN = $_HMAIN;
$seo_title = isset($HOME['seo_title']) && $HOME['seo_title'] ? $HOME['seo_title'] : '';
$head_title = '';
include template('index', $template);
?>