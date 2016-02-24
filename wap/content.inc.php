<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
if(check_group($_groupid, $MOD['group_show'])) {
	if($fee) {
		if($MG['fee_mode']) {
			$user_status = 3;
		} else {
			$pay_item = isset($resume) ? $moduleid.'-'.$itemid.'-' : $moduleid.'-'.$itemid;
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
?>