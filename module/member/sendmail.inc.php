<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['sendmail'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
require DT_ROOT.'/include/post.func.php';
if(isset($preview)) {
	$title = isset($title) ? trim(stripslashes($title)) : '';
	$content = isset($content) ? trim(stripslashes($content)) : '';
	include template('send', 'mail');
	exit;
}
if($submit) {
	captcha($captcha);
	$email = trim($email);
	if(!is_email($email)) message('请填写正确的收件人地址');
	$title = trim(stripslashes($title));
	if(strlen($title) < 5) message('请填写邮件标题');
	$title = trim(stripslashes($title));
	if(strlen($content) < 10) message('请填写邮件内容');
	$content = ob_template('send', 'mail');
	$DT['mail_name'] = $_company;
	if(send_mail($email, $title, $content, $_email, false)) {
		message('邮件已发送至 '.$email, $MOD['linkurl'].'sendmail.php?success=1');
	} else {
		message('邮件已发送失败，请重试');
	}
} else {
	$head_title = '发送电子邮件';
	$email = isset($email) ? trim(stripslashes($email)) : '';
	$title = isset($title) ? trim(stripslashes($title)) : '';
	$content = isset($content) ? trim(stripslashes($content)) : '';
	if(isset($itemid) && isset($title) && isset($linkurl)) {
		$content = '您的好友 <strong><a href="'.userurl($_username).'" target="_blank">'.$_username.'</a></strong> 向您推荐如下信息:<br/><br/>'.$title.'<br/><a href="'.$linkurl.'" target="_blank">'.$linkurl.'</a><br/><br/>附言：';
		$title = '推荐《'.$title.'》';
	}
	include template('sendmail', $module);
}
?>