<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
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
if($moduleid == 9 && $item['username']) {//招聘	
	foreach(array('truename', 'telephone','mobile','address', 'msn', 'qq') as $v) {
		$member[$v] = $item[$v];
	}
	$member['mail'] = $item['email'];
}
?>