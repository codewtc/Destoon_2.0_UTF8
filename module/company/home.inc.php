<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
switch($action) {
	case 'search':
		if(preg_match("/^[a-z0-9]+$/", $homepage) && in_array($file, array('sell', 'news', 'credit', 'job', 'price')) && $kw) {
			dheader(userurl($homepage, 'file='.$file.'&kw='.urlencode($kw)));
		}
	break;
	case 'message':
		if(!$username || !$template || !$skin || !$sign) exit;
		if($job == 'inquiry' || $job == 'order' || $job == 'price') {
			$title = urldecode($title);
			if(!$title || !$itemid) exit;
			check_sign($itemid.$template.$skin.$title.$username, $sign) or exit;
		} else if($job == 'guestbook') {
			check_sign($template.$skin.$username, $sign) or exit;
		} else {
			exit;
		}
		$company = $truename = $telephone = $email = $qq = $msn = '';
		if($_userid) {
			$user = userinfo($_username);
			$company = $user['company'];
			$truename = $user['truename'];
			$telephone = $user['telephone'] ? $user['telephone'] : $user['mobile'];
			$email = $user['mail'] ? $user['mail'] : $user['email'];
			$qq = $user['qq'];
			$msn = $user['msn'];
		}
		include template('message', $template);
	break;
	case 'send':
		require DT_ROOT.'/include/post.func.php';
		preg_match("/^[a-z0-9]{2,}$/", $username) or exit;
		in_array($job, array('inquiry', 'order', 'guestbook', 'price')) or exit;
		$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
		$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
		if($job == 'inquiry') {
			if($MG['inquiry_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=1 AND status=3");
				$r['num'] < $MG['inquiry_limit'] or dalert('今日可询价'.$MG['inquiry_limit'].'次 当前已询价'.$r['num'].'次');
			}
		} else if($job == 'price') {
			if($MG['price_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=2 AND status=3");
				$r['num'] < $MG['price_limit'] or dalert('今日可报价'.$MG['price_limit'].'次 当前已报价'.$r['num'].'次');
			}
		} else {
			if($MG['message_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND status=3");
				$r['num'] < $MG['message_limit'] or dalert('今日可留言'.$MG['message_limit'].'次 当前已留言'.$r['num'].'次');
			}
		}

		$msg = captcha($captcha, 1, true);
		if($msg) dalert($msg);
		$title = htmlspecialchars(trim($title));
		if(!$title) dalert('请填写主题');
		$content = htmlspecialchars(trim($content));
		if(!$content) dalert('请填写内容');

		$truename = htmlspecialchars(trim($truename));
		if(!$truename)  dalert('请填写联系人');
		if(!is_email($email)) dalert('请填写正确的电子邮件');
		$telephone = htmlspecialchars(trim($telephone));
		$company = htmlspecialchars(trim($company));
		$qq = htmlspecialchars(trim($qq));
		$msn = htmlspecialchars(trim($msn));

		$content = nl2br($content);
		$content .= '<hr size="1"/>联系人：'.$truename;
		$content .= '<br/>电子邮件：'.$email;
		if($company) $content .= '<br/>公司名：'.$company;
		if($telephone) $content .= '<br/>联系电话：'.$telephone;
		if(is_numeric($qq)) $content .= '<br/>联系QQ：'.$qq;
		if(is_email($msn)) $content .= '<br/>联系MSN：'.$msn;
		$content .= '<br/>(信息来自公司主页)';
		if($job == 'guestbook') {
			$type = 3;
		} else if($job == 'price') {
			$type = 2;
		} else {
			$type = 1;
		}
		if(send_message($username, $title, $content, $type, $_username)) {
			dalert('提交成功', '', 'parent.window.location=parent.window.location;');
		} else {
			dalert($_userid ? '提交失败，对方可能拒绝您的信息' : '提交失败，对方可能拒绝非会员的信息');
		}
	break;
	default:
		dheader($MOD['linkurl']);
	break;
}
?>