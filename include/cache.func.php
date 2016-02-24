<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function cache_all() {
	cache_module();
	cache_area();
	cache_category();
	cache_fields();
	cache_group();
	cache_pay();
	cache_type();
	cache_keylink();
	return true;
}

function cache_pay() {
	global $db, $DT_PRE;
	$setting = array();
	$query = $db->query("SELECT * FROM {$DT_PRE}setting WHERE item LIKE '%pay-%'");
	while($r = $db->fetch_array($query)) {
		if(substr($r['item'], 0, 4) == 'pay-') {
			$setting[substr($r['item'], 4)][$r['item_key']] = $r['item_value'];
		}
	}
	//Order
	$pay = array();
	$pay['chinabank'] = $setting['chinabank'];
	$pay['alipay'] = $setting['alipay'];
	$pay['tenpay'] = $setting['tenpay'];
	//$pay['paypal'] = $setting['paypal'];
	cache_write('pay.php', $pay);
}

function cache_module($moduleid = 0) {
	global $db, $DT_PRE;
	if($moduleid) {
		$r = $db->get_one("SELECT * FROM {$DT_PRE}module WHERE disabled=0 AND moduleid='$moduleid' ");
		$setting = array();
		$setting = get_setting($moduleid);
		$setting['moduleid'] = $moduleid;
		$setting['name'] = $r['name'];
		$setting['moduledir'] = $r['moduledir'];
		$setting['ismenu'] = $r['ismenu'];
		$setting['domain'] = $r['domain'];
		$setting['linkurl'] = $r['linkurl'];
		if(isset($setting['seo_title_index'])) $setting['seo_index'] = seo_title($setting['seo_title_index']);
		if(isset($setting['seo_title_list'])) $setting['seo_list'] = seo_title($setting['seo_title_list']);
		if(isset($setting['seo_title_show'])) $setting['seo_show'] = seo_title($setting['seo_title_show']);
		if(isset($setting['seo_title_search'])) $setting['seo_search'] = seo_title($setting['seo_title_search']);
		cache_write('module-'.$moduleid.'.php', $setting);
		return true;
	} else {
		$query = $db->query("SELECT moduleid,module,name,moduledir,domain,linkurl,style,listorder,islink,ismenu FROM {$DT_PRE}module WHERE disabled=0 ORDER by listorder asc,moduleid desc");
		$CACHE = array();
		$modules = array();
		while($r = $db->fetch_array($query)) {
			if(!$r['islink']) {
				$linkurl = $r['domain'] ? $r['domain'] : linkurl($r['moduledir'].'/');
				if($r['moduleid'] == 1) $linkurl = DT_URL;
				if($linkurl != $r['linkurl']) {
					$r['linkurl'] = $linkurl;
					$db->query("UPDATE {$DT_PRE}module set linkurl='$linkurl' WHERE moduleid='$r[moduleid]' ");
				}
				cache_module($r['moduleid']);
			}
			$modules[$r['moduleid']] = $r;
        }
		$CACHE['module'] = $modules;
		$CACHE['dt'] = cache_read('module-1.php');
		cache_write('module.php', $CACHE);
	}
}

function cache_area() {
	global $db, $DT_PRE;
	$data = array();
    $query = $db->query("SELECT areaid,areaname,parentid,arrparentid,child,arrchildid,listorder FROM {$DT_PRE}area ORDER by listorder,areaid");
    while($r = $db->fetch_array($query)) {
		$areaid = $r['areaid'];
        $data[$areaid] = $r;
    }
	cache_write('area.php', $data);
}

