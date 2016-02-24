<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class admin {
	var $userid;
	var $username;
	var $founderid;
	var $db;
	var $pre;
	var $errmsg = errmsg;

	function admin() {
		global $db, $DT_PRE, $admin, $CFG;
		$this->founderid = $CFG['founderid'];
		$this->pre = $DT_PRE;
		$this->db = &$db;
	}

	function is_member($username) {
		return $this->db->get_one("SELECT userid FROM {$this->pre}member WHERE username='$username' limit 0,1");
	}

	function count_admin() {
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}member WHERE groupid=1 AND level=1 ");
		return $r['num'];
	}

	function set_admin($username, $level) {
		$username = trim($username);
		$r = $this->is_member($username);
		if(!$r) return $this->_('会员不存在');
		$userid = $r['userid'];
		$this->db->query("UPDATE {$this->pre}member SET groupid=1,level=$level WHERE userid='$userid' ");
		$this->db->query("UPDATE {$this->pre}company SET groupid=1 WHERE userid='$userid'");
		return true;

	}

	function move_admin($username) {
		$r = $this->get_one($username);
		if($r && $r['level'] > 0) {			
			if($r['userid'] == $this->founderid) return $this->_('创始人不可改变级别');
			if($r['level'] == 1 && $this->count_admin() < 2) return $this->_('系统最少需要保留一位超级管理员');
			$level = $r['level'] == 1 ? 2 : 1;
			$this->db->query("UPDATE {$this->pre}member SET level=$level WHERE username='$username' ");
			return true;
		} else {
			return $this->_('管理员不存在');
		}
	}

	function delete_admin($username) {
		$r = $this->get_one($username);
		if($r) {
			if($r['userid'] == $this->founderid) return $this->_('创始人不可删除');
			if($r['level'] == 1 && $this->count_admin() < 2) return $this->_('系统最少需要保留一位超级管理员');
			$userid = $r['userid'];
			$this->db->query("UPDATE {$this->pre}member SET groupid=4,level=0 WHERE userid='$userid'");
			$this->db->query("UPDATE {$this->pre}member SET groupid=4,level=0 WHERE userid='$userid'");
			$this->db->query("DELETE FROM {$this->pre}admin WHERE userid='$userid'");
			cache_delete('menu-'.$r['userid'].'.php');
			cache_delete('right-'.$r['userid'].'.php');
			return true;
		} else {
			return $this->_('会员不存在');
		}
	}

	function get_one($user, $type = 1) {
		$fields = $type ? 'username' : 'userid';
        return $this->db->get_one("SELECT * FROM {$this->pre}member WHERE $fields='$user' limit 0,1");
	}

	function get_list() {
		global $pages, $page, $pagesize, $offset, $pagesize;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}member WHERE groupid=1 AND level>0");
		$pages = pages($r['num'], $page, $pagesize);	
		$admins = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}member WHERE groupid=1 AND level>0 ORDER BY level ASC LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['logintime'] = date('Y-m-d H:i', $r['logintime']);
			$r['adminname'] = $r['level'] == 1 ? '超级管理员' : '普通管理员';
			$admins[] = $r;
		}
		return $admins;
	}

	function get_right($userid) {
		$rights = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}admin WHERE userid=$userid AND url='' ORDER BY moduleid DESC,file DESC,adminid DESC ");
		while($r = $this->db->fetch_array($result)) {
			$rights[] = $r;
		}
		return $rights;
	}

	function get_menu($userid) {
		$menus = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}admin WHERE userid=$userid AND url!='' ORDER BY listorder ASC,adminid ASC ");
		while($r = $this->db->fetch_array($result)) {
			$menus[] = $r;
		}
		return $menus;
	}

	function update($userid, $right) {
		$this->add($userid, $right[0]);
		$type = isset($right[0]['title']) ? 0 : 1;
		unset($right[0]);
		foreach($right as $k=>$v) {
			if(isset($v['delete'])) {
				$this->delete($k);
				unset($right[$k]);
			}
		}
		$this->edit($right, $type);
		$this->cache_right($userid);
		$this->cache_menu($userid);
		return true;
	}

	function add($userid, $right) {
		if(isset($right['title'])) {
			if(!$right['title'] || !$right['url']) return false;
		} else {
			$right['moduleid'] = intval($right['moduleid']);
			if(!$right['moduleid']) return false;
			$_right = $this->get_right($userid);			
			foreach($_right as $v) {//module admin
				if($v['file'] == '' && $v['moduleid'] == $right['moduleid']) return false;
			}
			if($right['file']) {//file exists
				foreach($_right as $v) {
					if($v['file'] == $right['file'] && $v['moduleid'] == $right['moduleid']) return false;
				}
			} else {
				unset($right['action'], $right['catid']);
			}
		}
		$right['userid'] = $userid;
		$sql1 = $sql2 = '';
		foreach($right as $k=>$v) {
			$sql1 .= ','.$k;
			$sql2 .= ",'$v'";
		}		
        $sql1 = substr($sql1, 1);
        $sql2 = substr($sql2, 1);
		$this->db->query("INSERT INTO {$this->pre}admin ($sql1) VALUES($sql2)");
	}

	function edit($right, $type = 0) {
		if($type) {
			//when module admin, have all rights
			$moduleids = $adminids = array();
			foreach($right as $k=>$v) {
				if(!$v['file']) { 
					$moduleids[] = $v['moduleid'];
					$adminids[$v['moduleid']] = $k;
					$right[$k]['action'] = $right[$k]['catid'] = '';
				}
			}
			if($moduleids) {
				foreach($right as $k=>$v) {
					if(in_array($v['moduleid'], $moduleids) && !in_array($k, $adminids)) {
						unset($right[$k]);
						$this->delete($k);
					}
				}
			}
		}
		foreach($right as $key=>$value) {
			if(isset($value['title'])) {
				if(!$value['title'] || !$value['url']) continue;
			} else {
				$value['moduleid'] = intval($value['moduleid']);
				if(!$value['moduleid']) continue;
			}
			$sql = '';
			foreach($value as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			$this->db->query("UPDATE {$this->pre}admin SET $sql WHERE adminid='$key'");
		}
	}

	function cache_right($userid) {
		$rights = $this->get_right($userid);
		$right = $moduleids = array();
		foreach($rights as $v) {//get moduleids
			isset($moduleids[$v['moduleid']]) or $moduleids[$v['moduleid']] = $v['moduleid'];
		}
		foreach($moduleids as $m) {//get rights
			foreach($rights as $r) {
				if($r['moduleid'] == $m) {
					$r['file'] = $r['file'] ? $r['file'] : 'NA';
					$right[$m][$r['file']]['action'] = $r['action'] ? explode('|', $r['action']) : '';
					$right[$m][$r['file']]['catid'] = $r['catid'] ? explode('|', $r['catid']) : '';
				}
			}
		}
		foreach($right as $k=>$v) {
			if(isset($v['NA'])) $right[$k] = '';
		}
		foreach($right as $k=>$v) {
			if($v) {
				foreach($v as $i=>$j) {
					if(!$j['action'] && !$j['catid']) $right[$k][$i] = '';
				}
			}
		}
		cache_write('right-'.$userid.'.php', $right);		
	}

	function cache_menu($userid) {
		$menus = $this->get_menu($userid);
		$menu = $r = array();
		foreach($menus as $k=>$v) {
			$r['title'] = $v['title'];
			$r['style'] = $v['style'];
			$r['url'] = $v['url'];
			$menu[] = $r;
		}
		cache_write('menu-'.$userid.'.php', $menu);
	}

	function delete($adminid) {
		$this->db->query("DELETE FROM {$this->pre}admin WHERE adminid=$adminid");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>