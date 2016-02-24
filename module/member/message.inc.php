<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['inbox_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
require MOD_ROOT.'/message.class.php';
$do = new message;
$typeid = isset($typeid) ? intval($typeid) : -1;
$NAME = array('普通', '询价', '报价', '留言', '信使');
$action or $action = 'inbox';
$condition = '';
if($typeid > -1) $condition .= " AND typeid=$typeid";
if($keyword) $condition .= " AND title LIKE '%$keyword%'";
$head_title = '站内信';
switch($action) {
	case 'send'://发信
		$MG['message_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
		$limit_used = $limit_free = 0;
		if($MG['message_limit']) {
			$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
			$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND status=3");
			$limit_used = $r['num'];
			$limit_used < $MG['message_limit'] or dalert('今日可发送'.$MG['message_limit'].'次 当前已发送'.$limit_used.'次', 'goback');
			$limit_free = $MG['message_limit'] > $limit_used ? $MG['message_limit'] - $limit_used : 0;
		}

		$need_captcha = $MOD['captcha_sendmessage'] == 2 ? $MG['captcha'] : $MOD['captcha_sendmessage'];
		if($submit) {
			captcha($captcha, $need_captcha);
			$message['typeid'] = $typeid;
			clear_upload($message['content']);
			if($do->send($message)) {
				dmsg(isset($message['save']) ? '草稿保存成功' : '信件发送成功', '?action=send');
			} else {
				message($do->errmsg);
			}
		} else {
			$touser = isset($touser) ? trim($touser) : '';
			$title = isset($title) ? stripslashes($title) : '';
			$content = isset($content) ? stripslashes($content) : '';	
			$friends = array();
			$result = $db->query("SELECT username,name,company FROM {$DT_PRE}friend WHERE userid=$_userid AND username!='' ORDER BY listorder DESC,itemid DESC LIMIT 0,20");
			while($r = $db->fetch_array($result)) {
				$friends[] = $r;
			}
			if($typeid == 1) {
				$head = '发送询价';
			} else if($typeid == 2) {
				$head = '发送报价';
			} else {
				$typeid = 0;
				$head = '发送信件';
			}
		}
	break;
	case 'edit':
		$itemid or message('请指定要修改的信件');
		$do->itemid = $itemid;
		if($submit) {
			clear_upload($message['content']);
			if($do->edit($message)) {
				dmsg(isset($message['send']) ? '信件发送成功' : '草稿修改成功', '?action=draft');
			} else {
				message($do->errmsg);
			}
		} else {
			$message = $do->get_one();
			if(!$message || $message['status'] != 1 || $message['fromuser'] != $_username) message('信件不存在或无权修改');
			$touser = $message['touser'];
			$title = $message['title'];
			$content = $message['content'];
		}
	break;
	case 'clear':
		$status or message();
		$message = $do->clear($status);
		dmsg('成功清空', $forward);
	break;
	case 'delete':
		$itemid or message('请指定要删除的信件');
		$recycle = isset($recycle) ? 0 : 1;
		$do->itemid = $itemid;
		$message = $do->delete($recycle);
		dmsg('成功删除', $forward);
	break;
	case 'mark':
		$itemid or message('请指定要标记的信件');
		$do->itemid = $itemid;
		$message = $do->mark();
		dmsg('已标记为已读', $forward);
	break;
	case 'restore':
		$itemid or message('请指定要还原的信件');
		$do->itemid = $itemid;
		$message = $do->restore();
		dmsg('成功还原', $forward);
	break;
	case 'show':
		$itemid or message();
		$do->itemid = $itemid;
		$message = $do->get_one();
		if(!$message) message('信件不存在或无权查看');
		$fback = isset($feedback) ? 1 : 0;
		extract($message);
		if($status == 4 || $status == 3) {
			if($touser != $_username) message('无权限查看此信件');
			if(!$isread) {
				$do->read();
				--$_message;
				if($fback && $feedback) $do->feedback($message);
			}
		} else if($status == 2 || $status == 1) {
			if($fromuser != $_username) message('无权限查看此信件');
		}
		$addtime = timetodate($addtime, 5);
		$messages = array();
		if($_message) {
			$messages = $do->get_list("touser='$_username' AND status=3 AND isread=0");
		}
	break;
	case 'export':
		if($submit) {
			$do->export($message) or message($do->errmsg);
		} else {
			$fromdate = timetodate(strtotime('-1 month'), 3);
			$todate = timetodate($DT_TIME, 3);
		}
	break;
	case 'empty':
		if($submit) {
			$message['username'] = $_username;
			if($do->_clear($message)) {
				dmsg('清理成功', $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			$fromdate = '';
			$todate = timetodate(strtotime('-1 month'), 3);
		}
	break;
	case 'refuse':
		if(!$username) message('请指定要加入黑名单的会员');
		if(!$do->is_member($username)) message('会员不存在，请检查');
		$black = $db->get_one("SELECT black FROM {$DT_PRE}member WHERE userid=$_userid");
		$black = $black['black'];
		if($black) {
			$tmp = explode(' ', trim($black));
			if(in_array($username, $tmp)) {
				message('会员已经位于黑名单');
			} else {
				$black = $black.' '.$username;
			}
		} else {
			$black = $username;
		}
		$db->query("UPDATE {$DT_PRE}member SET black='$black' WHERE userid=$_userid");
		dmsg('黑名单更新成功', '?action=setting');
	break;
	case 'setting':
		if($submit) {
			if($black) {
				$blacks = array();
				$tmp = explode(' ', trim($black));
				foreach($tmp as $v) {
					if(($do->is_member($v) || $v == 'Guest') && !in_array($v, $blacks)) $blacks[] = $v;
				}
				$black = $blacks ? implode(' ', $blacks) : '';
			} else {
				$black = '';
			}
			$send = $send ? 1 : 0;
			$db->query("UPDATE {$DT_PRE}member SET black='$black',send='$send' WHERE userid=$_userid");
			dmsg('设置更新成功', '?action=setting');
		} else {
			$head_title = '黑名单'.$DT['seo_delimiter'].$head_title;
			$user = $db->get_one("SELECT black,send FROM {$DT_PRE}member WHERE userid=$_userid");
			$could_send = false;
			if($DT['message_email'] && $DT['mail_type'] != 'close') {
				if(check_group($_groupid, $DT['message_group'])) $could_send = true;
			}
		}
	break;
	case 'outbox':
		$status = 2;
		$name = '已发送';
		$condition = "fromuser='$_username' AND status=$status ".$condition;
		$messages = $do->get_list($condition);
	break;
	case 'draft':
		$status = 1;
		$name = '草稿箱';
		$condition = "fromuser='$_username' AND status=$status ".$condition;
		$messages = $do->get_list($condition);
	break;
	case 'recycle':
		$status = 4;
		$name = '回收站';
		$condition = "touser='$_username' AND status=$status ".$condition;
		$messages = $do->get_list($condition);
	break;
	case 'last':
		if($_message) {
			$item = $db->get_one("SELECT itemid,feedback FROM {$DT_PRE}message WHERE touser='$_username' AND status=3 AND isread=0 ORDER BY itemid DESC");
			if($item) message('', $MOD['linkurl'].'message.php?action=show&itemid='.$item['itemid'].($item['feedback'] ? '&feedback=1' : ''));
		} 
		dheader($MOD['linkurl'].'message.php');
	break;
	default:
		if($MG['inbox_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE touser='$_username' AND status=3");
			$limit_used = $r['num'];
			$limit_free = $MG['inbox_limit'] > $limit_used ? $MG['inbox_limit'] - $limit_used : 0;
			if($limit_used >= $MG['inbox_limit']) dalert('收件箱已满，请清理信件', '?action=empty');
		}
		$status = 3;
		$name = '收件箱';
		if($_message) $do->fix_message();
		$condition = "touser='$_username' AND status=$status ".$condition;
		$messages = $do->get_list($condition);
		$systems = $do->get_sys();
	break;
}
include template('message', $module);
?>