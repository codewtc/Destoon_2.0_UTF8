<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function article_pages($itemid, $catid, $addtime, $total, $page = 1) {
	global $MOD;
	$pages = '';
	$demo_url = $MOD['linkurl'].itemurl($itemid, $catid, $addtime, '{destoon_page}');
	$_page = $page <= 1 ? $total : ($page - 1);
	$url = str_replace('{destoon_page}', $_page, $demo_url);
	$pages .= '<a href="'.$url.'" id="destoon_previous" title="上一页(支持左方向键)">&nbsp;&#171;&nbsp;</a> ';
	for($_page = 1; $_page <= $total; $_page++) {
		$url = str_replace('{destoon_page}', $_page, $demo_url);
		$pages .= $page == $_page ? '<strong>&nbsp;'.$_page.'&nbsp;</strong> ' : ' <a href="'.$url.'">&nbsp;'.$_page.'&nbsp;</a>  ';
	}
	$_page = $page >= $total ? 1 : $page + 1;
	$url = str_replace('{destoon_page}', $_page, $demo_url);
	$pages .= '<a href="'.$url.'" id="destoon_next" title="下一页(支持右方向键)">&nbsp;&#187;&nbsp;</a> ';
	return $pages;
}

function home_pages($total, $pagesize, $demo_url, $page = 1) {
	global $MOD;
	$pages = '';
	$items = $total;
	$total = ceil($total/$pagesize);
	$page = intval($page);
	if($page < 1 || $page > $total) $page = 1;
	$demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);

	$pages .= '<label>第<span>'.$page.'</span>页/共<strong>'.$total.'</strong>页</label>&nbsp;&nbsp;';

	$url = str_replace('{destoon_page}', 1, $demo_url);
	$pages .= '<a href="'.$url.'">&nbsp;首页&nbsp;</a> ';

	$_page = $page <= 1 ? $total : ($page - 1);
	$url = str_replace('{destoon_page}', $_page, $demo_url);
	$pages .= '<a href="'.$url.'">&nbsp;上一页&nbsp;</a> ';

	$_page = $page >= $total ? 1 : $page + 1;
	$url = str_replace('{destoon_page}', $_page, $demo_url);
	$pages .= '<a href="'.$url.'">&nbsp;下一页&nbsp;</a> ';

	$url = str_replace('{destoon_page}', $total, $demo_url);
	$pages .= '<a href="'.$url.'">&nbsp;末页&nbsp;</a> ';

	$pages .= '<input type="text" class="pages_inp" id="destoon_pageno" onkeydown="if(event.keyCode==13 && this.value) {window.location=\''.$demo_url.'\'.replace(/\\{destoon_page\\}/, this.value);}"> <input type="button" class="pages_btn" value="GO" onclick="window.location=\''.$demo_url.'\'.replace(/\\{destoon_page\\}/, $(\'destoon_pageno\').value);"/>';

	return $pages;
}

function get_fee($item_fee, $mod_fee) {
	if($item_fee < 0) {
		$fee = 0;
	} else if($item_fee == 0) {
		$fee = $mod_fee;
	} else {
		$fee = $item_fee;
	}
	return $fee;
}

function keyword($kw, $items, $moduleid) {
	global $db, $DT_PRE, $DT_TIME, $DT;
	if(!$DT['search_kw'] || strlen($kw) < 3 || strlen($kw) > 30 || $items < 1) return;
	$kw = addslashes($kw);
	$r = $db->get_one("SELECT * FROM {$DT_PRE}keyword WHERE moduleid=$moduleid AND word='$kw'");
	if($r) {
		if($r['status'] == 2) return;
		$items = $items > $r['items'] ? $items : $r['items'];
		$month_search = date('Y-m', $r['updatetime']) == date('Y-m', $DT_TIME) ? 'month_search+1' : '1';
		$week_search = date('W', $r['updatetime']) == date('W', $DT_TIME) ? 'week_search+1' : '1';
		$today_search = date('Y-m-d', $r['updatetime']) == date('Y-m-d', $DT_TIME) ? 'today_search+1' : '1';
		$db->query("UPDATE {$DT_PRE}keyword SET items='$items',updatetime='$DT_TIME',total_search=total_search+1,month_search=$month_search,week_search=$week_search,today_search=$today_search WHERE itemid=$r[itemid]");
	} else {
		$letter = gb2py($kw);
		$status = $DT['search_check_kw'] ? 2 : 3;
		$db->query("INSERT INTO {$DT_PRE}keyword (moduleid,word,letter,items,updatetime,total_search,month_search,week_search,today_search,status) VALUES ('$moduleid','$kw','$letter','$items','$DT_TIME','1','1','1','1','$status')");
	}
}

