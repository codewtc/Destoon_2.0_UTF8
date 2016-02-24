<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
switch($action) {
	case 'login':
		if($_userid) wap_msg('你已经登录了', 'index.php');
		if($submit) {
			require DT_ROOT.'/include/post.func.php';
			require DT_ROOT.'/module/member/member.class.php';
			$do = new member;
			if(!$username) wap_msg('请输入用户名');
			if(!$password) wap_msg('请输入密码');
			if(strpos($username, '@') !== false) {
				$r = $db->get_one("SELECT username FROM {$DT_PRE}member WHERE email='$username' limit 0,1");
				$r or wap_msg('邮件地址不存在');
				$username = $r['username'];
			}
			$user = $do->login($username, $password, 86400*365);
			if($user) {
				wap_msg('登录成功', $forward);
			} else {
				wap_msg($do->errmsg);
			}
		} else {
			$head_title = '会员登录'.$DT['seo_delimiter'].$head_title;
			include template('login', 'wap');
		}
	break;
	case 'logout':
		set_cookie('auth', '');
		wap_msg('已经注销登录', 'index.php');
	break;
	case 'charge':
		if(!$_userid) wap_msg('请登录', 'index.php?moduleid='.$moduleid.'&amp;action=login');
		if($submit) {
			if(!preg_match("/^[0-9a-zA-z]{6,}$/", $number)) wap_msg('卡号无效');
			if(!preg_match("/^[0-9]{6,}$/", $password)) wap_msg('密码无效');
			$card = $db->get_one("SELECT * FROM {$DT_PRE}finance_card WHERE number='$number'");
			if($card) {
				if($card['updatetime']) wap_msg('充值卡无效');
				if($card['totime'] < $DT_TIME) wap_msg('充值卡已过期');
				if($card['password'] != $password) wap_msg('密码错误');
				$db->query("INSERT INTO {$DT_PRE}finance_charge (username,bank,amount,money,sendtime,receivetime,editor,status,note) VALUES ('$_username','card', '$card[amount]','$card[amount]','$DT_TIME','$DT_TIME','system','3','$number')");
				$db->query("UPDATE {$DT_PRE}finance_card SET username='$_username',updatetime='$DT_TIME',ip='$DT_IP' WHERE itemid='$card[itemid]'");
				money_add($_username, $card['amount']);
				record_add($_username, $card['amount'], '充值卡', 'system', '充值卡充值', '卡号:'.$number.'(WAP)');
				$_money = $_money + $card['amount'];
				wap_msg('充值卡充值成功', $forward);
			} else {
				wap_msg('无效的充值卡卡号');
			}
		} else {
			$head_title = '充值卡充值'.$DT['seo_delimiter'].$head_title;
			include template('charge', 'wap');
		}
	break;
	case 'message_send':
		if(!$_userid) wap_msg('请登录', 'index.php?moduleid='.$moduleid.'&amp;action=login');
		if($submit) {
			require DT_ROOT.'/include/post.func.php';
			require DT_ROOT.'/module/member/message.class.php';
			$do = new message;
			$message = array();
			$message['typeid'] = 0;
			$message['touser'] = $touser;
			$message['title'] = $title;
			$message['content'] = $content;
			if(strtolower($CFG['charset'] != 'utf-8')) {
				$message = convert($message, 'utf-8', $CFG['charset']);
			}
			if($do->send($message)) {
				wap_msg('信件发送成功', 'index.php?moduleid='.$moduleid.'&amp;action=message');
			} else {
				wap_msg($do->errmsg);
			}
		} else {			
			$head_title = '发送信件'.$DT['seo_delimiter'].$head_title;
			$touser = isset($touser) ? trim($touser) : '';
			$title = isset($title) ? trim($title) : '';
			$content = isset($content) ? trim($content) : '';
			include template('message_send', 'wap');
		}
	break;
	case 'message_delete':
		if(!$_userid) wap_msg('请登录', 'index.php?moduleid='.$moduleid.'&amp;action=login');
		if($itemid) {
			require DT_ROOT.'/include/post.func.php';
			require DT_ROOT.'/module/member/message.class.php';
			$do = new message;			
			$do->itemid = $itemid;
			$do->delete();
			wap_msg('信件删除成功', 'index.php?moduleid='.$moduleid.'&amp;action=message');
		} else {			
			wap_msg('未指定信件');
		}
	break;
	case 'message':
		if(!$_userid) wap_msg('请登录', 'index.php?moduleid='.$moduleid.'&amp;action=login');
		if($itemid) {
			require DT_ROOT.'/module/member/message.class.php';
			$do = new message;
			$do->itemid = $itemid;
			$message = $do->get_one();
			if(!$message) wap_msg('信件不存在或无权查看');
			extract($message);
			if($status == 4 || $status == 3) {
				if($touser != $_username) wap_msg('无权限查看此信件');
				if(!$isread) {
					$do->read();
					if($feedback) $do->feedback();
				}
			} else if($status == 2 || $status == 1) {
				if($fromuser != $_username) wap_msg('无权限查看此信件');
			}
			$content = strip_tags($content);
			$content = preg_replace("/\&([^;]+);/i", '', $content);
			$contentlength = strlen($content);
			if($contentlength > $maxlength) {
				$start = ($page-1)*$maxlength;
				$content = dsubstr($content, $maxlength, '', $start);
				$pages = wap_pages($contentlength, $page, $maxlength);
			}
			$content = nl2br($content);
			$adddate = timetodate($addtime, 5);
			$head_title = $title.$DT['seo_delimiter'].'站内信'.$DT['seo_delimiter'].$head_title;
		} else {
			$TYPE = array('普通', '询价', '报价', '留言', '信使');
			$head_title = '站内信'.$DT['seo_delimiter'].$head_title;
			$keyword = $kw ? str_replace(array(' ', '*'), array('%', '%'), $kw) : '';
			$typeid = isset($typeid) ? intval($typeid) : -1;
			$condition = "touser='$_username' AND status=3";
			if($typeid != -1) $condition .= " AND typeid=$typeid";
			if($keyword) $condition .= " AND title LIKE '%$keyword%'";
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $condition");
			$pages = wap_pages($r['num'], $page, $pagesize);
			$lists = array();
			$result = $db->query("SELECT * FROM {$DT_PRE}message WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 'm/d H:i');
				$r['type'] = $TYPE[$r['typeid']];
				$lists[] = $r;
			}
		}
		include template('message', 'wap');
	break;
	default:
		dheader('index.php');
	break;
}
?>