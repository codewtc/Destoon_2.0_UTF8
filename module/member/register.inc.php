<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($_userid) message('', DT_PATH);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(isset($print)) exit(include template('agreement', $module));
if(!$MOD['enable_register']) message('管理员关闭了用户注册', DT_PATH);
if($MOD['banagent']) {
	$banagent = explode('|', $MOD['banagent']);
	foreach($banagent as $v) {
		if(strpos($_SERVER['HTTP_USER_AGENT'], $v) !== false) message('您的客户端信息已经被网站屏蔽<br/>如有疑问，请与我们联系', DT_PATH, 5);
	}
}
if($MOD['iptimeout']) {
	$timeout = $DT_TIME - $MOD['iptimeout']*3600;
	$r = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE regip='$DT_IP' AND regtime>'$timeout' ");
	if($r) message('同一IP'.$MOD['iptimeout'].'小时内只能注册一次', DT_PATH);
}
require DT_ROOT.'/include/post.func.php';
require MOD_ROOT.'/member.class.php';
$do = new member;
if($submit) {
	if($action != crypt_action('register')) dalert('数据来源校验失败');
	$msg = captcha($captcha, $MOD['captcha_register'], true);
	if($msg) dalert($msg);
	$msg = question($answer, $MOD['question_register'], true);
	if($msg) dalert($msg);
	if($post['reg'] == 0) $post['company'] = $post['truename'].'(个人)';
	$post['groupid'] = $MOD['checkuser'] ? 4 : ($post['reg'] ? 6 : 5);
	$post['content'] = $post['introduce'] = $post['thumb'] = $post['banner'] = '';
	$post['passport'] = isset($post['passport']) && $post['passport'] ? $post['passport'] : $post['username'];
	$post['edittime'] = 0;
	if($do->add($post)) {
		$username = $post['username'];
		if($MOD['checkuser'] == 2) {//邮件验证
			$auth = $do->mk_auth($username);
			$authurl = linkurl($MOD['linkurl'], 1).'send.php?action=check&auth='.$auth;
			$title = $DT['sitename'].'用户注册激活信';
			$content = ob_template('check', 'mail');
			send_mail($post['email'], $title, $content);
			$email_home = explode('@', $post['email']);
			$email_home = 'http://'.(in_array($email_home[1], array('163.com', 'qq.com', 'yahoo.com', '139.com')) ? 'mail' : 'www').'.'.str_replace('vip.', '', $email_home[1]);
			dalert('', '', 'if(confirm("一封注册激活邮件已经发送至您的电子邮箱，是否现在查收？")){top.window.location="'.$email_home.'";}else{top.window.location="'.DT_PATH.'";}');
		} else if($MOD['checkuser'] == 1) {//人工审核
			dalert('', '', 'confirm("注册成功，请等待工作人员审核，稍后登录");top.window.location="'.DT_PATH.'";');
		} else if($MOD['checkuser'] == 0) {
			if($MOD['welcome'] > 0) {
				$title = '欢迎加入'.$DT['sitename'];
				$content = ob_template('welcome', 'mail');
				if($MOD['welcome'] == 1 || $MOD['welcome'] == 3) send_message($username, $title, $content);
				if($MOD['welcome'] == 2 || $MOD['welcome'] == 3) send_mail($post['email'], $title, $content);
			}
			if($MOD['passport']) {
				dalert('注册成功，请登录', '', 'top.window.location="'.$MOD['linkurl'].$DT['file_login'].'?username='.$username.'";');
			} else {
				$do->login($username, '', 0, true);
				dalert('', '', 'top.window.location="'.$MOD['linkurl'].'";');
			}
		}
	} else {
		dalert($do->errmsg);
	}
} else {
	$COM_TYPE = explode('|', $MOD['com_type']);
	$COM_SIZE = explode('|', $MOD['com_size']);
	$COM_MODE = explode('|', $MOD['com_mode']);
	$MONEY_UNIT = explode('|', $MOD['money_unit']);
	$mode_check = dcheckbox($COM_MODE, 'member[mode][]', '', 'onclick="check_mode(this);"', 0);
	$auth = isset($auth) ? urldecode($auth) : '';
	$username = $password = $email = $passport = '';
	if($auth) {
		$auth = dcrypt($auth, 1);
		$auth = explode('|', $auth);
		$passport = $auth[0];
		if(preg_match("/^[a-z0-9]+$/", $passport)) $username = $passport;
		$password = $auth[1];
		$email = is_email($auth[2]) ? $auth[2] : '';
	}
	$head_title = '会员注册';
	include template('register', $module);
}
?>