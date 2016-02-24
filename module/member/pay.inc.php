<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
if(!$_userid) dheader($MOD['linkurl']);
if(!$item || !$fee || !$sign || !$title || !$forward) message('参数错误', $MOD['linkurl']);
$title = urldecode($title);
check_sign($_username.$item.$fee.$forward.$title, $sign) or message('数据校验失败', $MOD['linkurl']);
$discount = $MG['discount'] > 0 && $MG['discount'] < 100 ? $MG['discount'] : 100;
$discount = dround($discount/100);
if($submit) {
	$password or message('请填写支付密码');
	is_payword($_username, $password) or message('支付密码不正确');
	$fee = dround($fee*$discount);
	$fee > 0 or message('支付金额错误');
	$_money > $fee or message('帐户余额不足，请先充值', $MOD['linkurl'].'charge.php?action=pay&amount='.($fee-$_money));
	$db->query("INSERT INTO {$DT_PRE}finance_pay (item,username,title,linkurl,fee,paytime,ip) VALUES ('$item','$_username','$title', '$forward','$fee','$DT_TIME','$DT_IP')");
	money_add($_username, -$fee);
	record_add($_username, -$fee, '站内', 'system', '站内支付', $title.'('.$forward.')');
	dheader($forward);
} else {
	$head_title = '站内支付';
	$amount = 100;
	$member_fee = dround($fee*$discount);
	if($member_fee > $_money) $amount = dround($member_fee - $_money);
	include template('pay', $module);
}
?>