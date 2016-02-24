<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$menus = array();
$do = new banword;
if($submit) {
	$do->update($post);
	dmsg('更新成功', '?file='.$file.'&item='.$item);
} else {
	$lists = $do->get_list();
	include tpl('banword');
}

class banword {
	var $db;
	var $table;

	function banword() {
		global $db, $DT_PRE;
		$this->table = $DT_PRE.'banword';
		$this->db = &$db;
	}

	function get_list() {
		global $pages, $page, $pagesize, $offset, $pagesize;
		$pages = pages($this->db->counter($this->table), $page, $pagesize);
		$lists = array();
		$result = $this->db->query("SELECT * FROM {$this->table} ORDER BY bid DESC LIMIT $offset,$pagesize");
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
		cache_banword();
	}

	function add($post) {
		if(!$post['replacefrom']) return false;
		$post['deny'] = $post['deny'] ? 1 : 0;
		$this->db->query("INSERT INTO {$this->table} (replacefrom,replaceto,deny) VALUES('$post[replacefrom]','$post[replaceto]','$post[deny]')");
	}

	function edit($post) {
		foreach($post as $k=>$v) {
			if(!$v['replacefrom']) return false;
			$v['deny'] = $v['deny'] ? 1 : 0;
			$this->db->query("UPDATE {$this->table} SET replacefrom='$v[replacefrom]',replaceto='$v[replaceto]',deny='$v[deny]' WHERE bid='$k'");
		}
	}

	function delete($bid) {
		$this->db->query("DELETE FROM {$this->table} WHERE bid=$bid");
	}
}
?>