function cache_category($moduleid = 0, $data = array()) {
	global $db, $DT_PRE, $DT, $MODULE;
	if($moduleid) {
		if(!$data) {
			$query = $db->query("SELECT * FROM {$DT_PRE}category WHERE moduleid='$moduleid' ORDER BY listorder,catid");
			while($r = $db->fetch_array($query)) {
				$data[$r['catid']] = $r;
			}
		}
		$mod = cache_read('module-'.$moduleid.'.php');
		foreach($data as $r) {
			$r['linkurl'] = listurl($moduleid, $r['catid'], 0, $data, $mod);
			if($DT['index']) $r['linkurl'] = str_replace($DT['index'].'.'.$DT['file_ext'], '', $r['linkurl']);
			cache_write('category_'.$r['catid'].'.php', $r);
			unset($r['moduleid'], $r['template'], $r['seo_title'], $r['seo_keywords'], $r['seo_description']);
			$data[$r['catid']] = $r;
		};
		cache_write('category-'.$moduleid.'.php', $data);
		//Cache Tree
		if(count($data) < 100) {
			$categorys = array();
			foreach($data as $id=>$cat) {
				$categorys[$id] = array('id'=>$id, 'parentid'=>$cat['parentid'], 'name'=>$cat['catname']);
			}
			require_once DT_ROOT.'/include/tree.class.php';
			$tree = new tree;
			$tree->tree($categorys);
			$content = $tree->get_tree(0, "<option value=\\\"\$id\\\">\$spacer\$name</option>").'</select>';
			cache_write('cattree-'.$moduleid.'.php', $content);
		} else {
			cache_delete('cattree-'.$moduleid.'.php');
		}
	} else {
		foreach($MODULE as $moduleid=>$module) {
			cache_category($moduleid);
		}
	}
}

function cache_fields($tb = '') {
	global $db, $DT_PRE, $DT;
	if($tb) {
		$data = array();
		$query = $db->query("SELECT * FROM {$DT_PRE}fields WHERE tb='$tb' ORDER BY listorder,itemid");
		while($r = $db->fetch_array($query)) {
			$data[$r['itemid']] = $r;
		}
		cache_write('fields-'.$tb.'.php', $data);
	} else {
		$tbs = array();
		$query = $db->query("SELECT * FROM {$DT_PRE}fields");
		while($r = $db->fetch_array($query)) {
			if(isset($tbs[$r['tb']])) continue;
			cache_fields($r['tb']);
			$tbs[$r['tb']] = $r['tb'];
		}
	}
}

function cache_product() {
	global $db, $DT_PRE;
	$data = array();
	$query = $db->query("SELECT pid,title,unit,catid FROM {$DT_PRE}sell_product ORDER BY listorder desc,pid desc");
	while($r = $db->fetch_array($query)) {
		$data[$r['pid']] = $r;
	}
	cache_write('product.php', $data);
}

function cache_quote_product() {
	global $db, $DT_PRE;
	$data = array();
	$query = $db->query("SELECT pid,title,catid FROM {$DT_PRE}quote_product ORDER BY listorder desc,pid desc");
	while($r = $db->fetch_array($query)) {
		$data[$r['pid']] = $r;
	}
	cache_write('quote_product.php', $data);
}

function cache_option($pid = 0) {
	global $db, $DT_PRE;
	if($pid) {
		$data = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}sell_option WHERE pid=$pid ORDER BY listorder DESC,oid DESC ");
		while($r = $db->fetch_array($result)) {
			$data[] = $r;
		}
		cache_write('option-'.$pid.'.php', $data);
	} else {
		$arr = array();
		$result = $db->query("SELECT pid FROM {$DT_PRE}sell_option");
		while($r = $db->fetch_array($result)) {
			if(!in_array($r['pid'], $arr)) {
				$arr[] = $r['pid'];
				cache_option($r['pid']);
			}
		}
	}
}

function cache_group() {
	global $db, $DT_PRE;
	$data = $group = array();
	$query = $db->query("SELECT * FROM {$DT_PRE}group ORDER BY groupid desc");
	while($r = $db->fetch_array($query)) {
		$tmp = array();
		$tmp = get_setting('group-'.$r['groupid']);
		$data[$r['groupid']] = $r;
		if($tmp) {
			foreach($tmp as $k=>$v) {
				$r[$k] = $v;
			}
		}
		cache_write('group-'.$r['groupid'].'.php', $r);
	}
	cache_write('group.php', $data);
}

