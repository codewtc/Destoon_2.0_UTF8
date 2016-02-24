<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['homepage'] && $MG['home'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
$HMENU = array('公司介绍', '供应产品', '采购清单', '新闻中心', '荣誉资质', '人才招聘', '联系方式', '友情链接');
$HSIDE = array('网站公告', '新闻中心', '产品分类', '联系方式', '站内搜索', '荣誉资质', '友情链接');
$HMAIN = array('推荐产品', '公司介绍', '最新供应', '公司新闻', '荣誉资质', '联系方式');
if($submit) {
	if(isset($reset)) {
		delete_upload($setting['background'], $_userid);
		delete_upload($setting['logo'], $_userid);
		delete_upload($setting['banner'], $_userid);
		foreach($setting as $k=>$v) {
			$db->query("DELETE FROM {$DT_PRE}company_setting WHERE userid=$_userid AND item_key='$k'");
		}
		dmsg('恢复成功', $MOD['linkurl'].'home.php?success=1');
	} else {
		clear_upload($setting['background'].$setting['logo'].$setting['banner']);
		update_company_setting($_userid, $setting);
		dmsg('保存成功', $MOD['linkurl'].'home.php?success=1');
	}
} else {
	//默认设置
	$_menu_show = '0,1,2,3,4,5,6,7';
	$_menu_order = '0,1,2,3,4,5,6,7';
	$_menu_num = '1,16,30,30,10,30,1,100';
	$_menu_name = implode(',' , $HMENU);

	$_main_show = '0,1,2';
	$_main_order = '0,1,2,3,4,5';
	$_main_num = '10,1,10,5,3,1';
	$_main_name = implode(',' , $HMAIN);

	$_side_show = '0,1,2,4,5,6';
	$_side_order = '0,1,2,3,4,5,6';
	$_side_num = '1,5,10,1,1,5,5';
	$_side_name = implode(',' , $HSIDE);

	$HOME = get_company_setting($_userid);
	extract($HOME);

	isset($HOME['menu_show']) or $menu_show = $_menu_show;
	$menu_show = explode(',', $menu_show);
	isset($HOME['menu_order']) or $menu_order = $_menu_order;
	$menu_order = explode(',', $menu_order);
	isset($HOME['menu_num']) or $menu_num = $_menu_num;
	$menu_num = explode(',', $menu_num);
	isset($HOME['menu_name']) or $menu_name = $_menu_name;
	$menu_name = explode(',', $menu_name);
	$_HMENU = array();
	asort($menu_order);
	foreach($menu_order as $k=>$v) {
		$_HMENU[$k] = $HMENU[$k];
	}
	$HMENU = $_HMENU;

	isset($HOME['main_show']) or $main_show = $_main_show;
	$main_show = explode(',', $main_show);
	isset($HOME['main_order']) or $main_order = $_main_order;
	$main_order = explode(',', $main_order);
	isset($HOME['main_num']) or $main_num = $_main_num;
	$main_num = explode(',', $main_num);
	isset($HOME['main_name']) or $main_name = $_main_name;
	$main_name = explode(',', $main_name);
	$_HMAIN = array();
	asort($main_order);
	foreach($main_order as $k=>$v) {
		$_HMAIN[$k] = $HMAIN[$k];
	}
	$HMAIN = $_HMAIN;

	isset($HOME['side_show']) or $side_show = $_side_show;
	$side_show = explode(',', $side_show);
	isset($HOME['side_order']) or $side_order = $_side_order;
	$side_order = explode(',', $side_order);
	isset($HOME['side_num']) or $side_num = $_side_num;
	$side_num = explode(',', $side_num);
	isset($HOME['side_name']) or $side_name = $_side_name;
	$side_name = explode(',', $side_name);
	$_HSIDE = array();
	asort($side_order);
	foreach($side_order as $k=>$v) {
		$_HSIDE[$k] = $HSIDE[$k];
	}
	$HSIDE = $_HSIDE;

	isset($HOME['side_pos']) or $side_pos = 0;
	isset($HOME['side_width']) or $side_width = 200;
	isset($HOME['intro_length']) or $intro_length = 1000;
	isset($HOME['map']) or $map = '';
	isset($HOME['background']) or $background = '';
	isset($HOME['bgcolor']) or $bgcolor = '';
	isset($HOME['banner']) or $banner = '';
	isset($HOME['logo']) or $logo = '';
	isset($HOME['css']) or $css = '';
	isset($HOME['announce']) or $announce = '';
	isset($HOME['seo_title']) or $seo_title = '';
	isset($HOME['seo_keywords']) or $seo_keywords = '';
	isset($HOME['seo_description']) or $seo_description = '';

	$head_title = '主页设置';
	include template('home', $module);
}
?>