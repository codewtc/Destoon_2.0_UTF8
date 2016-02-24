<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
require MOD_ROOT.'/member.class.php';
$do = new member;
$do->userid = $_userid;
$user = $do->get_one();
if(!$MG['vip'] || !$MG['fee'] || $user['totime'] < $DT_TIME) dheader($MOD['linkurl']);

if($submit) {
	is_payword($_username, $password) or message('支付密码不正确');
	in_array($year, array(1, 2, 3)) or $year = 1;
	$fee = dround($MG['fee']*$year);
	$fee > 0 or message('支付金额错误');
	$_money > $fee or message('帐户余额不足，请先充值', $MOD['linkurl'].'charge.php?action=pay&amount='.($fee-$_money));
	$totime = $user['totime'] + 365*86400*$year;
	money_add($_username, -$fee);
	record_add($_username, -$fee, '站内', 'system', VIP.'服务续费', $year.'年'.timetodate($totime, 3).'到期');
	$db->query("UPDATE {$DT_PRE}company SET totime=$totime WHERE userid=$_userid");
	dmsg('续费成功', $MOD['linkurl']);
} else {
	$head_title = VIP.'服务续费';
	$havedays = ceil(($user['totime']-$DT_TIME)/86400);
	$todate = timetodate($user['totime'], 3);
	include template('renew', $module);
}
?>