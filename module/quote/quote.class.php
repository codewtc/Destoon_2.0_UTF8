<?php
defined('IN_DESTOON') or exit('Access Denied');
class quote {
	var $moduleid;
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $text_data;
	var $fields;
	var $errmsg = errmsg;

    function quote($moduleid) {
		global $db, $table, $table_data, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $table;
		$this->table_data = $table_data;
		$this->text_data = $MOD['text_data'];
		$this->db = &$db;
		$this->fields = array('catid','pid','level','title','style','fee','introduce','thumb','tag','keyword','status','username','addtime','adddate', 'editor','edittime','ip','template', 'linkurl','note');
    }

	function pass($post) {
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_('请选择分类');
		if(!$post['title']) return $this->_('请填写标题');
		if(!$post['content']) return $this->_('请填写内容');
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $DT_IP, $CATEGORY, $QP, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = $DT_TIME;
		$post['ip'] = $DT_IP;
		$post['pid'] = intval($post['pid']);
		$post['fee'] = dround($post['fee']);
		$post['content'] = stripslashes($post['content']);
		if($post['tag'] && !$post['pid']) {
			foreach($QP as $v) {
				if($v['title'] == $post['tag']) {
					$post[$pid] = $v['pid'];
					break;
				}
			}
		}
		//clear link
		if($post['content'] && isset($post['clear_link'])) $post['content'] = clear_link($post['content']);
		//save pictures
		if($post['content'] && isset($post['save_remotepic'])) $post['content'] = save_remote($post['content']);
		//save thumb
		if($post['content'] && $post['thumb_no'] && !$post['thumb']) $post['thumb'] = save_thumb($post['content'], $post['thumb_no'], $MOD['thumb_width'], $MOD['thumb_height']);
		//get introduce
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		//make keyword
		$post['keyword'] = addslashes($post['tag'].','.$post['title'].','.strip_tags(cat_pos($post['catid'], ',')));
		//clear uploads
		clear_upload($post['content'].$post['thumb']);
		if($this->itemid) {
			$post['editor'] = $_username;
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'">';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			delete_diff($new, $old);
		} else {
			$post['username'] = $post['editor'] = $_username;
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

	function get_list($condition = 'status=3', $order = 'addtime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $items;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
		$items = $r['num'];
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['title'] = set_style($r['title'], $r['style']);
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
		if($post['status'] == 3) $this->tohtml($this->itemid, $post['catid']);
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
		if($post['status'] == 3) $this->tohtml($this->itemid, $post['catid']);
		return true;
	}

	function tohtml($itemid = 0, $catid = 0) {
		global $module, $MOD;
		if($MOD['show_html'] && $itemid) tohtml('show', $module, "itemid=$itemid");
		if($MOD['list_html'] && $catid) tohtml('list', $module, "catid=$catid&fid=1&num=3");
		if($MOD['index_html']) tohtml('index', $module);
	}

	function update($itemid, $r = array(), $content = '') {
		$r or $r = $this->db->get_one("SELECT catid,addtime FROM {$this->table} WHERE itemid=$itemid");
		$linkurl = itemurl($itemid, $r['catid'], $r['addtime']);
		$this->db->query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$itemid");
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
				if($r['thumb']) delete_upload($r['thumb'], $userid);
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

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>