function update_quote($date = 0) {
	global $do, $db, $MOD, $DT_TIME, $DT_PRE, $kw, $keyword;	
	if(!$QP) return;
	$qid = -1;
	foreach($QP as $k=>$v) {
		if($v['title'] == $kw) $qid = $k;
	}
	if($qid == -1) return;
	$date or $date = timetodate($DT_TIME, 3);
	$fromtime = strtotime($date.' 00:00:00');
	$totime = strtotime($date.' 23:59:59');
	$condition = "edittime>$fromtime AND edittime<$totime".$MOD['quote_condition'];
	$condition .= $MOD['quote_match'] ? " AND keyword LIKE '%$keyword%'" : " AND tag='$kw'";
	$tags = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}sell WHERE $condition ORDER BY edittime DESC LIMIT $MOD[quote_max]");
	while($r = $db->fetch_array($result)) {
		$tags[] = $r;
	}
	$items = count($tags);
	if($items < $MOD['quote_min']) return;
	$post = array();
	$post['content'] = ob_template('quote', 'quote');
	$today = timetodate($DT_TIME, 3);
	$Q = $db->get_one("SELECT * FROM {$DT_PRE}quote WHERE adddate='$today' AND tag='$kw'");
	if($Q) {
		if($Q['items'] > $items) return;
		foreach($Q as $k=>$v) {
			$post[$k] = $v;
		}
		$itemid = $Q['itemid'];
		$do->edit($post);
	} else {
		$post['title'] = timetodate($DT_TIME, 'n月d日').$kw.'网上报价';
		$post['catid'] = $QP[$qid]['catid'];
		$post['tag'] = $QP[$qid]['title'];
		$post['pid'] = $QP[$qid]['pid'];
		$post['introduce'] = $post['thumb'] = '';
		$post['username'] = 'system';
		$post['status'] = $MOD['quote_check'] ? 2 : 3;
		$do->add($post);
	}
	return true;
}

function money_add($username, $amount) {
	global $db, $DT_PRE;
	$db->query("UPDATE {$DT_PRE}member SET money=money+{$amount} WHERE username='$username'");
}

function money_lock($username, $amount) {
	global $db, $DT_PRE;
	$db->query("UPDATE {$DT_PRE}member SET money_lock=money_lock+{$amount} WHERE username='$username'");
}

function record_add($username, $amount, $bank, $editor, $reason, $note = '') {
	global $db, $DT_PRE, $DT_TIME;
	$db->query("INSERT INTO {$DT_PRE}finance_record (username,bank,amount,addtime,reason,note,editor) VALUES ('$username','$bank','$amount','$DT_TIME','$reason','$note','$editor')");
}

function secondstodate($seconds) {
	$date = '';
	$t = floor($seconds/86400);
	if($t) {
		$date .= $t.'天';
		$seconds = $seconds%86400;
	}
	$t = floor($seconds/3600);
	if($t) {
		$date .= $t.'小时';
		$seconds = $seconds%3600;
	}
	$t = floor($seconds/60);
	if($t) {
		$date .= $t.'分钟';
	}
	return $date;
}

function get_process($fromtime, $totime) {
	global $DT_TIME;
	if($DT_TIME < $fromtime) {
		return 1;
	} else if($DT_TIME <= $totime) {
		return 2;
	} else {
		return 3;
	}
}

function get_status($status, $check) {
	if($status == 0) {//Recycle
		return 0;
	} else if($status == 1) {//Rejected
		return 2;
	} else if($status == 2) {//Checking
		return 2;
	} else if($status == 3) {//
		return $check ? 2 : 3;
	} else if($status == 4) {//Expired
		return $check ? 2 : 3;
	} else {
		return 2;
	}
}

function get_intro($content, $length = 200) {
	if($length) {
		$content = dtrim(dsubstr(strip_tags($content), $length));
		$content = preg_replace("/&([a-z]{1,});/", '', $content);
		return str_replace(array(' ', '[pagebreak]'), array('', ''), $content);
	} else {
		return '';
	}
}

function get_description($content, $length) {
	if($length) {
		$content = str_replace(array(' ', '[pagebreak]'), array('', ''), $content);
		return nl2br(trim(dsubstr(strip_tags($content), $length, '...')));
	} else {
		return '';
	}
}

function get_module_setting($moduleid, $key = '') {
	$M = cache_read('module-'.$moduleid.'.php');
	return $key ? $M[$key] : $M;
}

function get_module_table($moduleid) {
	global $DT_PRE, $MODULE;
	$module = $MODULE[$moduleid]['module'];
	return in_array($module, array('article', 'info')) ? $DT_PRE.$module.'_'.$moduleid : $DT_PRE.$module;
}

function update_company_setting($userid, $setting) {
	global $db, $DT_PRE;
	$S = get_company_setting($userid);
	foreach($setting as $k=>$v) {
		if(is_array($v)) {
			foreach($v as $i=>$j) {
				$v[$i] = str_replace(',', '', $j);
			}
			$v = implode(',', $v);
		}
		if(isset($S[$k])) {
			$db->query("UPDATE {$DT_PRE}company_setting SET item_value='$v' WHERE userid='$userid' AND item_key='$k'");
		} else {
			$db->query("INSERT INTO {$DT_PRE}company_setting (userid,item_key,item_value) VALUES ('$userid','$k','$v')");
		}
	}
	return true;
}

