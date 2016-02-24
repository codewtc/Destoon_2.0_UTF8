<?php 
defined('IN_DESTOON') or exit('Access Denied');
class news {
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $fields;
	var $errmsg = errmsg;

    function news() {
		global $db, $DT_PRE;
		$this->table = $DT_PRE.'news';
		$this->table_data = $DT_PRE.'news_data';
		$this->db = &$db;
		$this->fields = array('title','style','status','username','addtime','editor','edittime','linkurl','note');
    }

	function pass($post) {
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_('请填写新闻标题');
		if(!$post['content']) return $this->_('请填写新闻内容');
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['edittime'] = $DT_TIME;
		//clear uploads
		clear_upload($post['content']);
		if($this->itemid) {
			$post['editor'] = $_username;
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
		return $post;
	}

	function get_one($condition = '') {
        return $this->db->get_one("SELECT * FROM {$this->table} n,{$this->table_data} c WHERE n.itemid=c.itemid AND n.itemid='$this->itemid' $condition limit 0,1");
	}

	function get_list($condition = 'status=3', $order = 'addtime DESC') {
		global $MOD, $pages, $page, $pagesize, $offset;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['title'] = set_style($r['title'], $r['style']);
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
		$this->update($this->itemid);
		return $this->itemid;
	}

	function edit($post) {
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
	    $this->db->query("UPDATE {$this->table_data} SET content='$post[content]' WHERE itemid=$this->itemid");
		$this->update($this->itemid);
		return true;
	}

	function update($itemid) {
		$r = $this->db->get_one("SELECT username FROM {$this->table} WHERE itemid=$itemid");
		$linkurl = userurl($r['username'], 'file=news&itemid='.$itemid); 
		return $this->db->query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$itemid");
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $CFG, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			$userid = get_user($r['username']);
			if($r['content']) delete_local($r['content'], $userid);
			$this->db->query("DELETE FROM {$this->table} WHERE itemid=$itemid");
			$this->db->query("DELETE FROM {$this->table_data} WHERE itemid=$itemid");
		}
	}

	function check($itemid) {
		global $_username, $DT_TIME;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=$DT_TIME WHERE itemid=$itemid");
			return true;
		}
	}

	function reject($itemid) {
		global $_username, $DT_TIME;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=1,editor='$_username',edittime=$DT_TIME WHERE itemid=$itemid");
			return true;
		}
	}

	function clear() {		
		$result = $this->db->query("SELECT itemid FROM {$this->table} WHERE status=0");
		while($r = $this->db->fetch_array($result)) {
			$this->delete($r['itemid']);
		}
	}
	
	function order($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			$this->db->query("UPDATE {$this->table} SET listorder=$v WHERE itemid=$k");
		}
		return true;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>