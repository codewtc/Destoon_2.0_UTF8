<?php 
defined('IN_DESTOON') or exit('Access Denied');
class member {
	var $userid;
	var $username;
	var $db;
	var $tb_member;
	var $tb_company;
	var $tb_company_data;
	var $errmsg = errmsg;

    function member()	{
		global $db, $DT_PRE;
		$this->tb_member = $DT_PRE.'member';
		$this->tb_company = $DT_PRE.'company';
		$this->tb_company_data = $DT_PRE.'company_data';
		$this->db = &$db;
    }

	function is_username($username) {
		global $MOD;
		if(!$username) return $this->_('会员登录名不能为空');
		$MOD['minusername'] or $MOD['minusername'] = 4;
		$MOD['maxusername'] or $MOD['maxusername'] = 20;
		if(strlen($username) < $MOD['minusername'] || strlen($username) > $MOD['maxusername']) return $this->_('会员登录名长度应在'.$MOD['minusername'].'-'.$MOD['maxusername'].'之间');
		if(preg_match("/^[0-9]+$/", $username)) return $this->_('用户名不能全为数字');
		if(!preg_match("/^[a-z0-9]+$/", $username)) return $this->_('只能使用小写字母(a-z)、数字(0-9)');
		if($MOD['banusername']) {
			$tmp = explode('|', $MOD['banusername']);
			foreach($tmp as $v) {
				if(strpos($username, $v) !== false) return $this->_('此登录名已经被禁止注册');
			}
		}
		if($this->username_exists($username)) return $this->_('会员登录名已经被注册');
		return true;
	}