function get_company_setting($userid, $key = '') {
	global $db, $DT_PRE;
	if($key) {
		$r = $db->get_one("SELECT * FROM {$DT_PRE}company_setting WHERE userid='$userid' AND item_key='$key'");
		return $r ? $r['item_value'] : '';
	} else {
		$setting = array();
		$query = $db->query("SELECT * FROM {$DT_PRE}company_setting WHERE userid='$userid'");
		while($r = $db->fetch_array($query)) {
			$setting[$r['item_key']] = $r['item_value'];
		}
		return $setting;
	}
}

function anti_spam($string) {
	global $MODULE;
	if(preg_match("/^[a-z0-9\.\-_@]+$/i", $string)) {
		return '<img src="'.$MODULE[3]['linkurl'].'image.php?auth='.urlencode(dcrypt($string)).'" align="absmddle"/>';
	} else {
		return $string;
	}
}

function hide_ip($ip, $sep = '*') {
	if(!preg_match("/[\d\.]{7,15}/", $ip)) return $ip;
	$tmp = explode('.', $ip);
	return $tmp[0].'.'.$tmp[1].'.'.$sep.'.'.$sep;
}

function check_pay($item, $username) {
	global $db, $DT_PRE;
	return $db->get_one("SELECT itemid FROM {$DT_PRE}finance_pay WHERE item='$item' AND username='$username'");
}

function check_sign($string, $sign) {
	return $sign == crypt_sign($string);
}

function crypt_sign($string) {
	global $CFG, $DT_IP;
	return strtoupper(md5(md5($DT_IP.$string.$CFG['authkey'])));
}

function text_write($itemid, $item, $content) {
	if(!$itemid || !$item || !$content) return;
	$text_dir = DT_ROOT.'/file/text/'.$item.'/'.dalloc($itemid).'/';
	if(!is_dir($text_dir)) {
		dir_create($text_dir);
		copy(DT_ROOT.'/file/index.html', $text_dir.'index.html');
	}
	file_put($text_dir.$itemid.'.php', '<?php exit; ?>'.stripslashes($content));
}

function text_delete($itemid, $item) {
	if(!$itemid || !$item) return;
	$text_file = DT_ROOT.'/file/text/'.$item.'/'.dalloc($itemid).'/'.$itemid.'.php';
	if(is_file($text_file)) unlink($text_file);
}

function text_read($itemid, $item) {
	if(!$itemid || !$item) return '';
	return substr(file_get_contents(DT_ROOT.'/file/text/'.$item.'/'.dalloc($itemid).'/'.$itemid.'.php'), 14);
}

function cache_page() {
	global $CFG, $cache_file;
    if(!$CFG['cache_page']) return false;
	$contents = ob_get_clean();
	if($cache_file) file_put($cache_file, $contents);
	echo $contents;
}

function cache_item($moduleid, $catid, $item) {
	$items = cache_read('items-'.$moduleid.'.php');
	is_array($items) or $items = array();
	$items[$catid] = $item;
	cache_write('items-'.$moduleid.'.php', $items);
}

function keylink($content, $item) {
	global $KEYLINK;
	$KEYLINK or $KEYLINK = cache_read('keylink-'.$item.'.php');
	if(!$KEYLINK) return $content;
	foreach($KEYLINK as $v) {
		$p = strpos($content, $v['title']);
		if($p !== false) {
			$tmp = substr($content, 0, $p);
			$content = $tmp.'<a href="'.$v['url'].'" target="_blank"><strong class="keylink">'.$v['title'].'</strong></a>'.str_replace($tmp.$v['title'], '', $content);
		}
	}
	return $content;
}

function gender($gender, $type = 0) {
	if($type) return $gender == 1 ? '男' : '女';
	return $gender == 1 ? '男士' : '女士';
}

function vip_year($fromtime) {
	global $DT_TIME;
	return $fromtime ? intval(date('Y', $DT_TIME) - date('Y', $fromtime)) + 1  : 1;
}

function get_albums($item, $type = 0) {
	$imgs = array();
	if($type == 0) {
		$nopic = SKIN_PATH.'image/nopic50.gif';
		$imgs[] = $item['thumb'] ? $item['thumb'] : $nopic;
		$imgs[] = $item['thumb1'] ? $item['thumb1'] : $nopic;
		$imgs[] = $item['thumb2'] ? $item['thumb2'] : $nopic;
	} else if($type == 1) {
		$nopic = SKIN_PATH.'image/nopic200.gif';
		$imgs[] = $item['thumb'] ? str_replace('.thumb.', '.middle.', $item['thumb']) : $nopic;
		$imgs[] = $item['thumb1'] ? str_replace('.thumb.', '.middle.', $item['thumb1']) : $nopic;
		$imgs[] = $item['thumb2'] ? str_replace('.thumb.', '.middle.', $item['thumb2']) : $nopic;
	}
	return $imgs;
}
?>