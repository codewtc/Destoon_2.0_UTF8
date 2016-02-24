<?php 
defined('IN_DESTOON') or exit('Access Denied');
class message {
	var $itemid;
	var $userid;
	var $username;
	var $db;
	var $pre;
	var $errmsg = errmsg;

    function message()	{
		global $db, $DT_PRE, $_userid, $_username;
		$this->userid = $_userid;
		$this->username = $_username;
		$this->pre = $DT_PRE;
		$this->db = &$db;
    }

	function is_message($message) {
		if(!is_array($message)) return false;
		if(empty($message['title'])) return $this->_('请填写标题');
		if(empty($message['content'])) return $this->_('请填写内容');
		return true;
	}

	function is_member($username) {
		return $this->db->get_one("SELECT * FROM {$this->pre}member WHERE username='$username' limit 0,1");
	}

	function send($message) {
		global $DT, $MODULE, $MOD, $DT_TIME, $DT_IP, $_email;
		if(!$this->is_message($message)) return false;
		$message['title'] = htmlspecialchars($message['title']);
		if(isset($message['save'])) {//存草稿
			$this->db->query("INSERT INTO {$this->pre}message(title,typeid,content,fromuser,touser,addtime,ip,status) values('$message[title]','$message[typeid]','$message[content]','$this->username','$message[touser]','$DT_TIME','$DT_IP','1')");
		} else {
			if(substr_count($message['touser'], ' ') > ($MOD['maxtouser']-1)) return $this->_('最多同时给'.$MOD['maxtouser'].'个人发送信件');
			$tousers = array();
			$feedback = isset($message['feedback']) ? 1 : 0;
			foreach(explode(' ', $message['touser']) as $touser) {
				$user = $this->db->get_one("SELECT black FROM {$this->pre}member WHERE username='$touser' limit 0,1");
				if($user) {
					$blacks = $user['black'] ? explode(' ', $user['black']) : array();
					if(!in_array($this->username, $blacks) && !in_array($touser, $tousers)) { 	
						$tousers[] = $touser;
						if(isset($message['copy'])) $this->db->query("INSERT INTO {$this->pre}message (title,typeid,content,fromuser,touser,addtime,ip,feedback,status) VALUES ('$message[title]','$message[typeid]','$message[content]','$this->username','$touser','$DT_TIME','$DT_IP','$feedback','2')");
						$this->db->query("UPDATE {$this->pre}member SET message=message+1 WHERE username='$touser'");
						$this->db->query("INSERT INTO {$this->pre}message (title,typeid,content,fromuser,touser,addtime,ip,feedback,status) VALUES ('$message[title]','$message[typeid]','$message[content]','$this->username','$touser','$DT_TIME','$DT_IP','$feedback','3')");
					}
				}
			}
		}
		$this->itemid = $this->db->insert_id();
		return true;
	}
	
	function edit($message) {
		if(!$this->is_message($message)) return false;
		$r = $this->get_one();
		if($r['status'] != 1 || $r['fromuser'] != $this->username) return $this->_('信件不存在或无权修改');
		$message['title'] = htmlspecialchars($message['title']);
		$this->db->query("UPDATE {$this->pre}message SET title='$message[title]',content='$message[content]' WHERE itemid='$this->itemid' ");
		if(isset($message['send'])) return $this->send($message);
		return true;
	}

	function get_one() {
        return $this->db->get_one("SELECT * FROM {$this->pre}message WHERE itemid='$this->itemid' limit 0,1");
	}

