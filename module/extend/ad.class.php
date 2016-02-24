<?php 
defined('IN_DESTOON') or exit('Access Denied');
class ad {
	var $aid;
	var $pid;
	var $db;
	var $table;
	var $table_place;
	var $errmsg = errmsg;

    function ad() {
		global $db, $DT_PRE;
		$this->table = $DT_PRE.'ad';
		$this->table_place = $DT_PRE.'ad_place';
		$this->db = &$db;
    }

	function is_place($place) {
		if(!is_array($place)) return false;
		if(!$place['name']) return $this->_('请填写广告位名称');
		if($place['typeid'] == 3 || $place['typeid'] == 4 || $place['typeid'] == 6) {
			if(!$place['width']) return $this->_('请填写宽度');
			if(!$place['height']) return $this->_('请填写高度');
		}
		if($place['typeid'] == 5 && !$place['moduleid']) return $this->_('请选择模块');
		return true;
	}

	function set_place($place) {
		global $DT_TIME, $_username;
		if(!$this->pid) $place['addtime'] = $DT_TIME;
		$place['edittime'] = $DT_TIME;
		$place['editor'] = $_username;
		$place['width'] = intval($place['width']);
		$place['height'] = intval($place['height']);
		return $place;
	}

	function add_place($place) {
		$place = $this->set_place($place);
		$sqlk = $sqlv = '';
		foreach($place as $k=>$v) {
			$sqlk .= ','.$k; $sqlv .= ",'$v'";
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		$this->db->query("INSERT INTO {$this->table_place} ($sqlk) VALUES ($sqlv)");
		$this->pid = $this->db->insert_id();
		return $this->pid;
	}
	
	function edit_place($place) {
		$place = $this->set_place($place);
		$sql = '';
		foreach($place as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table_place} SET $sql WHERE pid=$this->pid");
		return true;
	}

	function get_one_place() {
        return $this->db->get_one("SELECT * FROM {$this->table_place} WHERE pid='$this->pid' limit 0,1");
	}

	function get_list_place($condition = '1', $order = 'listorder DESC,pid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $DT_TIME;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table_place} WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);
		$extendurl = extendurl('ad');
		$ads = array();
		$result = $this->db->query("SELECT * FROM {$this->table_place} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['name'] = set_style($r['name'], $r['style']);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['width'] or $r['width'] = '--';
			$r['height'] or $r['height'] = '--';
			$r['price'] or $r['price'] = '待议';
			$r['typename'] = $TYPE[$r['typeid']];
			$r['typeurl'] = $extendurl.rewrite('index.php?typeid='.$r['typeid']);
			$ads[] = $r;
		}
		return $ads;
	}

	function order_place($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			$this->db->query("UPDATE {$this->table_place} SET listorder=$v WHERE pid=$k");
		}
		return true;
	}

	function delete_place($pid) {
		global $CFG;
		if(is_array($pid)) {
			foreach($pid as $v) { 
				$this->delete_place($v); 
			}
		} else {
			$this->db->query("DELETE FROM {$this->table_place} WHERE pid=$pid");
			@unlink(CACHE_ROOT.'/htm/ad_'.$pid.'.htm');
			$result = $this->db->query("SELECT aid FROM {$this->table} WHERE pid=$pid ORDER BY aid DESC");
			while($r = $this->db->fetch_array($result)) {
				$this->delete($r['aid']);
			}
		}
	}

