<?php
defined('IN_DESTOON') or exit('Access Denied');
$menu = array(
	array($name."列表", "?moduleid=$moduleid"),
	array(VIP."管理", "?moduleid=$moduleid&file=vip"),
	array("分类管理", "?file=category&mid=$moduleid"),
	array("荣誉资质", "?moduleid=2&file=credit"),
	array($name."新闻", "?moduleid=2&file=news"),
	array("友情链接", "?moduleid=2&file=link"),
	array($name."模板", "?moduleid=2&file=style"),
	array("生成网页", "?moduleid=$moduleid&file=html"),
	array("模块设置", "?moduleid=$moduleid&file=setting"),
);
?>