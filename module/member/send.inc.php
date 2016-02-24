<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require MOD_ROOT.'/member.class.php';
require DT_ROOT.'/include/post.func.php';
$do = new member;
switch($action) {
	case 'check'://验证邮件
		if($_userid) message('您已经登录了', DT_PATH);
		$auth = isset($auth) ? trim($auth) : '';
		if($auth) {
			$username = $do->ck_auth($auth);
			if($username) {
				$member = $db->get_one("SELECT email,company FROM {$DT_PRE}member WHERE username='$username'");
				$groupid = strpos($member['company'], '(个人)') === false ? 6 : 5;
				$db->query("UPDATE {$DT_PRE}member SET auth='',groupid=$groupid WHERE username='$username'");
				$db->query("UPDATE {$DT_PRE}company SET groupid=$groupid WHERE username='$username'");
				if($MOD['welcome'] > 0) {
					$title = '欢迎加入'.$DT['sitename'];
					$content = ob_template('welcome', 'mail');
					if($MOD['welcome'] == 1 || $MOD['welcome'] == 3) send_message($username, $title, $content);
					if($MOD['welcome'] == 2 || $MOD['welcome'] == 3) {
						send_mail($member['email'], $title, $content);
					}
				}
				$do->login($username, '', 0, true);
				message('您的帐号激活成功', $MOD['linkurl']);
			} else {
				message('您的请求未通过系统验证或请求已经过期');
			}
		} else {			
			if($MOD['checkuser'] != 2) message('系统未启用邮件验证', DT_PATH);		
			if($submit) {				
				captcha($captcha);
				$email = trim($email);
				if(!is_email($email)) message('请填写正确的邮件地址');
				$r = $db->get_one("SELECT username,groupid FROM {$DT_PRE}member WHERE email='$email' ");
				if($r['username']) {
					if($r['groupid'] != 4) message('您的帐号无需发送验证信', DT_PATH);
					if($r['username'] != $username) message('您的会员名输入错误');
					$auth = $do->mk_auth($username);
					$authurl = linkurl($MOD['linkurl'], 1).'send.php?action=check&auth='.$auth;
					$title = $DT['sitename'].'用户注册激活信';
					$content = ob_template('check', 'mail');
					send_mail($email, $title, stripslashes($content));
					message('验证邮件已发送至您的邮箱，请注意查收', DT_PATH, 10);
				} else {
					message('您输入的邮件地址尚未注册');
				}
			} else {
				$head_title = '重发验证信';
				include template('send_check', $module);
			}
		}
	break;			
	case 'payword'://找回支付密码
		login();
		$auth = isset($auth) ? trim($auth) : '';
		if($auth) {
			$username = $do->ck_auth($auth);
			if($username) {
				$password = random(7);
				$md_password = md5(md5($password));
				$db->query("UPDATE {$DT_PRE}member SET auth='',payword='$md_password' WHERE username='$username'");
				$msgcode = 'S1';
				include template('msg', $module);
			} else {
				message('您的请求未通过系统验证或请求已经过期', DT_PATH);
			}

		} else {
			$username = $_username;
			$auth = $do->mk_auth($username);
			$authurl = linkurl($MOD['linkurl'], 1).'send.php?action=payword&auth='.$auth;
			$title = $DT['sitename'].'用户找回支付密码';
			$content = ob_template('payword', 'mail');
			send_mail($_email, $title, stripslashes($content));
			message('验证邮件已发送至您的邮箱，请注意查收', $MOD['linkurl'], 3);
		}
	break;
	default:
		if($_userid) message('您已经登录了', DT_PATH);
		$auth = isset($auth) ? trim($auth) : '';
		if($auth) {
			$username = $do->ck_auth($auth);
			if($username) {
				$password = random(7);
				$md_password = md5(md5($password));
				$db->query("UPDATE {$DT_PRE}member SET auth='',password='$md_password' WHERE username='$username'");
				$msgcode = 'S0';
				include template('msg', $module);
			} else {
				message('您的请求未通过系统验证或请求已经过期', DT_PATH);
			}

		} else {
			if($submit) {
				captcha($captcha);
				$email = trim($email);
				if(!is_email($email)) message('请填写正确的邮件地址');				
				$options = array('username', 'passport', 'truename', 'company', 'mobile', 'userid');
				in_array($option, $options) or $option = 'username';
				$r = $db->get_one("SELECT username,groupid FROM {$DT_PRE}member WHERE email='$email' AND `$option`='$username'");
				if($r) {
					$username = $r['username'];
					if($r['groupid'] == 4) message('您的帐号尚未通过审核');
					$auth = $do->mk_auth($username);
					$authurl = linkurl($MOD['linkurl'], 1).'send.php?auth='.$auth;
					$title = $DT['sitename'].'用户找回密码';
					$content = ob_template('password', 'mail');
					send_mail($email, $title, stripslashes($content));
					message('验证邮件已发送至您的邮箱，请注意查收', DT_PATH, 10);
				} else {
					message('提供的信息不匹配');
				}
			} else {
				$head_title = '找回密码';
				include template('send_pass', $module);
			}
		}
	break;
}
?>