	function is_ad($ad) {
		if(!is_array($ad)) return false;
		if(!$ad['title']) return $this->_('请填写广告名称');
		if(!$ad['fromtime'] || !is_date($ad['fromtime'])) return $this->_('请选择广告开始日期');
		if(!$ad['totime'] || !is_date($ad['totime'])) return $this->_('请选择广告结束日期');
		if(strtotime($ad['fromtime'].' 0:0:0') > strtotime($ad['totime'].' 23:59:59')) return $this->_('开始日期必须在结束日期之前');
		if($ad['typeid'] == 1) {
			if(!$ad['code']) return $this->_('请填写广告代码');
		} else if($ad['typeid'] == 2) {
			if(!$ad['text_name']) return $this->_('请填写链接文字');
			if(!$ad['text_url']) return $this->_('请填写链接地址');
		} else if($ad['typeid'] == 3) {
			if(!$ad['image_src']) return $this->_('请填写图片地址');
		} else if($ad['typeid'] == 4) {
			if(!$ad['flash_src']) return $this->_('请填写FLASH地址');
		}
		return true;
	}

	function set_ad($ad) {
		global $DT_TIME, $_username;
		if(!$this->aid) $ad['addtime'] = $DT_TIME;
		$ad['edittime'] = $DT_TIME;
		$ad['editor'] = $_username;
		$ad['fromtime'] = strtotime($ad['fromtime'].' 0:0:0');
		$ad['totime'] = strtotime($ad['totime'].' 23:59:59');
		$ad['username'] or $ad['username'] = $_username;
		$ad['url'] = '';
		if($ad['typeid'] == 2) {
			$ad['url'] = $ad['text_url'];
		} else if($ad['typeid'] == 3) {
			$ad['url'] = $ad['image_url'];
		} else if($ad['typeid'] == 4) {
			$ad['url'] = $ad['flash_url'];
		}
		clear_upload($ad['image_src'].$ad['flash_src'].$ad['code']);
		return $ad;
	}

	function get_one() {
        return $this->db->get_one("SELECT * FROM {$this->table} WHERE aid='$this->aid' limit 0,1");
	}

	function get_list($condition = '1', $order = 'fromtime DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $DT_TIME;
		$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$ads = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['fromdate'] = timetodate($r['fromtime'], 3);
			$r['todate'] = timetodate($r['totime'], 3);
			$r['status'] = '';
			if($r['totime'] < $DT_TIME) {
				$r['status'] = '<span style="color:red;">已过期</span>';
			} else if($r['fromtime'] > $DT_TIME) {
				$r['status'] = '<span style="color:#888888;">未开始</span>';
			} else {
				$r['status'] = '<span style="color:blue;">投放中</span>';
			}
			$ads[] = $r;
		}
		return $ads;
	}

	function add($ad) {
		$ad = $this->set_ad($ad);
		$sqlk = $sqlv = '';
		foreach($ad as $k=>$v) {
			$sqlk .= ','.$k; $sqlv .= ",'$v'";
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		$this->db->query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->aid = $this->db->insert_id();
		$this->db->query("UPDATE {$this->table_place} SET ads=ads+1 WHERE pid='$ad[pid]'");
		return $this->aid;
	}

	function edit($ad) {
		$ad = $this->set_ad($ad);
		$sql = '';
		foreach($ad as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table} SET $sql WHERE aid=$this->aid");
		return true;
	}

	function delete($aid) {
		if(is_array($aid)) {
			foreach($aid as $v) { 
				$this->delete($v); 
			}
		} else {
			$this->aid = $aid;
			$r = $this->get_one();
			if($r['key_moduleid']) {
				if($r['key_word']) {
					$filename = 'ad_m'.$r['key_moduleid'].'_k'.urlencode($r['key_word']).'.htm';
				} else if($r['key_catid']) {
					$filename = 'ad_m'.$r['key_moduleid'].'_c'.$r['key_catid'].'.htm';
				} else {
					$filename = 'ad_m'.$r['key_moduleid'].'.htm';
				}
				@unlink(CACHE_ROOT.'/htm/'.$filename);
			}
			$userid = get_user($r['username']);
			if($r['image']) delete_upload($r['image'], $userid);
			if($r['flash']) delete_upload($r['flash'], $userid);
			$this->db->query("DELETE FROM {$this->table} WHERE aid=$aid");
			$this->db->query("UPDATE {$this->table_place} SET ads=ads-1 WHERE pid='$r[pid]'");
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>