<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($html == 'show') {
	$itemid or exit;
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status>2");
	$item or exit;
	extract($item);
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	($MOD['show_html'] || $fee) or exit;
	$member = array();
	if(check_group($_groupid, $MOD['group_contact'])) {
		if($fee) {
			if($MG['fee_mode']) {
				$user_status = 3;
			} else {
				$pay_item = $moduleid.'-'.$itemid;
				if($_userid) {
					if(check_pay($pay_item, $_username)) {
						$user_status = 3;
					} else {
						$user_status = 2;						
						$linkurl = linkurl($MOD['linkurl'].$item['linkurl'], 1);
						$pay_url = linkurl($MODULE[2]['linkurl'], 1).'pay.php?item='.$pay_item.'&fee='.$fee.'&sign='.crypt_sign($_username.$pay_item.$fee.$linkurl.$item['title']).'&title='.urlencode($item['title']).'&forward='.urlencode($linkurl);
					}
				} else {
					$user_status = 0;
				}
			}
		} else {
			$user_status = 3;
		}
	} else {
		$user_status = $_userid ? 1 : 0;
	}
	if($_username && $_username == $item['username']) $user_status = 3;
	if($user_status == 3) $member = $item['username'] ? userinfo($item['username']) : array();
	$contact = strip_nr(ob_template('contact', 'chip'), true);
	echo 'Inner("contact", \''.$contact.'\');';	
	echo 'Inner("hits", \''.$item['hits'].'\');';	
	$update = "hits=hits+1";
	if($item['totime'] && $item['totime'] < $DT_TIME && $status == 3) $update .= ",status=4";
	if($member) {
		foreach(array('vip','validated','company','areaid','truename','telephone','mobile','address','qq','msn') as $v) {
			if($item[$v] != $member[$v]) $update .= ",$v='$member[$v]'";
		}
		if($item['email'] != $member['mail']) $update .= ",email='$member[mail]'";
	}
	$db->query("UPDATE {$table} SET $update WHERE itemid=$itemid");
	
	if($MOD['show_html'] && $DT_TIME - @filemtime(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl']) > $task_item) tohtml('show', $module);

} else if($html == 'list') {
	$catid or exit;
	if($MOD['list_html'] && $DT_TIME - @filemtime(DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($moduleid, $catid, $page, $CATEGORY, $MOD)) > $task_list) {
		$fid = $page;
		$num = 3;
		tohtml('list', $module);
	}
} else if($html == 'index') {
	$file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
	if($MOD['index_html']) {
		if($DT_TIME - @filemtime($file) > $task_index) tohtml('index', $module);
	} else {
		if(is_file($file)) @unlink($file);
	}
}
?>