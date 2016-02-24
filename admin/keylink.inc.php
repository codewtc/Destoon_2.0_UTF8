<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
isset($item) or msg();
$menus = array();
$do = new keylink;
$do->item = $item;
if($submit) {
	if($do->update($post)) {
		dmsg('更新成功', '?file='.$file.'&item='.$item);
	} else {
		msg($do->errmsg);
	}
} else {
	$lists = $do->get_list();
	include tpl('keylink');
}

class keylink {
	var $item;
	var $db;
	var $pre;
	var $errmsg = errmsg;

	function keylink() {
		global $db, $DT_PRE;
		$this->pre = $DT_PRE;
		$this->db = &$db;
	}

	function get_list() {
		global $pages, $page, $pagesize, $offset, $pagesize;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->pre}keylink WHERE item='$this->item'");
		$pages = pages($r['num'], $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->pre}keylink WHERE item='$this->item' ORDER BY listorder DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$lists[] = $r;
		}
		return $lists;
	}

	function update($post) {
		$this->add($post[0]);
		unset($post[0]);
		foreach($post as $k=>$v) {
			if(isset($v['delete'])) {
				$this->delete($k);
				unset($post[$k]);
			}
		}
		$this->edit($post);
		cache_keylink($this->item);
		return true;
	}

	function add($post) {
		if(!$post['title'] || !$post['url']) return false;
		$post['listorder'] = intval($post['listorder']);
		$this->db->query("INSERT INTO {$this->pre}keylink (listorder,title,url,item) VALUES('$post[listorder]','$post[title]','$post[url]','$this->item')");
	}

	function edit($post) {
		foreach($post as $k=>$v) {
			if(!$v['title'] || !$v['url']) return false;
			$v['listorder'] = intval($v['listorder']);
			$this->db->query("UPDATE {$this->pre}keylink SET listorder='$v[listorder]',title='$v[title]',url='$v[url]' WHERE itemid='$k' AND item='$this->item'");
		}
	}

	function delete($itemid) {
		$this->db->query("DELETE FROM {$this->pre}keylink WHERE itemid=$itemid AND item='$this->item'");
		cache_keylink($this->item);
	}
}
?>