<?php 
defined('IN_DESTOON') or exit('Access Denied');
class job {
	var $moduleid;
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $text_data;
	var $errmsg = errmsg;

    function job($moduleid) {
		global $db, $table, $table_data, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $table;
		$this->table_data = $table_data;
		$this->text_data = $MOD['text_data'];
		$this->db = &$db;
		$this->fields = array('catid','areaid','typeid','level','title','style','fee','introduce','department','total','minsalary','maxsalary','type','gender','marriage','education','minage','maxage','experience','keyword','status','username','truename','telephone', 'mobile','address','email','msn','qq','totime','editor','addtime','adddate','edittime','editdate','ip','template','linkurl','note','company');
    }

	function pass($post) {
		global $DT_TIME, $MOD;
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_('请选择所属行业');
		if(!$post['title']) return $this->_('请填写职位名称');
		if(!$post['areaid']) return $this->_('请选择工作地区');
		if(!$post['content']) return $this->_('请填写具体要求');
		if($post['totime']) {
			if(!is_date($post['totime'])) return $this->_('请选择信息过期日期');
			$totime = strtotime($post['totime'].' 23:59:59');
			if($totime < $DT_TIME) return $this->_('信息过期时间必须在当前时间之后');
		}
		if(!$post['truename']) return $this->_('请填写联系人');
		if(!$post['telephone']) return $this->_('请填写联系电话');
		if(!is_email(trim($post['email']))) return $this->_('请填写电子邮件');
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $DT_IP, $CATEGORY, $TYPE, $_username, $_userid;
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = $DT_TIME;
		$post['ip'] = $DT_IP;
		$post['editdate'] = timetodate($post['edittime'], 3);
		$post['totime'] = $post['totime'] ? strtotime($post['totime'].' 23:59:59') : 0;
		$post['fee'] = dround($post['fee']);
		$post['total'] = intval($post['total']);
		$post['email'] = trim($post['email']);
		$post['minsalary'] = intval($post['minsalary']);
		$post['maxsalary'] = intval($post['maxsalary']);
		$post['type'] = intval($post['type']);
		$post['gender'] = intval($post['gender']);
		$post['education'] = intval($post['education']);
		$post['experience'] = intval($post['experience']);
		$post['minage'] = intval($post['minage']);
		$post['maxage'] = intval($post['maxage']);
		$post['content'] = stripslashes($post['content']);
		//clear link
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		//save pictures
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		//get introduce
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		//make keyword
		$post['keyword'] = addslashes($post['title'].','.$post['department'].','.strip_tags(cat_pos($post['catid'], ',')).strip_tags(area_pos($post['areaid'], ',')));
		//clear uploads
		clear_upload($post['content'].'etc');
		if($this->itemid) {
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
		} else {
			$CAT = cache_read('category_'.$post['catid'].'.php');
			if(!$post['template'] && $CAT['show_template']) $post['template'] = $CAT['show_template'];
		}
		if(!defined('DT_ADMIN')) {
			$content = $post['content'];
			unset($post['content']);
			$post = dhtmlspecialchars($post);
			$post['content'] = $content;
		}
		$post['content'] = addslashes($post['content']);
		return $post;
	}

	function get_one() {
        return $this->db->get_one("SELECT * FROM {$this->table} a,{$this->table_data} c WHERE a.itemid=c.itemid and a.itemid='$this->itemid' limit 0,1");
	}

	function get_list($condition = 'status=3', $order = 'edittime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $CATEGORY, $items;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
		$items = $r['num'];
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['title'] = set_style($r['title'], $r['style']);
			$r['userurl'] = userurl($r['username']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$r['parentid'] = $CATEGORY[$r['catid']]['parentid'] ? $CATEGORY[$r['catid']]['parentid'] : $r['catid'];
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		$this->db->query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = $this->db->insert_id();
		$this->db->query("INSERT INTO {$this->table_data} (itemid,content) VALUES ('$this->itemid', '$post[content]')");
		$this->update($this->itemid, $post, $post['content']);
		if($post['status'] > 2) $this->tohtml($this->itemid, $post['catid']);
		return $this->itemid;
	}

	function edit($post) {
		$this->delete($this->itemid, false);
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
	    $this->db->query("UPDATE {$this->table_data} SET content='$post[content]' WHERE itemid=$this->itemid");
		$this->update($this->itemid, $post, $post['content']);
		if($post['status'] > 2) $this->tohtml($this->itemid, $post['catid']);
		return true;
	}

	function tohtml($itemid = 0, $catid = 0) {
		global $module, $MOD, $DT;
		if($MOD['show_html'] && $itemid) tohtml('show', $module, "itemid=$itemid");
		if($MOD['list_html'] && $catid) tohtml('list', $module, "catid=$catid&fid=1&num=3");
	}

	function update($itemid, $r = array(), $content = '') {
		global $DT_PRE;
		$r or $r = $this->db->get_one("SELECT catid,addtime,username,keyword FROM {$this->table} WHERE itemid=$itemid");
		$linkurl = itemurl($itemid, $r['catid'], $r['addtime']);
		$sql = "linkurl='$linkurl'";
		if($r['username']) {
			$m = userinfo($r['username']);
			if($m) $sql .= ",company='$m[company]',vip='$m[vip]',validated='$m[validated]'";
		}
		$this->db->query("UPDATE {$this->table} SET $sql WHERE itemid=$itemid");
		if($this->text_data) {
			if(!$content) {
				$content = $this->db->get_one("SELECT content FROM {$this->table_data} WHERE itemid=$itemid");
				$content = $content['content'];
			}
			text_write($itemid, $this->moduleid, $content);
		} else {
			text_delete($itemid, $this->moduleid);
		}
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			$this->delete($itemid, false);
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $CFG, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) {
				$this->delete($v, $all);
			}
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			if($MOD['show_html']) {
				$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$r['linkurl'];
				if(is_file($_file)) unlink($_file);
				$i = 1;
				while($i) {
					$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($itemid, $r['catid'], $r['addtime'], $i);
					if(is_file($_file)) {
						unlink($_file);
						$i++;
					} else {
						break;
					}
				}
			}
			if($all) {
				$userid = get_user($r['username']);
				if($r['content']) delete_local($r['content'], $userid);
				$this->db->query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$this->db->query("DELETE FROM {$this->table_data} WHERE itemid=$itemid");
				if($this->text_data) text_delete($this->itemid, $this->moduleid);
			}
		}
	}

	function check($itemid) {
		global $_username, $DT_TIME;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=3,editor='$_username' WHERE itemid=$itemid");
			$this->tohtml($itemid);
			return true;
		}
	}

	function reject($itemid) {
		global $_username, $DT_TIME;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=1,editor='$_username' WHERE itemid=$itemid");
			return true;
		}
	}

	function expire($condition = '') {
		global $DT_TIME;
		$this->db->query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime>0 AND totime<$DT_TIME $condition");
	}

	function clear($condition = 'status=0') {		
		$result = $this->db->query("SELECT itemid FROM {$this->table} WHERE $condition ");
		while($r = $this->db->fetch_array($result)) {
			$this->delete($r['itemid']);
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$this->db->query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function refresh($itemid) {
		global $DT_TIME;
		$editdate = timetodate($DT_TIME, 3);
		$this->db->query("UPDATE {$this->table} SET edittime='$DT_TIME',editdate='$editdate' WHERE itemid='$itemid'");
	}

	function _update($username) {
		global $DT_TIME, $DT_PRE;
		$this->db->query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime>0 AND totime<$DT_TIME AND username='$username'");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>