	function get_list($condition, $order = 'itemid DESC') {
		global $MODULE, $pages, $page, $pagesize, $offset, $pagesize;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}message WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$messages = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}message WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = date('Y年m月d日 H:i', $r['addtime']);
			$r['dtitle'] = dsubstr($r['title'], 60, '...');
			$r['user'] = $r['status'] > 2 ? ($r['fromuser'] ? $r['fromuser'] : '系统信使') : $r['touser'];
			if($r['fromuser']) {
				$r['user'] =  $r['status'] > 2 ? $r['fromuser'] : $r['touser'];
				$r['userurl'] = $MODULE[3]['linkurl'].'redirect.php?username='.$r['user'];
			} else {
				$r['user'] = $r['typeid'] == 4 ? '系统信使' : '游客';
				$r['userurl'] = '';
			}
			$messages[] = $r;
		}
		return $messages;
	}

	function get_sys() {
		global $_groupid;
		$messages = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}message WHERE groupids!='' ORDER BY itemid DESC");
		while($r = $this->db->fetch_array($result)) {
			$groupids = explode(',', $r['groupids']);
			if(!in_array($_groupid, $groupids)) continue;
			$r['user'] = '系统广播';
			$r['adddate'] = date('Y年m月d日 H:i', $r['addtime']);
			$messages[] = $r;
		}
		return $messages;
	}

	function export($message) {
		global $DT_TIME, $module, $CFG, $DT;
		if(!in_array($message['status'], array(1, 2, 3 ,4))) return false;
		$status = $message['status'];
		$fromtime = isset($message['fromdate']) && is_date($message['fromdate']) ? strtotime($message['fromdate'].' 0:0:0') : 0;
		$totime = isset($message['todate']) && is_date($message['todate']) ? strtotime($message['todate'].' 23:59:59') : 0;
		$condition = "status='$status'";
		$condition .= $status > 2 ? " AND touser='$this->username'" : " AND fromuser='$this->username'";
		if($fromtime) $condition .= " AND addtime>'$fromtime' ";
		if($totime) $condition .= " AND addtime<'$totime' ";
		if(isset($message['isread'])) $condition .= " AND isread=0 ";
		$data = '';
		$result = $this->db->query("SELECT * FROM {$this->pre}message WHERE $condition ORDER BY itemid DESC Limit 100");
		while($r = $this->db->fetch_array($result)) {
			$r['addtime'] = date('Y年m月d日 H:i', $r['addtime']);
			$r['fromuser'] = $r['fromuser'] ? $r['fromuser'] : 'system';
			$data .= '<strong>'.$r['title'].'</strong><br/>'.$r['fromuser'].'@'.$r['addtime'].'<br/>'.$r['content'].'<hr size="1"/>';
		}
		if($data) {
			$names = array(1=>'草稿箱', 2=>'已发送', 3=>'收件箱', 4=>'回收站');
			$filename = '我的'.$names[$status].'信件';
			$data = '<html><meta http-equiv="Content-Type" content="text/html;charset='.$CFG['charset'].'"/><title>'.$this->username.'的'.$names[$status].'信件 '.$DT['sitename'].' '.timetodate($DT_TIME, 5).'导出 - Powered By Destoon.COM</title><style>*{font-size:13px;font-family:Verdana,Arial;}body{width:750px;margin:auto;line-height:200%;}</style><base target="_blank"/><base href="'.DT_URL.'"/><body><br/>'.$data.'<a href="http://www.destoon.com"><small>Powered By Destoon.COM</small></a><br/></body></html>';
			ob_start();
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', $DT_TIME + 31536000).' GMT');
			header('Content-Length: '.strlen($data));
			header('Content-Disposition:attachment; filename='.$filename.'.htm');
			header('Content-Type:application/octet-stream');
			echo $data;
			exit;
		} else {
			$this->errmsg = '指定范围暂无信件';
			return false;
		}
	}

	function clear($status) {
		if($status == 4 || $status == 3) {
			$this->db->query("DELETE FROM {$this->pre}message WHERE status='$status' AND touser='$this->username' ");
			if($status == 3) $this->db->query("UPDATE {$this->pre}member SET message=0 WHERE username='$this->username' ");
		} else if($status == 2 || $status == 1) {			
			$this->db->query("DELETE FROM {$this->pre}message WHERE status='$status' AND fromuser='$this->username' ");
		}
	}

	function delete($recycle = 0) {
		if(!$this->itemid) return false;
		$itemids = is_array($this->itemid) ? implode(',', $this->itemid) : intval($this->itemid);
		$result = $this->db->query("SELECT * FROM {$this->pre}message WHERE itemid IN($itemids) ORDER BY itemid DESC");
		while($r = $this->db->fetch_array($result)) {
			if(defined('DT_ADMIN')) {
				if($r['status'] == 3 && !$r['isread']) $this->db->query("UPDATE {$this->pre}member SET message=message-1 WHERE username='$r[touser]' ");
				$this->db->query("DELETE FROM {$this->pre}message WHERE itemid='$r[itemid]'");
			} else {
				if($r['status'] == 4) {
					if($this->username == $r['touser']) $this->_delete($r['itemid']);
				} else if($r['status'] == 3) {
					if($this->username == $r['touser']) {
						if($recycle) {
							$this->db->query("UPDATE {$this->pre}message SET status=4 WHERE itemid='$r[itemid]' ");
						} else {
							$this->_delete($r['itemid']);
						}
						if(!$r['isread']) $this->db->query("UPDATE {$this->pre}member SET message=message-1 WHERE username='$this->username' ");
					}
				} else if($r['status'] == 2 || $r['status'] == 1) {
					if($this->username == $r['fromuser']) $this->_delete($r['itemid']);
				}
			}
		}
	}

	function mark() {
		if(!$this->itemid) return false;
		$itemids = is_array($this->itemid) ? implode(',', $this->itemid) : intval($this->itemid);
		$condition = "status=3 AND isread=0 AND touser='$this->username' AND itemid IN($itemids)";
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}message WHERE $condition ");
		if($r['num']) {
			$this->db->query("UPDATE {$this->pre}message SET isread=1 WHERE $condition ");
			$this->db->query("UPDATE {$this->pre}member SET message=message-$r[num] WHERE username='$this->username' ");
		}
	}

	function restore() {
		if(!$this->itemid) return false;
		$itemids = is_array($this->itemid) ? implode(',', $this->itemid) : intval($this->itemid);
		$result = $this->db->query("SELECT * FROM {$this->pre}message WHERE itemid IN($itemids) ORDER BY itemid DESC");
		while($r = $this->db->fetch_array($result)) {
			if($r['status'] == 4 && $this->username == $r['touser']) {
				$this->db->query("UPDATE {$this->pre}message SET status=3 WHERE itemid='$r[itemid]' ");				
				if(!$r['isread']) $this->db->query("UPDATE {$this->pre}member SET message=message+1 WHERE username='$this->username' ");
			}
		}
	}

	function read() {
		$this->db->query("UPDATE {$this->pre}message SET isread=1 WHERE itemid='$this->itemid' ");
		$this->db->query("UPDATE {$this->pre}member SET message=message-1 WHERE userid='$this->userid' ");
	}

	function feedback($r) {
		global $DT_TIME;
		$r or $r = $this->get_one();
		$message = array();
		$message['typeid'] = 0;
		$message['touser'] = $r['fromuser'];
		$message['title'] = '您的来信 ['.dsubstr($r['title'], 20, '...').'] 已经阅读';
		$message['content'] = $this->username.' 于 <small style="color:blue;">'.timetodate($DT_TIME, 5).' </small> 阅读了您发送的信件<br/><div style="padding:10px;margin:10px 10px 0 0;border-left:#E5EBFA 3px solid;line-height:180%;background:#FFFFFF;"><strong>标题:</strong>'.$r['title'].'<br/><strong>时间:</strong>'.timetodate($r['addtime'], 5).'<br/><strong>原文:</strong><br/>'.$r['content'].'</div>';
		$this->send($message);
	}

	function fix_message() {
		global $_username, $_message;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}message WHERE touser='$_username' AND status=3 AND isread=0");
		$num = intval($r['num']);
		if($_message != $num) {
			$this->db->query("UPDATE {$this->pre}member SET message='$num' WHERE username='$_username'");
			dheader('message.php');
		}
	}

	function _is_message($message) {
		if(!is_array($message)) return false;
		if($message['type']) {
			if(!isset($message['groupids']) || !is_array($message['groupids']) || empty($message['groupids'])) return $this->_('请选择会员组');
		} else {
			if(!$message['touser']) return $this->_('收件人不能为空');
		}
		if(!$message['title'] || !$message['content']) return $this->_('标题或内容不能为空');
		return true;
	}

	function _send($message) {
		global $DT_TIME;
		if(!$this->_is_message($message)) return false;
		if($message['type']) {
			$message['groupids'] = implode(',', $message['groupids']);
			$this->db->query("INSERT INTO {$this->pre}message(title,content,fromuser,touser,addtime,status,groupids) values('$message[title]','$message[content]','$this->username','','$DT_TIME','0','$message[groupids]')");
		} else {
			foreach(explode(' ', $message['touser']) as $touser) {
				send_message($touser, $message['title'], $message['content']);
			}
		}
		return true;
	}

	function _edit($message) {
		if(!$this->_is_message($message)) return false;
		$message['groupids'] = implode(',', $message['groupids']);
		$this->db->query("UPDATE {$this->pre}message SET title='$message[title]',content='$message[content]',groupids='$message[groupids]' WHERE itemid='$this->itemid' ");
		return true;
	}

	function _clear($message) {
		if(!in_array($message['status'], array(0, 1, 2, 3 ,4))) return false;
		$status = $message['status'];
		$fromtime = isset($message['fromdate']) && is_date($message['fromdate']) ? strtotime($message['fromdate'].' 0:0:0') : 0;
		$totime = isset($message['todate']) && is_date($message['todate']) ? strtotime($message['todate'].' 23:59:59') : 0;
		$condition = "1";
		if($status) $condition .= " AND status='$status'";
		if($fromtime) $condition .= " AND addtime>'$fromtime'";
		if($totime) $condition .= " AND addtime<'$totime'";
		if(isset($message['isread'])) $condition .= " AND isread=1";
		if(isset($message['username'])) $condition .= " AND touser='$message[username]'";
		$this->db->query("DELETE FROM {$this->pre}message WHERE $condition");
		return $this->db->affected_rows() ? true : $this->_('指定范围暂无信件');
	}

	function _delete($itemid) {
		$this->itemid = $itemid;
		$r = $this->get_one();
		if($r['fromuser']) {
			$userid = get_user($r['fromuser']);
			if($r['content']) delete_local($r['content'], $userid);
		}
		$this->db->query("DELETE FROM {$this->pre}message WHERE itemid='$itemid' ");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>