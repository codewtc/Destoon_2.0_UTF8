<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/

/* 
采集软件入库接口 For Destoon
支持POST或GET两种方式发送数据
例如：
文章模型入库可发送 http://www.xxx.com/api/spider.php?moduleid=21&catid=1&title=测试标题&content=测试内容
获取栏目分类可请求 http://www.xxx.com/api/spider.php?moduleid=21&action=cat
返回状态会直接输出，请注意判断
*/

$verify_mode = 4; //身份验证模式
//1 验证是否为创始人，需要登录
//2 验证密钥，如果设置为2，则必须设置 入库密钥[推荐]
//3 验证IP，如果设置为3，则必须设置 允许的IP
//4 关闭接口

$spider_auth = '';   //入库密钥 最少6位
$spider_ip = '';     //允许的IP
$splider_status = 3; //信息状态 2为待审核 3为通过 0为通过软件发送


/*以下内容请勿修改*/

$_DPOST = $_POST;
$_DGET = $_GET;

require '../common.inc.php';

//校验身份
$pass = false;
if($verify_mode == 1) {
	if($_userid && $_userid == $CFG['founderid']) $pass = true;
} else if($verify_mode == 2) {
	$auth = $_DPOST ? $_DPOST['auth'] : $_DGET['auth'];
	if(strlen($auth) >= 6 && $auth == $spider_auth)  $pass = true;
} if($verify_mode == 3) {
	if($DT_IP && $DT_IP == $spider_ip) $pass = true;
}
$pass or exit('身份校验失败');

$class = DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
if(is_file($class)) {
	$CATEGORY = cache_read('category-'.$moduleid.'.php');
	if($action == 'cat') {//获取栏目ID
		echo '<select name="catid">';
		foreach($CATEGORY as $k=>$v) {
			echo '<option value="'.$v['catid'].'">'.$v['catname'].'</option>';
		}
		echo '</select>';
	} else {
		$AREA = cache_read('area.php');
		$post = array();
		if($_DPOST) {
			$post = $_DPOST;
		} else if($_DGET) {
			$post = $_DGET;
		} else {
			exit('未接收到数据');
		}
		require DT_ROOT.'/include/module.func.php';
		require DT_ROOT.'/include/post.func.php';
		require $class;
		if(in_array($module, array('article', 'info'))) {
			$table = $DT_PRE.$module.'_'.$moduleid;
			$table_data = $DT_PRE.$module.'_data_'.$moduleid;
		} else {
			$table = $DT_PRE.$module;
			$table_data = $DT_PRE.$module.'_data';
		}
		isset($CATEGORY[$post['catid']]) or exit('栏目不存在');
		$do = new $module($moduleid);	
		foreach($do->fields as $v) {
			isset($post[$v]) or $post[$v] = '';
		}
		if(isset($post['islink'])) unset($post['islink']);
		if($splider_status) $post['status'] = $splider_status;
		$post = array_map('addslashes', $post);
		if($_DGET) $post = array_map('urldecode', $post);
		if($do->pass($post)) {
			$do->add($post);
			exit('发布成功');
		} else {
			exit($do->errmsg);
		}
	}
} else {
	exit('模型不存在');
}
?>