	function is_passport($passport) {
		global $MOD;
		$MOD['minusername'] or $MOD['minusername'] = 4;
		$MOD['maxusername'] or $MOD['maxusername'] = 20;
		if(strlen($passport) < $MOD['minusername'] || strlen($passport) > $MOD['maxusername']) return $this->_('通行证长度应在'.$MOD['minusername'].'-'.$MOD['maxusername'].'之间');
		$badwords = array("$","\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
		foreach($badwords as $v) {
			if(strpos($passport, $v) !== false) return $this->_('通行证名不能含有特殊符号');
		}
		if($MOD['banusername']) {
			$tmp = explode('|', $MOD['banusername']);
			foreach($tmp as $v) {
				if(strpos($passport, $v) !== false) return $this->_('此通行证名已经被禁止注册');
			}
		}
		if($this->passport_exists($passport)) return $this->_('通行证名已经被注册');
		return true;
	}

	function is_password($password, $cpassword) {
		global $MOD;
		if(!$password) return $this->_('会员登录密码不能为空');
		if($password != $cpassword) return $this->_('两次输入的密码不一致');
		if(!$MOD['minpassword']) $MOD['minpassword'] = 6;
		if(!$MOD['maxpassword']) $MOD['maxpassword'] = 20;
		if(strlen($password) < $MOD['minpassword'] || strlen($password) > $MOD['maxpassword']) return $this->_('会员登录密码长度应在'.$MOD['minpassword'].'-'.$MOD['maxpassword'].'之间');
		return true;
	}

	function is_payword($password, $cpassword) {
		global $MOD;
		if(!$password) return $this->_('支付密码不能为空');
		if($password != $cpassword) return $this->_('两次输入的密码不一致');
		if(!$MOD['minpassword']) $MOD['minpassword'] = 6;
		if(!$MOD['maxpassword']) $MOD['maxpassword'] = 20;
		if(strlen($password) < $MOD['minpassword'] || strlen($password) > $MOD['maxpassword']) return $this->_('支付密码长度应在'.$MOD['minpassword'].'-'.$MOD['maxpassword'].'之间');
		return true;
	}

	function is_member($member) {
		if(!is_array($member)) return false;
		if($this->userid) {
			if($member['password'] && !$this->is_password($member['password'], $member['cpassword'])) return false;
			if($member['payword'] && !$this->is_payword($member['payword'], $member['cpayword'])) return false;
			if($member['groupid'] > 1 && $member['company'] != $member['truename'].'(个人)') {
				if(empty($member['regyear']) || strlen($member['regyear']) != 4 || !is_numeric($member['regyear'])) return $this->_('请填写公司注册年份');
				if(empty($member['regcity'])) return $this->_('请填写公司注册地');
				if(empty($member['address'])) return $this->_('请填写公司主要经营地点');
				if(strlen($member['telephone']) < 7) return $this->_('请填写公司电话');
				if(char_count($member['introduce']) < 10) return $this->_('公司介绍不能少于10字');
				if(!$member['business']) return $this->_('请填写公司主要经营范围');
			}
		} else {
			if(!$this->is_username($member['username'])) return false;
			if(!$this->is_password($member['password'], $member['cpassword'])) return false;
		}
		if(!$this->is_passport($member['passport'])) return false;
		if(!$member['groupid']) return $this->_('请选择会员组');
		if(empty($member['truename'])) return $this->_('请填写真实姓名');
		if(!is_email(trim($member['email']))) return $this->_('Email格式不正确');
		if($this->email_exists(trim($member['email']))) return $this->_('邮件地址已经存在');
		if(!$member['areaid']) return $this->_('请选择所在地区');
		if($member['groupid'] > 1) {
			if(empty($member['company'])) return $this->_('请填写公司名称');
			if($member['company'] != $member['truename'].'(个人)') {
				if($this->company_exists($member['company'])) return $this->_('公司名称已经存在');
				if(empty($member['type'])) return $this->_('请选择公司类型');
				if(!$member['catid']) return $this->_('请选择公司所属行业');
			}
		}
		if(isset($member['msn']) && $member['msn'] && !is_email(trim($member['msn']))) return $this->_('MSN格式不正确');
		if(isset($member['mail']) && $member['mail'] && !is_email(trim($member['mail']))) return $this->_('公司邮件格式不正确');
		if(isset($member['qq']) && $member['qq'] && !is_numeric($member['qq'])) return $this->_('QQ格式不正确');
		if(isset($member['postcode']) && $member['postcode'] && !is_numeric($member['postcode']))  return $this->_('邮政编码格式不正确');
		return true;
	}

	function set_member($member) {
		global $MOD;
		$member['email'] = trim($member['email']);
		$member['mail'] = isset($member['mail']) ? trim($member['mail']) : '';
		$member['msn'] = isset($member['msn']) ? trim($member['msn']) : '';
		if($this->userid) {
			//clear uploads
			clear_upload($member['thumb'].$member['banner'].$member['introduce']);
			$member['capital'] = isset($member['capital']) ? dround($member['capital']) : '';
			$member['mode'] = (isset($member['mode']) && $member['mode']) ? implode(',', $member['mode']) : '';
			$member['keyword'] = $member['company'].','.strip_tags(cat_pos($member['catid'], ',')).strip_tags(area_pos($member['areaid'], ',')).','.$member['regcity'].','.$member['business'].','.$member['sell'].','.$member['buy'].','.$member['mode'];
			$new = $member['introduce'];
			if($member['thumb']) $new .= '<img src="'.$member['thumb'].'">';
			if($member['banner']) $new .= '<img src="'.$member['banner'].'">';
			$r = $this->db->get_one("SELECT content FROM {$this->tb_company_data} WHERE userid=$this->userid");
			$old = $r['content'];
			$r = $this->get_one();
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			if($r['banner']) $old .= '<img src="'.$r['banner'].'">';
			delete_diff($new, $old);
			$member['content'] = $member['introduce'];
			$member['introduce'] = dsubstr(strip_tags($member['introduce']), $MOD['introduce_length']);
			if(!defined('DT_ADMIN')) {
				$content = $member['content'];
				unset($member['content']);
				$member = dhtmlspecialchars($member);
				$member['content'] = $content;
			}
		}
		return $member;
	}

	function email_exists($email) {
		$condition = "email='$email'";
		if($this->userid) $condition .= " AND userid!=$this->userid";
		return $this->db->get_one("SELECT userid FROM {$this->tb_member} WHERE $condition limit 0,1");
	}

	function username_exists($username) {
		return $this->db->get_one("SELECT userid FROM {$this->tb_member} WHERE username='$username' limit 0,1");
	}

	function company_exists($company) {
		$condition = "company='$company'";
		if($this->userid) $condition .= " AND userid!=$this->userid";
		return $this->db->get_one("SELECT userid FROM {$this->tb_company} WHERE $condition limit 0,1");
	}

	function passport_exists($passport) {
		$condition = "passport='$passport'";
		if($this->userid) $condition .= " AND userid!=$this->userid";
		return $this->db->get_one("SELECT userid FROM {$this->tb_member} WHERE $condition limit 0,1");
	}

	function add($member) {
		global $DT, $DT_TIME, $DT_IP;
		if(!$this->is_member($member)) return false;
		$member = $this->set_member($member);		
		$member['linkurl'] = userurl($member['username']);
		$member['password'] = $member['payword'] = md5(md5($member['password']));
		$member['agent'] = $_SERVER['HTTP_USER_AGENT'];
		$member_fields = array('username','company','passport', 'password','payword','email','gender','truename','mobile','msn','qq','department','career','groupid','edittime','agent');
		$company_fields = array('username','groupid','company','type','catid','areaid', 'mode','capital','regunit','size','regyear','regcity','sell','buy','business','telephone','fax','mail','address','postcode','homepage','introduce','thumb','keyword','linkurl');
		$member_sqlk = $member_sqlv = $company_sqlk = $company_sqlv = '';
		foreach($member as $k=>$v) {
			if(in_array($k, $member_fields)) {$member_sqlk .= ','.$k; $member_sqlv .= ",'$v'";}
			if(in_array($k, $company_fields)) {$company_sqlk .= ','.$k; $company_sqlv .= ",'$v'";}
		}
        $member_sqlk = substr($member_sqlk, 1);
        $member_sqlv = substr($member_sqlv, 1);
        $company_sqlk = substr($company_sqlk, 1);
        $company_sqlv = substr($company_sqlv, 1);
		$this->db->query("INSERT INTO {$this->tb_member} ($member_sqlk,regip,regtime,loginip,logintime)  VALUES ($member_sqlv,'$DT_IP','$DT_TIME','$DT_IP','$DT_TIME')");
		$this->userid = $this->db->insert_id();
		$this->username = $member['username'];
	    $this->db->query("INSERT INTO {$this->tb_company} (userid, $company_sqlk) VALUES ('$this->userid', $company_sqlv)");
	    $this->db->query("INSERT INTO {$this->tb_company_data} (userid, content) VALUES ('$this->userid', '$member[content]')");
		return $this->userid;
	}

	function edit($member)	{
		if(!$this->is_member($member)) return false;
		$member = $this->set_member($member);
		$r = $this->get_one();
		$member['linkurl'] = userurl($r['username'], '', $member['domain']);
		$member_fields = array('company','passport','email','msn','qq','gender','truename','mobile','department','career','groupid', 'edittime');
		$company_fields = array('company','type','areaid', 'catid','business','mode','regyear','regunit','capital','size','regcity','address','postcode','telephone','fax','mail','homepage','sell','buy','introduce','thumb','keyword','banner','css','linkurl','groupid','domain','icp');
		$member_sql = $company_sql = '';
		foreach($member as $k=>$v) {
			if(in_array($k, $member_fields)) $member_sql .= ",$k='$v'";
			if(in_array($k, $company_fields)) $company_sql .= ",$k='$v'";
		}
		if($member['password']) {
			$password = md5(md5($member['password']));
			$member_sql .= ",password='$password'";
		}
		if($member['payword']) {
			$payword = md5(md5($member['payword']));
			$member_sql .= ",payword='$payword'";
		}
        $member_sql = substr($member_sql, 1);
        $company_sql = substr($company_sql, 1);
	    $this->db->query("UPDATE {$this->tb_member} SET $member_sql WHERE userid=$this->userid");
	    $this->db->query("UPDATE {$this->tb_company} SET $company_sql WHERE userid=$this->userid");
	    $this->db->query("UPDATE {$this->tb_company_data} SET content='$member[content]' WHERE userid=$this->userid");
		return true;
	}

	function get_one($username = '') {
		$condition = $username ? "m.username='$username'" : "m.userid='$this->userid'";
        return $this->db->get_one("SELECT * FROM {$this->tb_member} m,{$this->tb_company} c WHERE m.userid=c.userid AND $condition limit 0,1");
	}

	function get_list($condition, $order = 'userid DESC') {
		global $pages, $page, $pagesize, $offset;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->tb_member} WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);
		$members = array();
		$result = $this->db->query("SELECT * FROM {$this->tb_member} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['logindate'] = timetodate($r['logintime'], 5);
			$r['regdate'] = timetodate($r['regtime'], 5);
			$members[] = $r;
		}
		return $members;
	}

	function login($login_username, $login_password, $login_cookietime = 0, $admin = false) {
		global $CFG, $DT_TIME, $DT_IP, $MOD;
		if(!preg_match("/^[a-z0-9]{2,}$/i", $login_username)) return $this->_('用户名格式错误');
		if(!$MOD || !isset($MOD['login_times'])) $MOD = cache_read('module-2.php');
		$login_lock = ($MOD['login_times'] && $MOD['lock_hour']) ? true : false;
		$LOCK = array();
		if($login_lock) {
			$LOCK = cache_read($DT_IP.'.php', 'lock');
			if($LOCK) {
				if($DT_TIME - $LOCK['time'] < $MOD['lock_hour']*3600) {
					if($LOCK['times'] >= $MOD['login_times']) return $this->_('累计'.$MOD['login_times'].'次错误尝试 您在'.$MOD['lock_hour'].'小时内不能登录系统');
				} else {
					$LOCK = array();
					cache_delete($DT_IP.'.php', 'lock');
				}
			}
		}

		$user = $this->db->get_one("SELECT * FROM {$this->tb_member} WHERE username='$login_username' limit 0,1");

		if(!$user) {
			$this->lock($login_lock, $LOCK, $DT_IP, $DT_TIME);
			return $this->_('会员不存在');
		}
		if(!$admin) {
			if($user['password'] != (is_md5($login_password) ? md5($login_password) : md5(md5($login_password)))) {
				$this->lock($login_lock, $LOCK, $DT_IP, $DT_TIME);
				return $this->_('密码错误,请重试');
			}
		}
		if($user['groupid'] == 2) return $this->_('该帐号已被禁止访问');
		if($user['groupid'] == 4) return $this->_('该帐号尚在审核中');

		if($user['vip'] && $user['totime']) {
			if($user['totime'] < $DT_TIME) {//VIP过期
				if($user['company'] == $user['truename'].'(个人)') {
					$user['groupid'] = 5;
				} else {
					$user['groupid'] = 6;
				}
				$this->db->query("UPDATE {$this->tb_company} SET groupid='$user[groupid]',vip=0 WHERE username='$login_username'");
				$this->db->query("UPDATE {$this->tb_member} SET groupid='$user[groupid]' WHERE username='$login_username'");
			}
		}
		$cookietime = $login_cookietime ? $DT_TIME + intval($login_cookietime) : 0;
		$auth = dcrypt($user['userid']."\t".$user['username']."\t".$user['groupid']."\t".$user['password'], 0, md5($CFG['authkey'].$_SERVER['HTTP_USER_AGENT']));
		set_cookie('auth', $auth, $cookietime);
		$this->db->query("UPDATE {$this->tb_member} SET loginip='$DT_IP',logintime=$DT_TIME,logintimes=logintimes+1 WHERE username='$login_username'");
		return $user;
	}

	function lock($login_lock, $LOCK, $DT_IP, $DT_TIME) {
		if($login_lock && $DT_IP) {
			$LOCK['time'] = $DT_TIME;
			$LOCK['times'] = isset($LOCK['times']) ? $LOCK['times']+1 : 1;
			cache_write($DT_IP.'.php', $LOCK, 'lock');
		}
	}

	function logout() {
		set_cookie('auth', '');
		return true;
	}

	function delete($userid) {
		global $DT_PRE, $CFG, $MODULE;
		if(!$userid) return false;
		if(is_array($userid)) {
			if(in_array(1, $userid) || in_array($CFG['founderid'], $userid)) return $this->_('创始人不可删除');
			$userids = implode(',', $userid);
		} else {
			if($userid == 1 || $userid == $CFG['founderid']) return $this->_('创始人不可删除');
			$userids = intval($userid);
		}
		$result = $this->db->query("SELECT username,userid FROM {$this->tb_member} WHERE userid IN ($userids)");
		while($r = $this->db->fetch_array($result)) {
			$userid = $r['userid'];
			$username = $r['username'];
			$this->deluser('member', $userid);
			$this->deluser('company', $userid);
			$this->deluser('company_data', $userid);
			$this->deluser('company_setting', $userid);
			$this->deluser('admin', $userid);
			$this->deluser('favorite', $userid);
			$this->deluser('friend', $userid);

			$this->deluser('ask', $username);
			$this->deluser('comment', $username);
			$this->deluser('credit', $username);
			$this->deluser('finance_card', $username);
			$this->deluser('finance_cash', $username);
			$this->deluser('finance_charge', $username);
			$this->deluser('finance_pay', $username);
			$this->deluser('finance_record', $username);
			$this->deluser('guestbook', $username);
			$this->deluser('job_talent', $username);
			$this->deluser('link', $username);
			$this->deluser('log', $username);
			$this->deluser('login', $username);
			$this->deluser('mail_list', $username);
			$this->deluser('spread', $username);
			$this->deluser('vip', $username);

			$this->deluser('buy', $username, true);
			$this->deluser('exhibit', $username, true);
			$this->deluser('job', $username, true);
			$this->deluser('news', $username, true);
			$this->deluser('quote', $username, true);
			$this->deluser('resume', $username, true);
			$this->deluser('sell', $username, true);
			foreach($MODULE as $m) {
				if(in_array($m['module'], array('article', 'info'))) $this->deluser($m['module'].'_'.$m['moduleid'], $username, true);
			}

			$this->db->query("DELETE FROM {$DT_PRE}finance_trade WHERE buyer='$username'");
			$this->db->query("DELETE FROM {$DT_PRE}finance_trade WHERE seller='$username'");
			$this->db->query("DELETE FROM {$DT_PRE}job_apply WHERE apply_username='$username'");
			$this->db->query("DELETE FROM {$DT_PRE}message WHERE fromuser='$username'");
			$this->db->query("DELETE FROM {$DT_PRE}message WHERE touser='$username'");
		}
		return true;
	}

	function deluser($table, $user, $data = false) {
		global $DT_PRE;
		$fields = is_numeric($user) ? 'userid' : 'username';
		if($data) {
			$result = $this->db->query("SELECT itemid FROM {$DT_PRE}{$table} WHERE `$fields`='$user'");
			while($r = $this->db->fetch_array($result)) {
				$itemid = $r['itemid'];
				$this->db->query("DELETE FROM {$DT_PRE}{$table} WHERE itemid='$itemid'");
				$table_data = strpos($table, '_') === false ? $table.'_data' : str_replace('_', '_data_', $table);
				$this->db->query("DELETE FROM {$DT_PRE}{$table_data} WHERE itemid='$itemid'");
			}
		} else {
			$this->db->query("DELETE FROM {$DT_PRE}{$table} WHERE `$fields`='$user'");
		}
	}

	function move($userid, $groupid) {
		global $CFG;
		if(!isset($userid) || !$userid || !$groupid) return false;
		$userids = is_array($userid) ? implode(',', $userid) : intval($userid);
		if(is_array($userid)) {
			if(in_array(1, $userid) || in_array($CFG['founderid'], $userid)) return $this->_('创始人不可移动');
			$userids = implode(',', $userid);
		} else {
			if($userid == 1 || $userid == $CFG['founderid']) return $this->_('创始人不可移动');
			$userids = intval($userid);
		}
		$this->db->query("UPDATE {$this->tb_member} SET groupid='$groupid' WHERE userid IN ($userids)");
		$this->db->query("UPDATE {$this->tb_company} SET groupid='$groupid' WHERE userid IN ($userids)");
		return true;
	}

	function check($userid) {
		if(is_array($userid)) {
			foreach($userid as $v) { $this->check($v); }
		} else {
			$this->userid = $usreid;
			$user = $this->get_one();
			$groupid = strpos($user['company'], '(个人)') === false ? 6 : 5;
			$this->db->query("UPDATE {$this->tb_member} SET groupid=$groupid WHERE userid=$userid");
			$this->db->query("UPDATE {$this->tb_company} SET groupid=$groupid WHERE userid=$userid");
			return true;
		}
	}

	function mk_auth($username) {
		global $DT_TIME;
		$auth = strtoupper(md5($username.random(10)));
	    $this->db->query("UPDATE {$this->tb_member} SET auth='$auth',authtime='$DT_TIME' WHERE username='$username'");
		return $auth;
	}

	function ck_auth($auth) {
		global $MOD, $DT_TIME;
        $r = $this->db->get_one("SELECT auth,authtime,username FROM {$this->tb_member} WHERE auth='$auth'");
		if($r) {
			if($MOD['auth_days'] && $DT_TIME - $r['authtime'] > $MOD['auth_days']*86400) return '';
			return $r['username'];
		} else {
			return '';
		}
	}

	function login_log($username, $password, $admin = 0, $message = '成功') {
		global $DT_PRE, $DT_TIME, $DT_IP;
		$password = is_md5($password) ? md5($password) : md5(md5($password));
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if($message == '成功') cache_delete($DT_IP.'.php', 'lock');
		$this->db->query("INSERT INTO {$DT_PRE}login (username,password,admin,loginip,logintime,message,agent) VALUES ('$username','$password','$admin','$DT_IP','$DT_TIME','$message','$agent')");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>