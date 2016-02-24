<?php 
defined('IN_DESTOON') or exit('Access Denied');
class resume {
	var $moduleid;
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $errmsg = errmsg;

    function resume($moduleid) {
		global $db, $DT_PRE, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $DT_PRE.'resume';
		$this->table_data = $DT_PRE.'resume_data';
		$this->db = &$db;
		$this->fields = array('catid','areaid','level','title','style','fee','introduce','keyword','truename','gender','birthday','age','marriage','height', 'weight','education','school','major','skill','language','minsalary','maxsalary','situation','type','experience','mobile','telephone','address','email','msn','qq','thumb','username','addtime','editor','edittime','ip','template','status','open','note');
    }

	function pass($post) {
		global $DT_TIME, $MOD;
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_('请填写简历名称');
		if(!$post['catid']) return $this->_('请选择求职行业');
		if(!$post['truename']) return $this->_('请填写真实姓名');
		if(!$post['areaid']) return $this->_('请选择居住地区');
		if(intval($post['height']) < 60) return $this->_('请填写身高');
		if(intval($post['weight']) < 20) return $this->_('请填写体重');
		if(!$post['truename']) return $this->_('请填写真实姓名');
		if(intval($post['byear']) > 9999 || intval($post['byear']) < 1900 || date('Y', $DT_TIME) - intval($post['byear']) > 100) return $this->_('请填写正确的出生年份');
		if(!$post['school']) return $this->_('请填写毕业院校');
		if(strlen($post['mobile']) < 7) return $this->_('请填写联系手机');
		if(!is_email(trim($post['email']))) return $this->_('请填写电子邮件');
		if(!$post['content']) return $this->_('请填写自我鉴定');
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $DT_IP, $CATEGORY, $TYPE, $_username, $_userid, $GENDER, $MARRIAGE, $EDUCATION;
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['edittime'] = $DT_TIME;
		$post['ip'] = $DT_IP;
		$post['fee'] = dround($post['fee']);
		$post['birthday'] = intval($post['byear']).'-'.intval($post['bmonth']).'-'.intval($post['bday']);
		$post['age'] = date('Y', $DT_TIME) - intval($post['byear']);
		$post['minsalary'] = intval($post['minsalary']);
		$post['maxsalary'] = intval($post['maxsalary']);
		$post['type'] = intval($post['type']);
		$post['marriage'] = intval($post['marriage']);
		$post['height'] = intval($post['height']);
		$post['height'] = intval($post['height']);
		$post['gender'] = intval($post['gender']);
		$post['education'] = intval($post['education']);
		$post['experience'] = intval($post['experience']);
		$post['situation'] = intval($post['situation']);
		$post['email'] = trim($post['email']);
		$post['status'] = intval($post['status']);
		$post['open'] = intval($post['open']);
		$post['content'] = stripslashes($post['content']);
		//clear link
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		//save pictures
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		//get introduce
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		//make keyword
		$post['keyword'] = addslashes($post['title'].','.$post['truename'].','.$post['major'].','.strip_tags(cat_pos($post['catid'], ',')).strip_tags(area_pos($post['areaid'], ',')).','.$post['skill'].','.$post['language'].','.$post['school'].','.$GENDER[$post['gender']].','.$MARRIAGE[$post['marriage']].','.$EDUCATION[$post['education']]);
		//clear uploads
		clear_upload($post['content'].$post['thumb'].'etc');
		if($this->itemid) {
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
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
        return $this->db->get_one("SELECT * FROM {$this->table} a,{$this->table_data} c WHERE a.itemid=c.itemid and a.itemid='$this->itemid' LIMIT 0,1");
	}

	function get_list($condition = 'status=3', $order = 'edittime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $CATEGORY;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
		$pages = defined('CATID') ? listpages(1, CATID, $r['num'], $page, $pagesize, 10, $MOD['linkurl']) : pages($r['num'], $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['title'] = set_style($r['title'], $r['style']);
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
		$linkurl = rewrite('resume.php?itemid='.$itemid);
		$this->db->query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$itemid");
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
			if($all) {
				$userid = get_user($r['username']);
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['content']) delete_local($r['content'], $userid);
				$this->db->query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$this->db->query("DELETE FROM {$this->table_data} WHERE itemid=$itemid");
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
		$this->db->query("UPDATE {$this->table} SET edittime='$DT_TIME' WHERE itemid='$itemid'");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>