<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function dmail($mail_to, $mail_subject, $mail_body, $mail_from = '', $mail_sign = true) {
	global $CFG, $DT;
	if($DT['mail_type'] == 'close') return false;
	$sendmail_from = $mail_from ? $mail_from : $DT['mail_sender'];
	$mail_from = "=?".strtolower($CFG['charset'])."?B?".base64_encode($DT['mail_name'] ? $DT['mail_name'] : $DT['sitename'])."?= <".$sendmail_from.">";
	$mail_subject = stripslashes($mail_subject);
	$mail_subject = str_replace("\r", '', str_replace("\n", '', $mail_subject));
	$mail_subject = "=?".strtolower($CFG['charset'])."?B?".base64_encode($mail_subject)."?=";
	if($DT['mail_sign'] && $mail_sign) $mail_body .= $DT['mail_sign'];
	$mail_body = stripslashes($mail_body);
	$mail_body = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $mail_body)))))));
	$mail_dlmt = $DT['mail_delimiter'] == 1 ? "\r\n" : ($DT['mail_delimiter'] == 2 ? "\n" : "\r");
	$headers = '';
	$headers .= "From: $mail_from".$mail_dlmt;
	$headers .= "X-Priority: 3".$mail_dlmt;
	$headers .= "X-Mailer: Destoon".$mail_dlmt;
	$headers .= "MIME-Version: 1.0".$mail_dlmt;
	$headers .= "Content-type: text/html; charset=".$CFG['charset'].$mail_dlmt;
	$headers .= "Content-Transfer-Encoding: base64".$mail_dlmt;
	if($DT['mail_type'] == 'smtp') {
		$host = $DT['smtp_host'].':'.$DT['smtp_port'].' ';
		if(!$fp = fsockopen($DT['smtp_host'], $DT['smtp_port'], $errno, $errstr, 30)) {
			log_write($host.'can not connect to the SMTP server', 'smtp');
			return false;
		}
		stream_set_blocking($fp, true);
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != '220') {
			log_write($host.$RE, 'smtp');
			return false;
		}
		fputs($fp, ($DT['smtp_auth'] ? 'EHLO' : 'HELO')." Destoon\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 220 && substr($RE, 0, 3) != 250) {
			log_write($host.'HELO/EHLO - '.$RE, 'smtp');
			return false;
		}
		while(1) {
			if(substr($RE, 3, 1) != '-' || empty($RE)) break;
			$RE = fgets($fp, 512);
		}

		if($DT['smtp_auth']) {
			fputs($fp, "AUTH LOGIN\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 334) {
				log_write($host.'AUTH LOGIN - '.$RE, 'smtp');
				return false;
			}
			fputs($fp, base64_encode($DT['smtp_user'])."\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 334) {
				log_write($host.'USERNAME - '.$RE, 'smtp');
				return false;
			}
			fputs($fp, base64_encode($DT['smtp_pass'])."\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 235) {
				log_write($host.'PASSWORD - '.$RE, 'smtp');
				return false;
			}
			$mail_from = $DT['smtp_user'];
		} else {
			$mail_from = $DT['mail_sender'];
		}
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $mail_from).">\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 250) {
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $mail_from).">\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 250) {
				log_write($host.'MAIL FROM - '.$RE, 'smtp');
				return false;
			}
		}
		foreach(explode(',', $mail_to) as $touser) {
			$touser = trim($touser);
			if($touser) {
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
				$RE = fgets($fp, 512);
				if(substr($RE, 0, 3) != 250) {
					fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
					$RE = fgets($fp, 512);
					log_write($host.'RCPT TO - '.$RE, 'smtp');
					return false;
				}
			}
		}
		fputs($fp, "DATA\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 354) {
			log_write($host.'DATA - '.$RE, 'smtp');
			return false;
		}
		list($msec, $sec) = explode(' ', microtime());
		$headers .= "Message-ID: <".date('YmdHis', $sec).".".($msec*1000000).".".substr($mail_from, strpos($mail_from,'@')).">".$mail_dlmt;
		fputs($fp, "Date: ".date('r')."\r\n");
		fputs($fp, "To: ".$mail_to."\r\n");
		fputs($fp, "Subject: ".$mail_subject."\r\n");
		fputs($fp, $headers."\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, "$mail_body\r\n.\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 250) {
			log_write($host.'END - '.$RE, 'smtp');
			return false;
		}
		fputs($fp, "QUIT\r\n");
		return true;
	} else {
		if($DT['mail_type'] != 'mail') {
			ini_set('SMTP', $DT['smtp_host']);
			ini_set('smtp_port', $DT['smtp_port']);
			ini_set('sendmail_from', $sendmail_from);
		}
		return  @mail($mail_to, $mail_subject, $mail_body, $headers);
	}
}
?>