<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
if($action == 'ftp') {
	require DT_ROOT.'/include/ftp.class.php';
	$ftp = new dftp($ftp_host, $ftp_user, $ftp_pass, $ftp_port, $ftp_path, $ftp_pasv, $ftp_ssl);
	if(!$ftp->connected) dialog('FTP无法连接，请检查设置');
	if(!$ftp->dftp_chdir()) dialog('FTP无法进入远程存储目录，请检查远程存储目录');
	dialog('FTP设置正常,可以使用');
} else if ($action == 'mail') {
	$DT['mail_type'] = $mail_type;
	$DT['smtp_host'] = $smtp_host;
	$DT['smtp_port'] = $smtp_port;
	$DT['smtp_auth'] = $smtp_auth;
	$DT['smtp_user'] = $smtp_user;
	$DT['smtp_pass'] = $smtp_pass;
	$DT['mail_sender'] = $mail_sender;
	$DT['mail_name'] = $mail_name;
	$DT['mail_delimiter'] = $mail_delimiter;
	$DT['mail_sign'] = '<br>------------------------------<br><a href="http://www.destoon.com/" target="_blank">By Destoon Mail Sender</a>';
	if(send_mail($testemail, 'Destoon系统邮件发送测试', 'Welcome To http://www.destoon.com')) dialog('邮件已发送至'.$testemail.'，请注意查收', $mail_sender);
	dialog('邮件发送失败，请检查设置');
} else {
	$tab = isset($tab) ? intval($tab) : 0;
	if($submit) {
		if(!is_writable(DT_ROOT.'/config.inc.php')) msg('Please chmod ./config.inc.php to '.DT_CHMOD);
		$tmp = file_get_contents(DT_ROOT.'/config.inc.php');
		foreach($config as $k=>$v)	{
			$tmp = preg_replace("/[$]CFG\['$k'\]\s*\=\s*[\"'].*?[\"']/is", "\$CFG['$k'] = '$v'", $tmp);
		}
		file_put(DT_ROOT.'/config.inc.php', $tmp);
		update_setting($moduleid, $setting);
		cache_module(1);
		cache_module();
		$filename = DT_ROOT.'/'.$setting['index'].'.'.$setting['file_ext'];
		if(!$setting['ishtml'] && $setting['file_ext'] != 'php') @unlink($filename);
		$mdir = DT_ROOT.'/'.$MODULE[2]['moduledir'].'/';
		if($setting['file_register'] != $old_file_register) @rename($mdir.$old_file_register, $mdir.$setting['file_register']);
		if($setting['file_login'] != $old_file_login) @rename($mdir.$old_file_login, $mdir.$setting['file_login']);
		if($setting['file_my'] != $old_file_my) @rename($mdir.$old_file_my, $mdir.$setting['file_my']);
		dmsg('更新成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
	} else {
		include DT_ROOT.'/config.inc.php';
		extract(dhtmlspecialchars($CFG));
		extract(dhtmlspecialchars($DT));
		include tpl('setting');
	}
}
?>