function cache_type($item = '') {
	global $db, $DT_PRE;
	if($item) {
		$types = array();
		$result = $db->query("SELECT typeid,typename,style FROM {$DT_PRE}type WHERE item='$item' AND cache=1 ORDER BY listorder DESC,typeid DESC ");
		while($r = $db->fetch_array($result)) {
			$types[$r['typeid']] = $r;
		}
		cache_write('type-'.$item.'.php', $types);
		return $types;
	} else {
		$arr = array();
		$result = $db->query("SELECT item FROM {$DT_PRE}type WHERE item!='' AND cache=1 ORDER BY typeid DESC ");
		while($r = $db->fetch_array($result)) {
			if(!in_array($r['item'], $arr)) {
				$arr[] = $r['item'];
				cache_type($r['item']);
			}
		}
	}
}

function cache_bancomment($moduleid = 0) {
	global $db, $DT_PRE, $MODULE;
	if($moduleid) {
		$data = array();
		$result = $db->query("SELECT itemid FROM {$DT_PRE}comment_ban WHERE moduleid='$moduleid' ORDER BY bid DESC ");
		while($r = $db->fetch_array($result)) {
			$data[] = $r['itemid'];
		}
		cache_write('bancomment-'.$moduleid.'.php', $data);
		return $data;
	} else {
		foreach($MODULE as $k=>$v) {
			if($k < 4 || $v['islink']) continue;
			cache_bancomment($k);
		}
	}
}

function cache_keylink($item = '') {
	global $db, $DT_PRE;
	if($item) {
		$keylinks = array();
		$result = $db->query("SELECT title,url FROM {$DT_PRE}keylink WHERE item='$item' ORDER BY listorder DESC,itemid DESC");
		while($r = $db->fetch_array($result)) {
			$keylinks[] = $r;
		}
		cache_write('keylink-'.$item.'.php', $keylinks);
		return $keylinks;
	} else {
		$arr = array();
		$result = $db->query("SELECT item FROM {$DT_PRE}keylink");
		while($r = $db->fetch_array($result)) {
			if(!in_array($r['item'], $arr)) {
				$arr[] = $r['item'];
				cache_keylink($r['item']);
			}
		}
	}
}

function cache_banip() {
	global $db, $DT_PRE, $DT_TIME;
	$data = array();
	$result = $db->query("SELECT ip,totime FROM {$DT_PRE}banip ORDER BY itemid DESC");
	while($r = $db->fetch_array($result)) {
		if($r['totime'] && $r['totime'] < $DT_TIME) continue;
		$data[] = $r;
	}
	cache_write('banip.php', $data);
}

function cache_banword() {
	global $db, $DT_PRE;
	$data = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}banword ORDER BY bid DESC");
	while($r = $db->fetch_array($result)) {
		unset($r['bid']);
		$r['replacefrom'] = str_replace('*', '.*', $r['replacefrom']);
		$data[] = $r;
	}
	cache_write('banword.php', $data);
}

function cache_clear_ad($all = false) {
	global $DT_TIME;
	$globs = glob(CACHE_ROOT.'/htm/*.htm');
	if($globs) {
		foreach($globs as $v) {
			if(strpos($v, 'ad_', basename($v)) === false) continue;
			if($all) {
				unlink($v);
			} else {
				$exptime = intval(substr(file_get_contents($v), 4, 14));
				if($exptime && $DT_TIME > $exptime) unlink($v);
			}
		}
	}
}

function cache_clear_tag($all = false) {
	global $DT_TIME;
	$globs = glob(CACHE_ROOT.'/tag/*.htm');
	if($globs) {
		foreach($globs as $v) {
			if($all) {
				unlink($v);
			} else {
				$exptime = intval(substr(file_get_contents($v), 4, 14));
				if($exptime && $DT_TIME > $exptime) unlink($v);
			}
		}
	}
}

function cache_clear_sql($dir, $all = false) {
	global $DT_TIME;
	if($dir) {
		$globs = glob(CACHE_ROOT.'/sql/'.$dir.'/*.php');
		if($globs) {
			if($globs) {
				foreach($globs as $v) {
					if($all) {
						unlink($v);
					} else {
						$exptime = intval(substr(file_get_contents($v), 8, 18));
						if($exptime && $DT_TIME > $exptime) unlink($v);
					}
				}
			}
		}
	} else {
		cache_clear('php', 'dir', 'sql');
	}
}
?>
