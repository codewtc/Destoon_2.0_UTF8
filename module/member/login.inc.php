<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($_userid) dheader($MOD['linkurl']);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require MOD_ROOT.'/member.class.php';
require DT_ROOT.'/include/post.func.php';
$do = new member;
$forward = $forward ? linkurl($forward, 1) : $CFG['url'];
if($submit && $MOD['captcha_login'] && strlen($captcha) < 4) $submit = false;
$action = 'login';
if($submit) {
	captcha($captcha, $MOD['captcha_login']);
	if(!$username) message('请输入登录名称');
	if(!$password) message('请输入密码');
	if(is_email($username)) $option = 'email';
	$goto = isset($goto) ? true : false;
	if($goto) $forward = $MOD['linkurl'];
	$cookietime = isset($cookietime) ? $cookietime : 0;
	$msg_code = '';
	$options = array('username', 'passport', 'email', 'userid');
	in_array($option, $options) or $option = 'username';
	$r = $db->get_one("SELECT username,passport FROM {$DT_PRE}member WHERE `$option`='$username' limit 0,1");
	if($r) {
		$username = $r['username'];
		$passport = $r['passport'];
	} else {
		if($option == 'username' || $option == 'passport') {
			$passport = $username;
		} else {
			message('登录名称不存在');
		}
	}
	if($MOD['passport'] == 'uc') require DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
	$user = $do->login($username, $password, $cookietime);
	if($user) {
		if($MOD['passport'] && $MOD['passport'] != 'uc') {
			$URI = '';
			$user['password'] = is_md5($password) ? $password : md5($password);//Once MD5
			if($MOD['passport_charset'] != $CFG['charset']) $user = convert($user, $CFG['charset'], $MOD['passport_charset']);
			extract($user);
			require DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
			if($URI) {
				if($DT['login_log'] == 2) $do->login_log($username, $password, 0);
				dheader($URI);
			}
		}
		if($msg_code) $msg_code = '登录成功'.$msg_code;
		if($DT['login_log'] == 2) $do->login_log($username, $password, 0);
		message($msg_code, $forward);
	} else {
		if($DT['login_log'] == 2) $do->login_log($username, $password, 0, $do->errmsg);
		message($do->errmsg);
	}
} else {
	$head_title = '会员登录';
	isset($username) or $username = $_username;
	isset($password) or $password = '';
	include template('login', $module);
}
?>