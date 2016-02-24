<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require_once DT_ROOT.'/include/post.func.php';
$password or wap_msg('请填写支付密码');
is_payword($_username, $password) or wap_msg('支付密码不正确');
$discount = $MG['discount'] > 0 && $MG['discount'] < 100 ? $MG['discount'] : 100;
$discount = dround($discount/100);
$_fee = dround($fee*$discount);
$_money > $_fee or wap_msg('余额不足，请充值');
$_item = $moduleid.'-'.$itemid;
if(isset($resume) && $resume) $_item = $moduleid.'-'.$itemid.'-';
$_url = linkurl($MOD['linkurl'].$item['linkurl'], 1);
$db->query("INSERT INTO {$DT_PRE}finance_pay (item,username,title,linkurl,fee,paytime,ip) VALUES ('$_item','$_username','$title', '$_url','$_fee','$DT_TIME','$DT_IP')");
money_add($_username, -$_fee);
record_add($_username, -$_fee, '站内', 'system', '站内支付', $title.'('.$_url.')');
wap_msg('支付成功', 'index.php?moduleid='.$moduleid.'&amp;itemid='.$itemid.((isset($resume) && $resume) ? '&amp;resume=1' : ''));
?>