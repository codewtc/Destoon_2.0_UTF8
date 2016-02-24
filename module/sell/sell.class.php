<?php 
defined('IN_DESTOON') or exit('Access Denied');
class sell {
	var $moduleid;
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $text_data;
	var $errmsg = errmsg;

    function sell($moduleid) {
		global $db, $table, $table_data, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $table;
		$this->table_data = $table_data;
		$this->text_data = $MOD['text_data'];
		$this->db = &$db;
		$this->fields = array('catid','mycatid','areaid','typeid','pid','level','title','style','fee','introduce','model','standard','brand','unit','price','minamount','amount','days','thumb','thumb1','thumb2','tag','keyword','status','username','totime','editor','addtime','adddate','edittime','editdate','ip','template','linkurl','elite','note','company','truename','telephone','mobile','address','email','msn','qq');
    }

	function pass($post) {
		global $DT_TIME, $MOD;
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_('请选择所属行业');
		if(!$post['tag']) return $this->_('请填写产品关键字');
		if(!$post['title']) return $this->_('请填写信息标题');
		if(!$post['content']) return $this->_('请填写详细说明');
		if(!$post['totime'] || !is_date($post['totime'])) return $this->_('请选择信息过期日期');
		$totime = strtotime($post['totime'].' 23:59:59');
		if($totime < $DT_TIME) return $this->_('信息过期时间必须在当前时间之后');
		if($totime > $DT_TIME + $MOD['max_days']*86400 + 86400) return $this->_('信息过期时间超出限制');
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $DT_IP, $CATEGORY, $TYPE, $_username, $_userid;
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = $DT_TIME;
		$post['editdate'] = timetodate($post['edittime'], 3);
		$post['totime'] = strtotime($post['totime'].' 23:59:59');
		$post['ip'] = $DT_IP;
		$post['fee'] = dround($post['fee']);
		$post['price'] = dround($post['price']);
		$post['minamount'] = dround($post['minamount']);
		$post['amount'] = dround($post['amount']);
		$post['mycatid'] = intval($post['mycatid']);
		$post['days'] = intval($post['days']);
		$post['elite'] = $post['elite'] ? 1 : 0;
		$post['content'] = stripslashes($post['content']);
		//clear link
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		//save pictures
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		//get introduce
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		//make keyword
		$post['keyword'] = addslashes($post['tag'].','.$TYPE[$post['typeid']].','.$post['title'].','.strip_tags(cat_pos($post['catid'], ',')));
		//clear uploads
		clear_upload($post['content'].$post['thumb'].$post['thumb1'].$post['thumb2'].'etc');
		if($this->itemid) {
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'">';
			if($post['thumb1']) $new .= '<img src="'.$post['thumb1'].'">';
			if($post['thumb2']) $new .= '<img src="'.$post['thumb2'].'">';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			if($r['thumb1']) $old .= '<img src="'.$r['thumb1'].'">';
			if($r['thumb2']) $old .= '<img src="'.$r['thumb2'].'">';
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
		global $MOD, $pages, $page, $pagesize, $offset, $items;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
		$items = $r['num'];
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['title'] = set_style($r['title'], $r['style']);
			$r['userurl'] = userurl($r['username']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
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
			if($m) $sql .= ",company='$m[company]',vip='$m[vip]',validated='$m[validated]',areaid='$m[areaid]',truename='$m[truename]',telephone='$m[telephone]',mobile='$m[mobile]',address='$m[address]',email='$m[mail]',msn='$m[msn]',qq='$m[qq]'";
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
		global $CFG, $MOD, $DT_PRE;
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
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['thumb1']) delete_upload($r['thumb1'], $userid);
				if($r['thumb2']) delete_upload($r['thumb2'], $userid);
				if($r['content']) delete_local($r['content'], $userid);
				$this->db->query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$this->db->query("DELETE FROM {$this->table_data} WHERE itemid=$itemid");
				if($this->text_data) text_delete($this->itemid, $this->moduleid);
				if($MOD['product_option']) $this->db->query("DELETE FROM {$DT_PRE}sell_value WHERE itemid=$itemid");
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
		$this->db->query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime<$DT_TIME $condition");
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
		$this->db->query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime<$DT_TIME AND username='$username'");
		$m = $this->db->get_one("SELECT c.company,c.areaid,c.vip,m.msn,m.qq FROM {$DT_PRE}company c,{$DT_PRE}member m WHERE m.userid=c.userid AND m.username='$username'");
		$this->db->query("UPDATE {$this->table} SET company='$m[company]',vip='$m[vip]',areaid='$m[areaid]',msn='$m[msn]',qq='$m[qq]' WHERE username='$username'");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>