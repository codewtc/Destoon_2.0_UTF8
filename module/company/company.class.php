<?php 
defined('IN_DESTOON') or exit('Access Denied');
class company {
	var $userid;
	var $username;
	var $itemid;
	var $db;
	var $table_member;
	var $table_company;
	var $table_vip;
	var $errmsg = errmsg;

    function company($username = '')	{
		global $db, $DT_PRE, $table_member, $table_company;
        $this->username = $username;
		$this->table_member = $DT_PRE.'member';
		$this->table_company = $DT_PRE.'company';
		$this->table_vip = $DT_PRE.'vip';
		$this->db = &$db;
    }

	function get_one($username = '') {
		$sql = $username ? "m.username='$username'" : "m.userid='$this->userid'";
        return $this->db->get_one("SELECT * FROM {$this->table_member} m,{$this->table_company} c WHERE m.userid=c.userid AND $sql limit 0,1");
	}

	function get_list($condition, $order = 'userid DESC', $cache = '') {
		global $pages, $page, $pagesize, $offset, $items;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table_company} WHERE $condition", $cache);
		$items = $r['num'];
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);		
		$members = array();
		$result = $this->db->query("SELECT * FROM {$this->table_company} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$members[] = $r;
		}
		return $members;
	}

	function update($userid) {
		global $DT, $CFG, $MOD;
		$this->userid = $userid;
		$r = $this->get_one();
		if(!$r) return false;
		$linkurl = userurl($r['username'], '', $r['domain']);
		$keyword = addslashes($r['company'].strip_tags(cat_pos($r['catid'], '')).strip_tags(area_pos($r['areaid'], '')).$r['regcity'].$r['business'].$r['sell'].$r['buy'].$r['mode']);
		if($r['vip']) {
			$vipt = $this->get_vipt($r['username']);
			$vip = $this->get_vip($vipt, $r['vipr']);
			$this->db->query("UPDATE {$this->table_company} SET linkurl='$linkurl',keyword='$keyword',vip='$vip',vipt='$vipt' WHERE userid=$userid");
		} else {
			$this->db->query("UPDATE {$this->table_company} SET linkurl='$linkurl',keyword='$keyword' WHERE userid=$userid");
		}
		return true;
	}

	function get_vipt($username) {
		global $MOD, $GROUP, $DT_TIME, $DT_PRE;
		$GROUP or $GROUP = cache_read('group.php');
		$r = $this->get_one($username);
		$_groupvip = $GROUP[$r['groupid']]['vip'] > $MOD['vip_maxgroupvip'] ? $MOD['vip_maxgroupvip'] : $GROUP[$r['groupid']]['vip'];
		$_cominfo = $r['validated'] ? intval($MOD['vip_cominfo']) : 0;
		$_year = $r['fromtime'] ? (date('Y', $DT_TIME) - date('Y', $r['fromtime']))*$MOD['vip_year'] : 0;
		$_year = $_year > $MOD['vip_maxyear'] ? $MOD['vip_maxyear'] : $_year;
		$m = $this->db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}credit WHERE username='$username' AND status=3");
		$_credit = $m['num'] > 4 ? $MOD['vip_credit'] : 0;
		$total = intval($_groupvip + $_cominfo + $_year + $_credit);
		if($total > 10) $total = 10;
		if($total < 1) $total = 1;
		return $total;
	}

	function get_vip($vipt, $vipr) {
		$vip = intval($vipt + ($vipr));
		if($vip > 10) $vip = 10;
		if($vip < 1) $vip = 1;
		return $vip;
	}

	function vip_edit($vip) {
		global $_username, $DT_TIME;
		if(!is_array($vip)) return false;
		if(!$vip['username']) return $this->_('会员名不能为空');
		$r = $this->get_one($vip['username']);
		if(!$r) return $this->_('会员不存在');
		if($r['groupid'] < 5) return $this->_('该会员所在会员组不能添加');
		if(!$vip['groupid']) return $this->_('请选择会员组');
		if(!$vip['fromtime'] || !is_date($vip['fromtime'])) return $this->_('请选择服务开始日期');
		if(!$vip['totime'] || !is_date($vip['totime'])) return $this->_('请选择服务结束日期');
		if(strtotime($vip['fromtime'].' 0:0:0') > strtotime($vip['totime'].' 23:59:59')) return $this->_('开始日期必须在结束日期之前');
		$vip['fromtime'] = strtotime($vip['fromtime'].' 0:0:0');
		$vip['totime'] = strtotime($vip['totime'].' 23:59:59');
		$vip['validated'] = $vip['validated'] ? 1 : 0;
		$vip['validtime'] = strtotime($vip['validtime']);
		$vip['vipr'] = isset($vip['vipr']) ? $vip['vipr'] : 0;
		$this->db->query("UPDATE {$this->table_company} SET groupid='$vip[groupid]',validated='$vip[validated]',validator='$vip[validator]',validtime='$vip[validtime]',vipr='$vip[vipr]',fromtime='$vip[fromtime]',totime='$vip[totime]' WHERE username='$vip[username]'");
		$vip['vipt'] = $this->get_vipt($vip['username']);
		$vip['vip'] = $this->get_vip($vip['vipt'], $vip['vipr']);
		$this->db->query("UPDATE {$this->table_company} SET vip='$vip[vip]',vipt='$vip[vipt]' WHERE username='$vip[username]'");
		$this->db->query("UPDATE {$this->table_member} SET groupid='$vip[groupid]' WHERE username='$vip[username]'");
		$v = $this->db->get_one("SELECT itemid FROM {$this->table_vip} WHERE username='$vip[username]'");
		if($v) {
			$this->db->query("UPDATE {$this->table_vip} SET note='$vip[note]' WHERE username='$vip[username]'");
		} else {
			$this->db->query("INSERT INTO {$this->table_vip} (content,username,company,addtime,status,editor,edittime,note) VALUES ('系统自动添加', '$vip[username]','$r[company]','$DT_TIME','3','$_username','$DT_TIME','$vip[note]')");
		}
		return true;
	}

	function vip_delete($userid) {
		if(!isset($userid) || !$userid) return false;
		$userids = is_array($userid) ? implode(',', $userid) : intval($userid);
		$this->db->query("UPDATE {$this->table_company} SET groupid=6,vip=0,vipr=0,vipt=0,validated=0,validator='',validtime=0 WHERE userid IN ($userids)");
		$this->db->query("UPDATE {$this->table_member} SET groupid=6 WHERE userid IN ($userids)");
		$this->db->query("DELETE FROM {$this->table_vip} WHERE userid IN ($userids)");
		return true;
	}

	function vedit($vip) {
		global $_username, $DT_TIME;
		if(in_array($vip['status'], array(1, 2, 3))) {
			if($vip['status'] == 3) $this->vip_edit($vip);
			$this->db->query("UPDATE {$this->table_vip} SET status='$vip[status]',editor='$_username',edittime='$DT_TIME',note='$vip[note]' WHERE itemid=$this->itemid");
			return true;
		} else {
			return false;
		}
	}
	
	function vdelete($itemid) {
		if(!isset($itemid) || !$itemid) return false;
		$itemids = is_array($itemid) ? implode(',', $itemid) : intval($itemid);
		$this->db->query("DELETE FROM {$this->table_vip} WHERE itemid IN ($itemids)");
		return $this->db->affected_rows();
	}

	function get_vone() {
        return $this->db->get_one("SELECT * FROM {$this->table_vip} WHERE itemid=$this->itemid limit 0,1");
	}

	function get_vlist($condition, $order = 'itemid DESC') {
		global $pages, $page, $pagesize, $offset;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table_vip} WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);
		$dstatus = array('1'=>'<span style="color:red;">已拒绝</span>', '2'=>'<span style="color:gray;">审核中</span>', '3'=>'<span style="color:green;">已通过</span>');
		$members = array();
		$result = $this->db->query("SELECT * FROM {$this->table_vip} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = $r['edittime'] ? timetodate($r['edittime'], 5) : '-';
			if(!$r['editor']) $r['editor'] = '-';
			$r['status'] = $dstatus[$r['status']];
			$members[] = $r;
		}
		return $members;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>