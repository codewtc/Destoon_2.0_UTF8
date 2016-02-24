<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
DT_LICENSE == strtoupper(md5(file_get_contents(DT_ROOT.'/license.txt'))) or msg('license.txt不可修改或删除，请检查');
if(!$forward) $forward = '?';
if($_dt_admin && $_userid && $_dt_admin == $_userid) msg('', $forward);
if($submit) {
	captcha($captcha, $DT['captcha_admin']);
	if(!$username) message('请输入用户名');
	if(!$password) message('请输入密码');
	require DT_ROOT.'/module/member/member.class.php';
	$do = new member;
	$user = $do->login($username, $password);
	if($user) {
		if($user['groupid'] != 1 || $user['level'] < 1) msg('您无权限访问后台', DT_PATH);
		$_SESSION['dt_admin'] = $user['userid'];
		require DT_ROOT.'/include/admin.class.php';
		$admin = new admin;
		$admin->cache_right($user['userid']);
		$admin->cache_menu($user['userid']);
		if($DT['login_log']) $do->login_log($username, $password, 1);
		msg('', $forward);
	} else {
		if($DT['login_log']) $do->login_log($username, $password, 1, $do->errmsg);
		msg($do->errmsg);
	}
} else {
	$username = isset($username) ? $username : $_username;
	include tpl('login');
}
?>